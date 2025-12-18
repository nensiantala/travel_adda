<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];
$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company_name']);
    $employee_count = trim($_POST['employee_count']);

    $stmt = $conn->prepare("UPDATE agents SET email=?, phone=?, company_name=?, employee_count=? WHERE id=?");
    $stmt->bind_param("ssssi", $email, $phone, $company, $employee_count, $agent_id);
    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Failed to update profile.";
    }
    $stmt->close();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    // Fetch current password hash
    $stmt = $conn->prepare("SELECT password FROM agents WHERE id = ?");
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_pass, $hashed_password)) {
        $error = "Current password is incorrect.";
    } elseif ($new_pass !== $confirm_pass) {
        $error = "New passwords do not match.";
    } else {
        $new_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE agents SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed, $agent_id);
        if ($stmt->execute()) {
            $success = "Password updated successfully.";
        } else {
            $error = "Failed to change password.";
        }
        $stmt->close();
    }
}

// Fetch agent data
$stmt = $conn->prepare("SELECT * FROM agents WHERE id = ?");
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
$agent = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agent Profile - Travel Adda</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4fcfa;
            font-family: 'Segoe UI', sans-serif;
            color: #00435a;
        }
        .profile-container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #17B978;
        }
        label {
            font-weight: 600;
            margin-top: 12px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .btn-save {
            background-color: #17B978;
            color: white;
            border: none;
            font-weight: bold;
            padding: 10px 18px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .btn-save:hover {
            background-color: #149f68;
        }
        .message {
            text-align: center;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<?php include 'agent_header.php'; ?>

<div class="container">
    <div class="profile-container">
        <h2>My Profile</h2>

        <?php if ($success): ?>
            <div class="message success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>

        <!-- Profile Update Form -->
        <form method="POST">
            <input type="hidden" name="update_profile" value="1">

            <label>Email:
                <input type="email" name="email" value="<?= htmlspecialchars($agent['email']) ?>" required>
            </label>

            <label>Phone Number:
                <input type="text" name="phone" value="<?= htmlspecialchars($agent['phone']) ?>" required>
            </label>

            <label>Company Name:
                <input type="text" name="company_name" value="<?= htmlspecialchars($agent['company_name']) ?>" required>
            </label>

            <label>Employee Count:
                <select name="employee_count" required>
                    <?php
                    $options = ['1-10', '11-50', '51-100', '100+'];
                    foreach ($options as $opt) {
                        $selected = ($agent['employee_count'] === $opt) ? 'selected' : '';
                        echo "<option value=\"$opt\" $selected>$opt</option>";
                    }
                    ?>
                </select>
            </label>

            <button type="submit" class="btn-save">Update Profile</button>
        </form>

        <hr class="my-4">

        <!-- Password Change Form -->
        <h4 class="text-center mt-4 mb-3 text-secondary">Change Password</h4>
        <form method="POST">
            <input type="hidden" name="change_password" value="1">

            <label>Current Password:
                <input type="password" name="current_password" required>
            </label>

            <label>New Password:
                <input type="password" name="new_password" required>
            </label>

            <label>Confirm New Password:
                <input type="password" name="confirm_password" required>
            </label>

            <button type="submit" class="btn-save">Change Password</button>
        </form>
    </div>
</div>

</body>
</html>
