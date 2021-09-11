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
require 'model.php';

// echo $db->migrate(['users'])[0];
$db->migrate(['users']);
