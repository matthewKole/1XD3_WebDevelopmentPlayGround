<?php
// 1. Capture POST data
$server_name = $_POST['server_name'] ?? '';
$email1 = $_POST['email1'] ?? '';
$email2 = $_POST['email2'] ?? '';
$bill = $_POST['bill'] ?? '';
$tip_percent = $_POST['tip_percent'] ?? '';
$cc_num = $_POST['cc_num'] ?? '';

// 2. Server-Side Validation (The "Robust" logic)
// Check if any field is empty
if (empty($server_name) || empty($email1) || empty($email2) || empty($bill) || empty($tip_percent) || empty($cc_num)) {
    die("Error: All fields are required.");
}

// Check if emails match
if ($email1 !== $email2) {
    die("Error: Emails do not match.");
}

// Check if CC is exactly 16 digits
if (strlen($cc_num) !== 16 || !is_numeric($cc_num)) {
    die("Error: Credit card number must be exactly 16 digits.");
}

// Check for negative bill or bad formatting
if (!is_numeric($bill) || $bill <= 0) {
    die("Error: Bill amount must be a positive number.");
}

// 3. Calculations
$tip_amount = $bill * ($tip_percent / 100);
$total = $bill + $tip_amount;

// 4. Final Output (Formatted nicely)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Receipt</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #eee; padding: 50px; text-align: center; }
        .receipt { background: white; display: inline-block; padding: 30px; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0,0,0,0.1); text-align: left; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>

<div class="receipt">
    <h2>RECEIPT</h2>
    <p>Server: <?php echo htmlspecialchars($server_name); ?></p>
    <p>Email: <?php echo htmlspecialchars($email1); ?></p>
    <p>Card: **** **** **** <?php echo substr($cc_num, -4); ?></p>
    <div class="line"></div>
    <p>Original Bill: <span class="bold">$<?php echo number_format($bill, 2); ?></span></p>
    <p>Tip (<?php echo $tip_percent; ?>%): <span class="bold">$<?php echo number_format($tip_amount, 2); ?></span></p>
    <div class="line"></div>
    <p class="bold">TOTAL: $<?php echo number_format($total, 2); ?></p>
    <br>
    <a href="tip_form.html">Back to Form</a>
</div>

</body>
</html>