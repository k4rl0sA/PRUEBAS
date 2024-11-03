<?php
function is_logged_in() {
    var_dump('en auth.php='.session_id(), $_SESSION);
    return isset($_SESSION[SESSION_NAME]) && !empty($_SESSION[SESSION_NAME]);
}

function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>