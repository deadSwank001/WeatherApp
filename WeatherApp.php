<?php
// Database configuration
$host = 'localhost'; // your database host
$db = 'weather_database.sql'; // your database name
// $user = 'your_username'; // your database username
// $pass = 'your_password'; // your database password

// Create a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a city has been submitted
if (isset($_POST['city'])) {
    $city = $_POST['city'];
    
    // Store the search in the database
    $stmt = $conn->prepare("INSERT INTO weather_searches (city) VALUES (?)");
    $stmt->bind_param("s", $city);
    $stmt->execute();
    $stmt->close();

    // Fetch weather data from the public API
    $apiKey = ''; // Replace with your API key
    $apiUrl = "http://api.openweatwhermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    $response = file_get_contents($apiUrl);
    $weatherData = json_decode($response, true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather App</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="city" placeholder="Enter city name" required>
        <button type="submit">Get Weather</button>
    </form>

    <?php if (isset($weatherData)) { ?>
        <h2>Weather in <?php echo htmlspecialchars($weatherData['name']); ?></h2>
        <p>Temperature: <?php echo htmlspecialchars($weatherData['main']['temp']); ?> °C</p>
        <p>Weather: <?php echo htmlspecialchars($weatherData['weather'][0]['description']); ?></p>
        <p>Humidity: <?php echo htmlspecialchars($weatherData['main']['humidity']); ?>%</p>
        <p>Wind Speed: <?php echo htmlspecialchars($weatherData['wind']['speed']); ?> m/s</p>
    <?php } ?>
</body>
</html>
