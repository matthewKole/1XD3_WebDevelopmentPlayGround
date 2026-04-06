<?php
session_start();

if (!isset($_POST['guess']) || !isset($_SESSION['target'])) {
    header("Location: page1.php");
    exit();
}

$guess = (int)$_POST['guess'];
$target = (int)$_SESSION['target'];
$correct = ($guess === $target);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Step 3: Results</title>
    <style>
        body { font-family: sans-serif; padding: 20px; text-align: center; }
        .win { color: green; font-weight: bold; }
        .lose { color: red; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #007BFF; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <?php if ($correct): ?>
        <h2 class="win">Correct!</h2>
        <p>The number was indeed <?php echo $target; ?>.</p>
        <?php session_destroy(); // Game over, clean up! ?>
        <a href="page1.php" class="btn">Play New Game</a>
        
    <?php else: ?>
        <h2 class="lose">Wrong!</h2>
        <p>You guessed <?php echo $guess; ?>, but that's not it.</p>
        <a href="page2.php" class="btn">Try Again</a>
    <?php endif; ?>
</body>
</html>