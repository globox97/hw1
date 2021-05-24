<?php
    require_once 'auth.php';
    //Se l'utente ha già effettuato l'accesso viene reindirizzato alla home
    if(checkAuth()) {
        header('Location: home.php');
        exit;
    }

    //Se sono stati inseriti i dati di registrazione vengono verificati e inviati al server
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_pwd'])) {
        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        //Controllo che l'username rispetti i criteri o che non sia già utilizzato
        if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Username già utilizzato";
            }
        }

        //Controllo che la password abbia almeno 8 caratteri
        if (strlen($_POST['password']) < 8) {
            $error[] = "Caratteri password insufficienti";
        } 

        //Controllo il campo 'conferma password'
        if (strcmp($_POST['password'], $_POST['confirm_pwd']) != 0) {
            $error[] = "Le password non coincidono";
        }

        //Controllo che l'email sia valida e non utilizzata
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Email già utilizzata";
            }
        }

        //Se validi carico i dati sul database
        if (count($error) == 0) {
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO users(username, email, password) VALUES('$username', '$email', '$password')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['user'] = $_POST['username'];
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                mysqli_close($conn);
                header("Location: login.php");
                exit;
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }

        mysqli_close($conn);

    } else if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_pwd'])) {
        $error = array("Riempi tutti i campi");
    }

?>

<html>
    <head>
        <link rel="stylesheet" href="signup.css">
        <script src="signup.js" defer></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <title>Registrati</title>
    </head>
    <body>
        <h1>Inserisci i tuoi dati</h1>
        <form method='post' autocomplete='off'>

            <div class='username'><label for='username'>Nome utente</label>
                <input type='text' name='username' <?php if(isset($_POST['username'])) {echo "value=".$_POST['username'];} ?>>
            </div>
            <span id='username_span'></span>

            <div class='email'><label for='email'>E-mail</label>
                <input type='text' name='email' <?php if(isset($_POST['email'])) {echo "value=".$_POST['email'];} ?>>
            </div>
            <span id='email_span'></span>

            <div class='password'><label for='password'>Password</label>
                <input type='password' name='password'>
            </div>
            <span id='password_span'></span>

            <div class='confirm_pwd'><label for='confirm_pwd'>Conferma Password</label>
                <input type='password' name='confirm_pwd'>
            </div>
            <span id='confirm_span'></span>

            <input type='submit' value="Registrati" id="submit" disabled>
        </form>
        <div class="signup">Hai già un account? <a href="login.php">Accedi</a></div>
    </body>
</html>