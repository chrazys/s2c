<?php
/** @var array $model */
$minDate = date('Y-m-d'); // Get today's date in the format required by the input type="date"
?>

<input type="date" min="<?php echo $minDate; ?>" value="<?php echo $model['field_value']; ?>" <?php echo $model['field_attributes']; ?> />