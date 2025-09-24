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
    <div class="auth-card">
        <div class="card">
            <div class="header">
                <div>
                    <div class="title">Note App</div>
                </div>
                <div class="auth-subtitle">Login to continue</div>
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
                    <label>Username</label>
                    <input class="auth-input" type="email" name="email" required />
                </div>
                <div>
                    <label>Password</label>
                    <input class="auth-input" type="password" name="password" required />
                </div>
                <div class="actions">
                    <button class="btn success" type="submit">Login</button>
                    <a class="btn gray" href="register.php">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


