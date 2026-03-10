<?php
$pageTitle = 'Search Items';
require_once 'includes/header.php';

$keyword  = trim($_GET['keyword'] ?? '');
$type     = $_GET['type'] ?? '';
$category = $_GET['category'] ?? '';
$location = trim($_GET['location'] ?? '');

$sql = "SELECT i.*, u.name as reporter FROM items i LEFT JOIN users u ON i.user_id = u.id WHERE 1=1";
$params = [];

if ($keyword) {
    $sql .= " AND (i.title LIKE ? OR i.description LIKE ?)";
    $params[] = "%$keyword%";
    $params[] = "%$keyword%";
}
if ($type) {
    $sql .= " AND i.type = ?";
    $params[] = $type;
}
if ($category) {
    $sql .= " AND i.category = ?";
    $params[] = $category;
}
if ($location) {
    $sql .= " AND i.location LIKE ?";
    $params[] = "%$location%";
}

$sql .= " ORDER BY i.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="page-title">🔍 Search Items</h1>
<p class="page-subtitle">Search through all lost and found reports</p>

<div class="form-container" style="max-width:100%;margin-bottom:2rem;">
    <form method="GET" action="search.php">
        <div class="form-row">
            <div class="form-group">
                <label>Keyword</label>
                <input type="text" name="keyword" placeholder="e.g. wallet, phone, keys..." value="<?php echo htmlspecialchars($keyword); ?>">
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" placeholder="e.g. Library, Canteen..." value="<?php echo htmlspecialchars($location); ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Type</label>
                <select name="type">
                    <option value="">All Types</option>
                    <option value="lost" <?php if ($type==='lost') echo 'selected'; ?>>Lost</option>
                    <option value="found" <?php if ($type==='found') echo 'selected'; ?>>Found</option>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="Wallet/Purse" <?php if ($category==='Wallet/Purse') echo 'selected'; ?>>Wallet / Purse</option>
                    <option value="Phone" <?php if ($category==='Phone') echo 'selected'; ?>>Phone</option>
                    <option value="Keys" <?php if ($category==='Keys') echo 'selected'; ?>>Keys</option>
                    <option value="Bag/Backpack" <?php if ($category==='Bag/Backpack') echo 'selected'; ?>>Bag / Backpack</option>
                    <option value="ID/Documents" <?php if ($category==='ID/Documents') echo 'selected'; ?>>ID / Documents</option>
                    <option value="Jewellery" <?php if ($category==='Jewellery') echo 'selected'; ?>>Jewellery</option>
                    <option value="Electronics" <?php if ($category==='Electronics') echo 'selected'; ?>>Electronics</option>
                    <option value="Clothing" <?php if ($category==='Clothing') echo 'selected'; ?>>Clothing</option>
                    <option value="Books/Notes" <?php if ($category==='Books/Notes') echo 'selected'; ?>>Books / Notes</option>
                    <option value="Other" <?php if ($category==='Other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
        </div>
        <div style="display:flex;gap:1rem;">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="search.php" class="btn btn-outline">Clear</a>
        </div>
    </form>
</div>

<div class="section-header">
    <h2><?php echo count($items); ?> Result<?php echo count($items) !== 1 ? 's' : ''; ?> Found</h2>
</div>

<?php if (empty($items)): ?>
<div class="empty-state">
    <div class="icon">🔎</div>
    <h3>No items found</h3>
    <p>Try different keywords or clear the filters.</p>
</div>
<?php else: ?>
<div class="cards-grid">
    <?php foreach ($items as $item): ?>
    <div class="card">
        <div class="card-img">
            <?php if ($item['image'] && file_exists('uploads/' . $item['image'])): ?>
                <img src="/lost_and_found/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Item">
            <?php else: ?>
                <?php echo $item['type'] === 'lost' ? '❓' : '📦'; ?>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <span class="badge badge-<?php echo $item['type']; ?>"><?php echo strtoupper($item['type']); ?></span>
            <span class="badge badge-<?php echo $item['status']; ?>"><?php echo strtoupper($item['status']); ?></span>
            <h3 style="margin-top:0.5rem;"><?php echo htmlspecialchars($item['title']); ?></h3>
            <div class="card-meta">
                <span>📂 <?php echo htmlspecialchars($item['category'] ?? 'N/A'); ?></span>
                <span>📍 <?php echo htmlspecialchars($item['location'] ?? 'N/A'); ?></span>
            </div>
            <p><?php echo htmlspecialchars(substr($item['description'] ?? '', 0, 90)) . (strlen($item['description'] ?? '') > 90 ? '...' : ''); ?></p>
            <div class="card-meta">
                <span>👤 <?php echo htmlspecialchars($item['reporter'] ?? 'Anonymous'); ?></span>
                <span>🗓 <?php echo date('d M Y', strtotime($item['date_occurred'] ?? $item['created_at'])); ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
