<?php
session_start();
require 'db_connection.php';

// Role check (case-insensitive)
if (!isset($_SESSION['id_no']) || strtolower($_SESSION['role']) !== 'super_admin') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// Filters
$searchUser = trim($_GET['username'] ?? '');
$searchDate = trim($_GET['date'] ?? '');

// Pagination
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

// Base query
$baseQuery = "FROM system_logs WHERE 1=1";
$params = [];
$types = "";

// Username filter
if ($searchUser !== '') {
    $baseQuery .= " AND username LIKE ?";
    $params[] = "%$searchUser%";
    $types .= "s";
}

// Date filter
if ($searchDate !== '') {
    $baseQuery .= " AND DATE(timestamp) = ?";
    $params[] = $searchDate;
    $types .= "s";
}

// Count total rows
$countSql = "SELECT COUNT(*) AS total " . $baseQuery;
$countStmt = $conn->prepare($countSql);
if ($params) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

$totalPages = max(ceil($totalRows / $limit), 1);

// Main query (THIS WAS MISSING)
$dataSql = "
    SELECT id_no, username, action, timestamp, browser, ip_address
    $baseQuery
    ORDER BY timestamp DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($dataSql);

// Bind params + limit & offset
if ($params) {
    $typesWithLimit = $types . "ii";
    $params[] = $limit;
    $params[] = $offset;
    $stmt->bind_param($typesWithLimit, ...$params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Logs</title>
    <link rel="stylesheet" href="../css/superadmin_home.css">
    
</head>
<body>
    <!-- Navbar -->
<div class="navbar">
    <div class="navbar-content container">
        <p class="system-title">Super Admin Dashboard</p>
    </div>
</div>

<div class="dashboard-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">Welcome <?= htmlspecialchars($username) ?></div>
        <h3>Management</h3>

        <a href="superadmin_home.php">Overview</a>
        <a href="system_logs.php">Logs</a>
        <a href="#">Admin Management</a>
        <a href="#.php">User Management</a>
        <a href="#.php">Meditation Records</a>
        <a href="#.php">Account Settings</a>
        <a href="homepage.php" class="btn-logout">Logout</a>
    </aside>

    <main class="dashboard-main container logs-container">
        <h1>System Logs</h1>

        <!-- Filter/Search Bar -->
        <form class="filter-bar" method="get" action="">
            <input type="text" name="username" placeholder="Search by username" value="<?= htmlspecialchars($searchUser) ?>">
            <input type="date" name="date" value="<?= htmlspecialchars($searchDate) ?>">
            <button type="submit">Filter</button>
            <button type="button" onclick="window.location.href='system_logs.php'">Reset</button>
        </form>

        <!-- Table -->
        <div class="logs-table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Username</th>
                        <th>Action</th>
                        <th>Date & Time</th>
                        <th>Browser</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['id_no']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td class="<?= $row['action'] === 'login' ? 'action-login' : 'action-logout' ?>">
                                <?= htmlspecialchars($row['action']) ?>
                            </td>
                            <td><?= $row['timestamp'] ?></td>
                            <td><?= htmlspecialchars($row['browser']) ?></td>
                            <td><?= htmlspecialchars($row['ip_address']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&username=<?= urlencode($searchUser) ?>&date=<?= urlencode($searchDate) ?>">« Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a class="<?= $i === $page ? 'active' : '' ?>"
                    href="?page=<?= $i ?>&username=<?= urlencode($searchUser) ?>&date=<?= urlencode($searchDate) ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&username=<?= urlencode($searchUser) ?>&date=<?= urlencode($searchDate) ?>">Next »</a>
                <?php endif; ?>
            </div>

        </div>
    </main>
<!-- Footer -->
<div class="footer">
    <p>&copy; 2026 Meditation Activity Tracker</p>
</div>

</body>
</html>
