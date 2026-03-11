<?php
// session_start must be the absolute first line
session_start();
require_once 'db_config.php';

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Make sure 'is_admin' is included in the SELECT query
    $stmt = $conn->prepare("SELECT fullname, email, password, is_admin FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            
            // SAVE THE ADMIN STATUS TO THE SESSION
            $_SESSION['is_admin'] = $user['is_admin']; 
            
            // REDIRECT based on admin status
            if ($user['is_admin'] == 1) {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: planning.php");
            }
            exit();
        }
    }
    echo "<script>alert('Invalid login'); window.location='index.php';</script>";
}if (isset($_POST['forgot_submit'])) {
    $email = $_POST['email'];
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime('+10 minutes'));

        // Update user with OTP
        $upd = $conn->prepare("UPDATE users SET otp_code = ?, otp_expiry = ? WHERE email = ?");
        $upd->bind_param("sss", $otp, $expiry, $email);
        
        if ($upd->execute()) {
            // REDIRECT is what prevents the blank screen
            header("Location: verify_otp.php?email=" . urlencode($email));
            exit();
        }
    } else {
        echo "<script>alert('Email not found.'); window.location='index.php';</script>";
    }
}
// --- VERIFY OTP LOGIC ---
if (isset($_POST['verify_otp_submit'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    
    // Check if the email and OTP match and are not expired
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expiry > NOW()");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt->close();
        // Redirect to the reset password page with a verification flag
        header("Location: reset_password.php?email=" . urlencode($email) . "&verified=true");
        exit(); // Always use exit() after a header redirect
    } else {
        $stmt->close();
        echo "<script>alert('Invalid or expired OTP. Please try again.'); window.location='verify_otp.php?email=" . urlencode($email) . "';</script>";
    }
}

// --- UPDATE PASSWORD LOGIC ---
if (isset($_POST['update_password_submit'])) {
    $email = $_POST['email'];
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
    // Update the password and clear the OTP code for security
    $stmt = $conn->prepare("UPDATE users SET password = ?, otp_code = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $new_pass, $email);
    
    if ($stmt->execute()) {
        $stmt->close();
        echo "<script>alert('Password updated successfully! You can now login.'); window.location='index.php';</script>";
        exit();
    } else {
        echo "Error updating password: " . $conn->error;
    }
}	

// --- SAVE TRIP LOGIC ---
if (isset($_POST['save_trip'])) {
    // Check if user is logged in via session
    if (!isset($_SESSION['email'])) {
        die("Error: You must be logged in to save a trip.");
    }

    $email = $_SESSION['email'];
    $dest = $_POST['destination'];
    $date = $_POST['travel_date'];
    $plan = $_POST['itinerary'];

    $stmt = $conn->prepare("INSERT INTO trips (user_email, destination, travel_date, itinerary) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $dest, $date, $plan);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: dashboard.php?msg=success");
        exit();
    } else {
        echo "Database Error: " . $conn->error;
    }
}
// Ensure no characters exist after the closing PHP tag
// --- ADMIN: ADD NEW PLACE ---
if (isset($_POST['add_new_place'])) {
    $name = $_POST['place_name'];
    $cat = $_POST['category'];
    $desc = $_POST['description'];
    
    // Handle Image Upload
    $img_name = $_FILES['place_image']['name'];
    $target = "images/" . basename($img_name);
    
    if (move_uploaded_file($_FILES['place_image']['tmp_name'], $target)) {
        // Insert into a new table called 'explore_places'
        $stmt = $conn->prepare("INSERT INTO explore_places (name, cat, description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $cat, $desc, $img_name);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('New place added successfully!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "Failed to upload image.";
    }
}
?>