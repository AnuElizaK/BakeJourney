<?php
session_start();
include 'db.php';
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login if not authorized
    exit();
}
// Prevent back after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | BakeJourney</title>
    <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
    <meta name="author" content="BakeJourney" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@bakejourney" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            padding-top: 80px;
            background: linear-gradient(#fee996 5%, #b8c1ce 60%);
            color: #1f2a38;
        }

        h1,
        h2 {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .message-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 36px;
            font-family: 'Segoe UI', Roboto, sans-serif;
            font-size: 1.125rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            color: white;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(217, 119, 6, 0.4);
        }

        .btn-large {
            padding: 18px 48px;
            font-size: 1.25rem;
        }

        .btn-full {
            width: 100%;
        }

        .btn-outline {
            border: 2px solid white;
            color: white;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: white;
            color: #f59e0b;
            transform: translateY(-2px);
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 3rem;
            font-weight: bold;
            color: #1f2a38;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .section-header p {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* message Section */
        .message-section {
            padding: 200px 0;
        }

        .message-content {
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 1024px) {
            .message-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .body {
                padding-top: 70px;
            }

            .message-section {
                padding: 140px 0;
            }

            .section-header h2 {
                font-size: 2rem;
            }

            .section-header p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<!-- Sticky Navigation Bar -->
<?php
if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
    include 'custnavbar.php';
} else {
    include 'bakernavbar.php';
}
?>

<body>
    <!-- Page Not Ready Message Section -->
    <section class="message-section" id="message">
        <div class="message-container">
            <div class="section-header">
                <h2>Oops! This page is not ready yet.</h2>
                <p>Sorry, but this page is still under construction. Come back later.☺️</p>
            </div>
        </div>
    </section>
    <?php include 'globalfooter.php'; ?>
</body>