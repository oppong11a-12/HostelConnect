<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/role_check.php';
requireRole(['Student']);

require_once __DIR__ . '/../../config/database.php';

/*
|--------------------------------------------------------------------------
| Helper Function
|--------------------------------------------------------------------------
*/

function escape(?string $value): string
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| Search and Filter
|--------------------------------------------------------------------------
*/

$keyword = trim($_GET['search'] ?? '');

if (strlen($keyword) > 100) {
    $keyword = substr($keyword, 0, 100);
}

$universityId = filter_input(
    INPUT_GET,
    'university_id',
    FILTER_VALIDATE_INT
);

if ($universityId === false || $universityId < 1) {
    $universityId = null;
}

/*
|--------------------------------------------------------------------------
| Fetch Universities
|--------------------------------------------------------------------------
*/

$universityStmt = $pdo->query("
    SELECT
        university_id,
        university_name
    FROM universities
    ORDER BY university_name ASC
");

$universities = $universityStmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Retrieve Hostels
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        h.hostel_id,
        h.hostel_name,
        h.location,
        h.description,
        h.contact_number,
        u.university_name,

        COUNT(r.room_id) AS room_count,

        MIN(r.price) AS minimum_price

    FROM hostels h

    INNER JOIN universities u
        ON h.university_id = u.university_id

    LEFT JOIN rooms r
        ON h.hostel_id = r.hostel_id

    WHERE 1 = 1
";

$params = [];

if ($keyword !== '') {

    $sql .= "
        AND (
            h.hostel_name LIKE :hostel_name
            OR h.location LIKE :location
            OR u.university_name LIKE :university_name
        )
    ";

    $searchTerm = '%' . $keyword . '%';

    $params[':hostel_name'] = $searchTerm;
    $params[':location'] = $searchTerm;
    $params[':university_name'] = $searchTerm;
}

if ($universityId !== null) {

    $sql .= "
        AND h.university_id = :university_id
    ";

    $params[':university_id'] = $universityId;
}

$sql .= "

    GROUP BY
        h.hostel_id,
        h.hostel_name,
        h.location,
        h.description,
        h.contact_number,
        u.university_name

    ORDER BY h.created_at DESC
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$hostels = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalHostels = count($hostels);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Explore student accommodation and discover hostels around your university with HostelConnect.">

    <title>Explore Hostels | HostelConnect</title>

    <link rel="stylesheet" href="../../public/css/dashboard.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            margin: 0;
        }

        /* TOP NAVIGATION */

        .topbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 17px 5%;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            color: #0f172a;
            text-decoration: none;
            letter-spacing: -1px;
        }

        .brand span {
            color: #2563eb;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
        }

        .user-role {
            font-size: 11px;
            color: #64748b;
        }

        .logout-btn,
        .back-btn {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: .25s;
        }

        .logout-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        .logout-btn:hover {
            background: #fecaca;
        }

        .back-btn {
            background: #eff6ff;
            color: #2563eb;
        }

        .back-btn:hover {
            background: #dbeafe;
        }

        /* MAIN CONTAINER */

        .page-container {
            max-width: 1250px;
            margin: 45px auto;
            padding: 0 25px;
        }

        /* PAGE HEADING */

        .page-heading {
            margin-bottom: 30px;
        }

        .page-heading h1 {
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -1px;
            margin: 0 0 10px;
        }

        .page-heading p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }

        .heading-label {
            display: inline-block;
            font-size: 11px;
            font-weight: 800;
            color: #2563eb;
            background: #eff6ff;
            padding: 7px 12px;
            border-radius: 20px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* SEARCH PANEL */

        .search-panel {
            background: #ffffff;
            padding: 25px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            margin-bottom: 35px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.025);
        }

        .search-title {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 13px;
        }

        .search-form input,
        .search-form select {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            color: #0f172a;
            font-family: inherit;
            font-size: 13px;
            outline: none;
            transition: .2s;
        }

        .search-form input:focus,
        .search-form select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .search-btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 14px 22px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .25s;
        }

        .search-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        /* RESULTS HEADER */

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .results-header h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
        }

        .results-count {
            font-size: 12px;
            color: #64748b;
            background: #e2e8f0;
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: 700;
        }

        /* HOSTEL GRID */

        .hostel-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        /* HOSTEL CARD */

        .hostel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 25px;
            transition: transform .25s, box-shadow .25s;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .hostel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(15, 23, 42, .08);
            border-color: #bfdbfe;
        }

        .university-tag {
            display: inline-block;
            width: fit-content;
            background: #eff6ff;
            color: #2563eb;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .hostel-card h2 {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.4px;
            margin: 0 0 15px;
            line-height: 1.4;
        }

        .hostel-card p {
            color: #64748b;
            font-size: 12px;
            line-height: 1.8;
            margin: 0 0 12px;
            overflow-wrap: anywhere;
        }

        .hostel-card p strong {
            color: #334155;
        }

        .description {
            min-height: 65px;
        }

        /* HOSTEL INFORMATION */

        .hostel-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: auto;
            padding: 18px 0;
            border-top: 1px solid #e2e8f0;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .meta-item span {
            font-size: 11px;
            color: #64748b;
        }

        .meta-item strong {
            color: #2563eb;
            font-size: 14px;
            font-weight: 800;
        }

        /* DETAILS BUTTON */

        .details-btn {
            display: block;
            text-align: center;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            padding: 13px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            transition: .25s;
        }

        .details-btn:hover {
            background: #1d4ed8;
        }

        /* EMPTY STATE */

        .empty-state {
            background: #ffffff;
            text-align: center;
            padding: 65px 25px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .empty-icon {
            font-size: 42px;
            margin-bottom: 15px;
        }

        .empty-state h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #64748b;
            font-size: 13px;
            line-height: 1.8;
        }

        .reset-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 11px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        /* FOOTER */

        .page-footer {
            text-align: center;
            padding: 35px 15px;
            color: #94a3b8;
            font-size: 11px;
            margin-top: 45px;
        }

        /* RESPONSIVE DESIGN */

        @media(max-width: 1000px) {

            .hostel-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }

        @media(max-width: 700px) {

            .topbar {
                padding: 15px 4%;
            }

            .topbar-right {
                gap: 8px;
            }

            .user-info {
                display: none;
            }

            .page-container {
                margin: 30px auto;
                padding: 0 16px;
            }

            .page-heading h1 {
                font-size: 25px;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .hostel-grid {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 400px) {

            .brand {
                font-size: 19px;
            }

            .logout-btn,
            .back-btn {
                padding: 9px;
                font-size: 10px;
            }

            .page-heading h1 {
                font-size: 22px;
            }

        }

    </style>

</head>

<body>

<!-- NAVIGATION -->

<header class="topbar">

    <a href="../../index.php" class="brand">
        Hostel<span>Connect.</span>
    </a>

    <div class="topbar-right">

        <div class="user-info">

            <span class="user-name">
                <?= escape($_SESSION['full_name'] ?? 'Student') ?>
            </span>

            <span class="user-role">
                Student Account
            </span>

        </div>

        <a href="../authentication/logout.php" class="logout-btn">
            Sign Out
        </a>

        <a href="../../index.php" class="back-btn">
            ← Home
        </a>

    </div>

</header>


<!-- MAIN CONTENT -->

<main class="page-container">


    <!-- PAGE HEADING -->

    <section class="page-heading">

        <span class="heading-label">
            Accommodation Discovery
        </span>

        <h1>
            Explore Student Hostels
        </h1>

        <p>
            Discover accommodation options, compare hostel prices
            and find a place that suits your university lifestyle.
        </p>

    </section>


    <!-- SEARCH SECTION -->

    <section class="search-panel">

        <div class="search-title">
            Find Your Ideal Accommodation
        </div>

        <form method="GET" class="search-form">

            <input
                type="search"
                name="search"
                placeholder="Search hostel name, location or university..."
                value="<?= escape($keyword) ?>"
                maxlength="100"
            >

            <select name="university_id">

                <option value="">
                    All Universities
                </option>

                <?php foreach ($universities as $university): ?>

                    <option
                        value="<?= (int)$university['university_id'] ?>"
                        <?= $universityId === (int)$university['university_id'] ? 'selected' : '' ?>
                    >

                        <?= escape($university['university_name']) ?>

                    </option>

                <?php endforeach; ?>

            </select>

            <button type="submit" class="search-btn">
                Search Hostels →
            </button>

        </form>

    </section>


    <!-- RESULTS HEADER -->

    <div class="results-header">

        <h2>
            Available Hostels
        </h2>

        <span class="results-count">

            <?= $totalHostels ?>
            <?= $totalHostels === 1 ? 'Hostel Found' : 'Hostels Found' ?>

        </span>

    </div>


    <!-- HOSTEL RESULTS -->

    <?php if ($totalHostels > 0): ?>

        <section class="hostel-grid">

            <?php foreach ($hostels as $hostel): ?>

                <article class="hostel-card">


                    <!-- UNIVERSITY -->

                    <span class="university-tag">

                        <?= escape($hostel['university_name']) ?>

                    </span>


                    <!-- HOSTEL NAME -->

                    <h2>

                        <?= escape($hostel['hostel_name']) ?>

                    </h2>


                    <!-- LOCATION -->

                    <p>

                        <strong>Location:</strong>

                        <?= escape($hostel['location']) ?>

                    </p>


                    <!-- DESCRIPTION -->

                    <p class="description">

                        <?= escape(
                            $hostel['description']
                            ?? 'No description provided.'
                        ) ?>

                    </p>


                    <!-- HOSTEL INFORMATION -->

                    <div class="hostel-meta">


                        <div class="meta-item">

                            <span>
                                Listed Rooms
                            </span>

                            <strong>
                                <?= (int)$hostel['room_count'] ?>
                            </strong>

                        </div>


                        <div class="meta-item">

                            <span>
                                Starting Price
                            </span>

                            <strong>

                                <?= $hostel['minimum_price'] !== null

                                    ? 'GHS ' . number_format(
                                        (float)$hostel['minimum_price'],
                                        2
                                    )

                                    : 'Not listed'

                                ?>

                            </strong>

                        </div>


                    </div>


                    <!-- DETAILS BUTTON -->

                    <a
                        href="hostel_details.php?id=<?= (int)$hostel['hostel_id'] ?>"
                        class="details-btn"
                    >

                        View Hostel Details →

                    </a>


                </article>

            <?php endforeach; ?>

        </section>


    <?php else: ?>


        <!-- EMPTY SEARCH RESULTS -->

        <section class="empty-state">

            <div class="empty-icon">
                🏠
            </div>

            <h2>
                No Hostels Found
            </h2>

            <p>

                We couldn't find any hostels matching your search.

                <br>

                Try another hostel name, location or university.

            </p>

            <a href="explore_hostels.php" class="reset-btn">

                View All Hostels

            </a>

        </section>


    <?php endif; ?>


</main>


<!-- FOOTER -->

<footer class="page-footer">

    © <?= date('Y') ?> HostelConnect.

    Connecting Students to Better Accommodation.

</footer>


</body>

</html>