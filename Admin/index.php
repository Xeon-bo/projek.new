<?php
require_once '../auth.php';
requireAdmin();
require_once '../config.php';

// Get statistics
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users"))['count'];
$total_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM gmail_accounts"))['count'];
$available_accounts = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM gmail_accounts WHERE status = 'available'"))['count'];
$total_transactions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM transactions"))['count'];
$pending_payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM payments WHERE status = 'pending'"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) as total FROM transactions WHERE type = 'purchase' AND status = 'completed'"))['total'];

// Recent transactions
$recent_transactions = mysqli_query($conn, "
    SELECT t.*, u.username 
    FROM transactions t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC 
    LIMIT 10
");

// Recent payments
$recent_payments = mysqli_query($conn, "
    SELECT p.*, u.username 
    FROM payments p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC 
    LIMIT 10
");

// Recent users
$recent_users = mysqli_query($conn, "
    SELECT * FROM users 
    ORDER BY created_at DESC 
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - GmailStore</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <!-- Admin Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <i class="fas fa-cogs"></i>
            <span>GmailStore Admin</span>
        </div>
        <nav class="admin-nav">
            <ul>
                <li class="active">
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="users.php">
                        <i class="fas fa-users"></i> Pengguna
                    </a>
                </li>
                <li>
                    <a href="accounts.php">
                        <i class="fas fa-envelope"></i> Akun Gmail
                    </a>
                </li>
                <li>
                    <a href="transactions.php">
                        <i class="fas fa-exchange-alt"></i> Transaksi
                    </a>
                </li>
                <li>
                    <a href="payments.php">
                        <i class="fas fa-credit-card"></i> Pembayaran
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
            </ul>
        </nav>
        <div class="admin-user">
            <div class="admin-user-info">
                <div class="admin-user-avatar">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
                <div class="admin-user-details">
                    <div class="admin-user-name"><?php echo $_SESSION['username']; ?></div>
                    <div class="admin-user-role">Administrator</div>
                </div>
            </div>
            <a href="../logout.php" class="btn btn-danger btn-small">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Admin Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <h1>Dashboard Admin</h1>
            <div class="admin-actions">
                <div class="date-display">
                    <i class="fas fa-calendar"></i> <?php echo date('d F Y'); ?>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="admin-stats">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Pengguna</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $total_accounts; ?></div>
                    <div class="stat-label">Total Akun</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $available_accounts; ?></div>
                    <div class="stat-label">Akun Tersedia</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $pending_payments; ?></div>
                    <div class="stat-label">Pembayaran Pending</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-card">
                <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">Rp <?php echo number_format($total_revenue ?? 0, 0, ',', '.'); ?></div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon secondary">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $total_transactions; ?></div>
                    <div class="stat-label">Total Transaksi</div>
                </div>
            </div>
        </div>

        <!-- Recent Data Tables -->
        <div class="admin-tables">
            <div class="table-card">
                <div class="table-header">
                    <h3><i class="fas fa-exchange-alt"></i> Transaksi Terbaru</h3>
                    <a href="transactions.php" class="btn btn-outline btn-small">Lihat Semua</a>
                </div>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pengguna</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($transaction = mysqli_fetch_assoc($recent_transactions)): ?>
                            <tr>
                                <td>#<?php echo str_pad($transaction['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $transaction['username']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $transaction['type']; ?>">
                                        <?php echo ucfirst($transaction['type']); ?>
                                    </span>
                                </td>
                                <td>Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $transaction['status']; ?>">
                                        <?php echo ucfirst($transaction['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M H:i', strtotime($transaction['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <h3><i class="fas fa-credit-card"></i> Pembayaran Pending</h3>
                    <a href="payments.php" class="btn btn-outline btn-small">Lihat Semua</a>
                </div>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pengguna</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($payment = mysqli_fetch_assoc($recent_payments)): ?>
                            <tr>
                                <td>#<?php echo str_pad($payment['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $payment['username']; ?></td>
                                <td>Rp <?php echo number_format($payment['amount'], 0, ',', '.'); ?></td>
                                <td><?php echo str_replace('_', ' ', ucfirst($payment['method'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $payment['status']; ?>">
                                        <?php echo ucfirst($payment['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($payment['status'] == 'pending'): ?>
                                        <button class="btn btn-success btn-xs approve-payment" data-id="<?php echo $payment['id']; ?>">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs reject-payment" data-id="<?php echo $payment['id']; ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn btn-outline btn-xs view-payment" data-id="<?php echo $payment['id']; ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <h3><i class="fas fa-user-plus"></i> Pengguna Baru</h3>
                    <a href="users.php" class="btn btn-outline btn-small">Lihat Semua</a>
                </div>
                <div class="table-container">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                            <tr>
                                <td>#<?php echo str_pad($user['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['role']; ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline btn-xs edit-user" data-id="<?php echo $user['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($user['role'] != 'admin'): ?>
                                        <button class="btn btn-danger btn-xs delete-user" data-id="<?php echo $user['id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
            <div class="actions-grid">
                <button class="action-btn" id="add-account">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Akun Gmail</span>
                </button>
                <button class="action-btn" id="send-broadcast">
                    <i class="fas fa-bullhorn"></i>
                    <span>Kirim Broadcast</span>
                </button>
                <button class="action-btn" id="backup-data">
                    <i class="fas fa-database"></i>
                    <span>Backup Database</span>
                </button>
                <button class="action-btn" id="view-reports">
                    <i class="fas fa-chart-bar"></i>
                    <span>Lihat Laporan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal" id="add-account-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Akun Gmail Baru</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-account-form">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="new-account-email" required 
                               placeholder="contoh@gmail.com">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" id="new-account-password" required 
                               placeholder="Password akun">
                    </div>
                    <div class="form-group">
                        <label>Recovery Email</label>
                        <input type="email" id="new-account-recovery" 
                               placeholder="Email recovery (opsional)">
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" id="new-account-price" required 
                               placeholder="50000" min="10000" step="1000">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select id="new-account-category">
                            <option value="basic">Basic</option>
                            <option value="premium">Premium</option>
                            <option value="business">Business</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/script.js"></script>
    <script src="../assets/admin.js"></script>
</body>
</html>