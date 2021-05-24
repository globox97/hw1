<?php
    require_once 'dbconfig.php';
    require_once 'api_config.php';

    if(!isset($_GET['q'])) {
        echo "Non dovresti essere qui";
        exit;
    }
    //Richiesta token per le api di Spotify
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $headers = array("Authorization: Basic ".base64_encode($clientID.":".$clientSecret));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $token = json_decode(curl_exec($curl), true);
    curl_close($curl);

    
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    $username = mysqli_real_escape_string($conn, $_GET['q']);
    $query = "SELECT id_fav, type_fav FROM favorites WHERE nickname = '$username'";
    $results = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $json_result = array();
    while(($result = mysqli_fetch_assoc($results))) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.spotify.com/v1/".$result['type_fav']."s/".$result['id_fav']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token['access_token']));
        $json_result[] = json_decode(curl_exec($curl), true);
        curl_close($curl);
    }
    header('Content-Type: application/json');
    echo json_encode($json_result);
?>