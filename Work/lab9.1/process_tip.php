<?php
// Check if data was sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill = $_POST['bill'];
    $percentage = $_POST['percentage'];

    // Double-check math safety on the server side
    if (is_numeric($bill) && is_numeric($percentage)) {
        $tipAmount = $bill * ($percentage / 100);
        $totalBill = $bill + $tipAmount;
    } else {
        die("Invalid input received.");
    }
} else {
    // If someone tries to load this page directly without submitting the form
    header("Location: tip_form.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Calculation Result</title>
    <style>
        body { font-family: sans-serif; padding: 20px; text-align: center; }
        .result-box { 
            display: inline-block; 
            background: #e8f5e9; 
            padding: 20px; 
            border: 1px solid #2e7d32; 
            border-radius: 8px; 
        }
        a { display: block; margin-top: 20px; color: #2c3e50; }
    </style>
</head>
<body>

    <div class="result-box">
        <h2>Your Results</h2>
        <p><strong>Original Bill:</strong> $<?php echo number_format($bill, 2); ?></p>
        <p><strong>Tip Amount:</strong> $<?php echo number_format($tipAmount, 2); ?></p>
        <hr>
        <h3><strong>Total:</strong> $<?php echo number_format($totalBill, 2); ?></h3>
    </div>

    <a href="tip_form.html">&laquo; Back to Calculator</a>

</body>
</html>