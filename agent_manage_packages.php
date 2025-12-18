<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Handle status toggle
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);
    $current_status = $_GET['current'];
    $new_status = ($current_status === 'active') ? 'inactive' : 'active';

    $stmt = $conn->prepare("UPDATE packages SET status=? WHERE id=? AND agent_id=?");
    $stmt->bind_param("sii", $new_status, $id, $agent_id);
    $stmt->execute();
    $stmt->close();
    header("Location: agent_manage_packages.php");
    exit();
}

// Handle deletion
if (isset($_GET['delete_package_id'])) {
    $delete_id = intval($_GET['delete_package_id']);
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ? AND agent_id = ?");
    $stmt->bind_param("ii", $delete_id, $agent_id);
    $stmt->execute();
    $stmt->close();
    header("Location: agent_manage_packages.php");
    exit();
}

// Fetch all packages for this agent
$stmt = $conn->prepare("SELECT * FROM packages WHERE agent_id = ?");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Packages - Agent</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
            color: #00435a;
            padding: 2rem;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: #00435a;
            font-size: 2.2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #17B978;
            color: white;
            font-size: 1rem;
        }
        tr:hover { background-color: #f0fbf7; }
        a { color: #17B978; font-weight: 600; text-decoration: none; }
        a:hover { color: #149f68; }
        .actions a { margin-right: 0.8rem; }
        .add-btn {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.8rem 1.5rem;
            background-color: #17B978;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
        }
        .add-btn:hover {
            background-color: #149f68;
        }
    </style>
</head>
<body>
    <div class="container">
    
    <h1>Your Packages</h1>
    <a href="agent_dashboard.php" class="add-btn">← Back to Dashboard</a><br>

    <table border="0.8" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th><th>Title</th><th>Location</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['location'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 50, "...")) ?></td>
                <td>₹<?= number_format($row['price'], 2) ?></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td class="actions">
                    <a href="agent_edit_package.php?id=<?= $row['id'] ?>">Edit</a> |
                    <a href="?delete_package_id=<?= $row['id'] ?>" onclick="return confirm('Delete this package?');">Delete</a> |
                    <a href="?toggle_id=<?= $row['id'] ?>&current=<?= $row['status'] ?>">
                        Set <?= ($row['status'] === 'active') ? 'Inactive' : 'Active' ?>
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="agent_add_package.php" class="add-btn">+ Add New Package</a>
        </div>
</body>
</html>
