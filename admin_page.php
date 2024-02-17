<html>
    <head>
        <link rel="stylesheet" href="src/styles.css">
        <?php
            include_once './src/login.php';
            $login_class = new login();
            $login_class->get_db_credentials();
            include_once './src/admin.php';
            $admin_class = new admin();
            $admin_class->get_db_credentials();
        ?>
    </head>
    <body>
        <?php
            $result = $login_class->check_login();
            if ($result == "false"){
                header("Refresh:1; url=login.php");
            }
        ?>
        <div class="content_upper">
            <h1>CLV Musical: Sound of Music</h1>
        </div>
        <div class="topnav">
            <a href="./index.php">Home</a>
            <a href="./admin_page.php">Administratie</a>
            <a href="./login.php">Uitloggen</a>
        </div>
        <div class="content_lower">
            <h1>Administratie</h1>
            <div>
                <div>
                    <h2>Beschikbare stoelen</h2>
                    <?php
                        echo "Try-out: ".$admin_class->get_available_seats("Tryout")." stoelen beschikbaar<br>";
                        echo "Premiere: ".$admin_class->get_available_seats("Premiere")." stoelen beschikbaar<br>";
                        echo "Matinee: ".$admin_class->get_available_seats("Matinee")." stoelen beschikbaar<br>";
                        echo "Grand finale: ".$admin_class->get_available_seats("Grand finale")." stoelen beschikbaar<br>";
                    ?>
                </div>
                <div>
                    <?php
                    if (isset($_POST["change_reservation"])){
                        $id_number = htmlspecialchars($_POST["id_number"]);
                        //echo $id_number;
                        $data = $admin_class->get_data($id_number);
                        echo "
                        <form name='change_reservation_2' method='post' action='#'>
                            <input type='hidden' name='reservation_nr' id='reservation_nr' value=$id_number>
                            Selecteer de show: <select id='show' name='show' required>";
                                if ($data[6] == 1){
                                    echo "<option value='1' selected>Tryout</option>";
                                }else{
                                    echo "<option value='1'>Tryout</option>";
                                }
                                if ($data[6] == 2){
                                    echo "<option value='2' selected>Premiere</option>";
                                }else{
                                    echo "<option value='2'>Premiere</option>";
                                }
                                if ($data[6] == 3){
                                    echo "<option value='3' selected>Matinee</option>";
                                }else{
                                    echo "<option value='3'>Matinee</option>";
                                }
                                if ($data[6] == 4){
                                    echo "<option value='4' selected>Grand finale</option>";
                                }else{
                                    echo "<option value='4'>Grand finale</option>";
                                }
                            echo "
                            </select><br>
                            Voer aantal kaartjes in: <input type='number' id='aantal_kaartjes' name='aantal_kaartjes' min='1' required value=".$data[5]."><br>
                            Voornaam: <input type='text' id='voornaam' name='voornaam' required value=".$data[0]."><br>
                            Tussenvoegsel: <input type='text' id='tussenvoegsel' name='tussenvoegsel' value=".$data[1]."><br>
                            Achternaam: <input type='text' id='achternaam' name='achternaam' required value=".$data[2]."><br>
                            Emailadres: <input type='text' id='emailadres' name='emailadres' required value=".$data[3]."><br>
                            Telefoon nummer: <input type='text' id='telefoon_nr' name='telefoon_nr' value=".$data[4]."><br>";
                            if ($data[8] == 1){
                                echo "Rolstoelplek: <input type='radio' id='rolstoelplek_wel' name='rolstoelplek' value='rolstoelplek_wel' required checked='checked'>Ja <input type='radio' id='rolstoelplek_niet' name='rolstoelplek' value='rolstoelplek_niet' required>Nee<br>";
                            }else{
                                echo "Rolstoelplek: <input type='radio' id='rolstoelplek_wel' name='rolstoelplek' value='rolstoelplek_wel' required>Ja <input type='radio' id='rolstoelplek_niet' name='rolstoelplek' value='rolstoelplek_niet' required checked='checked'>Nee<br>";
                            }
                            echo "Opmerkingen: <input type='text' id='opmerkingen' name='opmerkingen' value=".$data[9]."><br>
                            <input type='submit' value='Aanpassen' name='change'><input type='submit' value='Verwijderen' name='remove'>
                        </form>";
                        
                    }else{
                        echo "
                        <form name='change_reservation_2' method='post' action='#'>
                            Voornaam: <input type='text' id='voornaam' name='voornaam' required><br>
                            Tussenvoegsel: <input type='text' id='tussenvoegsel' name='tussenvoegsel'><br>
                            Achternaam: <input type='text' id='achternaam' name='achternaam' required><br>
                            Emailadres: <input type='text' id='emailadres' name='emailadres' required><br>
                            Telefoon nummer: <input type='text' id='telefoon_nr' name='telefoon_nr'><br>
                            Rolstoelplek: <input type='radio' id='rolstoelplek_wel' name='rolstoelplek' value='rolstoelplek_wel' required>Ja <input type='radio' id='rolstoelplek_niet' name='rolstoelplek' value='rolstoelplek_niet' required>Nee<br>
                            Opmerkingen: <input type='text' id='opmerkingen' name='opmerkingen'><br>
                            <input type='submit' value='Aanpassen' name='reservate'><input type='submit' value='Verwijderen' name='remove'>
                        </form>";
                    }
                    if (isset($_POST["remove"])){
                        $reservation_nr = htmlspecialchars($_POST["reservation_nr"]);
                        $admin_class->remove_data($reservation_nr);
                    }
                    if (isset($_POST["change"])){
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
                        $show = htmlspecialchars($_POST["show"]);
                        $aantal_kaartjes = htmlspecialchars($_POST["aantal_kaartjes"]);
                        $totaal_prijs = $admin_class->calculate_price($aantal_kaartjes, $show);
                        $admin_class->change_data($reservation_nr, $voornaam, $tussenvoegsel, $achternaam, $emailadres, $telefoon_nr, $rolstoel, $opmerkingen, $aantal_kaartjes, $show, $totaal_prijs);
                    }
                    ?>
                </div>
            </div>
            <div>
                <?php
                    echo "<p>Filteren data</p>
                    <form name='select_show' method='post' action='#' >
                        <select id='show' name='show'>
                            <option value='all'>All</option>
                            <option value='1'>Tryout (show 1)</option>
                            <option value='2'>Premiere (show 2)</option>
                            <option value='3'>Matinee (show 3)</option>
                            <option value='4'>Grand finale (show 4)</option>
                        </select>
                        <input type='submit' value='Filteren' name='select'><br><br>
                    </form>";
                ?>
                <table style="width:100%">
                    <tr><th>Voornaam</th><th>Tussenvoegsel</th><th>Achternaam</th><th>Emailadres</th><th>Telefoonnummer</th><th>Aantal kaartjes</th><th>Show nummer</th><th>Totaal prijs</th><th>Rolstoelplek</th><th>Opmerkingen</th><th>Status</th></tr>
                    <?php 
                    if (isset($_POST["select"])){
                        $show = htmlspecialchars($_POST["show"]);
                        if ($show != "all"){
                            $admin_class->print_html_show_table($show);
                        }else{
                            $admin_class->print_show_table();
                        }
                    }else{
                        $admin_class->print_show_table();
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>