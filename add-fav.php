<?php
    require_once 'dbconfig.php';
    require_once 'auth.php';
    if(!checkAuth()) {
        echo "Operazione non consentita";
    } else if(isset($_GET['id']) && isset($_GET['type'])) {
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $id_fav = urldecode($_GET['id']);
    $user = $_SESSION['username'];
    $type_fav = urldecode($_GET['type']);
    $query = "INSERT INTO favorites values('$user', '$id_fav', '$type_fav')";
    mysqli_query($conn, $query);
    }
?>