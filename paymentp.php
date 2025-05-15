<?php
include 'components/connect.php';

if (isset($_POST['submit'])) {

    $cardNo = filter_var($_POST['cardNo'], FILTER_SANITIZE_STRING);
    $expiryDate = filter_var($_POST['expiryDate'], FILTER_SANITIZE_STRING);
    $cvCode = filter_var($_POST['cvCode'], FILTER_SANITIZE_STRING);
    $cardName = filter_var($_POST['cardName'], FILTER_SANITIZE_STRING);
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_STRING);
    if (empty($cardNo) || empty($expiryDate) || empty($cvCode) || empty($cardName)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit;
    }

    // Insert payment data into the database
    $insert_payment = $conn->prepare("INSERT INTO `payments`(card_number, expiry_date, cv_code, card_owner, amount) VALUES (?, ?, ?, ?, ?)");
    $insert_payment->execute([$cardNo, $expiryDate, $cvCode, $cardName, $amount]);

    if ($insert_payment) {
        // Redirect to a success page after insertion
        header("Location: payment_success.php");
        exit;
    } else {
        echo "<script>alert('Payment failed, please try again.'); window.history.back();</script>";
        exit;
    }

} else {
    echo "<script>alert('No form data submitted.'); window.location.href = 'payment.php';</script>";
    exit;
}
?>
