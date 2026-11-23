<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth_check.php';

/*
|--------------------------------------------------------------------------
| USER INFORMATION
|--------------------------------------------------------------------------
*/

$fullName = htmlspecialchars(
    (string) ($_SESSION['full_name'] ?? 'User'),
    ENT_QUOTES,
    'UTF-8'
);

$role = (string) ($_SESSION['role'] ?? 'Student');

/*
|--------------------------------------------------------------------------
| HELPER FUNCTION
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

/*
|--------------------------------------------------------------------------
| DASHBOARD CONTENT
|--------------------------------------------------------------------------
*/

$dashboardContent = [

    /*
    |--------------------------------------------------------------------------
    | STUDENT DASHBOARD
    |--------------------------------------------------------------------------
    */

    'Student' => [

        'title' => 'Find Your Ideal Hostel',

        'description' =>
            'Discover comfortable accommodation, explore available rooms and manage your bookings with ease.',

        'icon' => '🏡',

        'cards' => [

            [
                'title' => 'Explore Hostels',
                'description' => 'Discover hostels around your university and find accommodation that suits your needs.',
                'icon' => '🏠',
                'link' => 'modules/hostels/browse_hostels.php',
                'label' => 'Explore Now'
            ],

            [
                'title' => 'Available Rooms',
                'description' => 'Explore room options, compare accommodation prices and find your ideal room.',
                'icon' => '🛏️',
                'link' => 'modules/hostels/browse_hostels.php',
                'label' => 'View Rooms'
            ],

            [
                'title' => 'My Bookings',
                'description' => 'View your hostel reservations, monitor booking status and manage your accommodation.',
                'icon' => '📋',
                'link' => 'modules/bookings/my_bookings.php',
                'label' => 'Manage Bookings'
            ],

            [
                'title' => 'Payment History',
                'description' => 'Review your transactions, monitor payment status and manage outstanding payments.',
                'icon' => '💳',
                'link' => 'modules/payments/payment_history.php',
                'label' => 'View Payments'
            ]

        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | HOSTEL OWNER DASHBOARD
    |--------------------------------------------------------------------------
    */

    'Owner' => [

        'title' => 'Manage Your Hostel Business',

        'description' =>
            'Manage your hostel properties, rooms, student reservations and payment activities from one place.',

        'icon' => '🏢',

        'cards' => [

            [
                'title' => 'My Hostels',
                'description' => 'Add, view and manage your registered hostel properties.',
                'icon' => '🏠',
                'link' => 'modules/hostels/view_hostels.php',
                'label' => 'Manage Hostels'
            ],

            [
                'title' => 'Manage Rooms',
                'description' => 'Add rooms, update room information and manage accommodation availability.',
                'icon' => '🛏️',
                'link' => 'modules/rooms/view_rooms.php',
                'label' => 'Manage Rooms'
            ],

            [
                'title' => 'Bookings',
                'description' => 'Review student reservations and monitor booking activities.',
                'icon' => '📋',
                'link' => 'modules/bookings/owner_bookings.php',
                'label' => 'View Bookings'
            ],

            [
                'title' => 'Payments',
                'description' => 'Monitor hostel booking payments and review transaction records.',
                'icon' => '💳',
                'link' => 'modules/payments/owner_payment_history.php',
                'label' => 'View Payments'
            ]

        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | ADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    'Admin' => [

        'title' => 'System Administration',

        'description' =>
            'Oversee platform activities, manage registered users and monitor hostel booking operations.',

        'icon' => '⚙️',

        'cards' => [

            [
                'title' => 'Users',
                'description' => 'Manage registered students, hostel owners and platform accounts.',
                'icon' => '👥',
                'link' => '#',
                'label' => 'Coming Soon'
            ],

            [
                'title' => 'Hostels',
                'description' => 'Oversee registered hostel properties and platform listings.',
                'icon' => '🏠',
                'link' => '#',
                'label' => 'Coming Soon'
            ],

            [
                'title' => 'Bookings',
                'description' => 'Monitor reservations and review booking activities across the platform.',
                'icon' => '📋',
                'link' => '#',
                'label' => 'Coming Soon'
            ],

            [
                'title' => 'Payments',
                'description' => 'Monitor transactions and oversee payment activities.',
                'icon' => '💳',
                'link' => '#',
                'label' => 'Coming Soon'
            ]

        ]

    ]

];

/*
|--------------------------------------------------------------------------
| SELECT DASHBOARD
|--------------------------------------------------------------------------
*/

$content = $dashboardContent[$role] ?? $dashboardContent['Student'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | HostelConnect</title>

    <meta
        name="description"
        content="HostelConnect - Discover, manage and book student accommodation."
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="public/css/dashboard.css">

    <style>

        :root {

            --primary: #2563eb;
            --primary-dark: #1d4ed8;

            --background: #f1f5f9;
            --surface: #ffffff;

            --text-dark: #0f172a;
            --text-muted: #64748b;

            --border: #e2e8f0;

            --radius: 15px;

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

        /* =================================
           NAVIGATION
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

            font-size: 24px;

            font-weight: 800;

            letter-spacing: -1px;

            color: var(--text-dark);

        }

        .brand span {
            color: var(--primary);
        }

        .topbar-right {

            display: flex;

            align-items: center;

            gap: 18px;

        }

        .user-info {

            display: flex;

            flex-direction: column;

            align-items: flex-end;

            gap: 4px;

        }

        .user-name {

            font-size: 13px;

            font-weight: 800;

            color: var(--text-dark);

        }

        .user-role {

            font-size: 11px;

            font-weight: 700;

            color: var(--primary);

            background: #eff6ff;

            padding: 4px 9px;

            border-radius: 20px;

        }

        .logout-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 10px 17px;

            border-radius: 8px;

            background: #fee2e2;

            color: #dc2626;

            font-size: 12px;

            font-weight: 800;

            transition: .25s ease;

        }

        .logout-btn:hover {

            background: #fecaca;

            transform: translateY(-2px);

        }

        /* =================================
           MAIN DASHBOARD
        ================================= */

        .dashboard-container {

            max-width: 1250px;

            margin: 40px auto;

            padding: 0 25px;

        }

        /* =================================
           WELCOME SECTION
        ================================= */

        .welcome-section {

            display: flex;

            justify-content: space-between;

            align-items: center;

            gap: 25px;

            padding: 40px;

            border-radius: 18px;

            background: linear-gradient(
                120deg,
                #1e40af,
                #2563eb,
                #3b82f6
            );

            color: white;

            margin-bottom: 40px;

            position: relative;

            overflow: hidden;

            box-shadow: 0 12px 30px rgba(37, 99, 235, .15);

        }

        .welcome-section::after {

            content: '';

            position: absolute;

            width: 260px;

            height: 260px;

            right: 80px;

            top: -150px;

            border-radius: 50%;

            background: rgba(255,255,255,.08);

        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-label {

            display: inline-block;

            margin-bottom: 15px;

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 2px;

            color: #dbeafe;

        }

        .welcome-section h1 {

            margin: 0 0 13px;

            font-size: 32px;

            font-weight: 800;

            letter-spacing: -1px;

        }

        .welcome-section p {

            max-width: 600px;

            margin: 0;

            font-size: 14px;

            line-height: 1.8;

            color: #dbeafe;

        }

        .welcome-icon {

            width: 110px;

            height: 110px;

            display: flex;

            justify-content: center;

            align-items: center;

            border-radius: 25px;

            background: rgba(255,255,255,.15);

            font-size: 58px;

            position: relative;

            z-index: 2;

            flex-shrink: 0;

            backdrop-filter: blur(10px);

        }

        /* =================================
           DASHBOARD HEADING
        ================================= */

        .dashboard-heading {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 24px;

        }

        .dashboard-heading h2 {

            margin: 0 0 8px;

            font-size: 23px;

            font-weight: 800;

            letter-spacing: -.5px;

        }

        .dashboard-heading p {

            margin: 0;

            color: var(--text-muted);

            font-size: 13px;

        }

        /* =================================
           DASHBOARD GRID
        ================================= */

        .dashboard-grid {

            display: grid;

            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: 22px;

        }

        .dashboard-card {

            background: var(--surface);

            border: 1px solid var(--border);

            border-radius: var(--radius);

            padding: 25px;

            min-height: 245px;

            box-shadow: 0 4px 15px rgba(15, 23, 42, .035);

            transition: .3s ease;

            position: relative;

        }

        .dashboard-card:hover {

            transform: translateY(-6px);

            box-shadow: 0 14px 30px rgba(15, 23, 42, .08);

            border-color: #bfdbfe;

        }

        .dashboard-card-link {

            display: block;

            height: 100%;

            color: inherit;

            text-decoration: none;

        }

        .card-icon {

            width: 55px;

            height: 55px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

            background: #eff6ff;

            font-size: 27px;

            margin-bottom: 20px;

        }

        .dashboard-card h3 {

            margin: 0 0 11px;

            font-size: 16px;

            font-weight: 800;

            color: var(--text-dark);

        }

        .dashboard-card p {

            margin: 0;

            font-size: 12px;

            color: var(--text-muted);

            line-height: 1.8;

        }

        .card-action {

            display: inline-block;

            margin-top: 19px;

            color: var(--primary);

            font-size: 12px;

            font-weight: 800;

        }

        .dashboard-card:hover .card-action {
            color: var(--primary-dark);
        }

        .coming-soon {
            color: var(--text-muted);
        }

        /* =================================
           FOOTER
        ================================= */

        .dashboard-footer {

            text-align: center;

            padding: 35px 15px;

            margin-top: 45px;

            border-top: 1px solid var(--border);

        }

        .dashboard-footer p {

            margin: 0;

            color: var(--text-muted);

            font-size: 12px;

            line-height: 1.7;

        }

        /* =================================
           RESPONSIVE DESIGN
        ================================= */

        @media (max-width: 1050px) {

            .dashboard-grid {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }

        }

        @media (max-width: 650px) {

            .topbar {

                padding: 14px 4%;

            }

            .brand {
                font-size: 19px;
            }

            .topbar-right {
                gap: 10px;
            }

            .user-name {
                font-size: 11px;
            }

            .user-role {
                font-size: 9px;
            }

            .logout-btn {
                padding: 9px 11px;
                font-size: 10px;
            }

            .dashboard-container {

                margin: 25px auto;

                padding: 0 15px;

            }

            .welcome-section {

                padding: 27px 22px;

                align-items: flex-start;

            }

            .welcome-section h1 {
                font-size: 24px;
            }

            .welcome-section p {
                font-size: 12px;
            }

            .welcome-icon {

                width: 65px;

                height: 65px;

                font-size: 34px;

                border-radius: 15px;

            }

            .dashboard-heading h2 {
                font-size: 19px;
            }

            .dashboard-grid {
                gap: 13px;
            }

            .dashboard-card {

                padding: 18px;

                min-height: 225px;

            }

            .dashboard-card h3 {
                font-size: 14px;
            }

            .dashboard-card p {
                font-size: 11px;
            }

        }

        @media (max-width: 400px) {

            .topbar {
                padding: 13px 3%;
            }

            .brand {
                font-size: 16px;
            }

            .topbar-right {
                gap: 6px;
            }

            .user-name {
                font-size: 10px;
            }

            .logout-btn {
                padding: 8px;
                font-size: 9px;
            }

            .welcome-icon {
                display: none;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-card {
                min-height: auto;
            }

        }

        /* =================================
           ACCESSIBILITY
        ================================= */

        .dashboard-card-link:focus-visible,
        .logout-btn:focus-visible {

            outline: 3px solid #93c5fd;

            outline-offset: 4px;

        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {

                animation-duration: .01ms !important;

                transition-duration: .01ms !important;

                scroll-behavior: auto !important;

            }

        }

    </style>

</head>

<body>


<!-- =================================
     TOP NAVIGATION
================================== -->

<header class="topbar">

    <a href="index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <div class="topbar-right">

        <div class="user-info">

            <span class="user-name">
                <?= $fullName ?>
            </span>

            <span class="user-role">
                <?= e($role) ?>
            </span>

        </div>

        <a
            href="modules/authentication/logout.php"
            class="logout-btn"
        >
            Sign Out
        </a>

    </div>

</header>


<!-- =================================
     MAIN DASHBOARD
================================== -->

<main class="dashboard-container">


    <!-- WELCOME SECTION -->

    <section class="welcome-section">

        <div class="welcome-content">

            <span class="welcome-label">
                YOUR ACCOMMODATION PARTNER
            </span>

            <h1>
                Welcome, <?= $fullName ?>!
            </h1>

            <p>
                <?= e($content['description']) ?>
            </p>

        </div>

        <div class="welcome-icon">
            <?= e($content['icon']) ?>
        </div>

    </section>


    <!-- DASHBOARD HEADING -->

    <section class="dashboard-heading">

        <div>

            <h2>
                <?= e($content['title']) ?>
            </h2>

            <p>
                Choose an area to get started.
            </p>

        </div>

    </section>


    <!-- DASHBOARD CARDS -->

    <section class="dashboard-grid">

        <?php foreach ($content['cards'] as $card): ?>

            <?php

                $hasLink = $card['link'] !== '#';

            ?>

            <article class="dashboard-card">

                <?php if ($hasLink): ?>

                    <a
                        href="<?= e($card['link']) ?>"
                        class="dashboard-card-link"
                    >

                <?php endif; ?>


                <div class="card-icon">
                    <?= e($card['icon']) ?>
                </div>


                <h3>
                    <?= e($card['title']) ?>
                </h3>


                <p>
                    <?= e($card['description']) ?>
                </p>


                <span class="card-action <?= !$hasLink ? 'coming-soon' : '' ?>">

                    <?= e($card['label']) ?>

                    <?= $hasLink ? ' →' : '' ?>

                </span>


                <?php if ($hasLink): ?>

                    </a>

                <?php endif; ?>

            </article>

        <?php endforeach; ?>

    </section>


    <!-- FOOTER -->

    <footer class="dashboard-footer">

        <p>

            &copy; <?= date('Y') ?> HostelConnect.

            Connecting Students to Their Ideal Accommodation.

        </p>

    </footer>


</main>


</body>

</html>