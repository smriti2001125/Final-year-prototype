<?php
$pageTitle = 'Login';
require_once 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (!$email || !$pass) {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['role']    = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<div style="max-width:460px;margin:2rem auto;">
    <div class="form-container">
        <h2>Welcome Back</h2>
        <p class="subtitle">Login to report items, search the database, and track matches.</p>

        <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <div style="background:#eaf0fb;border-radius:8px;padding:0.85rem 1rem;margin-top:1.25rem;font-size:0.82rem;color:var(--accent2);">
            <strong>Demo Admin:</strong> admin@lostandfound.com / password: <code>password</code>
        </div>

        <p style="text-align:center;margin-top:1.25rem;font-size:0.88rem;color:var(--gray);">
            No account? <a href="register.php" style="color:var(--accent2);font-weight:600;">Register here</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
