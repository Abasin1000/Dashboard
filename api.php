<?php
// Databaseverbinding
$servername = "localhost";
$username = "root"; // standaard gebruiker in XAMPP
$password = ""; // standaard wachtwoord is leeg
$dbname = "agenda_db";

// Maak de verbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Haal evenementen op
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);
    $events = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    echo json_encode($events);
}

// Voeg een nieuw evenement toe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $time = $data['time'];
    $day = $data['day'];
    $month = $data['month'];
    $year = $data['year'];

    $sql = "INSERT INTO events (title, time, day, month, year) VALUES ('$title', '$time', '$day', '$month', '$year')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Evenement toegevoegd"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Fout bij toevoegen evenement: " . $conn->error]);
    }
}

$conn->close();
?>
