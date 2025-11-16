<?php
session_start();

// Simple debug - remove this after it works
error_log("Admin login accessed");

if(isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if($username === 'admin' && $password === 'heartocean123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials! Use admin / heartocean123";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Heart of D' Ocean Resort</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body { background: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-container { background: var(--card); padding: 2rem; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 style="text-align: center; margin-bottom: 2rem;">Heart of D' Ocean Resort</h1>
        <h2 style="text-align: center; margin-bottom: 1.5rem;">Admin Login</h2>
        
        <?php if($error): ?>
            <div style="color: var(--error); margin-bottom: 1rem; text-align: center; padding: 0.75rem; background: rgba(229,62,62,0.1); border-radius: 6px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-card" style="box-shadow: none; padding: 0;">
            <label>Username
                <input type="text" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </label>
            <label>Password
                <input type="password" name="password" required>
            </label>
            <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">Login</button>
        </form>
        <p class="muted" style="text-align: center; margin-top: 1rem;">Demo credentials: admin / heartocean123</p>
    </div>
</body>
</html>