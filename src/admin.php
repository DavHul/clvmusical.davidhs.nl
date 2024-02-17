<?php
class admin{
    private $servername;
    private $username;
    private $password;
    private $dbname = "clv_musical";

    function get_db_credentials(){
        // Read the JSON file  
        $json = file_get_contents(__DIR__."/../../credentials.json"); 
        
        // Decode the JSON file 
        $json_data = json_decode($json,true); 
        
        // Display data 
        $this->servername = $json_data["data"][0]["servername"]; 
        $this->username = $json_data["data"][0]["username"]; 
        $this->password = $json_data["data"][0]["password"]; 
    }

    function print_table(){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM reservations";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
        //print_r($result->fetch_assoc());
            while($row = $result->fetch_assoc()) {
                echo "id: " . $row["id"]. " - voornaam: " . $row["voornaam"]. " - tussenvoegsel: " . $row["tussenvoegsel"]. " - achternaam: " . $row["achternaam"]. 
                " - emailadres: " . $row["emailadres"]. " - telefoonnummer: " . $row["telefoonnummer"]. " - aantal_kaartjes: " . $row["aantal_kaartjes"]. " - shown_nr: " . $row["shown_nr"].
                " - totaal_prijs: " . $row["totaal_prijs"]. " - rolstoelplek: " . $row["rolstoelplek"]. " - opmerkingen: " . $row["opmerkingen"]."<br>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    function get_available_seats($shownaam){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM shows WHERE naam='$shownaam'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $max_seats = $row["max_plekken"];
        $show_nr = $row["id"];

        $sql = "SELECT SUM(aantal_kaartjes) as aantal_kaartjes FROM reservations WHERE show_nr=$show_nr";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $reserved_seats = $row["aantal_kaartjes"];

        $available_seats = $max_seats - $reserved_seats;
        $conn->close();
        return $available_seats;
    }

    function print_show_table(){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM reservations";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["voornaam"]."</td><td>" . $row["tussenvoegsel"]. "</td><td>" . $row["achternaam"]. "</td><td>" . $row["emailadres"]."</td>
                <td> " . $row["telefoonnummer"]. " </td><td> " . $row["aantal_kaartjes"]. " </td><td> " . $row["show_nr"]. " </td><td>&euro; " . $row["totaal_prijs"]. 
                "</td><td>" . $row["rolstoelplek"]. "</td><td> " . $row["opmerkingen"]. " </td><td> " . $row["status"]. " </td>
                <td><form name='change_reservation' method='post' action='#'><input type='hidden' id=id_number name=id_number value=".$row["id"]."><input type='submit' value='Aanpassen' name='change_reservation'></form></td></tr>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }

    function get_data($id_number){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM reservations WHERE id=$id_number";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $conn->close();
        return [$row["voornaam"], $row["tussenvoegsel"], $row["achternaam"], $row["emailadres"], $row["telefoonnummer"], $row["aantal_kaartjes"], $row["show_nr"], $row["totaal_prijs"], $row["rolstoelplek"], $row["opmerkingen"], $row["status"]];
    }

    function remove_data($id_number){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "DELETE FROM reservations WHERE id=$id_number";
        if ($conn->query($sql) === TRUE) {
            echo "Inschrijving gewist!";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();
    }
    function change_data($reservation_id, $voornaam, $tussenvoegsel, $achternaam, $emailadres, $telefoonnummer, $rolstoelplek, $opmerkingen, $aantal_kaartjes, $show_nr, $totaal_prijs){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "UPDATE reservations SET voornaam='$voornaam',tussenvoegsel='$tussenvoegsel',achternaam='$achternaam',emailadres='$emailadres',telefoonnummer='$telefoonnummer',aantal_kaartjes=$aantal_kaartjes,show_nr=$show_nr,totaal_prijs=$totaal_prijs,rolstoelplek=$rolstoelplek,opmerkingen='$opmerkingen' WHERE id = $reservation_id";
        if ($conn->query($sql) === TRUE) {
            echo "Wijziging aangepast";
        } else {
            echo "Error updating record: " . $conn->error;
        }
        $conn->close();
    }
    function calculate_price($aantal_kaartjes, $show){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM shows WHERE id=$show";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $show_prijs = $row["prijs"];
        $totaal_prijs = $show_prijs * $aantal_kaartjes;
        $conn->close();
        return $totaal_prijs;
    }

    function print_html_show_table($show_nr){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM reservations WHERE show_nr = $show_nr";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row["voornaam"]."</td><td>" . $row["tussenvoegsel"]. "</td><td>" . $row["achternaam"]. "</td><td>" . $row["emailadres"]."</td>
                <td> " . $row["telefoonnummer"]. " </td><td> " . $row["aantal_kaartjes"]. " </td><td> " . $row["show_nr"]. " </td><td>&euro; " . $row["totaal_prijs"]. 
                "</td><td>" . $row["rolstoelplek"]. "</td><td> " . $row["opmerkingen"]. " </td><td> " . $row["status"]. " </td></tr>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
    }
    
}
date_default_timezone_set("Europe/Amsterdam");
?>