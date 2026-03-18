<?php
$tipAmount = 0;
$totalBill = 0;
$showResults = false;

// Check if the form was actually submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the global $_POST array
    $bill = $_POST['bill'];
    $percentage = $_POST['percentage'];

    // Perform the calculation
    if (is_numeric($bill) && is_numeric($percentage)) {
        $tipAmount = $bill * ($percentage / 100);
        $totalBill = $bill + $tipAmount;
        $showResults = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tip Calculator</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 400px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        .result { background: #e8f5e9; padding: 10px; margin-top: 20px; border-radius: 4px; border: 1px solid #2e7d32; }
        input[type="text"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        input[type="submit"] { background: #2c3e50; color: white; border: none; padding: 10px 20px; cursor: pointer; }
        .error { color: red; font-size: 0.9rem; display: none; }
    </style>

    <script>
        function validateForm() {
            let billInput = document.getElementById("bill").value;
            let errorMsg = document.getElementById("error-msg");

            // Check if input is empty or not a positive number
            if (billInput === "" || isNaN(billInput) || parseFloat(billInput) <= 0) {
                errorMsg.style.display = "block";
                return false; // Prevents the form from submitting to PHP
            }
            errorMsg.style.display = "none";
            return true;
        }
    </script>
</head>
<body>

    <div class="container">
        <h2>Tip Calculator</h2>
        
        <form method="POST" action="tip_calculator.php" onsubmit="return validateForm()">
            <label for="bill">Bill Amount ($):</label>
            <input type="text" name="bill" id="bill" placeholder="e.g. 50.00">
            <p id="error-msg" class="error">Please enter a valid positive number for the bill.</p>

            <label for="percentage">Tip Percentage:</label>
            <select name="percentage">
                <option value="10">10% (Okay)</option>
                <option value="15" selected>15% (Good)</option>
                <option value="20">20% (Great!)</option>
                <option value="25">25% (Amazing!)</option>
            </select>
            <br><br>

            <input type="submit" value="Calculate Tip">
        </form>

        <?php if ($showResults): ?>
            <div class="result">
                <p><strong>Tip:</strong> $<?php echo number_format($tipAmount, 2); ?></p>
                <p><strong>Total Bill:</strong> $<?php echo number_format($totalBill, 2); ?></p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>