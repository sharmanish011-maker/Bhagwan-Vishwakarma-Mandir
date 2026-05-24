<?php
/** Admin Pujas Module */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrfToken()) {
    $action = sanitize($_POST['action'] ?? '');
    if ($action === 'create') {
        Database::insert("INSERT INTO pujas (name_en, name_hi, description_en, description_hi, price, duration, category, icon, sort_order, is_active) VALUES (:ne, :nh, :de, :dh, :p, :dur, :cat, :icon, :so, :act)",
            ['ne' => sanitize($_POST['name_en']), 'nh' => sanitize($_POST['name_hi'] ?? ''), 'de' => sanitize($_POST['description_en'] ?? ''), 'dh' => sanitize($_POST['description_hi'] ?? ''), 'p' => sanitizeFloat($_POST['price']), 'dur' => sanitize($_POST['duration'] ?? ''), 'cat' => sanitize($_POST['category'] ?? 'special'), 'icon' => sanitize($_POST['icon'] ?? 'fa-om'), 'so' => sanitizeInt($_POST['sort_order'] ?? 0), 'act' => isset($_POST['is_active']) ? 1 : 0]);
        setFlash('success', 'Puja created.');
    } elseif ($action === 'delete') {
        Database::execute("DELETE FROM pujas WHERE id = :id", ['id' => sanitizeInt($_POST['puja_id'])]);
        setFlash('success', 'Puja deleted.');
    } elseif ($action === 'toggle') {
        Database::execute("UPDATE pujas SET is_active = 1 - is_active WHERE id = :id", ['id' => sanitizeInt($_POST['puja_id'])]);
        setFlash('success', 'Status toggled.');
    }
    redirect('index.php?module=pujas');
}
$pujas = Database::query("SELECT * FROM pujas ORDER BY sort_order");
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="fas fa-fire me-2"></i>Manage Pujas <span class="badge bg-primary ms-2"><?= count($pujas) ?></span></h5>
    <button class="btn btn-admin-primary" data-bs-toggle="modal" data-bs-target="#pujaModal"><i class="fas fa-plus me-2"></i>Add Puja</button>
</div>
<div class="admin-table-card"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Icon</th><th>Name</th><th>Price</th><th>Category</th><th>Duration</th><th>Active</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($pujas as $p): ?>
    <tr><td><i class="fas <?= e($p['icon']) ?> text-saffron" style="font-size:1.25rem;"></i></td><td><strong><?= e($p['name_en']) ?></strong><br><small class="text-muted"><?= e($p['name_hi'] ?? '') ?></small></td><td class="fw-bold"><?= formatCurrency((float)$p['price']) ?></td><td><span class="event-category-badge"><?= e(ucfirst($p['category'])) ?></span></td><td><?= e($p['duration'] ?? '-') ?></td><td>
        <form method="POST" class="d-inline"><?= csrfField() ?><input type="hidden" name="action" value="toggle"><input type="hidden" name="puja_id" value="<?= $p['id'] ?>"><button class="btn btn-sm <?= $p['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $p['is_active'] ? 'Active' : 'Off' ?></button></form></td>
        <td><form method="POST" class="d-inline" onsubmit="return confirm('Delete?')"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="puja_id" value="<?= $p['id'] ?>"><button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button></form></td>
    </tr>
    <?php endforeach; ?>
    <?php if (empty($pujas)): ?><tr><td colspan="7" class="text-center py-4 text-muted">No pujas</td></tr><?php endif; ?>
</tbody></table></div></div>

<!-- Add Puja Modal -->
<div class="modal fade" id="pujaModal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5>Add Puja</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <form method="POST"><div class="modal-body"><?= csrfField() ?><input type="hidden" name="action" value="create">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name (EN) *</label><input type="text" name="name_en" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Name (HI)</label><input type="text" name="name_hi" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Price (₹) *</label><input type="number" name="price" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Duration</label><input type="text" name="duration" class="form-control" placeholder="45 mins"></div>
            <div class="col-md-4"><label class="form-label">Category</label><select name="category" class="form-select"><option value="daily">Daily</option><option value="special">Special</option><option value="festival">Festival</option><option value="personal">Personal</option></select></div>
            <div class="col-md-4"><label class="form-label">Icon (FA class)</label><input type="text" name="icon" class="form-control" value="fa-om" placeholder="fa-fire"></div>
            <div class="col-md-4"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
            <div class="col-md-4 d-flex align-items-end"><div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" checked><label class="form-check-label">Active</label></div></div>
            <div class="col-12"><label class="form-label">Description (EN)</label><textarea name="description_en" class="form-control" rows="3"></textarea></div>
            <div class="col-12"><label class="form-label">Description (HI)</label><textarea name="description_hi" class="form-control" rows="3"></textarea></div>
        </div>
    </div><div class="modal-footer"><button class="btn btn-admin-primary"><i class="fas fa-save me-2"></i>Save Puja</button></div></form>
</div></div></div>
