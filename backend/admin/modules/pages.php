<?php
/** Admin Pages Module (placeholder) */
try { $pages = Database::query("SELECT * FROM pages ORDER BY id"); } catch (Exception $e) { $pages = []; }
?>
<h5 class="mb-4"><i class="fas fa-file-alt me-2"></i>Static Pages <span class="badge bg-primary ms-2"><?= count($pages) ?></span></h5>
<div class="admin-table-card"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Title</th><th>Slug</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($pages as $p): ?>
    <tr><td><?= e($p['title_en']) ?></td><td><code><?= e($p['slug']) ?></code></td><td><?= $p['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td></tr>
    <?php endforeach; ?>
    <?php if (empty($pages)): ?><tr><td colspan="3" class="text-center py-4 text-muted">No pages. Add via database.</td></tr><?php endif; ?>
</tbody></table></div></div>
