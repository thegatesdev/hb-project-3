<?php
function gdb_validate(mysqli $conn, gdb_login_settings $s, string $input_username, string $input_pwd)
{
    $result = mysqli_query($conn, "SELECT $s->pwd_col FROM $s->usertable WHERE $s->name_col='$input_username' LIMIT 1");

    if (mysqli_num_rows($result) < 1) return "User by this name not found";
    $hash = mysqli_fetch_array($result)[0];

    $valid = password_verify($input_pwd, $hash);
    if ($valid) {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $newHash = password_hash($input_pwd, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE $s->usertable SET $s->pwd_col='$newHash' WHERE $s->name_col='$input_username'");
        }
        return false;
    } else return "Invalid username or password";
}

function gdb_create(mysqli $conn, gdb_login_settings $s, string $input_username, string $input_pwd)
{
    $find_result = mysqli_query($conn, "SELECT 1 FROM $s->usertable WHERE $s->name_col='$input_username'");
    
    if ($find_result && mysqli_num_rows($find_result) != 0) {
        return "This username is already in use";
    }

    $hash = password_hash($input_pwd, PASSWORD_DEFAULT);

    $query = "INSERT INTO $s->usertable ($s->name_col, $s->pwd_col) VALUES ('$input_username', '$hash')";
    $insert_result = mysqli_query($conn, $query);
    if (!$insert_result) return "Inserting user failed";
    return false;
}

class gdb_login_settings
{
    public string $usertable;
    public string $name_col;
    public string $pwd_col;

    public function __construct($usertable,$name_col,$pwd_col) {
        $this->usertable = $usertable;
        $this->name_col = $name_col;
        $this->pwd_col = $pwd_col;
    }
}
