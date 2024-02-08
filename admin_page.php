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
                header("Refresh:1; url=index.php");
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