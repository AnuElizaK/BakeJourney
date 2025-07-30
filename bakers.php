<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Bakers | BakeJourney</title>
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
      margin-bottom: 20px;
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

    /* Filter Tabs */
    /*.filter-section {
      background: none;
      padding: 15px 20px;
      border-radius: 0.75rem;
      margin-bottom: 2rem;
    }
      .filter-tabs {
      display: flex;
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
      border-radius: 0.65rem;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .filter-btn {
      padding: 12px 15px;
      border: none;
      background: #f8f9fa;
      border-radius: 0.65rem;
      font-size: 0.9rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      color: #6b7280;
    }

    .filter-btn.active {
      background: linear-gradient(135deg, #fcd34d, #f59e0b);
      color: white;
    }

    .filter-btn:hover:not(.active) {
      background: #fee996;
    }*/

    .baker-search-input {
      width: 100%;
      padding: 12px 45px;
      background: transparent url("media/search.png") no-repeat 10px center;
      border: 1.5px solid #c6c8ca;
      border-radius: 0.65rem;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s ease;
      margin-top: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .baker-search-input:hover {
      border-color: #8b919c;
      background-color: #f8f9fa;
    }

    .baker-search-input:focus {
      outline: none;
      border-color: #f59e0b;
      box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
    }

    /* Top Bakers Section */
    .top-bakers {
      background: white;
    }

    .all-bakers {
      padding: 50px 0;
      background: linear-gradient(#fff1bb, #ffffff);
    }

    .bakers-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 40px;
    }

    @media (min-width: 768px) {
      .bakers-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .baker-card {
      background: white;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.4s ease;
      cursor: pointer;
      border: 1px solid rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .baker-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: #fef7cd;
      opacity: 0;
      transition: opacity 0.4s ease;
      border-radius: 24px;
    }

    .baker-card:hover::before {
      opacity: 1;
    }

    .baker-card:hover {
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      transform: translateY(-12px);
    }

    .baker-image {
      position: relative;
      overflow: hidden;
    }

    .baker-image img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      transition: transform 0.4s ease;
    }

    .baker-card:hover .baker-image img {
      transform: scale(1.08);
    }

    .ranking-badge {
      position: absolute;
      top: 20px;
      right: 20px;
      background: linear-gradient(135deg, #fcd34d, #d97706);
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 0.875rem;
      font-weight: 600;
      box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4);
    }

    .ranking-badge.large {
      font-size: 1rem;
      padding: 14px 24px;
      border-radius: 30px;
    }

    .baker-content {
      padding: 20px 30px 30px;
      position: relative;
      z-index: 2;
    }

    .baker-content h3 {
      font-size: 1.3rem;
      font-weight: 600;
      color: #1f2a38;
      margin-bottom: 10px;
      letter-spacing: -0.01em;
    }

    .baker-specialty {
      color: #d97706;
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1rem;
    }

    .baker-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
      gap: 16px;
    }

    .baker-stats.large {
      gap: 24px;
      margin-top: 32px;
      justify-content: space-between;
    }

    .baker-stats .stat {
      font-size: 0.66rem;
      color: #6b7280;
      background: #dadde3;
      padding: 5px 14px;
      border-radius: 20px;
      transition: all 0.3s ease;
    }

    .baker-stats.large .stat {
      flex-direction: column;
      text-align: center;
      background: linear-gradient(135deg, #fef3c7, #fee996);
      padding: 20px;
      border-radius: 16px;
      min-width: 120px;
      flex: 1;
    }

    .stat-number {
      display: block;
      font-size: 2rem;
      font-weight: bold;
      color: #d97706;
      line-height: 1;
    }

    .stat-label {
      display: block;
      font-size: 0.875rem;
      color: #b45309;
      margin-top: 4px;
    }

    /* Baker Profile Header */
    .baker-profile-header {
      padding: 100px 0;
      background: linear-gradient(135deg, #fef3c7, #fed7aa);
    }

    .profile-content {
      display: grid;
      grid-template-columns: 1fr;
      gap: 60px;
      align-items: center;
    }

    @media (min-width: 1024px) {
      .profile-content {
        grid-template-columns: 320px 1fr;
      }
    }

    .profile-image {
      position: relative;
      text-align: center;
    }

    .profile-image img {
      width: 280px;
      height: 280px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      border: 8px solid white;
    }

    .profile-info h1 {
      font-size: 3.5rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 20px;
      letter-spacing: -0.02em;
    }

    /* Baker Story */
    .baker-story {
      padding: 100px 0;
      background: white;
    }

    .baker-story h2 {
      font-size: 3rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 40px;
      text-align: center;
      letter-spacing: -0.02em;
    }

    .baker-story p {
      font-size: 1.125rem;
      color: #374151;
      margin-bottom: 28px;
      line-height: 1.8;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    }

    /* Baker Products */
    .baker-products {
      padding: 100px 0;
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .baker-products h2 {
      font-size: 3rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 60px;
      text-align: center;
      letter-spacing: -0.02em;
    }

    /* Baker Reviews */
    .baker-reviews {
      padding: 100px 0;
      background: white;
    }

    .baker-reviews h2 {
      font-size: 3rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 60px;
      text-align: center;
      letter-spacing: -0.02em;
    }

    .reviews-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 40px;
    }

    @media (min-width: 768px) {
      .reviews-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    .review-card {
      background: white;
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
    }

    .review-card:hover {
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
      transform: translateY(-4px);
    }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .reviewer-name {
      font-weight: 600;
      color: #1f2a38;
      font-size: 1.125rem;
    }

    .review-rating .star {
      font-size: 1rem;
    }

    .review-card p {
      color: #6b7280;
      line-height: 1.7;
      font-style: italic;
    }

    /* Contact Baker */
    .contact-baker {
      padding: 100px 0;
      background: linear-gradient(135deg, #fef3c7, #fed7aa);
    }

    .contact-card {
      background: white;
      border-radius: 24px;
      padding: 60px;
      text-align: center;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      max-width: 700px;
      margin: 0 auto;
    }

    .contact-card h3 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #1f2a38;
      margin-bottom: 20px;
      letter-spacing: -0.02em;
    }

    .contact-card p {
      color: #6b7280;
      margin-bottom: 32px;
      font-size: 1.125rem;
      line-height: 1.7;
    }

    .contact-info {
      background: linear-gradient(135deg, #f9fafb, #f3f4f6);
      border-radius: 16px;
      padding: 32px;
      margin: 32px 0;
      text-align: left;
    }

    .contact-info p {
      margin-bottom: 12px;
      color: #374151;
      font-weight: 500;
    }

    .contact-info p:last-child {
      margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .section-header h2 {
        font-size: 2rem;
      }

      .section-header p {
        font-size: 1rem;
      }

      .profile-info h1 {
        font-size: 2rem;
      }

      .baker-story h2,
      .baker-products h2,
      .baker-reviews h2 {
        font-size: 2rem;
      }

      .profile-image img {
        width: 220px;
        height: 220px;
      }
    }
  </style>
</head>

<!-- Sticky Navigation Bar -->
<?php include 'custnavbar.php'; ?>

<body>
  <!-- All Bakers Section -->
  <section class="all-bakers" id="bakers">
    <div class="container">
      <div class="section-header">
        <h2>Find Your Perfect Baker</h2>
        <p>Looking for the right baker but don't know where to start? Discover homebakers that get you like no one else.</p>
      </div>

      <!-- Baker Search -->
      <div class="filter-section">
        <div class="search-box">
          <input type="search" placeholder="Search bakers by name or specialty..." class="baker-search-input">
        </div>
      </div>

      <div class="bakers-grid">
        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1675285458906-26993548039c?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Sarah Johnson">
            <div class="ranking-badge">#1</div>
          </div>
          <div class="baker-content">
            <h3>Sarah Johnson</h3>
            <p class="baker-specialty">Specialty: Artisan Breads & Sourdoughs</p>
            <div class="baker-stats">
              <span class="stat">5+ Years exp.</span>
              <span class="stat">200+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
              alt="Mike Chen">
            <div class="ranking-badge">#2</div>
          </div>
          <div class="baker-content">
            <h3>Mike Chen</h3>

            <p class="baker-specialty">Specialty: Custom Cakes & Pastries</p>
            <div class="baker-stats">
              <span class="stat">3+ Years exp.</span>
              <span class="stat">150+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.unsplash.com/photo-1611432579402-7037e3e2c1e4?q=80&w=1965&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
              alt="Emma Williams">
            <div class="ranking-badge">#3</div>
          </div>
          <div class="baker-content">
            <h3>Emma Williams</h3>
            <p class="baker-specialty">Specialty: Gluten-Free Treats</p>
            <div class="baker-stats">
              <span class="stat">4+ Years exp.</span>
              <span class="stat">120+ Orders</span>
            </div>
          </div>
        </div>

        <div class="baker-card" onclick="window.location.href='bakerinfopage.php'">
          <div class="baker-image">
            <img
              src="https://images.pexels.com/photos/7966423/pexels-photo-7966423.jpeg?_gl=1*jma4f6*_ga*MTY3NDQ3MzE4NC4xNzM5NTAyMzg1*_ga_8JE65Q40S6*czE3NTExMDg2OTEkbzgkZzEkdDE3NTExMDg5MDckajEyJGwwJGgw"
              alt="Emma Williams">
            <div class="ranking-badge">#4</div>
          </div>
          <div class="baker-content">
            <h3>Clara Mei</h3>
            <p class="baker-specialty">Specialty: French Pastries</p>
            <div class="baker-stats">
              <span class="stat">3+ Years exp.</span>
              <span class="stat">100+ Orders</span>
            </div>
          </div>
        </div>
      </div>
      <div id="no-bakers-message"
        style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
        No bakers found.
      </div>
    </div>
  </section>

  <?php include 'globalfooter.php'; ?>

  <script>
    // ---Baker Search Function---
    document.querySelector('.baker-search-input').addEventListener('input', function (e) {
      const searchValue = e.target.value.toLowerCase();
      const bakers = document.querySelectorAll('.baker-card');
      const noBakers = document.getElementById('no-bakers-message');
      let visibleCount = 0;

      bakers.forEach(baker => {
        const title = baker.querySelector('.baker-content').textContent.toLowerCase();
        const specialty = baker.querySelector('.baker-specialty').textContent.toLowerCase();
        if (title.includes(searchValue) || specialty.includes(searchValue)) {
          baker.style.display = 'block';
          visibleCount++;
        } else {
          baker.style.display = 'none';
        }
      });
      if (noBakers) {
        noBakers.style.display = visibleCount === 0 ? 'block' : 'none';
      }
    });

  </script>
</body>

</html>