<?php
session_start();

// If we just arrived from Page 1, set the range and the target number
if (isset($_POST['min']) && isset($_POST['max'])) {
    $_SESSION['min'] = $_POST['min'];
    $_SESSION['max'] = $_POST['max'];
    $_SESSION['target'] = rand($_SESSION['min'], $_SESSION['max']);
}

// Redirect back to Page 1 if someone tries to access this page directly
if (!isset($_SESSION['target'])) {
    header("Location: page1.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Step 2: Guess</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .info { color: #555; font-style: italic; }
    </style>
</head>
<body>
    <h2>Guess the Number</h2>
    <p class="info">I'm thinking of a number between <?php echo $_SESSION['min']; ?> and <?php echo $_SESSION['max']; ?>.</p>
    
    <form action="page3.php" method="POST">
        <input type="number" name="guess" required autofocus>
        <button type="submit">Submit Guess</button>
    </form>
</body>
</html>