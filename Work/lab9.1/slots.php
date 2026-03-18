<?php
$fruits = ['images/1.png', 'images/2.png', 'images/3.png', 'images/4.png', 'images/5.png', 'images/6.png', 'images/7.png'];

$index1 = rand(0, count($fruits) - 1);
$index2 = rand(0, count($fruits) - 1);
$index3 = rand(0, count($fruits) - 1);

$slot1 = $fruits[$index1];
$slot2 = $fruits[$index2];
$slot3 = $fruits[$index3];

$message = "";
$resultClass = "";

if ($index1 == $index2 && $index2 == $index3) {
    $message = "JACKPOT!";
    $resultClass = "jackpot";
} elseif ($index1 == $index2 || $index1 == $index3 || $index2 == $index3) {
    $message = "You win!";
    $resultClass = "win";
} else {
    $message = "Try Again!";
    $resultClass = "lose";
}

$currentFile = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CS 1XD3 - Slot Machine</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #2c3e50; color: white; text-align: center; }
        
        .machine {
            background: #c0392b;
            border: 10px solid #7f8c8d;
            border-radius: 20px;
            display: inline-block;
            padding: 40px;
            margin-top: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .slots {
            background: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .slots img {
            width: 100px;
            height: 100px;
            border: 2px solid #ddd;
            object-fit: contain; 
        }

        .message {
            font-size: 2rem;
            margin: 20px 0;
            font-weight: bold;
            text-transform: uppercase;
            min-height: 2.5rem; 
        }

        .jackpot { color: #f1c40f; text-shadow: 2px 2px #000; animation: blink 0.5s infinite; }
        .win { color: #2ecc71; }
        
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        .spin-btn {
            display: inline-block;
            padding: 15px 30px;
            background: #f1c40f;
            color: #000;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: transform 0.1s;
        }

        .spin-btn:hover { background: #d4ac0d; transform: scale(1.05); }
        .spin-btn:active { transform: scale(0.95); }
    </style>
</head>
<body>

    <div class="machine">
        <div class="slots">
            <img src="<?php echo $slot1; ?>" alt="Slot 1">
            <img src="<?php echo $slot2; ?>" alt="Slot 2">
            <img src="<?php echo $slot3; ?>" alt="Slot 3">
        </div>

        <div class="message <?php echo $resultClass; ?>">
            <?php echo $message; ?>
        </div>

        <a href="<?php echo $currentFile; ?>" class="spin-btn">SPIN AGAIN</a>
    </div>

</body>
</html>