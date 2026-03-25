<?php
include './Work/connect.php';

$pdo = null;
$conn_error = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=poll;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $conn_error = "Could not connect to the database: " . $e->getMessage();
}

// ─── Handle form submission ────────────────────────────────────────────────
$vote_result  = null;   // "success" | "error"
$vote_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $pdo !== null) {

    $poll_id = $_POST["poll_id"] ?? "";
    $option  = $_POST["option"]  ?? "";

    // --- Validate poll_id ---
    if ($poll_id === "" || !ctype_digit($poll_id)) {
        $vote_result  = "error";
        $vote_message = "Invalid poll ID. Please enter a positive whole number.";
    }
    // --- Validate option ---
    elseif (!in_array($option, ["1", "2", "3", "4"])) {
        $vote_result  = "error";
        $vote_message = "Invalid option. Please choose 1, 2, 3, or 4.";
    } else {
        $poll_id = (int) $poll_id;
        $option  = (int) $option;

        // --- Check that this poll exists ---
        try {
            $check = $pdo->prepare("SELECT ID, option1, option2, option3, option4 FROM poll WHERE ID = ?");
            $check->execute([$poll_id]);
            $poll = $check->fetch(PDO::FETCH_ASSOC);

            if (!$poll) {
                $vote_result  = "error";
                $vote_message = "Poll ID $poll_id does not exist. Please check and try again.";
            }
            // --- Check that the chosen option actually has text (not NULL) ---
            elseif (empty($poll["option$option"])) {
                $vote_result  = "error";
                $vote_message = "Option $option is not available for this poll.";
            } else {
                // --- Cast the vote safely using a prepared UPDATE ---
                $col   = "vote$option";
                $update = $pdo->prepare("UPDATE poll SET $col = $col + 1 WHERE ID = ?");
                $update->execute([$poll_id]);

                if ($update->rowCount() === 1) {
                    $vote_result  = "success";
                    $vote_message = "Your vote for option $option in poll #$poll_id was recorded successfully!";
                } else {
                    $vote_result  = "error";
                    $vote_message = "The vote could not be recorded. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $vote_result  = "error";
            $vote_message = "A database error occurred: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Voting</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Bebas+Neue&display=swap');

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #0f0f0f;
            color: #e8e0d0;
            font-family: 'Courier Prime', monospace;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #1a1a1a;
            border: 2px solid #d4a853;
            max-width: 520px;
            width: 100%;
            padding: 2.5rem;
        }

        .header {
            border-bottom: 1px solid #d4a853;
            padding-bottom: 1.2rem;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.8rem;
            letter-spacing: 0.1em;
            color: #d4a853;
            line-height: 1;
        }

        .header p {
            font-size: 0.8rem;
            color: #888;
            margin-top: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

        label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #d4a853;
            margin-bottom: 0.4rem;
        }

        input[type="number"],
        select {
            width: 100%;
            background: #0f0f0f;
            border: 1px solid #444;
            color: #e8e0d0;
            font-family: 'Courier Prime', monospace;
            font-size: 1rem;
            padding: 0.65rem 0.85rem;
            outline: none;
            margin-bottom: 1.4rem;
            transition: border-color 0.2s;
            appearance: none;
        }

        input[type="number"]:focus,
        select:focus {
            border-color: #d4a853;
        }

        input[type="submit"] {
            width: 100%;
            background: #d4a853;
            color: #0f0f0f;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            letter-spacing: 0.15em;
            padding: 0.75rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        input[type="submit"]:hover {
            background: #e8c070;
        }

        .alert {
            padding: 1rem 1.2rem;
            margin-bottom: 1.6rem;
            font-size: 0.88rem;
            line-height: 1.5;
            border-left: 4px solid;
        }

        .alert-success {
            background: #0d2b1a;
            border-color: #3ecf6e;
            color: #3ecf6e;
        }

        .alert-error {
            background: #2b0d0d;
            border-color: #e05555;
            color: #e05555;
        }

        .db-error {
            background: #2b0d0d;
            border: 2px solid #e05555;
            color: #e05555;
            padding: 1rem 1.2rem;
            margin-bottom: 1.6rem;
            font-size: 0.88rem;
        }

        .hint {
            font-size: 0.75rem;
            color: #666;
            margin-top: -1rem;
            margin-bottom: 1.4rem;
        }
    </style>
</head>

<body>
    <div class="card">

        <div class="header">
            <h1>Cast Your Vote</h1>
        </div>

        <?php if ($conn_error): ?>
            <div class="db-error">
                <strong>DATABASE CONNECTION FAILED</strong><br>
                <?= htmlspecialchars($conn_error) ?>
            </div>
        <?php endif; ?>

        <?php if ($vote_result === "success"): ?>
            <div class="alert alert-success">
                &#10003; <?= htmlspecialchars($vote_message) ?>
            </div>
        <?php elseif ($vote_result === "error"): ?>
            <div class="alert alert-error">
                &#10007; <?= htmlspecialchars($vote_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($conn_error === null): ?>
            <form method="POST" action="">

                <label for="poll_id">Poll ID</label>
                <input
                    type="number"
                    id="poll_id"
                    name="poll_id"
                    min="1"
                    step="1"
                    placeholder="e.g. 1"
                    value="<?= isset($_POST['poll_id']) ? (int)$_POST['poll_id'] : '' ?>"
                    required>


                <label for="option">Your Choice</label>
                <select id="option" name="option" required>
                    <option value="" disabled <?= !isset($_POST['option']) ? 'selected' : '' ?>>Select option...</option>
                    <option value="1" <?= (($_POST['option'] ?? '') === '1') ? 'selected' : '' ?>>Option 1</option>
                    <option value="2" <?= (($_POST['option'] ?? '') === '2') ? 'selected' : '' ?>>Option 2</option>
                    <option value="3" <?= (($_POST['option'] ?? '') === '3') ? 'selected' : '' ?>>Option 3</option>
                    <option value="4" <?= (($_POST['option'] ?? '') === '4') ? 'selected' : '' ?>>Option 4</option>
                </select>

                <input type="submit" value="Submit Vote">

            </form>
        <?php endif; ?>

    </div>
</body>

</html>