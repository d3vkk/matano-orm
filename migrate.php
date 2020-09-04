<?php

require 'debug.php';
require 'model.php';

echo '<head><style>body{background: #111;color: #fdfdfd;font-size: 1.5rem;font-family: Roboto, sans-serif;}</style></head>';

$tables = $_SESSION['migrate_tables'];
for ($m = 0; $m < sizeof($tables); $m++) {
    ${'cols_'.$tables[$m]} = [];
    // To avoid tables with similar names
    $total_cols_rows = $db->countRows("DESCRIBE $tables[$m];");
    for ($n = 0; $n < $total_cols_rows; $n++) {
        ${'cols_'.$tables[$m]}[$n] = $db->getColumn("INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$tables[$m]."' LIMIT 1 OFFSET $n;", 'COLUMN_NAME');
    }

    // remove the 'id' column
    array_shift(${'cols_'.$tables[$m]});
}


// Table Loop
for ($table_loop = 0; $table_loop < sizeof($tables); $table_loop++) {
    $cols_array     = ${'cols_'.$tables[$table_loop]};
    $cols_array_num = count($cols_array);
    $total_rows     = $db->countTableRows($tables[$table_loop]);

    // Row Loop
    for ($row_loop = 0; $row_loop < $total_rows; $row_loop++) {
        echo "INSERT INTO $tables[$table_loop] (";

        // Column Loop
        // For column names
        for ($cols_loop = 0; $cols_loop < $cols_array_num; $cols_loop++) {
            echo $cols_loop == ($cols_array_num - 1) ? $cols_array[$cols_loop] : $cols_array[$cols_loop].',';
        }

        echo ') VALUES(';
        $offset = $row_loop;
        // For column values
        for ($l = 0; $l < $cols_array_num; $l++) {
            $cols_value = '\''.$db->getColumn(''.$tables[$table_loop]." LIMIT 1 OFFSET $offset", $cols_array[$l]).'\'';
            echo $l == ($cols_array_num - 1) ? $cols_value : $cols_value.',';
        }

        echo ');';
        echo '<br><br>';
    }//end for

    echo '<br><br>';
}//end for
