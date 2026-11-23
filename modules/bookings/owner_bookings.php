<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);

require_once __DIR__ . '/../../config/database.php';

$ownerId = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Ensure a CSRF token exists for booking status updates
|--------------------------------------------------------------------------
*/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
|--------------------------------------------------------------------------
| Retrieve Owner Bookings and Payment Status
|--------------------------------------------------------------------------
*/
$stmt = $pdo->prepare("
    SELECT
        b.booking_id,
        b.start_date,
        b.end_date,
        b.booking_status,
        b.booking_date,
        r.room_number,
        r.room_type,
        r.price,
        h.hostel_name,
        u.full_name AS student_name,
        u.email AS student_email,
        u.phone_number AS student_phone,
        (
            SELECT p.payment_status
            FROM payments p
            WHERE p.booking_id = b.booking_id
            ORDER BY p.payment_id DESC
            LIMIT 1
        ) AS payment_status
    FROM bookings b
    INNER JOIN rooms r ON b.room_id = r.room_id
    INNER JOIN hostels h ON r.hostel_id = h.hostel_id
    INNER JOIN users u ON b.student_id = u.user_id
    WHERE h.owner_id = :owner_id
    ORDER BY b.booking_date DESC
");

$stmt->execute([':owner_id' => $ownerId]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Flash Messages
|--------------------------------------------------------------------------
*/
$success = $_SESSION['booking_success'] ?? '';
$error = $_SESSION['booking_error'] ?? '';

unset(
    $_SESSION['booking_success'],
    $_SESSION['booking_error']
);

/*
|--------------------------------------------------------------------------
| HTML Escaping
|--------------------------------------------------------------------------
*/
function e($value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function statusClass($status): string
{
    $status = strtolower((string) $status);
    return preg_replace('/[^a-z0-9_-]/', '', $status) ?: 'unknown';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f1f5f9;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .home-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .home-btn:hover {
            background: #2563eb;
            color: #fff;
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading p {
            margin-top: 7px;
            color: #64748b;
        }

        .booking-card {
            margin-bottom: 20px;
            padding: 25px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
        }

        .booking-card h3 {
            margin-bottom: 15px;
            color: #1e293b;
        }

        .booking-card p {
            color: #64748b;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .booking-card strong {
            color: #334155;
        }

        .message {
            margin-bottom: 18px;
            padding: 13px;
            border-radius: 7px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        select,
        button {
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font: inherit;
        }

        select {
            margin-top: 10px;
            margin-right: 8px;
            background: #fff;
        }

        button {
            background: #2563eb;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s ease;
        }

        button:hover {
            background: #1d4ed8;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        .payment-paid {
            color: #15803d;
            font-weight: 700;
        }

        .payment-pending {
            color: #d97706;
            font-weight: 700;
        }

        .payment-failed {
            color: #dc2626;
            font-weight: 700;
        }

        .payment-none {
            color: #64748b;
            font-weight: 600;
        }

        .empty {
            padding: 40px;
            border-radius: 12px;
            background: #fff;
            text-align: center;
        }

        .empty p {
            margin-top: 8px;
            color: #64748b;
        }

        .booking-action {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }

        @media (max-width: 600px) {
            .topbar {
                padding: 14px 16px;
            }

            .topbar-actions {
                width: 100%;
            }

            .topbar-actions a {
                flex: 1;
                text-align: center;
            }

            .container {
                margin: 20px auto;
                padding: 15px;
            }

            .booking-card {
                padding: 18px;
            }

            .booking-action form {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .booking-action select,
            .booking-action button {
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <div class="topbar-actions">

        <a href="../../index.php" class="home-btn">
            <i class="fas fa-house" aria-hidden="true"></i>
            Back to Home
        </a>

        <a href="../authentication/logout.php" class="logout-btn">
            Sign Out
        </a>

    </div>

</header>

<main class="container">

    <section class="page-heading">
        <h1>Manage Student Bookings</h1>

        <p>
            Review student reservations, payment status and accommodation details.
        </p>
    </section>

    <?php if ($success): ?>
        <div class="message success" role="status">
            <?= e($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error" role="alert">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($bookings): ?>

        <?php foreach ($bookings as $booking): ?>

            <article class="booking-card">

                <h3><?= e($booking['hostel_name']) ?></h3>

                <p>
                    <strong>Booking Reference:</strong>
                    #<?= (int) $booking['booking_id'] ?>
                </p>

                <p>
                    <strong>Student:</strong>
                    <?= e($booking['student_name']) ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?= e($booking['student_email']) ?>
                </p>

                <p>
                    <strong>Phone:</strong>
                    <?= e($booking['student_phone'] ?? 'Not provided') ?>
                </p>

                <p>
                    <strong>Room:</strong>
                    <?= e($booking['room_number']) ?>
                    (<?= e($booking['room_type']) ?>)
                </p>

                <p>
                    <strong>Semester Price:</strong>
                    GH₵ <?= number_format((float) $booking['price'], 2) ?>
                </p>

                <p>
                    <strong>Check-in:</strong>
                    <?= e($booking['start_date']) ?>
                </p>

                <p>
                    <strong>Check-out:</strong>
                    <?= e($booking['end_date']) ?>
                </p>

                <p>
                    <strong>Booking Status:</strong>

                    <span class="status status-<?= e(statusClass($booking['booking_status'])) ?>">
                        <?= e($booking['booking_status']) ?>
                    </span>
                </p>

                <p>
                    <strong>Payment Status:</strong>

                    <?php $paymentStatus = $booking['payment_status']; ?>

                    <?php if ($paymentStatus === 'Successful'): ?>

                        <span class="payment-paid">Paid</span>

                    <?php elseif ($paymentStatus === 'Pending'): ?>

                        <span class="payment-pending">Payment Pending</span>

                    <?php elseif ($paymentStatus === 'Failed'): ?>

                        <span class="payment-failed">Payment Failed</span>

                    <?php else: ?>

                        <span class="payment-none">Not Started</span>

                    <?php endif; ?>

                </p>

                <!-- OWNER ACTIONS -->

                <?php if (in_array($booking['booking_status'], ['Pending', 'Confirmed'], true)): ?>

                    <div class="booking-action">

                        <form action="update_booking_status.php" method="POST">

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= (int) $booking['booking_id'] ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= e($_SESSION['csrf_token']) ?>"
                            >

                            <select name="booking_status" required>

                                <option value="">Select Action</option>

                                <?php if ($booking['booking_status'] === 'Pending'): ?>

                                    <option value="Cancelled">
                                        Cancel Unpaid Booking
                                    </option>

                                <?php elseif ($booking['booking_status'] === 'Confirmed'): ?>

                                    <option value="Completed">
                                        Mark as Completed
                                    </option>

                                <?php endif; ?>

                            </select>

                            <button type="submit">
                                Update Booking
                            </button>

                        </form>

                    </div>

                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty">

            <h2>No Bookings Yet</h2>

            <p>
                Student reservations for your hostels will appear here.
            </p>

        </div>

    <?php endif; ?>

</main>

</body>
</html>