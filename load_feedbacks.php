<?php
include 'db_connect.php';

$limit = 1;
$page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT f.*, c.username, p.title AS package_title
          FROM feedback f
          JOIN customers c ON f.customer_id = c.id
          JOIN packages p ON f.package_id = p.id
          WHERE f.status = 'approved'
          ORDER BY f.created_at DESC
          LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$html = "";

while ($fb = $result->fetch_assoc()) {
    $comment = htmlspecialchars($fb['comment']);
    $short = mb_substr($comment, 0, 100);
    $isLong = mb_strlen($comment) > 100;
    $stars = str_repeat("★", $fb['rating']) . str_repeat("☆", 5 - $fb['rating']);

    $html .= '<div class="col-md-6 mb-4">
                <div class="feedback-card">
                    <h6>' . htmlspecialchars($fb['username']) . '</h6>
                    <div class="package-name">Package: ' . htmlspecialchars($fb['package_title']) . '</div>
                    <div class="stars">' . $stars . '</div>
                    <p class="customer-comment">
                        <span class="short-comment">' . nl2br($short) . ($isLong ? '...' : '') . '</span>';
    if ($isLong) {
        $html .= '<span class="full-comment d-none">' . nl2br($comment) . '</span>
                  <a href="javascript:void(0);" class="toggle-comment text-primary" onclick="toggleComment(this)">Show more</a>';
    }
    $html .=   '</p>
                <div class="date">Reviewed on ' . date("d M Y, h:i A", strtotime($fb['created_at'])) . '</div>
                </div>
              </div>';
}

// Pagination buttons
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM feedback WHERE status = 'approved'");
$total = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$html .= '<div class="col-12">
            <nav>
                <ul class="pagination justify-content-center">';

for ($i = 1; $i <= $totalPages; $i++) {
    $active = ($i == $page) ? 'active' : '';
    $html .= '<li class="page-item ' . $active . '"><a class="page-link fb-page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
}

$html .=    '</ul>
            </nav>
        </div>';

echo $html;
?>
