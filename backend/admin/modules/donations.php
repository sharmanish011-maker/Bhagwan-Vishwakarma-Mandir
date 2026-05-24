<?php
/**
 * Admin Donations Module
 */
$statusFilter = sanitize($_GET['status'] ?? 'all');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $id = sanitizeInt($_POST['donation_id'] ?? 0);
    $newStatus = sanitize($_POST['new_status'] ?? '');
    if ($id > 0 && in_array($newStatus, ['completed', 'pending', 'failed'])) {
        Database::execute("UPDATE donations SET status = :s WHERE id = :id", ['s' => $newStatus, 'id' => $id]);
        setFlash('success', 'Donation status updated.');
    }
    redirect('index.php?module=donations');
}

$where = $statusFilter !== 'all' ? " WHERE status = :status" : "";
$params = $statusFilter !== 'all' ? ['status' => $statusFilter] : [];
$donations = Database::query("SELECT * FROM donations $where ORDER BY created_at DESC", $params);
$totalAmt = Database::queryOne("SELECT COALESCE(SUM(amount),0) as t FROM donations WHERE status = 'completed'")['t'] ?? 0;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Donations <span class="badge bg-success ms-2"><?= count($donations) ?></span></h5>
    <span class="fw-bold text-success">Total: <?= formatCurrency((float)$totalAmt) ?></span>
</div>

<div class="admin-table-card">
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Receipt</th><th>Donor</th><th>Amount</th><th>Category</th><th>Method</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($donations as $d): ?>
        <tr>
            <td><strong><?= e($d['receipt_number']) ?></strong></td>
            <td><?= $d['is_anonymous'] ? '<em>Anonymous</em>' : e($d['donor_name']) ?></td>
            <td class="fw-bold"><?= formatCurrency((float)$d['amount']) ?></td>
            <td><span class="event-category-badge"><?= e(ucfirst(str_replace('_',' ',$d['category']))) ?></span></td>
            <td><?= e($d['payment_method'] ?? '-') ?></td>
            <td><?= formatDate($d['created_at']) ?></td>
            <td><?= statusBadge($d['status']) ?></td>
            <td>
                <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="donation_id" value="<?= $d['id'] ?>">
                    <select name="new_status" class="form-select form-select-sm d-inline-block" style="width:auto;" onchange="this.form.submit()">
                        <option value="">--</option>
                        <option value="completed">✅ Complete</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="failed">❌ Failed</option>
                    </select>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($donations)): ?><tr><td colspan="8" class="text-center py-4 text-muted">No donations yet</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
