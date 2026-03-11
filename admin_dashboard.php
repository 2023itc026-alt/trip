<?php
session_start();
require_once 'db_config.php';

// --- SECURE EMAIL GATEKEEPER ---
// Only this specific email can access this page
$admin_email = "sanjayprasath297@gmail.com"; 

if (!isset($_SESSION['email']) || $_SESSION['email'] !== $admin_email) {
    die("Unauthorized Access: You do not have permission to view this page.");
}

// Fetch stats for the dashboard
$user_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$trip_count = $conn->query("SELECT COUNT(*) as count FROM trips")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Control | My Travel planner</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-panel { padding: 40px; color: white; max-width: 900px; margin: auto; }
        .admin-form { background: var(--glass); padding: 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; }
        .input-group input, .input-group textarea, .input-group select { 
            width: 100%; padding: 10px; border-radius: 8px; border: none; background: rgba(0,0,0,0.3); color: white; 
        }
    </style>
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Command Center</h1>
        <p>Current Stats: <strong><?php echo $user_count; ?> Users</strong> | <strong><?php echo $trip_count; ?> Trips Planned</strong></p>
        
        <hr style="opacity:0.2; margin: 30px 0;">

        <h2>Add New Place to Explore</h2>
        <form action="process.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="input-group">
                <label>Place Name:</label>
                <input type="text" name="place_name" required placeholder="e.g. Dubai Marina">
            </div>
            
            <div class="input-group">
                <label>Category:</label>
                <select name="category">
                    <option value="Hotel">Hotel</option>
                    <option value="Restaurant">Food</option>
                    <option value="Museum">Culture</option>
                    <option value="Park">Nature</option>
                </select>
            </div>

            <div class="input-group">
                <label>Description:</label>
                <textarea name="description" rows="3" required></textarea>
            </div>

            <div class="input-group">
                <label>Upload Image:</label>
                <input type="file" name="place_image" required>
            </div>

            <button type="submit" name="add_new_place" class="book-btn" style="width:100%">Add Place to Website</button>
        </form>
        
        <br>
        <a href="planning.php" style="color:var(--primary); text-decoration:none;">← Back to Project</a>
    </div>
</body>
</html>