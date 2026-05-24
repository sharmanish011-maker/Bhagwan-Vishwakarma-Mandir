<?php
/**
 * Admin Contacts (Messages) Module
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    $id = sanitizeInt($_POST['message_id'] ?? 0);
    if ($id > 0) {
        if ($action === 'read') Database::execute("UPDATE contact_messages SET is_read = 1 WHERE id = :id", ['id' => $id]);
        elseif ($action === 'unread') Database::execute("UPDATE contact_messages SET is_read = 0 WHERE id = :id", ['id' => $id]);
        elseif ($action === 'delete') Database::execute("DELETE FROM contact_messages WHERE id = :id", ['id' => $id]);
        setFlash('success', 'Message updated.');
    }
    redirect('index.php?module=contacts');
}

$messages = Database::query("SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC");
?>

<h5 class="mb-4"><i class="fas fa-inbox me-2"></i>Contact Messages <span class="badge bg-danger ms-2"><?= count($messages) ?></span></h5>

<div class="admin-table-card">
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th></th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($messages as $m): ?>
        <tr class="<?= !$m['is_read'] ? 'fw-bold' : '' ?>">
            <td><?= !$m['is_read'] ? '🔵' : '⚪' ?></td>
            <td><?= e($m['name']) ?></td>
            <td><a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a></td>
            <td><?= e($m['subject']) ?></td>
            <td><?= formatDateTime($m['created_at']) ?></td>
            <td>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#msg<?= $m['id'] ?>"><i class="fas fa-eye"></i></button>
                <?php if (!$m['is_read']): ?>
                <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="message_id" value="<?= $m['id'] ?>"><input type="hidden" name="action" value="read"><button class="btn btn-outline-success btn-sm" title="Mark Read"><i class="fas fa-check"></i></button></form>
                <?php endif; ?>
                <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="message_id" value="<?= $m['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form>
            </td>
        </tr>
        <!-- Message Modal -->
        <div class="modal fade" id="msg<?= $m['id'] ?>"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5>Message from <?= e($m['name']) ?></h5><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
            <p><strong>Email:</strong> <?= e($m['email']) ?></p>
            <p><strong>Phone:</strong> <?= e($m['phone'] ?? '-') ?></p>
            <p><strong>Subject:</strong> <?= e($m['subject']) ?></p>
            <hr><p><?= nl2br(e($m['message'])) ?></p>
            <small class="text-muted"><?= formatDateTime($m['created_at']) ?> | IP: <?= e($m['ip_address'] ?? '-') ?></small>
        </div></div></div></div>
        <?php endforeach; ?>
        <?php if (empty($messages)): ?><tr><td colspan="6" class="text-center py-4 text-muted">No messages</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
