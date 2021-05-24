<?php
    require_once 'auth.php';
    if(checkAuth()) {
        header('Location: home.php');
        exit;
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"])) {
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $query = "SELECT id, username, password FROM users WHERE username = '$username'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
        if(mysqli_num_rows($res) > 0) {
            $entry = mysqli_fetch_assoc($res);
            if(password_verify($_POST['password'], $entry['password'])) {
                session_start();
                $_SESSION['username'] = $entry['username'];
                if($_POST['remember']) {
                    setcookie("user_id", $entry['id'], time() + (60*60*24));
                }
                mysqli_free_result($res);
                mysqli_close($conn);
                header("Location: home.php");
                exit;
            }
        }
        $error = "Username e/o password errati.";
    }
    else if (isset($_COOKIE['user_id'])) {
        $connect = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connect));
        $userid = mysqli_real_escape_string($connect, $_COOKIE['user_id']);
        $query = "SELECT username FROM users WHERE id = '".$userid."'";
        $res = mysqli_query($connect, $query) or die(mysqli_error($connect));
        if(mysqli_num_rows($res) > 0) {
            $entry = mysqli_fetch_assoc($res);
            $_SESSION['username'] = $entry['username'];
            mysqli_free_result($res);
            mysqli_close($connect);
            header("Location: login.php");
            exit;
        }
    }
    
    else if(isset($_POST["username"]) || isset($_POST["password"])) {
        $error = "Inserisci username e password.";
    }
?>

<html>
    <head>
        <link rel='stylesheet' href='login.css'>
        <meta name='viewport' content= 'width=device-width, initial-scale=1'>
        <title>Accedi al tuo account</title>
    </head>
    <body>
        <h1>Accedi</h1>
        <form method='post'>
            <div>
                <label for='username'>Nome utente</label>
                <input type='text' name='username'>
            </div>
            <div>
                <label for='password'>Password</label>
                <input type='password' name='password'>
            </div>
            <section>
                <input type='checkbox' name='remember'>
                <label for='remember'>Rimani connesso</label>
            </section>
            <input type='submit'></input>
        </form>
        <?php
            if(isset($error)) {
                echo "<div id='error'>";
                echo $error;
                echo "</div>";
            }
        ?>
        <span>Non hai un account? <a href='signup.php'>Creane uno</a></span>
    </body>
</html>