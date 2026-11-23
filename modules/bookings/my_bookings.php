<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Student']);

require_once __DIR__ . '/../../config/database.php';

/*
|--------------------------------------------------------------------------
| Student Information
|--------------------------------------------------------------------------
*/

$studentId = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

/*
|--------------------------------------------------------------------------
| Retrieve Student Bookings
|--------------------------------------------------------------------------
| Retrieve each booking together with its latest payment information.
|--------------------------------------------------------------------------
*/

$sql = "
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
        h.location,

        u.university_name,

        p.payment_status,
        p.payment_reference

    FROM bookings b

    INNER JOIN rooms r
        ON b.room_id = r.room_id

    INNER JOIN hostels h
        ON r.hostel_id = h.hostel_id

    INNER JOIN universities u
        ON h.university_id = u.university_id

    LEFT JOIN payments p
        ON p.payment_id = (
            SELECT MAX(p2.payment_id)
            FROM payments p2
            WHERE p2.booking_id = b.booking_id
        )

    WHERE b.student_id = :student_id

    ORDER BY b.booking_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':student_id' => $studentId
]);

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
| Helper Functions
|--------------------------------------------------------------------------
*/

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function statusColor(string $status): string
{
    return match ($status) {
        'Confirmed' => '#15803d',
        'Pending' => '#d97706',
        'Cancelled' => '#dc2626',
        'Completed' => '#2563eb',
        default => '#64748b'
    };
}

function paymentStatusColor(?string $status): string
{
    return match ($status) {
        'Successful' => '#15803d',
        'Pending' => '#d97706',
        'Failed' => '#dc2626',
        default => '#64748b'
    };
}

function paymentStatusLabel(?string $status): string
{
    return $status ?? 'Not Started';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #15803d;
            --danger: #dc2626;
            --warning: #d97706;

            --background: #f1f5f9;
            --surface: #ffffff;
            --border: #e2e8f0;

            --text-dark: #0f172a;
            --text-muted: #64748b;

            --radius: 14px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
        }

        button {
            font-family: inherit;
        }

        /* ==============================
           TOP NAVIGATION
        ============================== */

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 17px 6%;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -1px;
        }

        .brand span {
            color: var(--primary);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .home-btn,
        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: 0.25s ease;
        }

        .home-btn {
            background: #eff6ff;
            color: var(--primary);
        }

        .home-btn:hover {
            background: #dbeafe;
        }

        .logout-btn {
            background: #fee2e2;
            color: var(--danger);
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        /* ==============================
           MAIN CONTAINER
        ============================== */

        .container {
            max-width: 1150px;
            margin: 40px auto;
            padding: 0 22px;
        }

        /* ==============================
           PAGE HEADING
        ============================== */

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 28px;
        }

        .heading-text h1 {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0 0 8px;
        }

        .heading-text p {
            color: var(--text-muted);
            margin: 0;
            font-size: 14px;
        }

        /* ==============================
           BUTTONS
        ============================== */

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 17px;
            margin: 7px 5px 0 0;
            border: none;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .history-btn {
            background: #475569;
        }

        .history-btn:hover {
            background: #334155;
        }

        .pay-btn {
            background: var(--success);
        }

        .pay-btn:hover {
            background: #166534;
        }

        .cancel-btn {
            background: var(--danger);
        }

        .cancel-btn:hover {
            background: #b91c1c;
        }

        .check-btn {
            background: var(--warning);
        }

        .check-btn:hover {
            background: #b45309;
        }

        /* ==============================
           NOTIFICATIONS
        ============================== */

        .message {
            padding: 15px 18px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 600;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* ==============================
           BOOKING CARDS
        ============================== */

        .booking-card {
            background: var(--surface);
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 22px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.035);
            transition: 0.25s ease;
        }

        .booking-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.07);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 17px;
            border-bottom: 1px solid var(--border);
        }

        .booking-header h3 {
            font-size: 19px;
            font-weight: 800;
            margin: 0;
        }

        .booking-id {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 6px;
        }

        .booking-details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .detail-item {
            padding: 13px;
            background: #f8fafc;
            border-radius: 9px;
        }

        .detail-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 7px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            overflow-wrap: anywhere;
        }

        .price-value {
            color: var(--success);
            font-size: 17px;
        }

        /* ==============================
           STATUS BADGES
        ============================== */

        .status {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border-radius: 30px;
            color: white;
            font-size: 11px;
            font-weight: 800;
        }

        /* ==============================
           PAYMENT INFORMATION
        ============================== */

        .payment-information {
            margin-top: 20px;
            padding: 17px;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .payment-information h4 {
            font-size: 14px;
            margin: 0 0 13px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 13px;
        }

        .payment-row + .payment-row {
            margin-top: 12px;
        }

        .payment-label {
            color: var(--text-muted);
            font-weight: 600;
        }

        .payment-value {
            font-weight: 700;
        }

        /* ==============================
           PAYMENT MESSAGES
        ============================== */

        .payment-notice {
            margin-top: 18px;
            padding: 13px 15px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.6;
            font-weight: 600;
        }

        .payment-success {
            background: #dcfce7;
            color: #166534;
        }

        .payment-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .payment-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ==============================
           ACTION BUTTONS
        ============================== */

        .booking-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 18px;
        }

        .booking-actions .btn {
            margin-top: 0;
        }

        /* ==============================
           EMPTY STATE
        ============================== */

        .empty {
            background: var(--surface);
            padding: 60px 25px;
            text-align: center;
            border-radius: var(--radius);
            border: 1px solid var(--border);
        }

        .empty-icon {
            width: 75px;
            height: 75px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #eff6ff;
            font-size: 32px;
        }

        .empty h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .empty p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 22px;
        }

        /* ==============================
           FOOTER
        ============================== */

        .footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--text-muted);
            font-size: 12px;
        }

        /* ==============================
           RESPONSIVE DESIGN
        ============================== */

        @media (max-width: 650px) {

            .topbar {
                padding: 14px 4%;
            }

            .brand {
                font-size: 19px;
            }

            .topbar-right {
                gap: 7px;
            }

            .home-btn,
            .logout-btn {
                padding: 9px 11px;
                font-size: 11px;
            }

            .container {
                margin: 28px auto;
                padding: 0 15px;
            }

            .heading-text h1 {
                font-size: 25px;
            }

            .page-heading {
                align-items: flex-start;
            }

            .booking-card {
                padding: 19px;
            }

            .booking-details {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .booking-header h3 {
                font-size: 17px;
            }

            .booking-actions {
                flex-direction: column;
            }

            .booking-actions .btn {
                width: 100%;
                margin-right: 0;
            }

            .payment-row {
                align-items: flex-start;
            }
        }

        @media (max-width: 380px) {

            .brand {
                font-size: 16px;
            }

            .home-btn,
            .logout-btn {
                padding: 8px;
                font-size: 10px;
            }

        }

    </style>

</head>

<body>

<!-- =================================
     TOP NAVIGATION
================================== -->

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <div class="topbar-right">

        <a href="../../index.php" class="home-btn">
            ← Home
        </a>

        <a href="../authentication/logout.php" class="logout-btn">
            Sign Out
        </a>

    </div>

</header>


<!-- =================================
     MAIN CONTENT
================================== -->

<main class="container">

    <section class="page-heading">

        <div class="heading-text">

            <h1>My Bookings</h1>

            <p>
                Manage your hostel reservations and monitor your payment status.
            </p>

        </div>

        <a
            href="../payments/payment_history.php"
            class="btn history-btn"
        >
            Payment History
        </a>

    </section>


    <!-- FLASH MESSAGES -->

    <?php if ($success): ?>

        <div class="message success">
            ✓ <?= e($success) ?>
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div class="message error">
            ⚠ <?= e($error) ?>
        </div>

    <?php endif; ?>


    <!-- =================================
         BOOKINGS
    ================================== -->

    <?php if (!empty($bookings)): ?>

        <?php foreach ($bookings as $booking): ?>

            <?php

                $bookingStatus = (string) $booking['booking_status'];

                $paymentStatus = $booking['payment_status'] !== null
                    ? (string) $booking['payment_status']
                    : null;

                $bookingId = (int) $booking['booking_id'];

                $paymentReference = $booking['payment_reference'];

                $canCancel = (
                    $bookingStatus === 'Pending'
                    && (
                        $paymentStatus === null
                        || $paymentStatus === 'Failed'
                    )
                );

            ?>

            <article class="booking-card">

                <!-- BOOKING HEADER -->

                <div class="booking-header">

                    <div>

                        <h3>
                            <?= e($booking['hostel_name']) ?>
                        </h3>

                        <div class="booking-id">
                            Booking Reference: #<?= $bookingId ?>
                        </div>

                    </div>

                    <span
                        class="status"
                        style="background: <?= statusColor($bookingStatus) ?>;"
                    >
                        <?= e($bookingStatus) ?>
                    </span>

                </div>


                <!-- BOOKING DETAILS -->

                <div class="booking-details">

                    <div class="detail-item">

                        <span class="detail-label">
                            University
                        </span>

                        <div class="detail-value">
                            <?= e($booking['university_name']) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Location
                        </span>

                        <div class="detail-value">
                            <?= e($booking['location']) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Room Number
                        </span>

                        <div class="detail-value">
                            <?= e($booking['room_number']) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Room Type
                        </span>

                        <div class="detail-value">
                            <?= e($booking['room_type']) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Semester Price
                        </span>

                        <div class="detail-value price-value">
                            GH₵ <?= number_format((float) $booking['price'], 2) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Payment Period
                        </span>

                        <div class="detail-value">
                            One Semester
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Check-in Date
                        </span>

                        <div class="detail-value">
                            <?= e($booking['start_date']) ?>
                        </div>

                    </div>


                    <div class="detail-item">

                        <span class="detail-label">
                            Check-out Date
                        </span>

                        <div class="detail-value">
                            <?= e($booking['end_date']) ?>
                        </div>

                    </div>

                </div>


                <!-- PAYMENT INFORMATION -->

                <div class="payment-information">

                    <h4>Payment Information</h4>

                    <div class="payment-row">

                        <span class="payment-label">
                            Payment Status
                        </span>

                        <span
                            class="status"
                            style="background: <?= paymentStatusColor($paymentStatus) ?>;"
                        >
                            <?= e(paymentStatusLabel($paymentStatus)) ?>
                        </span>

                    </div>


                    <?php if (!empty($paymentReference)): ?>

                        <div class="payment-row">

                            <span class="payment-label">
                                Payment Reference
                            </span>

                            <span class="payment-value">
                                <?= e($paymentReference) ?>
                            </span>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- PAYMENT NOTIFICATIONS -->

                <?php if ($paymentStatus === 'Successful'): ?>

                    <div class="payment-notice payment-success">
                        ✓ Your payment has been successfully verified.
                    </div>

                <?php elseif ($paymentStatus === 'Pending'): ?>

                    <div class="payment-notice payment-pending">
                        Your payment is being processed. Please check its status
                        before attempting another payment.
                    </div>

                <?php elseif ($paymentStatus === 'Failed'): ?>

                    <div class="payment-notice payment-failed">
                        Your previous payment was unsuccessful.
                        You can retry the payment if your booking is still pending.
                    </div>

                <?php endif; ?>


                <!-- =================================
                     BOOKING ACTIONS
                ================================== -->

                <div class="booking-actions">


                    <!-- VIEW BOOKING DETAILS -->

                    <a
                        class="btn"
                        href="booking_details.php?id=<?= $bookingId ?>"
                    >
                        View Details
                    </a>


                    <!-- CHECK PAYMENT -->

                    <?php if (
                        $bookingStatus === 'Pending'
                        && $paymentStatus === 'Pending'
                    ): ?>

                        <?php if (!empty($paymentReference)): ?>

                            <form
                                action="../payments/verify_payment.php"
                                method="POST"
                                onsubmit="this.querySelector('button').disabled = true;"
                            >

                                <input
                                    type="hidden"
                                    name="reference"
                                    value="<?= e($paymentReference) ?>"
                                >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= e($csrfToken) ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn check-btn"
                                >
                                    Check Payment Status
                                </button>

                            </form>

                        <?php endif; ?>

                    <?php endif; ?>


                    <!-- PAY OR RETRY PAYMENT -->

                    <?php if (
                        $bookingStatus === 'Pending'
                        && (
                            $paymentStatus === null
                            || $paymentStatus === 'Failed'
                        )
                    ): ?>

                        <a
                            class="btn pay-btn"
                            href="../payments/pay_now.php?booking_id=<?= $bookingId ?>"
                        >
                            <?= $paymentStatus === 'Failed'
                                ? 'Retry Payment'
                                : 'Pay Now' ?>
                        </a>

                    <?php endif; ?>


                    <!-- CANCEL BOOKING -->

                    <?php if ($canCancel): ?>

                        <form
                            action="cancel_booking.php"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                        >

                            <input
                                type="hidden"
                                name="booking_id"
                                value="<?= $bookingId ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= e($csrfToken) ?>"
                            >

                            <button
                                type="submit"
                                class="btn cancel-btn"
                            >
                                Cancel Booking
                            </button>

                        </form>

                    <?php endif; ?>


                </div>

            </article>

        <?php endforeach; ?>


    <?php else: ?>


        <!-- EMPTY STATE -->

        <section class="empty">

            <div class="empty-icon">
                🏠
            </div>

            <h2>No Bookings Yet</h2>

            <p>
                You haven't made any hostel reservations.
                Start exploring available hostels and find your accommodation.
            </p>

            <a
                href="../hostels/browse_hostels.php"
                class="btn"
            >
                Explore Hostels
            </a>

        </section>

    <?php endif; ?>

</main>


<!-- =================================
     FOOTER
================================== -->

<footer class="footer">

    &copy; <?= date('Y') ?> HostelConnect.
    All rights reserved.

</footer>

</body>

</html>