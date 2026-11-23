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

$userId = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function escapeOutput(mixed $value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function paymentBadgeClass(string $status): string
{
    return match ($status) {
        'Successful' => 'badge-successful',
        'Pending' => 'badge-pending',
        'Failed' => 'badge-failed',
        default => 'badge-default'
    };
}

/*
|--------------------------------------------------------------------------
| Flash Messages
|--------------------------------------------------------------------------
*/

$successMessage = $_SESSION['payment_success'] ?? '';
$errorMessage = $_SESSION['payment_error'] ?? '';

unset(
    $_SESSION['payment_success'],
    $_SESSION['payment_error']
);

/*
|--------------------------------------------------------------------------
| Retrieve Student Payment History
|--------------------------------------------------------------------------
*/

$payments = [];

$totalSuccessful = 0.00;
$totalPending = 0;
$totalFailed = 0;
$totalTransactions = 0;

try {

    $stmt = $pdo->prepare("
        SELECT
            p.payment_id,
            p.booking_id,
            p.amount,
            p.payment_reference,
            p.payment_status,
            p.payment_date,

            b.booking_status,

            r.room_number,
            r.room_type,

            h.hostel_name

        FROM payments p

        INNER JOIN bookings b
            ON p.booking_id = b.booking_id

        INNER JOIN rooms r
            ON b.room_id = r.room_id

        INNER JOIN hostels h
            ON r.hostel_id = h.hostel_id

        WHERE b.student_id = :student_id

        ORDER BY p.payment_id DESC
    ");

    $stmt->execute([
        ':student_id' => $userId
    ]);

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
    |--------------------------------------------------------------------------
    | Calculate Payment Summary
    |--------------------------------------------------------------------------
    */

    foreach ($payments as $payment) {

        $status = (string) $payment['payment_status'];

        if ($status === 'Successful') {

            $totalSuccessful += (float) $payment['amount'];

        } elseif ($status === 'Pending') {

            $totalPending++;

        } elseif ($status === 'Failed') {

            $totalFailed++;

        }

    }

    $totalTransactions = count($payments);

} catch (PDOException $e) {

    error_log(
        'Payment history error: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('Unable to retrieve payment history. Please try again later.');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment History | HostelConnect</title>

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
            --warning: #d97706;
            --danger: #dc2626;

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

        /* =================================
           TOP NAVIGATION
        ================================= */

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

        .nav-actions {

            display: flex;

            align-items: center;

            gap: 12px;

        }

        .nav-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 10px 16px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: 700;

            transition: .25s ease;

        }

        .home-btn {

            background: #eff6ff;

            color: var(--primary);

        }

        .home-btn:hover {
            background: #dbeafe;
        }

        .bookings-btn {

            background: #f1f5f9;

            color: #475569;

        }

        .bookings-btn:hover {
            background: #e2e8f0;
        }

        .logout-btn {

            background: #fee2e2;

            color: var(--danger);

        }

        .logout-btn:hover {
            background: #fecaca;
        }

        /* =================================
           MAIN CONTAINER
        ================================= */

        .container {

            max-width: 1200px;

            margin: 40px auto;

            padding: 0 22px;

        }

        /* =================================
           PAGE HEADER
        ================================= */

        .page-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 15px;

            margin-bottom: 28px;

        }

        .page-header h1 {

            margin: 0 0 9px;

            font-size: 30px;

            font-weight: 800;

            letter-spacing: -1px;

        }

        .page-header p {

            margin: 0;

            color: var(--text-muted);

            font-size: 14px;

            line-height: 1.6;

        }

        .transaction-count {

            padding: 9px 14px;

            background: #eff6ff;

            color: var(--primary);

            border-radius: 30px;

            font-size: 12px;

            font-weight: 700;

        }

        /* =================================
           SUMMARY CARDS
        ================================= */

        .summary {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 20px;

            margin-bottom: 30px;

        }

        .summary-card {

            background: var(--surface);

            padding: 23px;

            border: 1px solid var(--border);

            border-radius: var(--radius);

            box-shadow: 0 4px 15px rgba(15, 23, 42, .035);

            transition: .25s ease;

        }

        .summary-card:hover {

            transform: translateY(-4px);

            box-shadow: 0 10px 25px rgba(15, 23, 42, .07);

        }

        .summary-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 18px;

        }

        .summary-icon {

            width: 43px;

            height: 43px;

            display: flex;

            justify-content: center;

            align-items: center;

            border-radius: 11px;

            font-size: 21px;

        }

        .icon-success {
            background: #dcfce7;
        }

        .icon-pending {
            background: #fef3c7;
        }

        .icon-failed {
            background: #fee2e2;
        }

        .summary-card h3 {

            margin: 0;

            font-size: 13px;

            color: var(--text-muted);

            font-weight: 600;

        }

        .summary-card strong {

            font-size: 25px;

            font-weight: 800;

            letter-spacing: -.5px;

        }

        .success-text {
            color: var(--success);
        }

        .pending-text {
            color: var(--warning);
        }

        .failed-text {
            color: var(--danger);
        }

        /* =================================
           FLASH MESSAGES
        ================================= */

        .message {

            padding: 15px 18px;

            border-radius: 9px;

            margin-bottom: 22px;

            font-size: 14px;

            font-weight: 600;

        }

        .success-message {

            background: #dcfce7;

            color: #166534;

            border: 1px solid #bbf7d0;

        }

        .error-message {

            background: #fee2e2;

            color: #991b1b;

            border: 1px solid #fecaca;

        }

        /* =================================
           TRANSACTION TABLE
        ================================= */

        .table-container {

            background: var(--surface);

            border: 1px solid var(--border);

            border-radius: var(--radius);

            padding: 25px;

            box-shadow: 0 4px 15px rgba(15, 23, 42, .035);

        }

        .table-header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            flex-wrap: wrap;

            gap: 12px;

            margin-bottom: 22px;

        }

        .table-header h2 {

            margin: 0;

            font-size: 19px;

            font-weight: 800;

        }

        .table-header p {

            margin: 6px 0 0;

            color: var(--text-muted);

            font-size: 12px;

        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {

            width: 100%;

            border-collapse: collapse;

            min-width: 950px;

        }

        th {

            padding: 15px 13px;

            background: #f8fafc;

            color: #475569;

            text-align: left;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .5px;

            white-space: nowrap;

        }

        td {

            padding: 16px 13px;

            border-bottom: 1px solid var(--border);

            font-size: 13px;

            color: #334155;

            vertical-align: middle;

        }

        tbody tr {
            transition: .2s ease;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .payment-id {

            font-weight: 800;

            color: var(--primary);

        }

        .hostel-name {

            font-weight: 700;

            color: var(--text-dark);

        }

        .room-info {

            display: flex;

            flex-direction: column;

            gap: 5px;

        }

        .room-number {

            font-weight: 700;

            color: var(--text-dark);

        }

        .room-type {

            color: var(--text-muted);

            font-size: 11px;

        }

        .amount {

            font-weight: 800;

            color: var(--success);

            white-space: nowrap;

        }

        .reference {

            font-size: 11px;

            color: var(--text-muted);

            overflow-wrap: anywhere;

            max-width: 150px;

        }

        /* =================================
           PAYMENT BADGES
        ================================= */

        .badge {

            display: inline-flex;

            align-items: center;

            padding: 7px 11px;

            border-radius: 30px;

            font-size: 11px;

            font-weight: 800;

            white-space: nowrap;

        }

        .badge-successful {

            background: #dcfce7;

            color: #166534;

        }

        .badge-pending {

            background: #fef3c7;

            color: #92400e;

        }

        .badge-failed {

            background: #fee2e2;

            color: #991b1b;

        }

        .badge-default {

            background: #e2e8f0;

            color: #475569;

        }

        /* =================================
           ACTION BUTTONS
        ================================= */

        .action-button {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 9px 12px;

            border: none;

            border-radius: 7px;

            background: var(--primary);

            color: white;

            cursor: pointer;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;

            transition: .25s ease;

        }

        .action-button:hover {

            background: var(--primary-dark);

        }

        .action-button:disabled {

            opacity: .6;

            cursor: not-allowed;

        }

        .retry-button {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 9px 12px;

            border-radius: 7px;

            background: var(--success);

            color: white;

            font-size: 11px;

            font-weight: 700;

            white-space: nowrap;

            transition: .25s ease;

        }

        .retry-button:hover {
            background: #166534;
        }

        /* =================================
           EMPTY STATE
        ================================= */

        .empty-state {

            text-align: center;

            padding: 55px 20px;

        }

        .empty-icon {

            width: 75px;

            height: 75px;

            display: flex;

            justify-content: center;

            align-items: center;

            margin: 0 auto 20px;

            background: #eff6ff;

            border-radius: 50%;

            font-size: 32px;

        }

        .empty-state h3 {

            margin: 0 0 10px;

            font-size: 20px;

        }

        .empty-state p {

            color: var(--text-muted);

            font-size: 14px;

            line-height: 1.7;

            margin-bottom: 22px;

        }

        .explore-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 12px 20px;

            background: var(--primary);

            color: white;

            border-radius: 8px;

            font-size: 13px;

            font-weight: 700;

            transition: .25s ease;

        }

        .explore-btn:hover {
            background: var(--primary-dark);
        }

        /* =================================
           FOOTER
        ================================= */

        .footer {

            text-align: center;

            padding: 30px 15px;

            color: var(--text-muted);

            font-size: 12px;

        }

        /* =================================
           RESPONSIVE DESIGN
        ================================= */

        @media (max-width: 850px) {

            .summary {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .topbar {
                padding: 14px 4%;
            }

            .brand {
                font-size: 18px;
            }

            .nav-actions {
                gap: 6px;
            }

            .nav-btn {
                padding: 9px 10px;
                font-size: 10px;
            }

            .container {
                margin: 28px auto;
                padding: 0 14px;
            }

            .page-header h1 {
                font-size: 25px;
            }

            .summary {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .summary-card {
                padding: 19px;
            }

            .table-container {
                padding: 17px;
            }

            .table-header h2 {
                font-size: 17px;
            }

        }

        @media (max-width: 380px) {

            .brand {
                font-size: 15px;
            }

            .nav-btn {
                padding: 8px;
                font-size: 9px;
            }

        }

    </style>

</head>

<body>


<!-- =================================
     NAVIGATION
================================== -->

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <nav class="nav-actions">

        <a href="../../index.php" class="nav-btn home-btn">
            ← Home
        </a>

        <a href="../student/my_bookings.php" class="nav-btn bookings-btn">
            My Bookings
        </a>

        <a href="../authentication/logout.php" class="nav-btn logout-btn">
            Sign Out
        </a>

    </nav>

</header>


<!-- =================================
     MAIN CONTENT
================================== -->

<main class="container">


    <!-- PAGE HEADER -->

    <section class="page-header">

        <div>

            <h1>Payment History</h1>

            <p>
                Monitor your transactions, review payment records
                and manage outstanding payments.
            </p>

        </div>

        <div class="transaction-count">

            <?= $totalTransactions ?>
            Transaction<?= $totalTransactions !== 1 ? 's' : '' ?>

        </div>

    </section>


    <!-- FLASH MESSAGES -->

    <?php if ($successMessage): ?>

        <div class="message success-message">

            ✓ <?= escapeOutput($successMessage) ?>

        </div>

    <?php endif; ?>


    <?php if ($errorMessage): ?>

        <div class="message error-message">

            ⚠ <?= escapeOutput($errorMessage) ?>

        </div>

    <?php endif; ?>


    <!-- =================================
         PAYMENT SUMMARY
    ================================== -->

    <section class="summary">


        <!-- SUCCESSFUL PAYMENTS -->

        <article class="summary-card">

            <div class="summary-top">

                <h3>Successful Payments</h3>

                <div class="summary-icon icon-success">
                    ✓
                </div>

            </div>

            <strong class="success-text">

                GH₵ <?= number_format($totalSuccessful, 2) ?>

            </strong>

        </article>


        <!-- PENDING PAYMENTS -->

        <article class="summary-card">

            <div class="summary-top">

                <h3>Pending Transactions</h3>

                <div class="summary-icon icon-pending">
                    ◷
                </div>

            </div>

            <strong class="pending-text">

                <?= $totalPending ?>

            </strong>

        </article>


        <!-- FAILED PAYMENTS -->

        <article class="summary-card">

            <div class="summary-top">

                <h3>Failed Transactions</h3>

                <div class="summary-icon icon-failed">
                    !
                </div>

            </div>

            <strong class="failed-text">

                <?= $totalFailed ?>

            </strong>

        </article>


    </section>


    <!-- =================================
         TRANSACTION RECORDS
    ================================== -->

    <section class="table-container">

        <div class="table-header">

            <div>

                <h2>Transaction Records</h2>

                <p>
                    A complete record of your hostel payment activities.
                </p>

            </div>

        </div>


        <?php if (empty($payments)): ?>


            <!-- EMPTY STATE -->

            <div class="empty-state">

                <div class="empty-icon">
                    💳
                </div>

                <h3>No Payment Records</h3>

                <p>
                    You haven't made any payment transactions yet.
                    Your payment history will appear here once you make a reservation.
                </p>

                <a
                    href="../hostels/browse_hostels.php"
                    class="explore-btn"
                >
                    Explore Hostels
                </a>

            </div>


        <?php else: ?>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Payment ID</th>

                            <th>Hostel</th>

                            <th>Room</th>

                            <th>Amount</th>

                            <th>Reference</th>

                            <th>Status</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($payments as $payment): ?>

                            <?php

                                $status = (string) $payment['payment_status'];

                                $badgeClass = paymentBadgeClass($status);

                                $bookingId = (int) $payment['booking_id'];

                                $paymentId = (int) $payment['payment_id'];

                            ?>


                            <tr>


                                <!-- PAYMENT ID -->

                                <td>

                                    <span class="payment-id">

                                        #<?= $paymentId ?>

                                    </span>

                                </td>


                                <!-- HOSTEL -->

                                <td>

                                    <span class="hostel-name">

                                        <?= escapeOutput($payment['hostel_name']) ?>

                                    </span>

                                </td>


                                <!-- ROOM -->

                                <td>

                                    <div class="room-info">

                                        <span class="room-number">

                                            <?= escapeOutput($payment['room_number']) ?>

                                        </span>

                                        <span class="room-type">

                                            <?= escapeOutput($payment['room_type']) ?>

                                        </span>

                                    </div>

                                </td>


                                <!-- AMOUNT -->

                                <td>

                                    <span class="amount">

                                        GH₵ <?= number_format((float) $payment['amount'], 2) ?>

                                    </span>

                                </td>


                                <!-- PAYMENT REFERENCE -->

                                <td>

                                    <span class="reference">

                                        <?= escapeOutput($payment['payment_reference'] ?? 'Not available') ?>

                                    </span>

                                </td>


                                <!-- PAYMENT STATUS -->

                                <td>

                                    <span class="badge <?= $badgeClass ?>">

                                        <?= escapeOutput($status) ?>

                                    </span>

                                </td>


                                <!-- PAYMENT DATE -->

                                <td>

                                    <?= !empty($payment['payment_date'])
                                        ? escapeOutput($payment['payment_date'])
                                        : 'Not completed' ?>

                                </td>


                                <!-- PAYMENT ACTION -->

                                <td>


                                    <?php if ($status === 'Pending'): ?>


                                        <form
                                            method="POST"
                                            action="verify_payment.php"
                                            onsubmit="const button = this.querySelector('button'); button.disabled = true; button.textContent = 'Checking...';"
                                        >

                                            <input
                                                type="hidden"
                                                name="reference"
                                                value="<?= escapeOutput($payment['payment_reference']) ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= escapeOutput($csrfToken) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="action-button"
                                            >

                                                Check Status

                                            </button>

                                        </form>


                                    <?php elseif (
                                        $status === 'Failed'
                                        && $payment['booking_status'] === 'Pending'
                                    ): ?>


                                        <a
                                            class="retry-button"
                                            href="pay_now.php?booking_id=<?= $bookingId ?>"
                                        >

                                            Retry Payment

                                        </a>


                                    <?php else: ?>


                                        <span>—</span>


                                    <?php endif; ?>


                                </td>


                            </tr>


                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


        <?php endif; ?>


    </section>


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