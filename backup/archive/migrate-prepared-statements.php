<?php
echo '
<head>
<style>
body{
    background: #111;
    color: #fdfdfd;
    font-size: 1.5rem;
    font-family: Roboto, sans-serif;
}
</style>
</head>
';
require 'debug.php';
require 'native.php';

// $offset = 1;
// $stmt = $lsapp_prep->prepare("SELECT email FROM users LIMIT 1 OFFSET ?;");
// $stmt->bind_param('i',$offset);
// $stmt->execute();
// $stmt->bind_result($col_value);
// $stmt->execute();
// while ($stmt->fetch()) {
//     echo $col_value;
//     echo '<br><br>';
// }

$tables = 'users';
$stmt = $lsapp_prep->prepare("DESCRIBE ?;");
$stmt->bind_param('s', $tables);
$stmt->execute();
$stmt->bind_result($pre_result_here);
$stmt->execute();
while ($stmt->fetch()) {
    $total_cols_rows = mysqli_num_rows($pre_result_here);
    echo $total_cols_rows;
}
