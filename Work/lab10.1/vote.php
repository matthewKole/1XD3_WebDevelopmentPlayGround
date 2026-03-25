<?php
include '../connect.php';
// connect.php creates $dbh — if it fails it calls die() automatically

$vote_result  = null;
$vote_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $poll_id = $_POST["poll_id"] ?? "";
    $option  = $_POST["option"]  ?? "";

    if ($poll_id === "" || !ctype_digit($poll_id)) {
        $vote_result  = "error";
        $vote_message = "Invalid poll ID. Please enter a positive whole number.";
    } elseif (!in_array($option, ["1", "2", "3", "4"])) {
        $vote_result  = "error";
        $vote_message = "Invalid option. Please choose 1, 2, 3, or 4.";
    } else {
        $poll_id = (int) $poll_id;
        $option  = (int) $option;

        try {
            $check = $dbh->prepare("SELECT ID, option1, option2, option3, option4 FROM poll WHERE ID = ?");
            $check->execute([$poll_id]);
            $poll = $check->fetch(PDO::FETCH_ASSOC);

            if (!$poll) {
                $vote_result  = "error";
                $vote_message = "Poll ID $poll_id does not exist.";
            } elseif (empty($poll["option$option"])) {
                $vote_result  = "error";
                $vote_message = "Option $option is not available for this poll.";
            } else {
                $col    = "vote$option";
                $update = $dbh->prepare("UPDATE poll SET $col = $col + 1 WHERE ID = ?");
                $update->execute([$poll_id]);

                if ($update->rowCount() === 1) {
                    $vote_result  = "success";
                    $vote_message = "Vote recorded for option $option in poll #$poll_id!";
                } else {
                    $vote_result  = "error";
                    $vote_message = "Vote could not be recorded. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $vote_result  = "error";
            $vote_message = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poll Vote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        h1 {
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            color: #333;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: bold;
            color: #555;
            margin-bottom: 0.3rem;
        }

        input[type="number"],
        select {
            width: 100%;
            padding: 0.5rem 0.7rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 1.1rem;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 0.6rem;
            background: #3a7bd5;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background: #2f65b8;
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Poll Voting</h1>

        <?php if ($vote_result === "success"): ?>
            <div class="alert success"><?= htmlspecialchars($vote_message) ?></div>
        <?php elseif ($vote_result === "error"): ?>
            <div class="alert error"><?= htmlspecialchars($vote_message) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="poll_id">Poll ID</label>
            <input type="number" id="poll_id" name="poll_id" min="1" step="1"
                placeholder="e.g. 1"
                value="<?= isset($_POST['poll_id']) ? (int)$_POST['poll_id'] : '' ?>"
                required>

            <label for="option">Option</label>
            <select id="option" name="option" required>
                <option value="" disabled <?= !isset($_POST['option']) ? 'selected' : '' ?>>Select...</option>
                <option value="1" <?= (($_POST['option'] ?? '') === '1') ? 'selected' : '' ?>>Option 1</option>
                <option value="2" <?= (($_POST['option'] ?? '') === '2') ? 'selected' : '' ?>>Option 2</option>
                <option value="3" <?= (($_POST['option'] ?? '') === '3') ? 'selected' : '' ?>>Option 3</option>
                <option value="4" <?= (($_POST['option'] ?? '') === '4') ? 'selected' : '' ?>>Option 4</option>
            </select>

            <input type="submit" value="Submit Vote">
        </form>
    </div>
</body>

</html>