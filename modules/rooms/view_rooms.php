<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);

require_once __DIR__ . '/../../config/database.php';

$sql = "
    SELECT
        r.room_id,
        r.room_number,
        r.room_type,
        r.price,
        r.description,
        h.hostel_name,
        u.university_name
    FROM rooms r
    INNER JOIN hostels h
        ON r.hostel_id = h.hostel_id
    INNER JOIN universities u
        ON h.university_id = u.university_id
    WHERE h.owner_id = :owner_id
    ORDER BY h.hostel_name, r.room_number
";

$statement = $pdo->prepare($sql);

$statement->execute([
    ':owner_id' => $_SESSION['user_id']
]);

$rooms = $statement->fetchAll();

$success = $_SESSION['room_success'] ?? '';
$error = $_SESSION['room_error'] ?? '';

unset($_SESSION['room_success'], $_SESSION['room_error']);

// Generate CSRF token for secure delete requests.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Rooms | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <style>
        .page-container {
            max-width: 1200px;
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

        .room-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .room-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 25px;
            border-radius: 13px;
            transition: .25s;
        }

        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, .07);
        }

        .room-number {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .room-card h2 {
            font-size: 19px;
            margin-bottom: 10px;
        }

        .room-card p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 8px;
        }

        .room-price {
            font-size: 20px;
            font-weight: 800;
            color: #2563eb;
            margin-top: 17px;
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
        }

        /* Room action buttons */
        .room-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .edit-btn,
        .delete-btn {
            padding: 10px 14px;
            border: none;
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

        .edit-btn:hover {
            background: #dbeafe;
        }

        .delete-btn:hover {
            background: #fee2e2;
        }

        @media(max-width: 900px) {
            .room-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 600px) {
            .room-grid {
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
            <h1>Room Management</h1>
            <p>View the rooms registered under your hostel properties.</p>
        </div>

        <a href="add_room.php" class="add-btn">
            + Add New Room
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

    <?php if (count($rooms) > 0): ?>

        <section class="room-grid">

            <?php foreach ($rooms as $room): ?>

                <article class="room-card">

                    <span class="room-number">
                        <?= htmlspecialchars($room['room_number'], ENT_QUOTES, 'UTF-8') ?>
                    </span>

                    <h2>
                        <?= htmlspecialchars($room['room_type'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>

                    <p>
                        <strong>Hostel:</strong>
                        <?= htmlspecialchars($room['hostel_name'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p>
                        <strong>University:</strong>
                        <?= htmlspecialchars($room['university_name'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                    <p>
                        <?= htmlspecialchars(
                            $room['description'] ?? 'No description provided.',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </p>

                    <div class="room-price">
                        GHS <?= number_format((float) $room['price'], 2) ?>
                    </div>

                    <!-- Edit and Delete Actions -->
                    <div class="room-actions">

                        <a
                            href="edit_room.php?id=<?= (int)$room['room_id'] ?>"
                            class="edit-btn">
                            Edit Room
                        </a>

                        <form
                            action="delete_room.php"
                            method="POST"
                            class="delete-form"
                            onsubmit="return confirm('Are you sure you want to delete this room?');">

                            <input
                                type="hidden"
                                name="room_id"
                                value="<?= (int)$room['room_id'] ?>">

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(
                                    $_SESSION['csrf_token'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>">

                            <button type="submit" class="delete-btn">
                                Delete Room
                            </button>

                        </form>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <div class="empty-state">
            <h2>No Rooms Available Yet</h2>
            <p>Add your first room to begin managing accommodation.</p>
        </div>

    <?php endif; ?>

</main>

</body>
</html>