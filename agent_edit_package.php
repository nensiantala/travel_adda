<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];
$id = intval($_GET['id'] ?? 0);

// Check if package belongs to this agent
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ? AND agent_id = ?");
$stmt->bind_param("ii", $id, $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    echo "Package not found or you don't have permission to edit it.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $duration = $_POST['duration'];
    $itinerary = $_POST['itinerary'];
    $status = $_POST['status'];

    $updatedImages = [];

    for ($i = 1; $i <= 4; $i++) {
        $imageField = "image$i";
        $imagePath = $package[$imageField];

        if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] === 0) {
            $imageTmp = $_FILES[$imageField]['tmp_name'];
            $imageName = time() . '_' . basename($_FILES[$imageField]['name']);
            $targetDir = 'assets/';
            $targetPath = $targetDir . $imageName;

            if (move_uploaded_file($imageTmp, $targetPath)) {
                $imagePath = $imageName;
            }
        }

        $updatedImages[$imageField] = $imagePath;
    }

    // Assign updated image values and package ID
    $image1 = $updatedImages['image1'];
    $image2 = $updatedImages['image2'];
    $image3 = $updatedImages['image3'];
    $image4 = $updatedImages['image4'];
    $package_id = $id;

    $stmt = $conn->prepare("UPDATE packages SET title=?, location=?, description=?, price=?, start_date=?, end_date=?, duration=?, itinerary=?, status=?, image1=?, image2=?, image3=?, image4=? WHERE id=? AND agent_id=?");
    $stmt->bind_param('sssdsssssssssii', 
        $title, $location, $description, $price, $start_date, $end_date,
        $duration, $itinerary, $status,
        $image1, $image2, $image3, $image4,
        $package_id, $agent_id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: agent_manage_packages.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Travel Package</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
            padding: 2rem;
        }

        form {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #00435a;
            margin-bottom: 2rem;
        }

        label {
            display: block;
            font-weight: 600;
            margin-top: 1rem;
            color: #00435a;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .image-preview {
            max-width: 200px;
            max-height: 150px;
            margin: 10px 0;
            border-radius: 8px;
        }

        .btn {
            background-color: #17B978;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #149f68;
        }

        .btn-secondary {
            background-color: #6c757d;
            margin-right: 10px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #17B978;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            color: #149f68;
        }
    </style>
</head>
<body>

<a href="agent_manage_packages.php" class="back-link">← Back to Manage Packages</a>

<form method="POST" enctype="multipart/form-data">
    <h1>Edit Travel Package</h1>

    <label>Title:
        <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" required>
    </label>

    <label>City / Location:
        <input type="text" name="location" value="<?= htmlspecialchars($package['location']) ?>" required placeholder="e.g., Manali, Goa, Jaipur">
    </label>

    <label>Description:
        <textarea name="description" required><?= htmlspecialchars($package['description']) ?></textarea>
    </label>

    <label>Price:
        <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($package['price']) ?>" required>
    </label>

    <label>Start Date:
        <input type="date" name="start_date" value="<?= htmlspecialchars($package['start_date']) ?>" required>
    </label>

    <label>End Date:
        <input type="date" name="end_date" value="<?= htmlspecialchars($package['end_date']) ?>" required>
    </label>

    <label>Duration:
        <input type="text" name="duration" value="<?= htmlspecialchars($package['duration']) ?>" required placeholder="e.g., 5 Days">
    </label>

    <label>Status:
        <select name="status" required>
            <option value="active" <?= $package['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $package['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </label>

    <label>Itinerary Details:
        <textarea name="itinerary" rows="5" placeholder="Day 1|Title|Duration per line"><?= htmlspecialchars($package['itinerary']) ?></textarea>
    </label>

    <?php for ($i = 1; $i <= 4; $i++): ?>
        <label>Image <?= $i ?>:
            <input type="file" name="image<?= $i ?>" accept="image/*">
            <?php if (!empty($package["image$i"])): ?>
                <div>
                    <strong>Current Image:</strong><br>
                    <img src="assets/<?= htmlspecialchars($package["image$i"]) ?>" class="image-preview" alt="Current Image <?= $i ?>">
                </div>
            <?php endif; ?>
        </label>
    <?php endfor; ?>

    <button type="submit" class="btn">Update Package</button>
</form>

</body>
</html> 