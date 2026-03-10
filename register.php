<?php
$pageTitle = 'Register';
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (!$name || !$email || !$pass || !$confirm) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif (strlen($pass) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($pass !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'Email already registered. Please login.';
        } else {
            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hash]);
            $success = 'Account created successfully! You can now <a href="login.php">login</a>.';
        }
    }
}
?>

<div style="max-width:500px;margin:2rem auto;">
    <div class="form-container">
        <h2>Create Account</h2>
        <p class="subtitle">Join the Lost & Found system to report and track items.</p>

        <?php if ($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Your full name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="At least 6 characters" required>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
        <p style="text-align:center;margin-top:1.25rem;font-size:0.88rem;color:var(--gray);">
            Already have an account? <a href="login.php" style="color:var(--accent2);font-weight:600;">Login here</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
