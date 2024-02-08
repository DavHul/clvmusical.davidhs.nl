<?php
class reservation{
    private $servername;
    private $username;
    private $password;
    private $dbname = "clv_musical";
    private $value;

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
    function get_show_nr($shownaam){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM shows WHERE naam='$shownaam'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $show_nr = $row["id"];
        $conn->close();
        return $show_nr;
    }
    function get_show_price($shownaam){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "SELECT * FROM shows WHERE naam='$shownaam'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $show_prijs = $row["prijs"];
        $conn->close();
        return $show_prijs;
    }

    function reserve_seats($number_seats, $show_nr, $price){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "INSERT INTO reservations (voornaam, tussenvoegsel, achternaam, emailadres, telefoonnummer, aantal_kaartjes, show_nr, totaal_prijs, rolstoelplek, opmerkingen, status) 
        VALUES ('tmp', 'tmp', 'tmp', 'tmp', 'tmp', $number_seats, $show_nr,  $price, 0, '', 'reserving')";
        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $sql = "SELECT max(id) as id FROM reservations ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $reservation_id = $row["id"];

        $conn->close();
        return $reservation_id;
    }

    function finalize_reservation($reservation_id, $voornaam, $tussenvoegsel, $achternaam, $emailadres, $telefoonnummer, $rolstoelplek, $opmerkingen){
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $sql = "UPDATE reservations SET voornaam='$voornaam', tussenvoegsel='$tussenvoegsel', achternaam='$achternaam', emailadres='$emailadres', telefoonnummer='$telefoonnummer', rolstoelplek=$rolstoelplek , opmerkingen='$opmerkingen', status='gereserveerd'
        WHERE id = $reservation_id";
        if ($conn->query($sql) === FALSE) {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
    }
    
}
date_default_timezone_set("Europe/Amsterdam");
?>