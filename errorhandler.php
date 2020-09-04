<?php

session_start();
$array = $_SESSION['error_array'];

echo '<head><style>body{background: #111;color: firebrick;font-size: 1.5rem;font-family: Roboto, sans-serif;font-weight: bold;}</style></head>';
foreach ($array as $item) {
    echo '<div>'.var_dump($item).'</div><br>';
}

echo '<br><br>Query failed.';
