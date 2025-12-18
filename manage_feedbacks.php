<?php
include 'db_connect.php';

$sql = "SELECT f.id, f.rating, f.comment, f.status, f.created_at, c.username, p.title 
        FROM feedback f 
        JOIN customers c ON f.customer_id = c.id 
        JOIN packages p ON f.package_id = p.id 
        ORDER BY f.created_at DESC";

$feedbacks = $conn->query($sql);

if (!$feedbacks) {
    die("Query Failed: " . $conn->error);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Feedbacks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    thead tr th {
    background-color: #17B978 !important;
    color: white !important;
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
    .comment-cell {
    max-width: 300px;
    word-wrap: break-word;
    white-space: normal;
}
.toggle-comment {
    cursor: pointer;
    font-weight: 500;
    display: inline-block;
    margin-top: 5px;
}

    </style>
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4">Manage Feedback</h3>
    <a href="admin_dashboard.php" class="btn ">Back to Dashboard</a>
    <table class="table table-striped">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Package</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Status</th>
                <th>Change Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; while ($fb = $feedbacks->fetch_assoc()): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($fb['username']) ?></td>
                    <td><?= htmlspecialchars($fb['title']) ?></td>
                    <td><?= $fb['rating'] ?>/5</td>
                    <?php
$comment = htmlspecialchars($fb['comment']);
$short = mb_substr($comment, 0, 100);
$isLong = mb_strlen($comment) > 100;
?>
<td class="comment-cell">
    <span class="short-comment"><?= nl2br($short) ?><?= $isLong ? '...' : '' ?></span>
    <?php if ($isLong): ?>
        <span class="full-comment d-none"><?= nl2br($comment) ?></span>
        <a href="javascript:void(0);" class="toggle-comment text-primary" onclick="toggleComment(this)">Show more</a>
    <?php endif; ?>
</td>

                    <td><?= date("d M Y, h:i A", strtotime($fb['created_at'])) ?></td>
                    <td><span class="badge bg-<?= $fb['status'] === 'approved' ? 'success' : ($fb['status'] === 'blocked' ? 'danger' : 'warning') ?>">
                        <?= ucfirst($fb['status']) ?></span>
                    </td>
                    <td>
                        <form method="post" action="update_feedback_status.php">
                            <input type="hidden" name="feedback_id" value="<?= $fb['id'] ?>">
                            <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="pending" <?= $fb['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $fb['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="blocked" <?= $fb['status'] === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                            </select>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script>
function toggleComment(link) {
    const td = link.closest('.comment-cell');
    const shortText = td.querySelector('.short-comment');
    const fullText = td.querySelector('.full-comment');

    if (fullText.classList.contains('d-none')) {
        shortText.style.display = 'none';
        fullText.classList.remove('d-none');
        link.textContent = 'Show less';
    } else {
        shortText.style.display = 'inline';
        fullText.classList.add('d-none');
        link.textContent = 'Show more';
    }
}
</script>

</body>
</html>
