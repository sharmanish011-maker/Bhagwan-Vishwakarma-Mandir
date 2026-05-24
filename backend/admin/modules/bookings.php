<?php
/**
 * Admin Bookings Module
 */
$statusFilter = sanitize($_GET['status'] ?? 'all');

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    $bookingId = sanitizeInt($_POST['booking_id'] ?? 0);
    if ($bookingId > 0) {
        if (in_array($action, ['approve', 'reject', 'complete', 'cancel', 'delete'])) {
            if ($action === 'delete') {
                Database::execute("DELETE FROM bookings WHERE id = :id", ['id' => $bookingId]);
                setFlash('success', 'Booking deleted.');
            } else {
                $statusMap = ['approve' => 'approved', 'reject' => 'rejected', 'complete' => 'completed', 'cancel' => 'cancelled'];
                Database::execute("UPDATE bookings SET status = :s WHERE id = :id", ['s' => $statusMap[$action], 'id' => $bookingId]);
                setFlash('success', 'Booking ' . $action . 'd.');
            }
        }
    }
    redirect('index.php?module=bookings' . ($statusFilter !== 'all' ? '&status=' . $statusFilter : ''));
}

$where = $statusFilter !== 'all' ? " WHERE b.status = :status" : "";
$params = $statusFilter !== 'all' ? ['status' => $statusFilter] : [];
$bookings = Database::query("SELECT b.*, p.name_en as puja_name FROM bookings b LEFT JOIN pujas p ON b.puja_id = p.id $where ORDER BY b.created_at DESC", $params);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Manage Bookings <span class="badge bg-warning ms-2"><?= count($bookings) ?></span></h5>
</div>

<div class="admin-tabs">
    <?php foreach (['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label): ?>
    <a href="?module=bookings&status=<?= $key ?>" class="admin-tab <?= $statusFilter === $key ? 'active' : '' ?>"><?= $label ?></a>
    <?php endforeach; ?>
</div>

<div class="admin-table-card">
    <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Booking #</th><th>Devotee</th><th>Phone</th><th>Puja</th><th>Date</th><th>Amount</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><strong><?= e($b['booking_number']) ?></strong></td>
            <td><?= e($b['devotee_name']) ?></td>
            <td><?= e($b['devotee_phone'] ?? '') ?></td>
            <td><?= e($b['puja_name'] ?? '-') ?></td>
            <td><?= formatDate($b['puja_date']) ?></td>
            <td class="fw-bold"><?= formatCurrency((float)$b['total_amount']) ?></td>
            <td><?= statusBadge($b['status']) ?></td>
            <td>
                <div class="btn-group btn-group-sm">
                    <?php if ($b['status'] === 'pending'): ?>
                    <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="booking_id" value="<?= $b['id'] ?>"><input type="hidden" name="action" value="approve"><button class="btn btn-success btn-sm" title="Approve"><i class="fas fa-check"></i></button></form>
                    <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="booking_id" value="<?= $b['id'] ?>"><input type="hidden" name="action" value="reject"><button class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-times"></i></button></form>
                    <?php endif; ?>
                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this booking?')"><?= csrfField() ?><input type="hidden" name="booking_id" value="<?= $b['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button></form>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?><tr><td colspan="8" class="text-center py-4 text-muted">No bookings found</td></tr><?php endif; ?>
    </tbody></table></div>
</div>
