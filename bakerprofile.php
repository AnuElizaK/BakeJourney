<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Baker Profile | BakeJourney</title>
    <meta name="description" content="Manage your baker profile and showcase your specialties" />
    <link rel="stylesheet" href="bakerprofile.css" />
  </head>

  <body>
    <div class="container">
      <a href="bakerhomepage.php" class="back-link">← Back to Home</a>

      <h1 class="page-title">Baker Profile</h1>
      <div class="baker-data">
        <!-- Profile Header -->
        <div class="profile-header">
          <div class="profile-avatar">
            SJ
            <div class="ranking-badge">#1 Baker</div>
          </div>
          
          <div class="profile-info">
            <h1>Sarah Johnson</h1>
            <p>sarah.baker@bakejourney.com • +91 xxxxx 34568</p>
            <div class="baker-rating">
              <div class="stars">
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
              </div>
              <span class="rating-number">5.0 (127 reviews)</span>
            </div>
            <p><strong>Specialty:</strong> Artisan Breads & Sourdoughs</p>
          </div>

          <div class="profile-stats">
            <div class="stat-card">
              <span class="stat-number">5+</span>
              <span class="stat-label">Years Experience</span>
            </div>
            <div class="stat-card">
              <span class="stat-number">200+</span>
              <span class="stat-label">Orders Completed</span>
            </div>
            <div class="stat-card">
              <span class="stat-number">98%</span>
              <span class="stat-label">Customer Satisfaction</span>
            </div>
          </div>
        </div>

        <!-- Baker Information -->
        <div class="profile-section">
          <h2 class="section-title">Baker Information</h2>
          <form>
            <div class="form-grid">
              <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" value="Sarah Johnson">
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="sarah.baker@bakejourney.com">
              </div>
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="+91 xxxxx 34568">
              </div>
              <div class="form-group">
                <label for="specialty">Baking Specialty</label>
                <select id="specialty" name="specialty">
                  <option value="breads" selected>Artisan Breads & Sourdoughs</option>
                  <option value="cakes">Custom Cakes & Pastries</option>
                  <option value="gluten-free">Gluten-Free Treats</option>
                  <option value="desserts">Desserts & Sweets</option>
                  <option value="cookies">Cookies & Biscuits</option>
                  <option value="pies">Pies & Tarts</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="form-group">
                <label for="bio">Baker Bio</label>
                <textarea id="bio" name="bio" rows="4" placeholder="Tell customers about your baking journey and specialties">Sarah started her baking journey 5 years ago in her grandmother's kitchen, learning traditional sourdough techniques passed down through generations. Her passion for creating the perfect crust and crumb has made her the most sought-after artisan bread baker in our community.</textarea>
              </div>
            </div>
            <button type="submit" class="btn">Update Profile</button>
          </form>
        </div>
      </div>

      <!-- Business Settings -->
      <div class="profile-section">
        <h2 class="section-title">Business Settings</h2>
        <form>
          <div class="form-grid">
            <div class="form-group">
              <label for="leadTime">Order Lead Time (days)</label>
              <select id="leadTime" name="leadTime">
                <option value="1">1 day</option>
                <option value="2" selected>2-3 days</option>
                <option value="4">4-5 days</option>
                <option value="7">1 week</option>
                <option value="14">2 weeks</option>
                <option value="30">1 month</option>
                <option value="-">More than 1 month</option>
              </select>
            </div>
            <div class="form-group">
              <label for="availability">Availability Status</label>
              <select id="availability" name="availability">
                <option value="available" selected>Available for orders</option>
                <option value="busy">Busy - limited availability</option>
                <option value="unavailable">Temporarily unavailable</option>
              </select>
            </div>
            <div class="form-group">
              <label for="custom">Custom orders</label>
              <select id="custom" name="custom">
                <option value="available" selected>Takes custom orders</option>
                <option value="busy">Takes limited custom orders</option>
                <option value="unavailable">Temporarily unavailable</option>
                <option value="unavailable">Does not take custom orders</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn">Save Settings</button>
        </form>
      </div>
    </div>

    <script>
      // Form submission handlers
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          alert('Profile updated successfully! (This is a demo)');
        });
      });

      // Product management
      document.querySelectorAll('.product-card .btn.small').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          if (this.textContent === 'Edit') {
            alert('Edit product functionality (This is a demo)');
          } else if (this.textContent === 'Remove') {
            if (confirm('Are you sure you want to remove this product?')) {
              this.closest('.product-card').remove();
            }
          }
        });
      });
    </script>
  </body>
</html>