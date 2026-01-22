<?php
require_once 'auth.php';
requireLogin();
require_once 'config.php';

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Get user's gmail accounts
$accounts_sql = "SELECT * FROM gmail_accounts WHERE buyer_id = $user_id ORDER BY sold_at DESC";
$accounts_result = mysqli_query($conn, $accounts_sql);

// Get user's transactions
$transactions_sql = "SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$transactions_result = mysqli_query($conn, $transactions_sql);

// Get user's payments
$payments_sql = "SELECT * FROM payments WHERE user_id = $user_id ORDER BY created_at DESC";
$payments_result = mysqli_query($conn, $payments_sql);

// Get available gmail accounts for sale
$available_sql = "SELECT * FROM gmail_accounts WHERE status = 'available' ORDER BY price ASC LIMIT 6";
$available_result = mysqli_query($conn, $available_sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GmailStore</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-envelope"></i>
                    <span>GmailStore</span>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="#my-accounts">Akun Saya</a></li>
                    <li><a href="#purchase">Beli Akun</a></li>
                    <li><a href="#payment">Pembayaran</a></li>
                    <li><a href="#history">Riwayat</a></li>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="admin/index.php">Admin Panel</a></li>
                    <?php endif; ?>
                </ul>
                <div class="user-menu">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name"><?php echo $_SESSION['username']; ?></div>
                            <div class="user-balance">
                                <i class="fas fa-wallet"></i> Rp <?php echo number_format($_SESSION['user_balance'], 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>
                    <a href="logout.php" class="btn btn-outline btn-small">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="dashboard-container">
        <div class="container">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
                <div class="balance-card">
                    <div class="balance-info">
                        <h3>Saldo Anda</h3>
                        <div class="balance-amount">Rp <?php echo number_format($_SESSION['user_balance'], 0, ',', '.'); ?></div>
                        <a href="#deposit" class="btn btn-primary btn-small">
                            <i class="fas fa-plus-circle"></i> Top Up Saldo
                        </a>
                    </div>
                    <div class="balance-stats">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo mysqli_num_rows($accounts_result); ?></div>
                            <div class="stat-label">Akun Dimiliki</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                <?php 
                                $active_sql = "SELECT COUNT(*) as count FROM gmail_accounts 
                                             WHERE buyer_id = $user_id AND status = 'sold'";
                                $active_result = mysqli_query($conn, $active_sql);
                                $active = mysqli_fetch_assoc($active_result);
                                echo $active['count'];
                                ?>
                            </div>
                            <div class="stat-label">Akun Aktif</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Accounts Section -->
            <section id="purchase" class="dashboard-section">
                <div class="section-header">
                    <h2><i class="fas fa-shopping-cart"></i> Akun Gmail Tersedia</h2>
                    <a href="#all-accounts" class="btn btn-outline">Lihat Semua</a>
                </div>
                <div class="accounts-grid">
                    <?php while ($account = mysqli_fetch_assoc($available_result)): ?>
                    <div class="account-card">
                        <div class="account-header">
                            <div class="account-email"><?php echo $account['email']; ?></div>
                            <div class="account-price">Rp <?php echo number_format($account['price'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="account-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span>Dibuat: <?php echo date('d M Y', strtotime($account['created_at'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Status: <?php echo ucfirst($account['status']); ?></span>
                            </div>
                        </div>
                        <div class="account-actions">
                            <?php if ($_SESSION['user_balance'] >= $account['price']): ?>
                            <button class="btn btn-primary btn-small buy-btn" data-id="<?php echo $account['id']; ?>" data-price="<?php echo $account['price']; ?>">
                                <i class="fas fa-cart-plus"></i> Beli Sekarang
                            </button>
                            <?php else: ?>
                            <button class="btn btn-outline btn-small" disabled>
                                <i class="fas fa-exclamation-circle"></i> Saldo Tidak Cukup
                            </button>
                            <?php endif; ?>
                            <a href="#detail" class="btn btn-outline btn-small">Detail</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </section>

            <!-- My Accounts Section -->
            <section id="my-accounts" class="dashboard-section">
                <div class="section-header">
                    <h2><i class="fas fa-envelope-open"></i> Akun Gmail Saya</h2>
                </div>
                <?php if (mysqli_num_rows($accounts_result) > 0): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Tanggal Pembelian</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($account = mysqli_fetch_assoc($accounts_result)): ?>
                            <tr>
                                <td><?php echo $account['email']; ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($account['sold_at'])); ?></td>
                                <td>Rp <?php echo number_format($account['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $account['status']; ?>">
                                        <?php echo ucfirst($account['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-outline btn-small view-details" data-email="<?php echo $account['email']; ?>">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-envelope fa-3x"></i>
                    <h3>Belum ada akun Gmail</h3>
                    <p>Belilah akun Gmail pertama Anda dari koleksi kami.</p>
                    <a href="#purchase" class="btn btn-primary">Beli Akun Sekarang</a>
                </div>
                <?php endif; ?>
            </section>

            <!-- Payment History Section -->
            <section id="history" class="dashboard-section">
                <div class="section-header">
                    <h2><i class="fas fa-history"></i> Riwayat Transaksi</h2>
                </div>
                <?php if (mysqli_num_rows($transactions_result) > 0): ?>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($transaction = mysqli_fetch_assoc($transactions_result)): ?>
                            <tr>
                                <td><?php echo date('d M Y H:i', strtotime($transaction['created_at'])); ?></td>
                                <td><?php echo ucfirst($transaction['type']); ?></td>
                                <td>Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $transaction['status']; ?>">
                                        <?php echo ucfirst($transaction['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $transaction['description']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-receipt fa-3x"></i>
                    <h3>Belum ada transaksi</h3>
                    <p>Riwayat transaksi Anda akan muncul di sini.</p>
                </div>
                <?php endif; ?>
            </section>
        </div>
    </div>

    <!-- Deposit Modal -->
    <div class="modal" id="deposit-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Top Up Saldo</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="deposit-form">
                    <div class="form-group">
                        <label>Jumlah Top Up</label>
                        <input type="number" id="deposit-amount" min="10000" max="10000000" 
                               placeholder="Masukkan jumlah" required>
                    </div>
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select id="payment-method" required>
                            <option value="">Pilih metode</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="e_wallet">E-Wallet</option>
                            <option value="credit_card">Kartu Kredit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-credit-card"></i> Lanjutkan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
    <script>
        // Buy account functionality
        document.querySelectorAll('.buy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const accountId = this.dataset.id;
                const accountPrice = this.dataset.price;
                
                if (confirm(`Beli akun seharga Rp ${accountPrice.toLocaleString('id-ID')}?`)) {
                    // Send AJAX request to purchase
                    fetch('includes/purchase.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            account_id: accountId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Pembelian berhasil! Detail akun telah dikirim ke email Anda.');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                }
            });
        });

        // Deposit modal
        document.querySelectorAll('[href="#deposit"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('deposit-modal').style.display = 'block';
            });
        });

        // Close modal
        document.querySelector('.close-modal').addEventListener('click', function() {
            document.getElementById('deposit-modal').style.display = 'none';
        });
    </script>
</body>
</html>