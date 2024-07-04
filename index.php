<?php
ob_start(); // Start output buffering
// Your PHP code here
header("Location: lisa.php");
exit;
ob_end_flush(); // Flush the output buffer
?>

