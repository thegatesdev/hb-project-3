<?php

function gdb_get(gdb_conn $conn): mysqli | false{
    if (!isset($conn->conn)){
        try{
            $conn->conn = mysqli_connect($conn->host, $conn->user, $conn->password, $conn->database);
        }catch(mysqli_sql_exception $conn_err){
            return false;
        }
        mysqli_set_charset($conn->conn, 'utf8');
    }
    if ($conn->refcount < 0) $conn->refcount = 1;
    else $conn->refcount++;
    return $conn->conn;
}

function gdb_stop(gdb_conn $conn){
    if (isset($conn->conn) && --$conn->refcount <= 0) mysqli_close($conn->conn);
}

class gdb_conn{
    public ?mysqli $conn;
    public int $refcount = 0;
    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $password
    ){
    }
}

function gdb_validate(gdb_login_db $db, string $input_username, string $input_pwd): string | false{
    $conn = gdb_get($db->conn);
    if (!$conn) return "Database connection failed";

    $result = mysqli_query($conn, "SELECT $db->pwd_col FROM $db->usertable WHERE $db->name_col='$input_username' LIMIT 1");

    if (mysqli_num_rows($result) < 1){
        gdb_stop($db->conn);
        return "User by this name not found";
    } 
    $hash = mysqli_fetch_array($result)[0];

    $valid = password_verify($input_pwd, $hash);
    if ($valid){
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)){
            $newHash = password_hash( $input_pwd, PASSWORD_DEFAULT );
            mysqli_query($conn, "UPDATE $db->usertable SET $db->pwd_col='$newHash' WHERE $db->name_col='$input_username'");
        }
    }else{
        gdb_stop($db->conn);
        return "Invalid username or password";
    }
    gdb_stop($db->conn);
    return false;
}

function gdb_create(gdb_login_db $db, string $input_username, string $input_pwd): string | false{
    $conn = gdb_get($db->conn);
    if (!$conn) return "Database connection failed";

    $find_result = mysqli_query($conn, "SELECT 1 FROM $db->usertable WHERE $db->name_col='$input_username'");
    if (mysqli_num_rows($find_result) != 0){
        gdb_stop($db->conn);
        return "This username is already in use";
    }

    $hash = password_hash($input_pwd, PASSWORD_DEFAULT);

    $insert_result = mysqli_query($conn, "INSERT INTO $db->usertable ($db->name_col, $db->pwd_col) VALUES (
        '$input_username', '$hash'
    )");
    gdb_stop($db->conn);
    if (!$insert_result) return "Inserting user failed";
    return false; 
}

class gdb_login_db{
    public function __construct(
        public gdb_conn $conn,
        public string $usertable,
        public string $name_col,
        public string $pwd_col
    ){
    }
}

?>