<?php
include_once("./lib/gdb.php");

$login_settings = new gdb_login_settings("user", "name", "pwd_hash");

try {
    $conn = mysqli_connect("localhost", "d71414_root", "A4S4h0CtbFcoNU8r", "d71414_buskruit");
    mysqli_set_charset($conn, 'utf8');
} catch (mysqli_sql_exception $e) {
    $conn = false;
}

function user_type(mysqli $conn, string $username)
{
    $query = "SELECT user_type.id, user_type.name FROM user
        INNER JOIN user_type ON user.user_type = user_type.id 
        WHERE user.name LIKE '$username' 
        LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) return mysqli_fetch_array($result);
    else return false;
}
