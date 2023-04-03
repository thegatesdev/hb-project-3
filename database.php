<?php
    include_once("./lib/gdb.php");

    $db = new gdb_conn("localhost", "buskruit", "root", "");
    $logindb = new gdb_login_db($db, "user", "name", "pwd_hash");

    function user_type(string $username): array | false{
        global $db;
        $query = "SELECT user_type.id, user_type.name FROM user
        INNER JOIN user_type ON user.user_type = user_type.id 
        WHERE user.name LIKE '$username' 
        LIMIT 1";
        $result = mysqli_query(gdb_get($db), $query);
        if ($result && mysqli_num_rows($result) > 0) return mysqli_fetch_array($result);
        else return false;
    }
?>