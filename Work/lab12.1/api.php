<?php
session_start();
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// Handle Game Initialization
if ($action === 'init') {
    $_SESSION['credits'] = 10;
    echo json_encode(['success' => true, 'credits' => 10, 'message' => 'Game started with 10 credits.']);
    exit;
}

// Handle Spinning
if ($action === 'spin') {
    // Error Code: No active session
    if (!isset($_SESSION['credits'])) {
        echo json_encode(['error' => 'NO_SESSION', 'message' => 'Your session has expired or you are out of money. Please restart.']);
        exit;
    }

    $bet = (int)($_POST['bet'] ?? 0);
    $credits = $_SESSION['credits'];

    // Error Code: Bet is too small
    if ($bet < 1) {
        echo json_encode(['error' => 'INVALID_BET_MIN', 'message' => 'You must bet at least 1 credit.']);
        exit;
    }

    // Error Code: Bet is too large
    if ($bet > $credits) {
        echo json_encode(['error' => 'INVALID_BET_MAX', 'message' => "You only have $credits credits left!"]);
        exit;
    }

    // Deduct the bet from their balance
    $credits -= $bet;

    // Spin the wheels
    $fruits = ['( ͡❛ ͜ʖ ͡❛)', '( ͡◉ ͜ʖ ͡◉)', '( ͡⇀ ͜ʖ ͡↼)', '( ͡⋄ ͜ʖ ͡⋄)', '( ͡° ͜ʖ ͡°)'];
    $w1 = $fruits[array_rand($fruits)];
    $w2 = $fruits[array_rand($fruits)];
    $w3 = $fruits[array_rand($fruits)];

    // Calculate matches and payout
    $matches = 0;
    $winnings = 0;

    if ($w1 === $w2 && $w2 === $w3) {
        $matches = 3;
        $winnings = $bet * 5; // 3 matches pays 5 to 1
    } elseif ($w1 === $w2 || $w1 === $w3 || $w2 === $w3) {
        $matches = 2;
        $winnings = $bet * 2; // 2 matches pays 2 to 1
    }

    // Add winnings to balance
    $credits += $winnings;
    $_SESSION['credits'] = $credits;

    // Check for Game Over
    $gameOver = false;
    if ($credits <= 0) {
        $gameOver = true;
        session_destroy(); // Kill session when out of money
    }

    // Return the JSON response
    echo json_encode([
        'success' => true,
        'wheels' => [$w1, $w2, $w3],
        'matches' => $matches,
        'winnings' => $winnings,
        'credits' => $credits,
        'gameOver' => $gameOver
    ]);
    exit;
}

// Fallback for invalid actions
echo json_encode(['error' => 'INVALID_ACTION', 'message' => 'Unknown request.']);
?>