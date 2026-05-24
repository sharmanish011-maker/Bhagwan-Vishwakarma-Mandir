<?php
/**
 * Admin Dashboard Module
 */
// Stats
try {
    $totalBookings = Database::queryOne("SELECT COUNT(*) as c FROM bookings")['c'] ?? 0;
    $pendingBookings = Database::queryOne("SELECT COUNT(*) as c FROM bookings WHERE status = 'pending'")['c'] ?? 0;
    $totalDonations = Database::queryOne("SELECT COALESCE(SUM(amount), 0) as total FROM donations WHERE status = 'completed'")['total'] ?? 0;
    $activeEvents = Database::queryOne("SELECT COUNT(*) as c FROM events WHERE is_active = 1 AND start_date > NOW()")['c'] ?? 0;
    $subscribers = Database::queryOne("SELECT COUNT(*) as c FROM newsletter_subscribers")['c'] ?? 0;
    $unreadMsgs = Database::queryOne("SELECT COUNT(*) as c FROM contact_messages WHERE is_read = 0")['c'] ?? 0;
} catch (Exception $e) {
    $totalBookings = $pendingBookings = $totalDonations = $activeEvents = $subscribers = $unreadMsgs = 0;
}

try { $recentBookings = Database::query("SELECT b.*, p.name_en as puja_name FROM bookings b LEFT JOIN pujas p ON b.puja_id = p.id ORDER BY b.created_at DESC LIMIT 10"); } catch (Exception $e) { $recentBookings = []; }
try { $recentDonations = Database::query("SELECT * FROM donations ORDER BY created_at DESC LIMIT 10"); } catch (Exception $e) { $recentDonations = []; }
?>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-saffron"><i class="fas fa-calendar-check"></i></div><div class="stat-info"><h3><?= $totalBookings ?></h3><p>Total Bookings</p></div></div></div>
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-gold"><i class="fas fa-hourglass-half"></i></div><div class="stat-info"><h3><?= $pendingBookings ?></h3><p>Pending Bookings</p></div></div></div>
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-green"><i class="fas fa-rupee-sign"></i></div><div class="stat-info"><h3><?= formatCurrency((float)$totalDonations) ?></h3><p>Total Donations</p></div></div></div>
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-blue"><i class="fas fa-calendar-days"></i></div><div class="stat-info"><h3><?= $activeEvents ?></h3><p>Active Events</p></div></div></div>
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-purple"><i class="fas fa-envelope-open-text"></i></div><div class="stat-info"><h3><?= $subscribers ?></h3><p>Newsletter Subscribers</p></div></div></div>
    <div class="col-lg-4 col-md-6"><div class="stat-card"><div class="stat-icon bg-red"><i class="fas fa-inbox"></i></div><div class="stat-info"><h3><?= $unreadMsgs ?></h3><p>Unread Messages</p></div></div></div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-auto"><a href="?module=bookings" class="quick-action-btn"><i class="fas fa-plus"></i> View Bookings</a></div>
    <div class="col-auto"><a href="?module=events" class="quick-action-btn"><i class="fas fa-calendar-plus"></i> Manage Events</a></div>
    <div class="col-auto"><a href="?module=gallery" class="quick-action-btn"><i class="fas fa-images"></i> Upload Photos</a></div>
    <div class="col-auto"><a href="?module=settings" class="quick-action-btn"><i class="fas fa-cog"></i> Site Settings</a></div>
</div>

<!-- Recent Data Tables -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="card-header"><h5><i class="fas fa-calendar-check me-2"></i>Recent Bookings</h5><span class="badge bg-warning"><?= count($recentBookings) ?></span></div>
            <div class="table-responsive"><table class="table table-hover"><thead><tr><th>#</th><th>Devotee</th><th>Puja</th><th>Date</th><th>Status</th></tr></thead><tbody>
                <?php foreach ($recentBookings as $b): ?>
                <tr><td><?= e($b['booking_number']) ?></td><td><?= e($b['devotee_name']) ?></td><td><?= e($b['puja_name'] ?? '-') ?></td><td><?= formatDate($b['puja_date']) ?></td><td><?= statusBadge($b['status']) ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($recentBookings)): ?><tr><td colspan="5" class="text-center text-muted py-3">No bookings yet</td></tr><?php endif; ?>
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="admin-table-card">
            <div class="card-header"><h5><i class="fas fa-hand-holding-heart me-2"></i>Recent Donations</h5><span class="badge bg-success"><?= count($recentDonations) ?></span></div>
            <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Receipt</th><th>Donor</th><th>Amount</th><th>Category</th><th>Status</th></tr></thead><tbody>
                <?php foreach ($recentDonations as $d): ?>
                <tr><td><?= e($d['receipt_number']) ?></td><td><?= e($d['is_anonymous'] ? 'Anonymous' : $d['donor_name']) ?></td><td class="fw-bold"><?= formatCurrency((float)$d['amount']) ?></td><td><span class="event-category-badge"><?= e(ucfirst(str_replace('_',' ',$d['category']))) ?></span></td><td><?= statusBadge($d['status']) ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($recentDonations)): ?><tr><td colspan="5" class="text-center text-muted py-3">No donations yet</td></tr><?php endif; ?>
            </tbody></table></div>
        </div>
    </div>
</div>
