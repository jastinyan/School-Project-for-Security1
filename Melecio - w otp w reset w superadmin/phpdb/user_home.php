<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['id_no'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../css/user_home.css"> 
</head>
<body>

<!-- Navbar -->
    <div class="navbar">
        <p class="system-title container">Meditation Activity Tracker</p>
    </div>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">Welcome <?= htmlspecialchars($username) ?></div>
    <a href="#">Change Username</a>
    <a href="forgot_password_flow.php">Change Password</a>
    <a href="#">Update Profile</a>
    <a href="homepage.php" class="btn-logout">Logout</a>
</div>


<!-- Main content -->
<div class="main">
    <h2>Your Calendar</h2>
    <div class="calendar" id="calendar"></div>
</div>

<!-- Calendar JS -->
<script>
    // Simple JS calendar
    function createCalendar(id) {
        const calendarDiv = document.getElementById(id);
        const date = new Date();
        const monthNames = ["January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"];
        const days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
        const year = date.getFullYear();
        const month = date.getMonth();
        const today = date.getDate();

        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month+1, 0).getDate();

        let table = '<table>';
        table += `<tr><th colspan="7">${monthNames[month]} ${year}</th></tr>`;
        table += '<tr>' + days.map(d=>`<th>${d}</th>`).join('') + '</tr>';

        let day = 1;
        for (let i = 0; i < 6; i++) {
            table += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDay) {
                    table += '<td></td>';
                } else if (day > lastDate) {
                    table += '<td></td>';
                } else {
                    const todayClass = day === today ? 'today' : '';
                    table += `<td class="${todayClass}">${day}</td>`;
                    day++;
                }
            }
            table += '</tr>';
        }
        table += '</table>';
        calendarDiv.innerHTML = table;
    }

    createCalendar('calendar');
</script>

 <!-- Footer -->
    <div class="footer">
        <p class="all">&copy; 2026 Meditation Activity Tracker. All rights reserved.</p>
    </div>
</body>
</html>
