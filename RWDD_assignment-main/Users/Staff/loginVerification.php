<?php
if (password_verify($password, $stored_password_hash)) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
    // Redirect to the user dashboard
    header("Location: dashboard.php");
} else {
    // Show error if the password is incorrect
    echo "Invalid credentials!";
}
