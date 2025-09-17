<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Notes App</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
    <div class="appbar">
        <div class="appbar-inner">
            <div class="brand">
                <span>Notes</span>
                <span class="badge">PHP + MySQL</span>
            </div>
            <div class="actions">
                <a class="btn secondary" href="register.php">Create account</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="title">Sign in</div>
                <a href="register.php">Create account</a>
            </div>
            <?php
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $stmt = $mysqli->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($userId, $passwordHash);
                if ($stmt->fetch()) {
                    if (password_verify($password, $passwordHash)) {
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['email'] = $email;
                        header('Location: index.php');
                        exit;
                    }
                }
                $stmt->close();
                $error = 'Invalid email or password.';
            }
            ?>
            <?php if ($error): ?><div class="flash error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <form method="post" autocomplete="off">
                <div>
                    <label>Email</label>
                    <input type="email" name="email" required />
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="password" required />
                </div>
                <button class="btn" type="submit">Sign in</button>
                <div class="auth-links">No account? <a href="register.php">Create one</a></div>
            </form>
        </div>
    </div>
</body>
</html>


