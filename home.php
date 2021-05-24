<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Homepage</title>
        <link rel="stylesheet" href="home.css">
        <script src="home.js" defer></script>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@100&display=swap" rel="stylesheet">
    </head>

    <body>
        <header>
            <div id='overlay'></div>
            <nav>
                <span class='flex-item'>Album</span>
                <span class='flex-item'>Artist</span>
                <span class='flex-item'>Playlist</span>
                <span class='flex-item'>Track</span>
                <?php
                    require_once 'auth.php';
                    if(isset($_SESSION['username'])) {
                        echo "<span class='flex-item' id='fav_redirect'>Preferiti</span>";
                    }
                ?>
                <div id='menu'>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </nav>
            <article id='intestazione'>
                <span>
                    <?php
                        if(isset($_SESSION['username'])) {
                            echo "Bentornato/a ".$_SESSION['username']."<br>Ecco le ultime uscite";
                        } else echo "Ultime uscite";
                    ?>
                </span>
            </article>
        </header>
        <div>
            <?php
                if(isset($_SESSION['username'])) {
                    echo "<div class='user'>";
                    echo "Accesso effettuato: ".$_SESSION['username']."<br>";
                    echo "<a href='logout.php'>Esci</a>";
                    echo "</div>";
                } else {
                    echo "<div class='login'>";
                    echo "Hai un account?<br>";
                    echo "<a href='login.php'>Accedi</a><br>";
                    echo "Oppure<br>";
                    echo "<a href='signup.php'>Creane uno</a>";
                    echo "</div>";
                }
            ?>
            <form class='search'>
                <input type='text' id='ricerca'>
                <input type='submit' value='Cerca'>
            </form>
        </div>
        <section></section>
        <footer>
            <span>Designed by Angelo Barbasola O46001232 for Web Programming 2021</span>
        </footer>
    </body>
</html>