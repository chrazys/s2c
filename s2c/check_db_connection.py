import mysql.connector
from mysql.connector import Error

# Ρυθμίσεις σύνδεσης στη βάση δεδομένων
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 's2cnew1'
}

try:
    print("Προσπάθεια σύνδεσης στη βάση δεδομένων...")
    # Προσπάθεια σύνδεσης
    connection = mysql.connector.connect(**db_config)
    if connection.is_connected():
        print("Η σύνδεση στη βάση δεδομένων ήταν επιτυχής!")
        # Λήψη πληροφοριών για τη βάση δεδομένων
        db_info = connection.get_server_info()
        print(f"Σύνδεση στη βάση δεδομένων MySQL έκδοση: {db_info}")
except Error as e:
    print(f"Σφάλμα κατά τη σύνδεση: {e}")
finally:
    # Κλείσιμο της σύνδεσης αν είναι ανοιχτή
    if connection.is_connected():
        connection.close()
        print("Η σύνδεση στη βάση δεδομένων έκλεισε.")
