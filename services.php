<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us | BakeJourney</title>
    <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
    <meta name="author" content="BakeJourney" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@bakejourney" />
    <link rel="stylesheet" href="customerdashboard.css">
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
            margin-bottom: 80px;
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

        /* Services Section */
        .services {
            padding: 50px 0;
            background: linear-gradient(#ffffff, #b8c1ce);
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        @media (min-width: 1024px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .service-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .service-card:hover {
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.15);
            transform: translateY(-8px);
        }

        .service-image {
            position: relative;
            overflow: hidden;
        }

        .service-image img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .service-card:hover .service-image img {
            transform: scale(1.05);
        }

        .service-content {
            padding: 30px;
        }

        .service-content h3 {
            font-size: 1.5rem;
            color: #1f2a38;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .service-content p {
            color: #6b7280;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .service-features {
            list-style: none;
            margin-bottom: 30px;
        }

        .service-features li {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 12px;
        }

        .service-features li::before {
            content: '';
            width: 10px;
            height: 10px;
            background: linear-gradient(135deg, #fcd34d, #f59e0b);
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Contact Section */
        .contact {
            padding: 100px 0;
            background: linear-gradient(#fee996, #b8c1ce);
        }

        .linked-page-contact {
            padding: 100px 0;
            background: linear-gradient(#ffffff 40%, #d4dbe7 100%);
        }

        .contact-content {
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 1024px) {
            .contact-content {
                grid-template-columns: 1fr 1fr;
            }
        }

        .contact-info h3 {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2a38;
            margin-bottom: 30px;
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h4 {
            font-weight: 600;
            color: #1f2a38;
            margin-bottom: 12px;
            font-size: 1.125rem;
        }

        .info-section p {
            color: #6b7280;
            line-height: 1.6;
        }

        .special-orders {
            background: linear-gradient(135deg, #fef3c7, #fed7aa);
            border-radius: 20px;
            padding: 30px;
            margin-top: 40px;
        }

        .special-orders h4 {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 12px;
        }

        .special-orders p {
            font-size: 0.875rem;
            color: #b45309;
            line-height: 1.6;
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
<?php include 'custnavbar.php'; ?>

<body>
    <!-- Services Section -->
    <section class="services" id="services">
        <div class="container">
            <div class="section-header">
                <h2>Services From Our Bakers</h2>
                <p>From daily fresh baking to custom celebrations, we're here to make every moment sweeter.</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1490644120458-f5e5c71d2ab0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Home Delivery">
                    </div>
                    <div class="service-content">
                        <h3>Home Delivery</h3>
                        <p>From the kitchen straight to your doorstep.</p>
                        <ul class="service-features">
                            <li>Available from 500+ bakers</li>
                            <li>Freshly made</li>
                            <li>Your treat, your location</li>
                            <li>Safe, contactless delivery</li>
                        </ul>
                        <button class="btn btn-primary">Learn More</button>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-image">
                        <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                            alt="Custom Cakes">
                    </div>
                    <div class="service-content">
                        <h3>Custom Cakes</h3>
                        <p>Personalized cakes for all your special moments.</p>
                        <ul class="service-features">
                            <li>Custom designs</li>
                            <li>Countless flavors</li>
                            <li>Dietary accommodations</li>
                            <li>Delivery available</li>
                        </ul>
                        <button class="btn btn-primary">Learn More</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'globalfooter.php'; ?>

</body>

</html>