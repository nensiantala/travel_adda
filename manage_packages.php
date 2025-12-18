
<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle status toggle
if (isset($_GET['toggle_id'])) {
    $id = intval($_GET['toggle_id']);
    $current_status = $_GET['current'];
    $new_status = ($current_status === 'active') ? 'inactive' : 'active';
    $stmt = $conn->prepare("UPDATE packages SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_packages.php");
    exit();
}


// Handle deletion
if (isset($_GET['delete_package_id'])) {
    $delete_id = intval($_GET['delete_package_id']);

    // First delete related feedbacks
    $conn->query("DELETE FROM feedback WHERE package_id = $delete_id");

    // Then delete the package
    $stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_packages.php");
    exit();
}


// Fetch all packages
$result = $conn->query("SELECT * FROM packages");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Packages</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Nunito Sans', sans-serif;
        background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
        color: #00435a;
        padding: 2rem;
    }

    h1 {
        text-align: center;
        margin-bottom: 2rem;
        color: #00435a;
        font-size: 2.2rem;
        animation: fadeDown 1s ease-out;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        animation: fadeInTable 0.8s ease-in-out;
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

    tr:hover {
        background-color: #f0fbf7;
    }

    a {
        text-decoration: none;
        color: #17B978;
        font-weight: 600;
        transition: color 0.3s;
    }

    a:hover {
        color: #149f68;
    }

    .actions a {
        margin-right: 0.8rem;
    }

    .add-btn {
        display: inline-block;
        margin-top: 2rem;
        padding: 0.8rem 1.5rem;
        background-color: #17B978;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .add-btn:hover {
        background-color: #149f68;
    }

    /* Animations */
    @keyframes fadeInTable {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }

        th {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }

        tr {
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            background: #fff;
        }

        td {
            border: none;
            position: relative;
            padding-left: 50%;
            font-size: 0.95rem;
        }

        td:before {
            position: absolute;
            top: 1rem;
            left: 1rem;
            width: 45%;
            white-space: nowrap;
            font-weight: bold;
            color: #00435a;
        }

        td:nth-of-type(1):before { content: "ID"; }
        td:nth-of-type(2):before { content: "Title"; }
        td:nth-of-type(3):before { content: "Description"; }
        td:nth-of-type(4):before { content: "Price"; }
        td:nth-of-type(5):before { content: "Status"; }
        td:nth-of-type(6):before { content: "Actions"; }

        .add-btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

</head>
<body>
<h1>Packages List</h1>
<a href="admin_dashboard.php" class="btn btn-secondary ">Back to Dashboard</a>
<table border="0.8" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th><th>Title</th><th>Description</th><th>Price</th><th>Status</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= $row['price'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
            <td>
                <a href="edit_package.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="manage_packages.php?delete_package_id=<?= $row['id'] ?>" onclick="return confirm('Delete this package?');">Delete</a> |
                <a href="manage_packages.php?toggle_id=<?= $row['id'] ?>&current=<?= $row['status'] ?>">
                    Set <?= ($row['status'] === 'active') ? 'Inactive' : 'Active' ?>
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<a href="add_package.php">Add New Package</a>
</body>
</html>
