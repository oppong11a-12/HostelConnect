<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);

require_once __DIR__ . '/../../config/database.php';

$sql = "
    SELECT
        h.hostel_id,
        h.hostel_name,
        h.location,
        h.description,
        h.contact_number,
        u.university_name
    FROM hostels h
    INNER JOIN universities u
        ON h.university_id = u.university_id
    WHERE h.owner_id = :owner_id
    ORDER BY h.created_at DESC
";

$statement = $pdo->prepare($sql);

$statement->execute([
    ':owner_id' => $_SESSION['user_id']
]);

$hostels = $statement->fetchAll();

$success = $_SESSION['hostel_success'] ?? '';
$error = $_SESSION['hostel_error'] ?? '';

unset($_SESSION['hostel_success'], $_SESSION['hostel_error']);

// Generate CSRF token for delete forms.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Hostels | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <style>
        .page-container {
            max-width: 1150px;
            margin: 45px auto;
            padding: 0 22px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .page-heading p {
            color: #64748b;
            font-size: 14px;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 13px 19px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .hostel-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .hostel-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            padding: 25px;
            transition: .25s;
        }

        .hostel-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, .07);
        }

        .hostel-card h2 {
            font-size: 19px;
            margin-bottom: 12px;
        }

        .hostel-card p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 9px;
        }

        .university-tag {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .notice {
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .success {
            background: #f0fdf4;
            color: #15803d;
        }

        .error {
            background: #fef2f2;
            color: #b91c1c;
        }

        .empty-state {
            background: white;
            text-align: center;
            padding: 55px 20px;
            border-radius: 13px;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        .empty-state h2 {
            color: #0f172a;
            margin-bottom: 12px;
        }

        .hostel-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        .edit-btn,
        .delete-btn {
            border: none;
            padding: 10px 16px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-btn {
            background: #eff6ff;
            color: #2563eb;
        }

        .delete-btn {
            background: #fef2f2;
            color: #dc2626;
        }

        .delete-form {
            margin: 0;
        }

        @media(max-width: 700px) {
            .hostel-grid {
                grid-template-columns: 1fr;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <div class="topbar-right">

        <div class="user-info">
            <span class="user-name">
                <?= htmlspecialchars($_SESSION['full_name'], ENT_QUOTES, 'UTF-8') ?>
            </span>

            <span class="user-role">Hostel Owner</span>
        </div>

        <a href="../authentication/logout.php" class="logout-btn">
            Sign Out
        </a>

    </div>

</header>

<main class="page-container">

    <div class="page-heading">

        <div>
            <h1>My Hostels</h1>
            <p>View and manage your registered hostel properties.</p>
        </div>

        <a href="add_hostel.php" class="add-btn">
            + Add New Hostel
        </a>

        <a href="/HostelConnect/index.php" class="add-btn">
            ← Back to Home
        </a>

    </div>

    <?php if ($success): ?>
        <div class="notice success">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="notice error">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if (count($hostels) > 0): ?>

        <section class="hostel-grid">

            <?php foreach ($hostels as $hostel): ?>

                <article class="hostel-card">

                    <span class="university-tag">
                        <?= htmlspecialchars($hostel['university_name'], ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <h2>
                        <?= htmlspecialchars($hostel['hostel_name'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <p>
                        <strong>Location:</strong>
                        <?= htmlspecialchars($hostel['location'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p>
                        <strong>Contact:</strong>
                        <?= htmlspecialchars($hostel['contact_number'] ?? 'Not provided', ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p>
                        <?= htmlspecialchars($hostel['description'] ?? 'No description provided.', ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <!-- Edit and Delete Actions -->
                    <div class="hostel-actions">

                        <a
                            href="edit_hostel.php?id=<?= (int)$hostel['hostel_id'] ?>"
                            class="edit-btn">
                            Edit Hostel
                        </a>

                        <form
                            action="delete_hostel.php"
                            method="POST"
                            class="delete-form"
                            onsubmit="return confirm('Are you sure you want to delete this hostel?');">

                            <input
                                type="hidden"
                                name="hostel_id"
                                value="<?= (int)$hostel['hostel_id'] ?>">

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                            <button type="submit" class="delete-btn">
                                Delete Hostel
                            </button>

                        </form>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <div class="empty-state">

            <h2>No Hostels Added Yet</h2>

            <p>
                You haven't registered any hostel properties.
                Start by adding your first hostel.
            </p>

        </div>

    <?php endif; ?>

</main>

</body>
</html>