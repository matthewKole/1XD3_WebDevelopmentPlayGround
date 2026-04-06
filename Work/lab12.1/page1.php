<?php
session_start();
session_unset(); // Clear any old game data
?>
<!DOCTYPE html>
<html>
<head>
    <title>Step 1: Set Range</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        form { background: #f4f4f4; padding: 20px; border-radius: 8px; display: inline-block; }
        input { margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <h2>Enter a Numeric Range</h2>
    <form action="page2.php" method="POST">
        <label>Minimum Number:</label>
        <input type="number" name="min" required>
        <label>Maximum Number:</label>
        <input type="number" name="max" required>
        <button type="submit">Start Game</button>
    </form>
</body>
</html>