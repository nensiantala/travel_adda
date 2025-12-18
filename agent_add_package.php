<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

$agent_id = $_SESSION['agent_id']; // Get agent ID from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $duration = $_POST['duration'];
    $itinerary = $_POST['itinerary'];

    $upload_dir = 'assets/';
    $imageFields = ['image1', 'image2', 'image3', 'image4'];
    $uploadedImages = [];

    foreach ($imageFields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $filename = time() . '_' . basename($_FILES[$field]['name']);
            $tmp_name = $_FILES[$field]['tmp_name'];
            $destination = $upload_dir . $filename;

            if (move_uploaded_file($tmp_name, $destination)) {
                $uploadedImages[$field] = $filename;
            } else {
                $upload_error = "Failed to upload $field.";
                break;
            }
        } else {
            $uploadedImages[$field] = null;
        }
    }

    if (empty($upload_error)) {
        $stmt = $conn->prepare("INSERT INTO packages (title, location, description, price, status, image1, image2, image3, image4, start_date, end_date, duration, itinerary, agent_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssdsssssssssi",
            $title,
            $location,
            $description,
            $price,
            $status,
            $uploadedImages['image1'],
            $uploadedImages['image2'],
            $uploadedImages['image3'],
            $uploadedImages['image4'],
            $start_date,
            $end_date,
            $duration,
            $itinerary,
            $agent_id
        );
        $stmt->execute();
        $stmt->close();
        header("Location: agent_manage_packages.php");
        exit();
    }
}
?>


<!-- Add Package HTML: Use same as before with image preview -->

<!DOCTYPE html>
<html>
<head>
    <title>Add Package</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap');
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
            color: #00435a;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form {
            background-color: #fff;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            color: #00435a;
            margin-bottom: 2rem;
        }
        label {
            display: block;
            font-weight: 600;
            margin-top: 1rem;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
            margin-top: 0.3rem;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        button {
            width: 100%;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 700;
            background-color: #17B978;
            color: white;
            border: none;
            border-radius: 10px;
            margin-top: 2rem;
            cursor: pointer;
        }
        button:hover {
            background-color: #149f68;
        }
        .preview-img {
            display: none;
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            margin-top: 10px;
            border-radius: 10px;
        }
        .error-msg {
            color: red;
            text-align: center;
        }
        .btn {
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
    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Add New Travel Package</h2>
<a href="agent_dashboard.php" class="btn ">Back to Dashboard</a>

    <?php if (isset($upload_error)): ?>
        <div class="error-msg"><?= $upload_error ?></div>
    <?php endif; ?>

    <label>Title:
        <input type="text" name="title" required>
    </label>

    <label>City / Location:
        <input type="text" name="location" required placeholder="e.g., Manali, Goa, Jaipur">
    </label>

    <label>Description:
        <textarea name="description" required></textarea>
    </label>

    <label>Price:
        <input type="number" name="price" step="0.01" required>
    </label>

    <label>Start Date:
        <input type="date" name="start_date" id="start_date" required onchange="formatDate('start_date', 'start_preview')">
        <small id="start_preview" class="text-muted"></small>
    </label>

    <label>End Date:
        <input type="date" name="end_date" id="end_date" required onchange="formatDate('end_date', 'end_preview')">
        <small id="end_preview" class="text-muted"></small>
    </label>


    <label>Duration (e.g., 5 Days):
        <input type="text" name="duration" required>
    </label>

    <label>Status:
        <select name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </label>

    <label>Itinerary Details <small>(Format: Day|Title|Duration per line)</small>:
        <textarea name="itinerary" rows="5" placeholder="Day 1|Arrival & Hotel Check-in|Full Day&#10;Day 2|Sightseeing|1 Day"></textarea>
    </label>

    <?php for ($i = 1; $i <= 4; $i++): ?>
        <label>Image <?= $i ?>:
            <input type="file" name="image<?= $i ?>" accept="image/*" onchange="previewImage(event, 'preview<?= $i ?>')">
            <img id="preview<?= $i ?>" class="preview-img" alt="Image Preview <?= $i ?>" style="display:none;">
        </label>
    <?php endfor; ?>

    <button type="submit">Add Package</button>
</form>


<script>
function previewImage(event, previewId) {
    const reader = new FileReader();
    reader.onload = function () {
        const img = document.getElementById(previewId);
        img.src = reader.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function formatDate(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    if (input.value) {
        const dateParts = input.value.split('-'); // yyyy-mm-dd
        preview.innerText = `Selected: ${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
    } else {
        preview.innerText = '';
    }
}

</script>

</body>
</html>
