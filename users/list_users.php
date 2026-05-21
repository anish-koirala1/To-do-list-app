<?php
require_once '../includes/auth_check.php';
require_once '../config/database.php';

$pageTitle = 'All Users';
$pdo = getDB();

/* ---- Sanitise inputs ---- */
$search  = trim($_GET['search']  ?? '');
$role    = $_GET['role']         ?? '';
$status  = $_GET['status']       ?? '';
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;

$allowedRoles    = ['Admin', 'Teacher', 'Student'];
$allowedStatuses = ['1', '0'];

if (!in_array($role, $allowedRoles, true))       $role   = '';
if (!in_array($status, $allowedStatuses, true))  $status = '';

/* ---- Build WHERE clause ---- */
$conditions = [];
$params     = [];

if ($search !== '') {
    $conditions[] = '(full_name LIKE ? OR email LIKE ?)';
    $params[]     = '%' . $search . '%';
    $params[]     = '%' . $search . '%';
}

if ($role !== '') {
    $conditions[] = 'role = ?';
    $params[]     = $role;
}

if ($status !== '') {
    $conditions[] = 'is_active = ?';
    $params[]     = (int)$status;
}

$where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

/* ---- Total count for pagination ---- */
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users $where");
$countStmt->execute($params);
$total      = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

/* ---- Fetch rows ---- */
$stmt = $pdo->prepare("SELECT user_id, full_name, email, role, is_active, created_date
                        FROM users
                        $where
                        ORDER BY created_date DESC, user_id DESC
                        LIMIT ? OFFSET ?");
$stmt->execute(array_merge($params, [$perPage, $offset]));
$users = $stmt->fetchAll();

/* ---- Stats ---- */
$statsStmt = $pdo->query("SELECT
    COUNT(*) AS total,
    SUM(is_active = 1) AS active,
    SUM(is_active = 0) AS inactive,
    SUM(role = 'Admin') AS admins,
    SUM(role = 'Teacher') AS teachers,
    SUM(role = 'Student') AS students
    FROM users");
$stats = $statsStmt->fetch();

/* ---- Flash messages ---- */
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require_once '../includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">All Users</h1>
        <p class="page-subtitle">Manage and monitor all registered accounts</p>
    </div>
    <?php if (isAdmin()): ?>
    <a href="add_user.php" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Add User
    </a>
    <?php endif; ?>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>" data-autohide="4000">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<!-- Stats -->
<div class="stats-row">
    <div class="stat-card accent-primary">
        <span class="stat-label">Total Users</span>
        <span class="stat-value"><?= $stats['total'] ?></span>
    </div>
    <div class="stat-card accent-success">
        <span class="stat-label">Active</span>
        <span class="stat-value"><?= $stats['active'] ?></span>
    </div>
    <div class="stat-card accent-danger">
        <span class="stat-label">Disabled</span>
        <span class="stat-value"><?= $stats['inactive'] ?></span>
    </div>
    <div class="stat-card accent-warning">
        <span class="stat-label">Teachers</span>
        <span class="stat-value"><?= $stats['teachers'] ?></span>
    </div>
    <div class="stat-card accent-primary">
        <span class="stat-label">Students</span>
        <span class="stat-value"><?= $stats['students'] ?></span>
    </div>
</div>

<!-- User Table -->
<div class="card">
    <!-- Search / Filter Bar -->
    <form method="GET" action="list_users.php" id="filter-form">
        <div class="filter-bar">
            <div class="filter-group" style="flex:2; min-width:220px;">
                <label for="search-input">Search</label>
                <input
                    type="text"
                    id="search-input"
                    name="search"
                    class="form-control"
                    placeholder="Search by name or email&hellip;"
                    value="<?= htmlspecialchars($search) ?>"
                    autocomplete="off"
                >
            </div>

            <div class="filter-group">
                <label for="role-filter">Role</label>
                <select id="role-filter" name="role" class="form-control filter-select">
                    <option value="">All Roles</option>
                    <?php foreach ($allowedRoles as $r): ?>
                        <option value="<?= $r ?>" <?= $role === $r ? 'selected' : '' ?>><?= $r ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="status-filter">Status</label>
                <select id="status-filter" name="status" class="form-control filter-select">
                    <option value="">All Status</option>
                    <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Active</option>
                    <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Search
                </button>
                <?php if ($search || $role || $status): ?>
                    <a href="list_users.php" class="btn btn-outline btn-sm">Clear</a>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <p>No users found<?= ($search || $role || $status) ? ' matching your filters' : '' ?>.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <?php if (isAdmin()): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="color:var(--color-text-muted); font-size:.8rem;"><?= $user['user_id'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($user['full_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower(htmlspecialchars($user['role'])) ?>">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge badge-active">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-inactive">Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td style="color:var(--color-text-muted); font-size:.82rem;">
                                <?= htmlspecialchars(date('M j, Y', strtotime($user['created_date']))) ?>
                            </td>
                            <?php if (isAdmin()): ?>
                            <td>
                                <div class="actions">
                                    <a href="edit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-outline btn-sm">Edit</a>

                                    <!-- Disable / Enable -->
                                    <form method="POST" action="disable_user.php"
                                          data-confirm="<?= $user['is_active']
                                              ? 'Disable this user? They will no longer be able to log in.'
                                              : 'Re-enable this user?' ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                        <input type="hidden" name="redirect" value="list_users.php">
                                        <button type="submit" class="btn btn-sm <?= $user['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                            <?= $user['is_active'] ? 'Disable' : 'Enable' ?>
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="delete_user.php"
                                          data-confirm="Permanently delete &quot;<?= htmlspecialchars(addslashes($user['full_name'])) ?>&quot;? This cannot be undone.">
                                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                        <input type="hidden" name="redirect" value="list_users.php">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <?php
        $queryBase = http_build_query(array_filter([
            'search' => $search,
            'role'   => $role,
            'status' => $status,
        ]));
        $queryBase = $queryBase ? $queryBase . '&' : '';
        ?>
        <div class="pagination">
            <a href="?<?= $queryBase ?>page=<?= $page - 1 ?>"
               class="page-link <?= $page <= 1 ? 'disabled' : '' ?>">&#8592;</a>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <?php if ($p === 1 || $p === $totalPages || abs($p - $page) <= 2): ?>
                    <a href="?<?= $queryBase ?>page=<?= $p ?>"
                       class="page-link <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
                <?php elseif (abs($p - $page) === 3): ?>
                    <span class="page-info">&hellip;</span>
                <?php endif; ?>
            <?php endfor; ?>

            <a href="?<?= $queryBase ?>page=<?= $page + 1 ?>"
               class="page-link <?= $page >= $totalPages ? 'disabled' : '' ?>">&#8594;</a>

            <span class="page-info">
                <?= (($page - 1) * $perPage) + 1 ?>&ndash;<?= min($page * $perPage, $total) ?> of <?= $total ?>
            </span>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>
