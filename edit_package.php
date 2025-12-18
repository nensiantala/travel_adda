<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    echo "Package not found.";
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
    $category = $_POST['category']; // ✅ NEW

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

    // ✅ Added category in update query
    $stmt = $conn->prepare("UPDATE packages 
        SET title=?, location=?, description=?, price=?, start_date=?, end_date=?, duration=?, itinerary=?, status=?, category=?, image1=?, image2=?, image3=?, image4=? 
        WHERE id=?");
    $stmt->bind_param('sssdssssssssssi', 
        $title, $location, $description, $price, $start_date, $end_date,
        $duration, $itinerary, $status, $category,
        $image1, $image2, $image3, $image4,
        $package_id
    );

    $stmt->execute();
    $stmt->close();

    header("Location: manage_packages.php");
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
            font-weight: bold;
            display: block;
            margin-top: 1.3rem;
            color: #00435a;
        }

        input, textarea, select {
            width: 100%;
            padding: 0.75rem;
            margin-top: 0.4rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #17B978;
            outline: none;
        }

        .preview-img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-top: 0.6rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .note {
            font-size: 0.85rem;
            color: #777;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #17B978;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            border: none;
            border-radius: 10px;
            margin-top: 2rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #149f68;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 1.8rem;
            color: #17B978;
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            color: #0d845a;
        }
    </style>
</head>
<body>

<h1>Edit Travel Package</h1>

<form method="POST" enctype="multipart/form-data">
    <label>Title:
        <input type="text" name="title" value="<?= htmlspecialchars($package['title']) ?>" required>
    </label>
    
    <label>City / Location:
        <input type="text" name="location" value="<?= htmlspecialchars($package['location']) ?>" required>
    </label>

    <label>Description:
        <textarea name="description" rows="4" required><?= htmlspecialchars($package['description']) ?></textarea>
    </label>

    <label>Price (₹):
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($package['price']) ?>" required>
    </label>

    <label>Start Date:
        <input type="date" name="start_date" value="<?= htmlspecialchars($package['start_date']) ?>" required>
    </label>

    <label>End Date:
        <input type="date" name="end_date" value="<?= htmlspecialchars($package['end_date']) ?>" required>
    </label>

    <label>Duration (e.g. 5 Days / 4 Nights):
        <input type="text" name="duration" value="<?= htmlspecialchars($package['duration']) ?>" required>
    </label>

    <label>Status:
        <select name="status" required>
            <option value="active" <?= $package['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $package['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </label>

    <!-- ✅ NEW CATEGORY DROPDOWN -->
    <label>Category:
        <select name="category" required>
            <option value="Beach" <?= $package['category'] === 'Beach' ? 'selected' : '' ?>>Beach</option>
            <option value="Mountain" <?= $package['category'] === 'Mountain' ? 'selected' : '' ?>>Mountain</option>
            <option value="City Vibe" <?= $package['category'] === 'City Vibe' ? 'selected' : '' ?>>City Vibe</option>
            <option value="Nature" <?= $package['category'] === 'Nature' ? 'selected' : '' ?>>Nature</option>
            <option value="Heritage" <?= $package['category'] === 'Heritage' ? 'selected' : '' ?>>Heritage</option>
            <option value="Adventure" <?= $package['category'] === 'Adventure' ? 'selected' : '' ?>>Adventure</option>
            <option value="Other" <?= $package['category'] === 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
    </label>

    <label>Itinerary Details <span class="note">(Each line in format: Day|Title|Duration)</span>:
        <textarea name="itinerary" rows="5"><?= htmlspecialchars($package['itinerary']) ?></textarea>
    </label>

    <?php for ($i = 1; $i <= 4; $i++): 
        $imgKey = 'image' . $i;
    ?>
        <label>Current Image <?= $i ?>:</label>
        <?php if (!empty($package[$imgKey])): ?>
            <img src="assets/<?= htmlspecialchars($package[$imgKey]) ?>" class="preview-img" id="preview<?= $i ?>" alt="Image <?= $i ?>">
        <?php else: ?>
            <p class="note">No image uploaded.</p>
            <img src="#" class="preview-img" id="preview<?= $i ?>" style="display:none;">
        <?php endif; ?>

        <label>Change Image <?= $i ?>:
            <input type="file" name="image<?= $i ?>" accept="image/*" onchange="previewImage(event, 'preview<?= $i ?>')">
        </label>
    <?php endfor; ?>

    <button type="submit">Update Package</button>
</form>

<a href="manage_packages.php">← Back to Manage Packages</a>

<script>
function previewImage(event, previewId) {
    const input = event.target;
    const reader = new FileReader();
    reader.onload = function () {
        const preview = document.getElementById(previewId);
        preview.src = reader.result;
        preview.style.display = 'block';
    };
    if (input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

</body>
</html>
