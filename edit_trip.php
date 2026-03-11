<?php
session_start();
require_once 'db_config.php';

$trip_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM trips WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $trip_id, $_SESSION['email']);
$stmt->execute();
$trip = $stmt->get_result()->fetch_assoc();

if (!$trip) { die("Trip not found."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Trip</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Edit your trip to <?php echo $trip['destination']; ?></h2>
        <form action="process.php" method="POST">
            <input type="hidden" name="trip_id" value="<?php echo $trip['id']; ?>">
            
            <label>Travel Date:</label>
            <input type="date" name="travel_date" value="<?php echo $trip['travel_date']; ?>" required class="search-box">
            
            <label>Itinerary Details:</label>
            <textarea name="itinerary" class="search-box" style="height:200px;"><?php echo $trip['itinerary']; ?></textarea>
            
            <button type="submit" name="update_trip" class="book-btn">Save Changes</button>
            <a href="dashboard.php" style="color:white; margin-left:20px;">Cancel</a>
        </form>
    </div>
</body>
</html>