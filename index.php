<?php require_once __DIR__ . '/config.php'; require_login(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Notes</title>
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
                <span style="color:#94a3b8;"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></span>
                <a class="btn secondary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="title">Your Notes</div>
            </div>

            <?php
            $userId = (int)($_SESSION['user_id'] ?? 0);
            $noteError = '';
            $noteSuccess = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
                $title = trim($_POST['title'] ?? '');
                $content = trim($_POST['content'] ?? '');
                if ($title === '' || $content === '') {
                    $noteError = 'Title and content are required.';
                } else {
                    $stmt = $mysqli->prepare('INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)');
                    $stmt->bind_param('iss', $userId, $title, $content);
                    if ($stmt->execute()) {
                        $noteSuccess = 'Note added.';
                    } else {
                        $noteError = 'Failed to save note.';
                    }
                    $stmt->close();
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
                $noteId = (int)($_POST['note_id'] ?? 0);
                if ($noteId > 0) {
                    $stmt = $mysqli->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
                    $stmt->bind_param('ii', $noteId, $userId);
                    if ($stmt->execute()) {
                        $noteSuccess = 'Note deleted.';
                    } else {
                        $noteError = 'Failed to delete note.';
                    }
                    $stmt->close();
                }
            }

            $notes = [];
            $q = $mysqli->prepare('SELECT id, title, content, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC');
            $q->bind_param('i', $userId);
            $q->execute();
            $res = $q->get_result();
            while ($row = $res->fetch_assoc()) { $notes[] = $row; }
            $q->close();
            ?>

            <?php if ($noteError): ?><div class="flash error"><?php echo htmlspecialchars($noteError); ?></div><?php endif; ?>
            <?php if ($noteSuccess): ?><div class="flash success"><?php echo htmlspecialchars($noteSuccess); ?></div><?php endif; ?>

            <form method="post" style="margin-bottom:16px;">
                <input type="hidden" name="action" value="create" />
                <div class="row">
                    <div>
                        <label>Title</label>
                        <input type="text" name="title" required />
                    </div>
                </div>
                <div>
                    <label>Content</label>
                    <textarea name="content" required></textarea>
                </div>
                <button class="btn" type="submit">Add Note</button>
            </form>

            <div class="notes">
                <?php if (empty($notes)): ?>
                    <div class="empty">No notes yet. Add your first one!</div>
                <?php else: ?>
                    <div class="note-grid">
                        <?php foreach ($notes as $n): ?>
                            <div class="note">
                                <div class="note-title"><?php echo htmlspecialchars($n['title']); ?></div>
                                <div class="meta">Created <?php echo htmlspecialchars($n['created_at']); ?></div>
                                <div style="margin-top:8px; white-space:pre-wrap;"><?php echo nl2br(htmlspecialchars($n['content'])); ?></div>
                                <form method="post" style="margin-top:10px;">
                                    <input type="hidden" name="action" value="delete" />
                                    <input type="hidden" name="note_id" value="<?php echo (int)$n['id']; ?>" />
                                    <button class="btn danger" type="submit" onclick="return confirm('Delete this note?');">Delete</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>


