<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}
include 'db_connect.php';
include 'agent_header.php';

// Fetch agent info for greeting
$agent_name = $_SESSION['agent_name'] ?? 'Agent';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agent Dashboard - Travel Adda</title>
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       .total_card {
    background: #ffffff;
    color: #000000ff;
    border: 3px solid #17B978; /* Theme-colored border */
    border-radius: 16px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    min-height: 150px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.total_card h5 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    opacity: 0.9;
}

.total_card h3 {
    font-size: 2.2rem;
    font-weight: bold;
    margin: 0;
}

.total_card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 14px 25px rgba(23,185,120,0.25); /* green glow */
    border-color: #129966; /* darker green on hover */
}


        .agent-stat-card {
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: 0.3s ease;
}

.agent-stat-card:hover {
    transform: translateY(-5px);
}

.pending-card { background: #fff3cd; color: #856404; }
.accepted-card { background: #d4edda; color: #155724; }
.rejected-card { background: #f8d7da; color: #721c24; }
.revenue-card { background: #d1ecf1; color: #0c5460; }
.agent-menu {
    display: flex;
    justify-content: center;
    margin: 30px 0;
}

.agent-menu ul {
    display: flex;
    flex-wrap: wrap; /* Allows wrapping on small screens */
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 15px;
}

.agent-menu ul li {
    margin: 0;
}

.agent-menu ul li a {
    display: inline-block;
    padding: 10px 18px;
    background-color: #17B978;
    color: #fff;
    border-radius: 6px;
    font-weight: 600;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.agent-menu ul li a:hover {
    background-color: #129966;
    transform: translateY(-2px);
}

@media (max-width: 576px) {
    .agent-menu ul {
        flex-direction: column;
        align-items: center;
    }
    .agent-menu ul li a {
        width: 100%;
        text-align: center;
    }
}
.target-widget {
    background: linear-gradient(135deg, #ffffff, #f8fdfb);
    border: 2px solid #1d9665cb;
    border-radius: 16px;
    padding: 20px 25px;
    box-shadow: 0 6px 15px rgba(23, 185, 120, 0.15);
    max-width: 600px;
    margin: auto;
    font-family: 'Poppins', sans-serif;
}

.target-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.target-info {
    font-size: 0.9rem;
    color: #666;
}

.target-text {
    color: #333;
    font-size: 1rem;
    margin-bottom: 15px;
}

.progress-container {
    background: #eafaf3;
    border-radius: 50px;
    height: 30px;
    overflow: hidden;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}

.progress-bar-custom {
    background: linear-gradient(90deg, #17B978, #129966);
    height: 100%;
    text-align: center;
    line-height: 30px;
    color: white;
    font-weight: bold;
    transition: width 0.6s ease;
}

    </style>
</head>
<body>

<div class="container">
        <h4 class="text-center mb-4">Agent Panel</h4>
            <div class="agent-menu">
              <ul>
                <li><a href="agent_add_package.php">Add Package</a></li>
                <li><a href="agent_manage_packages.php">Manage Packages</a></li>
                <li><a href="agent_view_bookings.php">View Bookings</a></li>
                <!-- <li><a href="agent_manage_feedbacks.php">Manage Feedbacks</a></li> -->
              </ul>
            </div>

        <!-- Main Content -->
        <div class="col-md-12 dashboard-content">
            <div class="welcome-box text-center">
                <h3><b>Welcome,</b> <?= htmlspecialchars($agent_name); ?>!</h3>
                <p>Here’s a quick overview of your activity on Travel Adda.</p>
            </div>

            <div class="row g-4">
    <div class="col-md-5">
        <div class="total_card">
            <h5>Total Packages</h5>
            <h3>
                <?php
                $pkgStmt = $conn->prepare("SELECT COUNT(*) as total FROM packages WHERE agent_id = ?");
                $pkgStmt->bind_param("i", $_SESSION['agent_id']);
                $pkgStmt->execute();
                $pkgResult = $pkgStmt->get_result()->fetch_assoc();
                echo $pkgResult['total'];
                ?>
            </h3>
        </div>
    </div>

    <div class="col-md-5">
        <div class="total_card">
            <h5>Total Bookings</h5>
            <h3>
                <?php
                $bookStmt = $conn->prepare("
                    SELECT COUNT(*) as total 
                    FROM bookings b 
                    JOIN packages p ON b.package_id = p.id 
                    WHERE p.agent_id = ?
                ");
                $bookStmt->bind_param("i", $_SESSION['agent_id']);
                $bookStmt->execute();
                $bookResult = $bookStmt->get_result()->fetch_assoc();
                echo $bookResult['total'];
                ?>
            </h3>
        </div>
    </div>
</div>
<?php
// Example monthly target
$monthlyTarget = 15;

// Get current month's bookings
$targetStmt = $conn->prepare("
    SELECT COUNT(*) as total 
    FROM bookings b 
    JOIN packages p ON b.package_id = p.id 
    WHERE p.agent_id = ? 
      AND MONTH(b.booking_date) = MONTH(CURRENT_DATE())
      AND YEAR(b.booking_date) = YEAR(CURRENT_DATE())
");
$targetStmt->bind_param("i", $_SESSION['agent_id']);
$targetStmt->execute();
$targetResult = $targetStmt->get_result()->fetch_assoc();
$currentBookings = $targetResult['total'];

// Calculate percentage
$progressPercent = $monthlyTarget > 0 ? round(($currentBookings / $monthlyTarget) * 100) : 0;
if ($progressPercent > 100) $progressPercent = 100; // cap at 100%
?>

<div class="target-widget mt-5">
    <div class="target-header">
        <h3>🎯 Monthly Target Progress</h3>
        <span class="target-info">
            Goal: <?php echo $monthlyTarget; ?> bookings
        </span>
    </div>
    <p class="target-text">
        You’ve reached <b><?php echo $progressPercent; ?>%</b> of your booking target this month!
    </p>
    <div class="progress-container">
        <div class="progress-bar-custom" style="width: <?php echo $progressPercent; ?>%;">
            <?php echo $progressPercent; ?>%
        </div>
    </div>
</div><br>
                <!-- STAT CARDS WITH UNIQUE CSS CLASSES -->
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="agent-stat-card pending-card p-4 text-center">
        <h6>Pending</h6>
        <h3>
            <?php
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE p.agent_id = ? AND b.status = 'pending'
            ");
            $stmt->bind_param("i", $_SESSION['agent_id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo $result['total'];
            ?>
        </h3>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="agent-stat-card accepted-card p-4 text-center">
        <h6>Accepted</h6>
        <h3>
            <?php
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE p.agent_id = ? AND b.status = 'accepted'
            ");
            $stmt->bind_param("i", $_SESSION['agent_id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo $result['total'];
            ?>
        </h3>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="agent-stat-card rejected-card p-4 text-center">
        <h6>Rejected</h6>
        <h3>
            <?php
            $stmt = $conn->prepare("
                SELECT COUNT(*) as total 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE p.agent_id = ? AND b.status = 'rejected'
            ");
            $stmt->bind_param("i", $_SESSION['agent_id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo $result['total'];
            ?>
        </h3>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="agent-stat-card revenue-card p-4 text-center">
        <h6>Total Revenue</h6>
        <h3>
            ₹<?php
            $stmt = $conn->prepare("
                SELECT SUM(p.price) as total 
                FROM bookings b 
                JOIN packages p ON b.package_id = p.id 
                WHERE p.agent_id = ? AND b.status = 'accepted'
            ");
            $stmt->bind_param("i", $_SESSION['agent_id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo $result['total'] ? $result['total'] : 0;
            ?>
        </h3>
    </div>
</div>

</div><br>
            <div class="row">
  <div class="col-md-5">
    <canvas id="bookingsChart" height="200"></canvas>
  </div>
  <div class="col-md-5">
    <canvas id="revenueChart" height="200"></canvas>
  </div>
</div><br><br>
  <div class="row">
  <div class="col-md-4 mt-4">
    <canvas id="statusChart" height="200"></canvas>
  </div>
  <div class="col-md-4 mt-4">
    <canvas id="popularChart" height="200"></canvas>
  </div>
</div><br>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
fetch('agent_all_chart_data.php')
  .then(res => res.json())
  .then(data => {
    // 1. Bookings Line Chart
    const months = data.monthly_bookings.map(row => row.month);
    const bookings = data.monthly_bookings.map(row => row.total);

    new Chart(document.getElementById('bookingsChart'), {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Monthly Bookings',
          data: bookings,
          backgroundColor: '#17B978',
          borderColor: '#17B978',
          fill: false,
          tension: 0.3
        }]
       },
      options: {
        scales: {
          y: {
            beginAtZero: true, // starts Y-axis at 0
            title: {
              display: true,
              text: 'Total Bookings'
            },
            ticks: {
              stepSize: 1, // force step of 1 if needed
              callback: function(value) {
                return value + ' bookings'; // example: "5 bookings"
              }
            }
          }
        }
      }
    });

    // 2. Revenue Bar Chart
    const revMonths = data.monthly_revenue.map(row => row.month);
    const revenue = data.monthly_revenue.map(row => row.revenue);

    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: revMonths,
        datasets: [{
          label: 'Monthly Revenue (INR)',
          data: revenue,
          backgroundColor: '#1abc9c'
        }]
      }
    });

    // 3. Status Pie Chart
    const statusLabels = data.status_distribution.map(row => row.status);
    const statusData = data.status_distribution.map(row => row.count);

    new Chart(document.getElementById('statusChart'), {
      type: 'pie',
      data: {
        labels: statusLabels,
        datasets: [{
          label: 'Booking Status',
          data: statusData,
          backgroundColor: ['#3498db', '#2ecc71', '#e74c3c']
        }]
      }
    });

    // 4. Popular Packages Doughnut Chart
    const popularLabels = data.popular_packages.map(row => row.title);
    const popularCounts = data.popular_packages.map(row => row.total);

    new Chart(document.getElementById('popularChart'), {
      type: 'doughnut',
      data: {
        labels: popularLabels,
        datasets: [{
          label: 'Popular Packages',
          data: popularCounts,
          backgroundColor: ['#f39c12', '#9b59b6', '#e67e22', '#1abc9c', '#2c3e50']
        }]
      }
    });
  })
  .catch(err => {
    console.error("Chart loading failed:", err);
  });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

