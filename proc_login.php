<?php
// Set up database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set up ThingSpeak API endpoint
$num_results = 100; // change to the number of results you want to retrieve
$channel_id = "yourchannelid"; // replace with your ThingSpeak channel ID
$api_key = "yourapikey"; // replace with your ThingSpeak API key
$url = "https://api.thingspeak.com/channels/$channel_id/feeds.json?results=$num_results&api_key=$api_key";

// Retrieve data from ThingSpeak API
$response = file_get_contents($url);
$data = json_decode($response, true);

// Insert data into database
foreach ($data["feeds"] as $row) {
    $humidity = $row["field1"];
    $temperature_c = $row["field2"];
    $temperature_f = $row["field3"];
    $timestamp = $row["created_at"];
    $sql = "INSERT INTO mytable (humidity, temperature_c, temperature_f, timestamp) VALUES ('$humidity', '$temperature_c', '$temperature_f', '$timestamp')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
