<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);
require_once __DIR__ . '/../../config/database.php';

$hostelId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$hostelId) {
    exit("Invalid hostel ID.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM hostels WHERE hostel_id = ? AND owner_id = ?"
);
$stmt->execute([$hostelId, $_SESSION['user_id']]);
$hostel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hostel) {
    http_response_code(404);
    exit("Hostel not found.");
}

$universities = $pdo->query(
    "SELECT university_id, university_name 
     FROM universities 
     ORDER BY university_name"
)->fetchAll(PDO::FETCH_ASSOC);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Hostel | HostelConnect</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --background: #f5f7fb;
    --sidebar: #ffffff;
    --text: #172033;
    --muted: #718096;
    --border: #e5eaf2;
    --success: #16a34a;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--background);
    color: var(--text);
}

/* LAYOUT */

.layout {
    display: flex;
    min-height: 100vh;
}

/* SIDEBAR */

.sidebar {
    width: 250px;
    background: var(--sidebar);
    border-right: 1px solid var(--border);
    padding: 28px 20px;
    position: fixed;
    height: 100vh;
}

.logo {
    font-size: 23px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 45px;
}

.logo span {
    color: var(--text);
}

.sidebar-label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: 1px;
    margin-bottom: 15px;
    padding-left: 12px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 13px 14px;
    text-decoration: none;
    color: #64748b;
    border-radius: 10px;
    margin-bottom: 7px;
    font-size: 14px;
    font-weight: 500;
    transition: .3s;
}

.nav-link:hover,
.nav-link.active {
    background: #eff6ff;
    color: var(--primary);
}

.nav-link i {
    width: 19px;
    font-size: 16px;
}

/* MAIN */

.main {
    margin-left: 250px;
    width: calc(100% - 250px);
    padding: 35px 45px;
}

/* TOP HEADER */

.top-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
}

.breadcrumb {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 9px;
}

.breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

.top-header h1 {
    font-size: 27px;
    font-weight: 800;
}

.owner-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}

.avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #dbeafe;
    color: var(--primary);
    display: grid;
    place-items: center;
    font-weight: 700;
}

/* CONTENT */

.page-intro {
    margin-bottom: 25px;
}

.page-intro p {
    color: var(--muted);
    font-size: 14px;
    margin-top: 8px;
    line-height: 1.6;
}

/* FORM CARD */

.form-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    max-width: 950px;
    overflow: hidden;
    box-shadow: 0 5px 25px rgba(0,0,0,.025);
}

.card-header {
    padding: 25px 30px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 15px;
}

.card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #eff6ff;
    color: var(--primary);
    display: grid;
    place-items: center;
    font-size: 20px;
}

.card-header h3 {
    font-size: 16px;
    margin-bottom: 5px;
}

.card-header p {
    font-size: 12px;
    color: var(--muted);
}

/* FORM */

.form-body {
    padding: 30px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

.form-group.full {
    grid-column: span 2;
}

label {
    font-size: 13px;
    font-weight: 600;
}

input,
select,
textarea {
    width: 100%;
    padding: 13px 15px;
    border: 1px solid var(--border);
    border-radius: 9px;
    font-family: inherit;
    font-size: 13px;
    outline: none;
    background: #fff;
    color: var(--text);
    transition: .25s;
}

input:focus,
select:focus,
textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}

textarea {
    resize: vertical;
    min-height: 120px;
}

.field-hint {
    font-size: 11px;
    color: var(--muted);
}

/* ACTIONS */

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid var(--border);
}

.btn {
    padding: 13px 21px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    transition: .25s;
    border: none;
}

.btn-cancel {
    background: #f1f5f9;
    color: #475569;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

.btn-primary {
    background: var(--primary);
    color: white;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37,99,235,.2);
}

/* FOOTER */

.footer {
    margin-top: 30px;
    color: #94a3b8;
    font-size: 12px;
    text-align: center;
}

/* MOBILE */

@media(max-width: 900px) {

    .sidebar {
        display: none;
    }

    .main {
        margin-left: 0;
        width: 100%;
        padding: 25px 18px;
    }

}

@media(max-width: 600px) {

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-group.full {
        grid-column: span 1;
    }

    .form-body {
        padding: 20px;
    }

    .card-header {
        padding: 20px;
    }

    .top-header h1 {
        font-size: 22px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        justify-content: center;
        width: 100%;
    }

}

</style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="logo">
            Hostel<span>Connect.</span>
        </div>

        <div class="sidebar-label">OWNER MENU</div>

        <a href="../../index.php" class="nav-link">
            <i class="fas fa-house"></i>
            Home
        </a>

        <a href="view_hostels.php" class="nav-link active">
            <i class="fas fa-building"></i>
            My Hostels
        </a>

        <a href="../../index.php" class="nav-link">
            <i class="fas fa-arrow-left"></i>
            Back to Website
        </a>

    </aside>


    <!-- MAIN CONTENT -->

    <main class="main">

        <header class="top-header">

            <div>
                <div class="breadcrumb">
                    <a href="view_hostels.php">My Hostels</a>
                    / Edit Hostel
                </div>

                <h1>Edit Hostel</h1>
            </div>

            <div class="owner-profile">

                <div class="avatar">
                    <i class="fas fa-user"></i>
                </div>

                <div>
                    <strong style="font-size:13px;">Hostel Owner</strong>
                    <p style="font-size:11px;color:#718096;margin-top:4px;">
                        Account Management
                    </p>
                </div>

            </div>

        </header>


        <section class="page-intro">

            <h2 style="font-size:19px;">
                Update your hostel information
            </h2>

            <p>
                Keep your hostel details accurate and up to date
                so students can easily find and book your accommodation.
            </p>

        </section>


        <!-- FORM CARD -->

        <div class="form-card">

            <div class="card-header">

                <div class="card-icon">
                    <i class="fas fa-building"></i>
                </div>

                <div>
                    <h3>Hostel Information</h3>
                    <p>Modify the details of your registered hostel.</p>
                </div>

            </div>


            <div class="form-body">

                <form action="process_edit_hostel.php" method="POST">

                    <input type="hidden"
                           name="csrf_token"
                           value="<?= e($_SESSION['csrf_token']) ?>">

                    <input type="hidden"
                           name="hostel_id"
                           value="<?= (int)$hostel['hostel_id'] ?>">

                    <div class="form-grid">

                        <div class="form-group">

                            <label>Hostel Name *</label>

                            <input
                                type="text"
                                name="hostel_name"
                                value="<?= e($hostel['hostel_name']) ?>"
                                placeholder="Enter hostel name"
                                maxlength="150"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label>University *</label>

                            <select name="university_id" required>

                                <option value="">Select University</option>

                                <?php foreach ($universities as $university): ?>

                                    <option
                                        value="<?= (int)$university['university_id'] ?>"
                                        <?= (int)$hostel['university_id'] === (int)$university['university_id'] ? 'selected' : '' ?>
                                    >
                                        <?= e($university['university_name']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="form-group full">

                            <label>Hostel Location *</label>

                            <input
                                type="text"
                                name="location"
                                value="<?= e($hostel['location']) ?>"
                                placeholder="Enter hostel location"
                                maxlength="255"
                                required
                            >

                            <span class="field-hint">
                                Provide a clear location students can identify.
                            </span>

                        </div>


                        <div class="form-group full">

                            <label>Contact Number</label>

                            <input
                                type="tel"
                                name="contact_number"
                                value="<?= e($hostel['contact_number'] ?? '') ?>"
                                placeholder="e.g. 0241234567"
                                maxlength="30"
                            >

                        </div>


                        <div class="form-group full">

                            <label>Hostel Description</label>

                            <textarea
                                name="description"
                                placeholder="Describe your hostel, facilities, security and accommodation..."
                                maxlength="2000"
                            ><?= e($hostel['description'] ?? '') ?></textarea>

                            <span class="field-hint">
                                Give students useful information about your hostel.
                            </span>

                        </div>

                    </div>


                    <!-- BUTTONS -->

                    <div class="form-actions">

                        <a href="view_hostels.php" class="btn btn-cancel">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update Hostel
                        </button>

                    </div>

                </form>

            </div>

        </div>


        <div class="footer">
            © <?= date('Y') ?> HostelConnect. All rights reserved.
        </div>

    </main>

</div>

</body>
</html>