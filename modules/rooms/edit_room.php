<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);

require_once __DIR__ . '/../../config/database.php';

/*
|--------------------------------------------------------------------------
| Helper Function
|--------------------------------------------------------------------------
*/

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| Retrieve Room ID
|--------------------------------------------------------------------------
*/

$roomId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$roomId || $roomId <= 0) {
    http_response_code(400);
    exit('Invalid room ID.');
}

$ownerId = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Fetch Room
| Only retrieve rooms belonging to the logged-in owner.
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        r.room_id,
        r.hostel_id,
        r.room_number,
        r.room_type,
        r.price,
        r.description
    FROM rooms r
    INNER JOIN hostels h
        ON r.hostel_id = h.hostel_id
    WHERE r.room_id = :room_id
      AND h.owner_id = :owner_id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':room_id' => $roomId,
    ':owner_id' => $ownerId
]);

$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    http_response_code(404);
    exit('Room not found or you do not have permission to edit it.');
}

/*
|--------------------------------------------------------------------------
| Fetch Owner's Hostels
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT hostel_id, hostel_name
    FROM hostels
    WHERE owner_id = :owner_id
    ORDER BY hostel_name ASC
");

$stmt->execute([
    ':owner_id' => $ownerId
]);

$hostels = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Generate CSRF Token
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| Room Types
|--------------------------------------------------------------------------
*/

$roomTypes = [
    'Single Room',
    'Two in a Room',
    'Three in a Room',
    'Self-Contained',
    'Shared Room'
];

/*
|--------------------------------------------------------------------------
| Flash Messages
|--------------------------------------------------------------------------
*/

$error = $_SESSION['room_error'] ?? '';

unset($_SESSION['room_error']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Edit and manage your HostelConnect room information.">

    <title>Edit Room | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 6%;
        }

        .form-container {
            width: 92%;
            max-width: 760px;
            margin: 45px auto;
            padding: 35px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.06);
        }

        .form-heading {
            margin-bottom: 30px;
        }

        .form-heading h1 {
            margin-bottom: 8px;
            font-size: 27px;
            color: #0f172a;
        }

        .form-heading p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .auth-alert {
            padding: 13px 15px;
            border-radius: 8px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .auth-alert.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            display: block;
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #ffffff;
            font-size: 14px;
            color: #0f172a;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .form-group textarea {
            min-height: 125px;
            resize: vertical;
            line-height: 1.6;
        }

        .form-hint {
            display: block;
            margin-top: 7px;
            font-size: 12px;
            color: #94a3b8;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .submit-btn {
            background: #2563eb;
            color: #ffffff;
            padding: 13px 24px;
            border: none;
            border-radius: 9px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .submit-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            padding: 13px 18px;
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .back-link:hover {
            background: #e2e8f0;
        }

        @media (max-width: 600px) {

            .form-container {
                margin: 25px auto;
                padding: 23px;
            }

            .form-heading h1 {
                font-size: 23px;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .submit-btn,
            .back-link {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>

<body>

<!-- HEADER -->

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <a href="../authentication/logout.php" class="logout-btn">
        Sign Out
    </a>

</header>


<!-- MAIN CONTENT -->

<main class="form-container">

    <div class="form-heading">

        <h1>Edit Room</h1>

        <p>
            Update your room information and keep your accommodation
            listings accurate and up to date.
        </p>

    </div>


    <?php if (!empty($error)): ?>

        <div class="auth-alert error" role="alert">
            <?= e($error) ?>
        </div>

    <?php endif; ?>


    <form action="process_edit_room.php" method="POST">

        <!-- SECURITY -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= e($_SESSION['csrf_token']) ?>"
        >

        <input
            type="hidden"
            name="room_id"
            value="<?= (int) $room['room_id'] ?>"
        >


        <!-- HOSTEL -->

        <div class="form-group">

            <label for="hostel_id">Select Hostel</label>

            <select name="hostel_id" id="hostel_id" required>

                <?php foreach ($hostels as $hostel): ?>

                    <option
                        value="<?= (int) $hostel['hostel_id'] ?>"
                        <?= (int) $room['hostel_id'] === (int) $hostel['hostel_id'] ? 'selected' : '' ?>
                    >
                        <?= e($hostel['hostel_name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- ROOM NUMBER -->

        <div class="form-group">

            <label for="room_number">Room Number</label>

            <input
                type="text"
                id="room_number"
                name="room_number"
                maxlength="30"
                value="<?= e($room['room_number']) ?>"
                placeholder="e.g. A101"
                required
            >

        </div>


        <!-- ROOM TYPE -->

        <div class="form-group">

            <label for="room_type">Room Type</label>

            <select name="room_type" id="room_type" required>

                <?php foreach ($roomTypes as $type): ?>

                    <option
                        value="<?= e($type) ?>"
                        <?= $room['room_type'] === $type ? 'selected' : '' ?>
                    >
                        <?= e($type) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- PRICE -->

        <div class="form-group">

            <label for="price">Price per Semester (GHS)</label>

            <input
                type="number"
                id="price"
                name="price"
                min="0.01"
                max="99999999.99"
                step="0.01"
                value="<?= e($room['price']) ?>"
                placeholder="Enter room price"
                required
            >

            <small class="form-hint">
                Enter the accommodation price for one semester.
            </small>

        </div>


        <!-- DESCRIPTION -->

        <div class="form-group">

            <label for="description">Room Description</label>

            <textarea
                id="description"
                name="description"
                maxlength="5000"
                placeholder="Describe the room, facilities and other important details..."
            ><?= e($room['description'] ?? '') ?></textarea>

            <small class="form-hint">
                Maximum 5,000 characters.
            </small>

        </div>


        <!-- ACTION BUTTONS -->

        <div class="form-actions">

            <button type="submit" class="submit-btn">
                Save Changes
            </button>

            <a href="view_rooms.php" class="back-link">
                Cancel
            </a>

        </div>

    </form>

</main>

</body>
</html>