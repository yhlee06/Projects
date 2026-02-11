<?php
include('db.php'); // Include database connection

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_recipe'])) {
    $title = $_POST['title'];
    $ingredients = $_POST['ingredients'];
    $steps = $_POST['steps'];
    $user_id = $_SESSION['user_id']; // Logged in user's ID

    // Insert new recipe into database
    $sql = "INSERT INTO recipes (title, ingredients, steps, created_by) 
            VALUES ('$title', '$ingredients', '$steps', '$user_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert success'>New recipe added successfully!</div>";
    } else {
        echo "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add a New Recipe</h1>
        <form method="POST" action="add_recipe.php">
            <label for="title">Recipe Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" required></textarea>

            <label for="steps">Steps:</label>
            <textarea id="steps" name="steps" required></textarea>

            <button type="submit" name="add_recipe" class="btn btn-primary">Add Recipe</button>
        </form>
</div>
</body>
</html>



