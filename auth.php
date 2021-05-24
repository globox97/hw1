<?php
    require_once 'dbconfig.php';
    session_start();

    function checkAuth() {
        if(isset($_SESSION['username'])) {
            return $_SESSION['username'];
        } else return 0;
    }
?>
