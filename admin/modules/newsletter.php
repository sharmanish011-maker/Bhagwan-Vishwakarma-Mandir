<?php
/** Admin Newsletter Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    if ($action === 'delete') {
        Database::execute("DELETE FROM newsletter_subscribers WHERE id = :id", ['id' => sanitizeInt($_POST['sub_id'])]);
        setFlash('success', 'Subscriber removed.');
    } elseif ($action === 'export') {
        $subs = Database::query("SELECT email, created_at FROM newsletter_subscribers ORDER BY created_at DESC");
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Email', 'Subscribed On']);
        foreach ($subs as $s) fputcsv($out, [$s['email'], $s['created_at']]);
        fclose($out);
        exit;
    }
    redirect('index.php?module=newsletter');
}
$subscribers = Database::query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>Newsletter Subscribers <span class="badge bg-primary ms-2"><?= count($subscribers) ?></span></h5>
    <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="action" value="export"><button class="btn btn-outline-success btn-sm"><i class="fas fa-file-csv me-2"></i>Export CSV</button></form>
</div>

<div class="admin-table-card">
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th>#</th><th>Email</th><th>IP Address</th><th>Subscribed</th><th>Action</th></tr></thead><tbody>
        <?php foreach ($subscribers as $i => $s): ?>
        <tr><td><?= $i + 1 ?></td><td><?= e($s['email']) ?></td><td><?= e($s['ip_address'] ?? '-') ?></td><td><?= formatDateTime($s['created_at']) ?></td>
            <td><form method="POST" class="d-inline" onsubmit="return confirm('Remove subscriber?')"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="sub_id" value="<?= $s['id'] ?>"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($subscribers)): ?><tr><td colspan="5" class="text-center py-4 text-muted">No subscribers</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
