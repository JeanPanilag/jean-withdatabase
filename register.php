<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Notes App</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
    <div class="auth-card">
        <div class="card">
            <div class="header">
                <div>
                    <div class="title">Note App</div>
                </div>
                <div class="auth-subtitle">Create your account</div>
            </div>
            <?php
            $error = '';
            $success = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } elseif (strlen($password) < 6) {
                    $error = 'Password must be at least 6 characters.';
                } elseif ($password !== $confirm) {
                    $error = 'Passwords do not match.';
                } else {
                    $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $error = 'Email is already registered.';
                    } else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $insert = $mysqli->prepare('INSERT INTO users (email, password_hash) VALUES (?, ?)');
                        $insert->bind_param('ss', $email, $hash);
                        if ($insert->execute()) {
                            $success = 'Account created. You can now sign in.';
                        } else {
                            $error = 'Failed to create account. Please try again.';
                        }
                        $insert->close();
                    }
                    $stmt->close();
                }
            }
            ?>
            <?php if ($error): ?><div class="flash error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
            <?php if ($success): ?><div class="flash success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
            <form method="post" autocomplete="off">
                <div>
                    <label>Email</label>
                    <input class="auth-input" type="email" name="email" required />
                </div>
                <div class="row">
                    <div>
                        <label>Password</label>
                        <input class="auth-input" type="password" name="password" minlength="6" required />
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input class="auth-input" type="password" name="confirm_password" minlength="6" required />
                    </div>
                </div>
                <div class="actions">
                    <button class="btn success" type="submit">Create account</button>
                    <a class="btn gray" href="login.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>



