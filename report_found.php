<?php
$pageTitle = 'Report Found Item';
require_once 'includes/header.php';
require_once 'includes/matching.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $date        = $_POST['date_occurred'];

    if (!$title || !$category || !$description || !$location || !$date) {
        $error = 'Please fill in all required fields.';
    } else {
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if (in_array($ext, $allowed)) {
                $imageName = uniqid('img_') . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
            } else {
                $error = 'Only jpg, jpeg, png, gif, webp images allowed.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("INSERT INTO items (user_id, type, title, category, description, location, date_occurred, image) VALUES (?, 'found', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title, $category, $description, $location, $date, $imageName]);
            $itemId = $pdo->lastInsertId();
            runMatching($pdo, $itemId, 'found');
            $success = 'Found item reported successfully! We will notify you if a match is found.';
        }
    }
}
?>

<div style="max-width:620px;margin:0 auto;">
    <h1 class="page-title">Report a Found Item</h1>
    <p class="page-subtitle">Help return this item to its rightful owner.</p>

    <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

    <div class="form-container" style="max-width:100%;">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Item Title *</label>
                <input type="text" name="title" placeholder="e.g. Blue Nokia phone" required value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category *</label>
                    <select name="category" required>
                        <option value="">-- Select --</option>
                        <option value="Wallet/Purse">Wallet / Purse</option>
                        <option value="Phone">Phone</option>
                        <option value="Keys">Keys</option>
                        <option value="Bag/Backpack">Bag / Backpack</option>
                        <option value="ID/Documents">ID / Documents</option>
                        <option value="Jewellery">Jewellery</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Books/Notes">Books / Notes</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date Found *</label>
                    <input type="date" name="date_occurred" required value="<?php echo htmlspecialchars($_POST['date_occurred'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Location Found *</label>
                <input type="text" name="location" placeholder="e.g. Canteen, near entrance" required value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" placeholder="Describe the item: color, brand, condition, any markings..." required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Upload Image (optional)</label>
                <input type="file" name="image" id="image" accept="image/*">
                <img id="image-preview" src="" alt="Preview" style="display:none;margin-top:0.5rem;max-height:180px;border-radius:8px;border:1px solid var(--border);">
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="background:var(--success);">Submit Found Report</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
