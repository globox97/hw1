<?php
    include 'dbconfig.php';

    session_start();
    session_destroy();
    if(isset($_COOKIE['user_id'])) {
        setcookie("user_id", "");
    }
    header('Location: home.php');
?>