<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Owner']);

require_once __DIR__ . '/../../config/database.php';

/*
|--------------------------------------------------------------------------
| Owner Information
|--------------------------------------------------------------------------
*/

$ownerId = (int) $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Escape Output
|--------------------------------------------------------------------------
*/

function escapeOwnerPaymentOutput(mixed $value): string
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| Retrieve Owner Payment Records
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
        SELECT
            p.payment_id,
            p.booking_id,
            p.amount,
            p.payment_reference,
            p.payment_status,
            p.payment_date,

            b.start_date,
            b.end_date,
            b.booking_status,

            r.room_number,
            r.room_type,

            h.hostel_name,

            u.full_name AS student_name,
            u.email AS student_email,
            u.phone_number AS student_phone

        FROM payments p

        INNER JOIN bookings b
            ON p.booking_id = b.booking_id

        INNER JOIN rooms r
            ON b.room_id = r.room_id

        INNER JOIN hostels h
            ON r.hostel_id = h.hostel_id

        INNER JOIN users u
            ON b.student_id = u.user_id

        WHERE h.owner_id = :owner_id

        ORDER BY p.payment_id DESC
    ");

    $stmt->execute([
        ':owner_id' => $ownerId
    ]);

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    error_log(
        'Owner payment history error: ' . $e->getMessage()
    );

    http_response_code(500);

    exit('Unable to retrieve payment records. Please try again later.');
}

/*
|--------------------------------------------------------------------------
| Calculate Payment Statistics
|--------------------------------------------------------------------------
*/

$totalSuccessful = 0.00;
$totalPending = 0;
$totalFailed = 0;
$totalTransactions = count($payments);

$successfulCount = 0;

foreach ($payments as $payment) {

    $amount = (float) $payment['amount'];

    switch ($payment['payment_status']) {

        case 'Successful':

            $totalSuccessful += $amount;
            $successfulCount++;

            break;

        case 'Pending':

            $totalPending++;

            break;

        case 'Failed':

            $totalFailed++;

            break;
    }
}

$successRate = $totalTransactions > 0
    ? ($successfulCount / $totalTransactions) * 100
    : 0;

/*
|--------------------------------------------------------------------------
| Payment Status Styling
|--------------------------------------------------------------------------
*/

function paymentBadgeClass(string $status): string
{
    return match ($status) {

        'Successful' => 'badge-successful',

        'Pending' => 'badge-pending',

        'Failed' => 'badge-failed',

        default => 'badge-default'
    };
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Manage and monitor payments associated with your HostelConnect accommodation bookings.">

    <title>Payment Management | HostelConnect</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
            color: #1e293b;
            min-height: 100vh;
        }

        /* HEADER */

        .topbar {
            background: #ffffff;
            padding: 18px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
        }

        .brand {
            text-decoration: none;
            font-size: 23px;
            font-weight: 800;
            color: #172554;
        }

        .brand span {
            color: #2563eb;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .dashboard-btn {
            text-decoration: none;
            background: #eff6ff;
            color: #2563eb;
            padding: 11px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: 0.2s;
        }

        .dashboard-btn:hover {
            background: #dbeafe;
        }

        .logout-btn {
            text-decoration: none;
            background: #fee2e2;
            color: #b91c1c;
            padding: 11px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        /* MAIN CONTAINER */

        .container {
            max-width: 1450px;
            width: 92%;
            margin: 40px auto;
        }

        /* PAGE HEADING */

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 29px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 9px;
        }

        .page-heading p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .report-date {
            color: #64748b;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 12px 17px;
            border-radius: 9px;
            font-size: 13px;
        }

        /* SUMMARY CARDS */

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: white;
            padding: 25px;
            border-radius: 13px;
            border: 1px solid #e8edf5;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.035);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.07);
        }

        .summary-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 15px;
        }

        .summary-value {
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .summary-note {
            margin-top: 10px;
            color: #94a3b8;
            font-size: 12px;
        }

        .successful {
            color: #15803d;
        }

        .pending {
            color: #d97706;
        }

        .failed {
            color: #dc2626;
        }

        .neutral {
            color: #2563eb;
        }

        /* TRANSACTIONS */

        .table-container {
            background: white;
            border: 1px solid #e8edf5;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.035);
        }

        .table-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        .table-heading h2 {
            font-size: 19px;
            font-weight: 800;
        }

        .transaction-count {
            background: #eff6ff;
            color: #2563eb;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 15px 13px;
            text-align: left;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        td {
            padding: 16px 13px;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .student-name {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .student-email {
            color: #94a3b8;
            font-size: 11px;
        }

        .room-number {
            font-weight: 700;
            color: #334155;
        }

        .room-type {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 5px;
        }

        .amount {
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
        }

        .reference {
            font-size: 12px;
            color: #475569;
            word-break: break-word;
        }

        .badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
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

        .payment-date {
            color: #64748b;
            font-size: 12px;
        }

        /* EMPTY STATE */

        .empty-state {
            text-align: center;
            padding: 65px 20px;
        }

        .empty-icon {
            font-size: 45px;
            margin-bottom: 18px;
        }

        .empty-state h3 {
            color: #334155;
            font-size: 19px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 13px;
            line-height: 1.6;
        }

        /* FOOTER */

        .footer {
            text-align: center;
            padding: 30px 15px;
            color: #94a3b8;
            font-size: 12px;
        }

        /* RESPONSIVENESS */

        @media (max-width: 1100px) {

            .summary {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .topbar {
                padding: 16px 5%;
            }

            .brand {
                font-size: 19px;
            }

            .header-right {
                gap: 7px;
            }

            .dashboard-btn,
            .logout-btn {
                padding: 9px 10px;
                font-size: 11px;
            }

            .container {
                width: 94%;
                margin: 28px auto;
            }

            .page-heading h1 {
                font-size: 23px;
            }

            .summary {
                grid-template-columns: 1fr;
                gap: 13px;
            }

            .summary-card {
                padding: 21px;
            }

            .table-container {
                padding: 17px;
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

    <div class="header-right">

        <a href="../../index.php" class="dashboard-btn">
            ← Dashboard
        </a>

        <a href="../authentication/logout.php" class="logout-btn">
            Sign Out
        </a>

    </div>

</header>


<main class="container">

    <!-- PAGE HEADING -->

    <section class="page-heading">

        <div>

            <h1>Payment Management</h1>

            <p>
                Monitor transactions and track payments associated
                with bookings in your hostels.
            </p>

        </div>

        <div class="report-date">

            <?= date('d M Y') ?>

        </div>

    </section>


    <!-- PAYMENT SUMMARY -->

    <section class="summary">

        <div class="summary-card">

            <div class="summary-label">
                Total Successful Payments
            </div>

            <div class="summary-value successful">

                GHS <?= number_format($totalSuccessful, 2) ?>

            </div>

            <div class="summary-note">
                Confirmed payment amounts
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-label">
                Pending Transactions
            </div>

            <div class="summary-value pending">

                <?= number_format($totalPending) ?>

            </div>

            <div class="summary-note">
                Awaiting payment confirmation
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-label">
                Failed Transactions
            </div>

            <div class="summary-value failed">

                <?= number_format($totalFailed) ?>

            </div>

            <div class="summary-note">
                Unsuccessful payment attempts
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-label">
                Total Transactions
            </div>

            <div class="summary-value neutral">

                <?= number_format($totalTransactions) ?>

            </div>

            <div class="summary-note">
                <?= number_format($successRate, 1) ?>% successful transactions
            </div>

        </div>

    </section>


    <!-- PAYMENT RECORDS -->

    <section class="table-container">

        <div class="table-heading">

            <h2>Payment Records</h2>

            <span class="transaction-count">

                <?= number_format($totalTransactions) ?>
                Transactions

            </span>

        </div>


        <?php if (empty($payments)): ?>

            <div class="empty-state">

                <div class="empty-icon">
                    💳
                </div>

                <h3>No Payment Records Found</h3>

                <p>
                    Payments associated with bookings in your hostels
                    will appear here once transactions are initiated.
                </p>

            </div>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Payment ID</th>

                            <th>Student Information</th>

                            <th>Hostel</th>

                            <th>Room</th>

                            <th>Amount</th>

                            <th>Reference</th>

                            <th>Payment Date</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($payments as $payment): ?>

                            <?php

                            $status = (string) $payment['payment_status'];

                            $badgeClass = paymentBadgeClass($status);

                            ?>

                            <tr>

                                <td>
                                    #<?= (int) $payment['payment_id'] ?>
                                </td>


                                <td>

                                    <div class="student-name">

                                        <?= escapeOwnerPaymentOutput(
                                            $payment['student_name']
                                        ) ?>

                                    </div>

                                    <div class="student-email">

                                        <?= escapeOwnerPaymentOutput(
                                            $payment['student_email']
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <?= escapeOwnerPaymentOutput(
                                        $payment['hostel_name']
                                    ) ?>

                                </td>


                                <td>

                                    <div class="room-number">

                                        <?= escapeOwnerPaymentOutput(
                                            $payment['room_number']
                                        ) ?>

                                    </div>

                                    <div class="room-type">

                                        <?= escapeOwnerPaymentOutput(
                                            $payment['room_type']
                                        ) ?>

                                    </div>

                                </td>


                                <td>

                                    <span class="amount">

                                        GHS <?= number_format(
                                            (float) $payment['amount'],
                                            2
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <span class="reference">

                                        <?= escapeOwnerPaymentOutput(
                                            $payment['payment_reference'] ?: 'N/A'
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <span class="payment-date">

                                        <?= !empty($payment['payment_date'])
                                            ? escapeOwnerPaymentOutput(
                                                $payment['payment_date']
                                            )
                                            : 'Not completed' ?>

                                    </span>

                                </td>


                                <td>

                                    <span class="badge <?= $badgeClass ?>">

                                        <?= escapeOwnerPaymentOutput($status) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>


<footer class="footer">

    &copy; <?= date('Y') ?> HostelConnect.
    All Rights Reserved.

</footer>

</body>

</html>