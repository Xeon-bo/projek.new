<?php
require_once '../config.php';

function getUserById($id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function getAccountById($id) {
    global $conn;
    $sql = "SELECT * FROM gmail_accounts WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatDate($date, $format = 'd M Y H:i') {
    return date($format, strtotime($date));
}

function getStatusBadge($status) {
    $badges = [
        'available' => '<span class="status-badge status-available">Tersedia</span>',
        'sold' => '<span class="status-badge status-sold">Terjual</span>',
        'pending' => '<span class="status-badge status-pending">Pending</span>',
        'completed' => '<span class="status-badge status-completed">Selesai</span>',
        'failed' => '<span class="status-badge status-failed">Gagal</span>',
        'approved' => '<span class="status-badge status-approved">Disetujui</span>',
        'rejected' => '<span class="status-badge status-rejected">Ditolak</span>'
    ];
    
    return $badges[$status] ?? '<span class="status-badge">Unknown</span>';
}

function sendEmail($to, $subject, $message) {
    // Implement email sending logic here
    // For production, use PHPMailer or similar library
    return true;
}

function logActivity($user_id, $action, $details = '') {
    global $conn;
    $sql = "INSERT INTO activity_logs (user_id, action, details) VALUES ($user_id, '$action', '$details')";
    return mysqli_query($conn, $sql);
}

function validatePassword($password) {
    // At least 6 characters, one uppercase, one lowercase, one number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $password);
}

function sanitizeInput($input) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($input)));
}
?>