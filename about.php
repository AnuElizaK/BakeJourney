<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | BakeJourney</title>
  <meta name="description" content="BakeJourney - The Home Baker's Marketplace" />
  <meta name="author" content="BakeJourney" />
  <link rel="stylesheet" href="customerdashboard.css">
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
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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
                            <p style="color: #374151;">Our bakers source only the finest, locally-sourced ingredients for authentic flavors.</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M2 18l5-10 5 6 5-6 5 10" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="5" cy="5" r="1" />
                                    <circle cx="12" cy="3" r="1" />
                                    <circle cx="19" cy="5" r="1" />
                                    <path d="M2 18h20" stroke-linecap="round" />
                                </svg>
                            </div>
                            <h3>Unrivaled Quality</h3>
                            <p style="color: #374151;">Baking techniques passed down through generations that ensure exceptional taste and
                                texture.</p>
                        </div>

                        <div class="value-item">
                            <div class="value-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
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
                    <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Baker at work">
                    <div class="experience-badge">
                        <div class="experience-number">10,000+</div>
                        <div class="experience-text">bakers strong</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'globalfooter.php'; ?>
    
</body>

</html>