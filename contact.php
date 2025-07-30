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
            color: #1f2a38;
        }

        h1,
        h2 {
            font-family: 'Puanto', Roboto, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 36px;
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

        /* Contact Section */
        .contact {
            padding: 50px 0;
            background: linear-gradient(#fee996, #b8c1ce);
        }

        .form-card {
            background: linear-gradient(135deg, #fef3c7 0%, #fee996 100%);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-card h3 {
            font-size: 1.75rem;
            font-weight: 600;
            text-align: center;
            color: #1f2a38;
            margin-bottom: 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (min-width: 640px) {
            .form-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        form input,
        form textarea {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        form input:focus,
        form textarea:focus {
            outline: none;
            border-color: #f59e0b;
            background: white;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }

        form textarea {
            resize: vertical;
            font-family: 'Segoe UI', Roboto, sans-serif;
            min-height: 140px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
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
    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p>Got any questions or complaints? We'd love to hear from you!</p>
            </div>

            <div class="contact-content">
                <!--<div class="contact-info">
            <h3>Visit Our Bakery</h3>
            
            <div class="info-section">
              <h4>Address</h4>
              <p>123 Baker Street<br>Sweet Valley, SV 12345</p>
            </div>
            
            <div class="info-section">
              <h4>Hours</h4>
              <p>Monday - Friday: 6:00 AM - 7:00 PM<br>
                 Saturday: 7:00 AM - 8:00 PM<br>
                 Sunday: 8:00 AM - 6:00 PM</p>
            </div>
            
            <div class="info-section">
              <h4>Contact</h4>
              <p>Phone: (555) 123-BAKE<br>
                 Email: hello@sweetdreamsbakery.com</p>
            </div>

            <div class="special-orders">
              <h4>Special Orders</h4>
              <p>Need something special? Custom cakes and large orders require 48 hours advance notice. Call us to discuss your requirements!</p>
            </div>
          </div>-->

                <div class="contact-form">
                    <div class="form-card">
                        <h3 class="contact-form-title">Send us a Message</h3>
                        <form>
                            <div class="form-row">
                                <input type="text" placeholder="Your Name" required>
                                <input type="email" placeholder="Email Address" required>
                            </div>
                            <input class="form-row" type="text" placeholder="Subject" required>
                            <textarea class="form-row" placeholder="Your message..." rows="5" required></textarea>
                            <button type="submit" class="btn btn-primary btn-full">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'globalfooter.php'; ?>

</body>