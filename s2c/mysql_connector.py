import mysql.connector
from mysql.connector import Error

# Ρυθμίσεις σύνδεσης στη βάση δεδομένων
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 's2cnew'
}

try:
    print("Προσπάθεια σύνδεσης στη βάση δεδομένων...")
    # Σύνδεση στη βάση δεδομένων
    connection = mysql.connector.connect(**db_config)
    if connection.is_connected():
        print("Η σύνδεση στη βάση δεδομένων ήταν επιτυχής!")

        cursor = connection.cursor()

        # Λήψη της λίστας των πινάκων
        cursor.execute("SHOW TABLES")
        tables = cursor.fetchall()

        # Δημιουργία triggers για κάθε πίνακα
        for (table_name,) in tables:
            print(f"Δημιουργία triggers για τον πίνακα: {table_name}")

            # Λήψη των στηλών του πίνακα από το INFORMATION_SCHEMA
            cursor.execute(f"SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{table_name}'")
            columns = cursor.fetchall()

            # Δημιουργία της λίστας των στηλών για το query
            column_names = [column[0] for column in columns]

            # Δημιουργία triggers για INSERT, UPDATE, DELETE
            for action in ['INSERT', 'UPDATE', 'DELETE']:
                trigger_name = f"log_{action.lower()}_{table_name}"

                if action == 'INSERT':
                    sql_query = f"""
                    CREATE TRIGGER {trigger_name}
                    AFTER INSERT ON {table_name}
                    FOR EACH ROW
                    BEGIN
                        INSERT INTO wp_migration_logs (query)
                        VALUES (
                            CONCAT('INSERT INTO {table_name} (',
                            '{', '.join(column_names)}', ') VALUES (',
                            {', '.join([f"NEW.{col}" for col in column_names])}, ')')
                        );
                    END;
                    """
                elif action == 'UPDATE':
                    sql_query = f"""
                    CREATE TRIGGER {trigger_name}
                    AFTER UPDATE ON {table_name}
                    FOR EACH ROW
                    BEGIN
                        INSERT INTO wp_migration_logs (query)
                        VALUES (
                            CONCAT('UPDATE {table_name} SET ',
                            {', '.join([f"{col} = NEW.{col}" for col in column_names])},
                            ' WHERE id = OLD.id')
                        );
                    END;
                    """
                else:  # DELETE
                    sql_query = f"""
                    CREATE TRIGGER {trigger_name}
                    AFTER DELETE ON {table_name}
                    FOR EACH ROW
                    BEGIN
                        INSERT INTO wp_migration_logs (query)
                        VALUES (
                            CONCAT('DELETE FROM {table_name} WHERE id = OLD.id')
                        );
                    END;
                    """

                # Εκτέλεση του query για το trigger
                try:
                    cursor.execute(sql_query)
                    print(f"Trigger {trigger_name} δημιουργήθηκε για {action} στον πίνακα {table_name}")
                except mysql.connector.Error as err:
                    print(f"Σφάλμα κατά τη δημιουργία του trigger {trigger_name}: {err}")

        cursor.close()
    else:
        print("Αποτυχία σύνδεσης στη βάση δεδομένων.")

except Error as e:
    print(f"Σφάλμα κατά τη σύνδεση: {e}")
finally:
    # Κλείσιμο της σύνδεσης
    if connection.is_connected():
        connection.close()
        print("Η σύνδεση στη βάση δεδομένων έκλεισε.")
