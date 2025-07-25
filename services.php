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