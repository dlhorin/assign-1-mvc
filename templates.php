<?php
require_once("config.php");
require_once("db.php");

function generate_table($table_data, $name){
    if(!isset($table_data) || !$table_data || !count($table_data))
        return false;


    $i = 0;
    $row = $table_data[$i];

    echo "<table border='1' name='$name' id='$name' >\n";

    echo "<tr>";
    foreach($row as $key => $val)
        echo "<th>" . $key. "</th>";
    echo "</tr>\n";

    foreach($table_data as $row){
        echo "<tr>";
        foreach($row as $key => $val)
            echo "<td>" . $val . "</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    return true;
}

function generate_dropdown($data, $opt_col, $val_col, $name, $selected = NULL){
    if(!$data)
        return NULL;
    if($val_col === NULL)
        $val_col = $opt_col;

    echo "<select name='$name'>\n";
    foreach($data as $row){
        $val = $row[$val_col];
            echo "<option ";
            echo "value = '$val'";
        if( !($selected === NULL) && $selected == $val)
            echo " selected = 'selected'";
        echo ">";
        echo $row[$opt_col];
        echo "</option>";
    }
    echo "</select>";
    return 1;
}
?>
