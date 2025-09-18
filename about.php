<?php
session_start();
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
    <title>About Us | BakeJourney</title>
    <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
    <meta name="author" content="BakeJourney" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1f2a38;
            background: linear-gradient(135deg, #fef3c7, #fee996);
            margin-top: 80px;
        }

        h1,
        h2,
        .baker-cta-title,
        .customer-cta-title,
        .nav-title,
        .footer-title,
        .footer-subtitle,
        .contact-form-title,
        .quick-links,
        .follow-us,
        .attributions {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .about {
            padding: 50px 0;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 80px;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .about-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .about-text h2 {
            font-size: 3.5rem;
            font-weight: bold;
            color: #1f2a38;
            margin-bottom: 32px;
        }

        .about-text p {
            font-size: 1.125rem;
            color: #374151;
            margin-bottom: 32px;
            line-height: 1.8;
        }

        .values-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 32px;
            margin-top: 40px;
        }

        @media (min-width: 640px) {
            .values-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .value-item {
            text-align: center;
        }

        .value-icon {
            background: white;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            color: #f59e0b;
            transition: transform 0.3s ease;
        }

        .value-item:hover .value-icon {
            transform: translateY(-5px);
        }

        .value-item h3 {
            font-weight: 600;
            color: #1f2a38;
            margin-bottom: 12px;
            font-size: 1.125rem;
        }

        .value-item p {
            font-size: 0.875rem;
            color: #374151;
            line-height: 1.6;
        }

        .about-image {
            position: relative;
        }

        .about-image img {
            border-radius: 24px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
            width: 100%;
        }

        .experience-badge {
            position: absolute;
            bottom: -30px;
            left: -30px;
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .experience-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #f59e0b;
        }

        .experience-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            body {
                margin-top: 0;
            }

            .about {
                padding-bottom: 100px;
            }

            .about-content {
                gap: 20px;
            }

            .about-text h2 {
                font-size: 2rem;
            }

            .about-text p {
                font-size: 1rem;
            }

            .values-grid {
                gap: 20px;
            }

            .value-item {
                margin: 0 50px;
            }

            .value-item p {
                font-size: 0.875rem;
            }

            .value-icon {
                width: 60px;
                height: 60px;
            }

            .experience-badge {
                padding: 15px;
            }

            .experience-number {
                font-size: 1.7rem;
            }

            .experience-text {
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>What Is BakeJourney?</h2>
                    <p>BakeJourney exists to support home bakers who dream big and bake even bigger. Born from the real
                        challenges
                        faced by small, home-based bakeries, our platform is designed to be your one-stop digital
                        toolkit and
                        simplify day-to-day operations, so bakers can stay focused on the flour-dusted magic in the
                        kitchen.</p>
                    <p>Whether it's managing orders, tracking your inventory, or connecting with loyal customers,
                        BakeJourney is
                        here to support your journey with style, efficiency, and a sprinkle of sweetness.</p>
                    <p>And for customers craving something special? BakeJourney helps them discover talented local
                        bakers, browse
                        personalized menus, place custom orders, and support small businesses, all from the comfort of
                        home.</p>
                    <p style="font-weight: 500;">Because every journey is sweeter when it’s homemade.</p>

                    <div class="values-grid">
                        <div class="value-item">
                            <div class="value-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5" />
                                    <path d="M8.5 8.5v.01" />
                                    <path d="M16 15.5v.01" />
                                    <path d="M12 12v.01" />
                                    <path d="M11 17v.01" />
                                    <path d="M7 14v.01" />
                                </svg>
                            </div>
                            <h3>Fresh Ingredients</h3>
                            <p style="color: #374151;">Our bakers source only the finest, locally-sourced ingredients
                                for authentic flavors.</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M2 18l5-10 5 6 5-6 5 10" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="5" cy="5" r="1" />
                                    <circle cx="12" cy="3" r="1" />
                                    <circle cx="19" cy="5" r="1" />
                                    <path d="M2 18h20" stroke-linecap="round" />
                                </svg>
                            </div>
                            <h3>Unrivaled Quality</h3>
                            <p style="color: #374151;">Baking techniques passed down through generations that ensure
                                exceptional taste and
                                texture.</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 21C12 21 4 13.5 4 8a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 13-10 13z" />
                                </svg>


                            </div>
                            <h3>Made with Love</h3>
                            <p style="color: #374151;">Every item is crafted with care and attention to detail.</p>
                        </div>
                    </div>
                </div>

                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1630507103234-00d3e621cae2?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Baker at work">
                    <div class="experience-badge">
                        <div class="experience-number">1000+</div>
                        <div class="experience-text">products to choose from,</div>
                        <div class="experience-text">made by the best</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'globalfooter.php'; ?>

</body>

</html>