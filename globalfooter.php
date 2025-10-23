<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .footer-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-title,
        .footer-subtitle,
        .quick-links,
        .follow-us,
        .attributions {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .footer {
            background: #1f2a38;
            color: white;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            padding: 60px 0 30px;
            padding-top: 40px;
        }

        .footer-content {
            display: flex;
            flex-wrap: nowrap;
            gap: 2rem;
            justify-content: space-between;
        }

        @media (min-width: 768px) {
            .footer-content {
                grid-template-columns: 2fr 1fr 1fr;
            }
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .footer-brand span {
            font-size: 1.75rem;
            font-weight: bold;
        }

        .footer-main p {
            margin-bottom: 20px;
            max-width: 400px;
            line-height: 1.7;
        }

        .footer-subtitle {
            margin-top: 0;
            font-size: 1.0rem;
            color: #fcd34d;
        }

        .footer-contact {
            font-size: 0.875rem;
            color: #b2b9c6;
        }

        .footer-links h3,
        .footer-social h3,
        .footer-attributions h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer-links ul,
        .footer-social ul,
        .footer-attributions ul {
            font-size: 0.9rem;
            list-style: none;
        }

        .footer-links li,
        .footer-social li,
        .footer-attributions li {
            margin-bottom: 12px;
        }

        .footer-links a,
        .footer-social a {
            color: #d1d5db;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-attributions a {
            color: #fee996;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover,
        .footer-social a:hover,
        .footer-attributions a:hover {
            color: #f59e0b;
        }

        .footer-social img {
            transition: all 0.3s ease;
        }

        .footer-social a:hover img {
            transform: scale(1.2);
        }

        .footer-bottom {
            border-top: 1px solid #374151;
            margin-top: 32px;
            padding: 26px 0 2px 0;
            font-size: 0.95em;
            text-align: center;
        }

        .footer-bottom p {
            color: #9ca3af;
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                gap: 24px;
                align-items: flex-start;
            }

            .footer-main,
            .footer-links,
            .footer-social,
            .footer-attributions {
                min-width: 0;
                width: 100%;
                margin-bottom: 8px;
            }

            .footer-main {
                order: 1;
            }

            .footer-links {
                order: 2;
            }

            .footer-social {
                order: 3;
            }

            .footer-attributions {
                order: 4;
            }

            .footer-brand {
                flex-direction: row;
                align-items: center;
                gap: 10px;
            }

            .footer-title {
                font-size: 1.3em;
            }

            .footer-subtitle {
                font-size: 1em;
            }

            .footer-links ul,
            .footer-social ul,
            .footer-attributions ul {
                padding-left: 0;
            }

            .footer-links li,
            .footer-social li,
            .footer-attributions li {
                margin-bottom: 6px;
            }
        }
    </style>
</head>

<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-main">
                    <div class="footer-brand">
                        <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
                        <span class="footer-title">BakeJourney</span>
                    </div>
                    <p class="footer-subtitle">The Home Baker's Marketplace</p>
                    <p>Handcrafted with love, baked to perfection. Experience the warmth of homemade goodness in every
                        bite.</p>
                    <div class="footer-contact">
                        <p>123 Baker Street, Cake Valley, SV 12345</p>
                        <p>Phone: +91 xxxxx baker</p>
                        <p>Email: hello@bakejourney.com</p>
                    </div>
                </div>

                <div class="footer-links">
                    <h3 class="quick-links">Quick Links</h3>
                    <ul>
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="pagenotready.php">Sitemap</a></li>
                        <li><a href="pagenotready.php">Privacy Policy</a></li>
                        <?php
                        if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer') {
                            echo '<li><a href="blog.php">Blog</a></li>';
                        } else {
                            echo '<li><a href="bakerblog.php">Blog</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                <div class="footer-social">
                    <h3 class="follow-us">Follow Us</h3>
                    <ul>
                        <li><a href="#"><img src="media/facebook.svg" alt="Facebook"
                                    style="vertical-align: bottom;">&nbsp;Facebook</a></li>
                        <li><a href="#"><img src="media/instagram.svg" alt="Instagram"
                                    style="vertical-align: bottom;">&nbsp;Instagram</a></li>
                        <li><a href="#"><img src="media/pinterest.svg" alt="Pinterest"
                                    style="vertical-align: bottom;">&nbsp;Pinterest</a></li>
                        <li><a href="#"><img src="media/x.svg" alt="X (Twitter)"
                                    style="vertical-align: bottom;">&nbsp;X</a></li>
                        <li><a href="#"><img src="media/linkedin.svg" alt="LinkedIn"
                                    style="vertical-align: bottom;">&nbsp;LinkedIn</a></li>
                        <li><a href="#"><img src="media/github.svg" alt="GitHub"
                                    style="vertical-align: bottom;">&nbsp;GitHub</a></li>
                    </ul>
                </div>
                <div class="footer-attributions">
                    <h3 class="attributions">Attributions</h3>
                    <ul>
                        <li>Icons by <a href="https://icons8.com">Icons8</a> & <a
                                href="https://www.flaticon.com/">Flaticon</a></li>
                        <li>Images by <a href="https://unsplash.com/">Unsplash</a> & <a
                                href="https://www.pexels.com/">Pexels</a></li>
                        <li>Fonts by <a href="https://fonts.google.com/">Google Fonts</a></li>
                        <li>Illustrations by <a href="https://storyset.com/">Storyset</a></li>
                        <li>Branding font (Puanto) by <a href="https://creativemarket.com/pasha.larin">Larin Type
                                Co.</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 BakeJourney. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>