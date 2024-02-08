<html>
    <head>
        <link rel="stylesheet" href="src/styles.css">
        <?php
          include_once './src/reservation.php';
          $reservation_class = new reservation();
          $reservation_class->get_db_credentials();
        ?>
    </head>
    <body>
        <div class="content_upper">
            <h1>CLV Musical: Sound of Music</h1>
        </div>
        <div class="topnav">
            <a href="./index.php">Home</a>
            <a href="./login.php">Inloggen</a>
        </div>
        <div class="content_lower">
            <h1>Kaartjes reserveren</h1>
            <?php
                if (!isset($_POST["check_available"])){
                    echo "<form name='first_form' method='post' action='#' >
                        Selecteer de show: <select id='show' name='show' required>
                            <option value='Tryout'>Tryout</option>
                            <option value='Premiere'>Premiere</option>
                            <option value='Matinee'>Matinee</option>
                            <option value='Grand finale'>Grand finale</option>
                        </select><br>
                        Voer aantal kaartjes in: <input type='number' id='aantal_kaartjes' name='aantal_kaartjes' min='1' required><br>
                        <input type='submit' value='Beschikbaarheid controleren' name='check_available'><br><br>
                    </form>";
                }
                if (isset($_POST["check_available"])){
                    $show = htmlspecialchars($_POST["show"]);
                    $show_nr = $reservation_class->get_show_nr($show);
                    $aantal_kaartjes = htmlspecialchars($_POST["aantal_kaartjes"]);
                    $available_seats = $reservation_class->get_available_seats($show);
                    echo "<p>Je wil $aantal_kaartjes kaartjes reserveren voor de $show. ";
                    if ($aantal_kaartjes <= $available_seats){
                        $total_price = $aantal_kaartjes * $reservation_class->get_show_price($show);
                        $reservation_id = $reservation_class->reserve_seats($aantal_kaartjes, $show_nr, $total_price);
                        echo "<br>Voer hieronder de gegevens in om de reservering te bevestigen.</p>
                            <form name='second_form' method='post' action='#' >
                            <input type='hidden' name='reservation_nr' value=$reservation_id>
                            Voornaam: <input type='text' id='voornaam' name='voornaam' required><br>
                            Tussenvoegsel: <input type='text' id='tussenvoegsel' name='tussenvoegsel'><br>
                            Achternaam: <input type='text' id='achternaam' name='achternaam' required><br>
                            Emailadres: <input type='text' id='emailadres' name='emailadres' required><br>
                            Telefoon nummer: <input type='text' id='telefoon_nr' name='telefoon_nr'><br>
                            Rolstoelplek: <input type='radio' id='rolstoelplek_wel' name='rolstoelplek' value='rolstoelplek_wel' required>Ja <input type='radio' id='rolstoelplek_niet' name='rolstoelplek' value='rolstoelplek_niet' required>Nee<br>
                            Opmerkingen: <input type='text' id='opmerkingen' name='opmerkingen'><br>
                            <br><input type='submit' value='Reserveren' name='reservate'><br><br>
                        </form>";
                    }else{
                        echo "<br>Helaas zijn er niet zoveel kaartjes beschikbaar. Probeer een andere show!</p>";
                    }
                }

                if (isset($_POST["reservate"])){
                    $reservation_nr = htmlspecialchars($_POST["reservation_nr"]);
                    $voornaam = htmlspecialchars($_POST["voornaam"]);
                    $tussenvoegsel = htmlspecialchars($_POST["tussenvoegsel"]);
                    $achternaam = htmlspecialchars($_POST["achternaam"]);
                    $emailadres = htmlspecialchars($_POST["emailadres"]);
                    $telefoon_nr = htmlspecialchars($_POST["telefoon_nr"]);
                    $rolstoelplek = htmlspecialchars($_POST["rolstoelplek"]);
                    if ($rolstoelplek == "rolstoelplek_wel"){
                        $rolstoel = 1;
                    }else{
                        $rolstoel = 0;
                    }
                    $opmerkingen = htmlspecialchars($_POST["opmerkingen"]);
                    $reservation_class->finalize_reservation($reservation_nr, $voornaam, $tussenvoegsel, $achternaam, $emailadres, $telefoon_nr, $rolstoel, $opmerkingen);
                    echo "Bedankt voor de reservering!";
                    header("Refresh:5; url=index.php");
                }
            ?>
        </div>
    </body>
</html>