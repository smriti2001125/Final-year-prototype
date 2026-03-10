<?php
$pageTitle = 'Home';
require_once 'includes/header.php';

// Fetch stats
$totalLost = $pdo->query("SELECT COUNT(*) FROM items WHERE type='lost'")->fetchColumn();
$totalFound = $pdo->query("SELECT COUNT(*) FROM items WHERE type='found'")->fetchColumn();
$totalMatched = $pdo->query("SELECT COUNT(*) FROM items WHERE status='matched'")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();

// Fetch recent items
$stmt = $pdo->query("SELECT i.*, u.name as reporter FROM items i LEFT JOIN users u ON i.user_id = u.id ORDER BY i.created_at DESC LIMIT 6");
$recentItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero">
    <h1>Lost Something? Found Something?<br>We've Got You Covered.</h1>
    <p>Report lost or found items, search the database, and our smart matching system will connect you with the right person.</p>
    <div class="hero-btns">
        <a href="report_lost.php" class="btn btn-primary">📢 Report Lost Item</a>
        <a href="report_found.php" class="btn btn-secondary">✅ Report Found Item</a>
        <a href="search.php" class="btn btn-secondary">🔍 Search Items</a>
    </div>
</div>

<div class="stats-bar">
    <div class="stat-card">
        <div class="stat-num"><?php echo $totalLost; ?></div>
        <div class="stat-label">Lost Items</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $totalFound; ?></div>
        <div class="stat-label">Found Items</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $totalMatched; ?></div>
        <div class="stat-label">Matched</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $totalUsers; ?></div>
        <div class="stat-label">Users</div>
    </div>
</div>

<div class="section-header">
    <h2>Recent Reports</h2>
    <p>Latest lost and found items submitted by users</p>
</div>

<?php if (empty($recentItems)): ?>
<div class="empty-state">
    <div class="icon">📭</div>
    <h3>No items reported yet</h3>
    <p>Be the first to report a lost or found item!</p>
</div>
<?php else: ?>
<div class="cards-grid">
    <?php foreach ($recentItems as $item): ?>
    <div class="card">
        <div class="card-img">
            <?php if ($item['image'] && file_exists('uploads/' . $item['image'])): ?>
                <img src="/lost_and_found/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Item image">
            <?php else: ?>
                <?php echo $item['type'] === 'lost' ? '❓' : '📦'; ?>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <span class="badge badge-<?php echo $item['type']; ?>"><?php echo strtoupper($item['type']); ?></span>
            <?php if ($item['status'] === 'matched'): ?>
                <span class="badge badge-matched">MATCHED</span>
            <?php endif; ?>
            <h3 style="margin-top:0.5rem;"><?php echo htmlspecialchars($item['title']); ?></h3>
            <div class="card-meta">
                <span>📂 <?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></span>
                <span>📍 <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></span>
            </div>
            <p><?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 80)) . (strlen($item['description'] ?? '') > 80 ? '...' : ''); ?></p>
            <div class="card-meta">
                <span>👤 <?php echo htmlspecialchars($item['reporter'] ?? 'Anonymous'); ?></span>
                <span>🗓 <?php echo date('d M Y', strtotime($item['created_at'])); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<div style="text-align:center;margin-top:1rem;">
    <a href="search.php" class="btn btn-outline">View All Items</a>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
