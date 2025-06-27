<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Place Your Order - BakeJourney</title>
    <meta name="description" content="Order fresh baked goods" />
    <link rel="stylesheet" href="cart.css" />
  </head>

  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="header-content">
          <div class="brand">
            <div class="brand-logo">
              <img src="media/Logo.png" alt="BakeJourney Logo" width="40" height="40">
            </div>
            <div>
              <h1 class="brand-title">BakeJourney</h1>
              <p class="brand-subtitle">Place Your Order</p>
            </div>
          </div>
          <a href="customerdashboard.php" class="back-btn">
            ← Back to Home
          </a>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main">
      <div class="container">
        <div class="order-grid">
          <!-- Product Selection -->
          <div class="content-card">
            <div class="card-header">
              <h2>Your Cart</h2>
              <p class="card-description">Select your items </p>
            </div>
            <div class="card-content">
              <!-- Category Tabs -->
              <div class="category-tabs">
                <div class="category-tab active">All Items</div>
                <div class="category-tab">Breads</div>
                <div class="category-tab">Cakes</div>
                <div class="category-tab">Cookies</div>
                <div class="category-tab">Pastries</div>
                <div class="category-tab">Specialty</div>
              </div>

              <!-- Products Grid -->
              <div class="products-grid">
                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🍞</div>
                  <div class="product-info">
                    <div class="product-name">Sourdough Bread</div>
                    <div class="product-description">Fresh daily sourdough with crispy crust</div>
                    <div class="product-price">$8.50</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="2" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🍰</div>
                  <div class="product-info">
                    <div class="product-name">Chocolate Cake</div>
                    <div class="product-description">Rich chocolate cake with buttercream frosting</div>
                    <div class="product-price">$45.00</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="0" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🍪</div>
                  <div class="product-info">
                    <div class="product-name">Chocolate Chip Cookies</div>
                    <div class="product-description">Classic cookies with premium chocolate chips</div>
                    <div class="product-price">$12.00</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="1" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🥐</div>
                  <div class="product-info">
                    <div class="product-name">Butter Croissants</div>
                    <div class="product-description">Flaky, buttery croissants (pack of 6)</div>
                    <div class="product-price">$18.00</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="0" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🧁</div>
                  <div class="product-info">
                    <div class="product-name">Cupcakes</div>
                    <div class="product-description">Assorted flavored cupcakes (pack of 12)</div>
                    <div class="product-price">$24.00</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="0" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>

                <div class="product-card" onclick="toggleSelect(this)">
                  <div class="product-image">🥧</div>
                  <div class="product-info">
                    <div class="product-name">Apple Pie</div>
                    <div class="product-description">Traditional apple pie with flaky crust</div>
                    <div class="product-price">$35.00</div>
                    <div class="quantity-controls">
                      <button class="quantity-btn">-</button>
                      <input type="number" class="quantity-input" value="0" min="0">
                      <button class="quantity-btn">+</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Special Instructions -->
              <div class="special-section">
                <h4>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                  </svg>
                  Special Instructions
                </h4>
                <textarea 
                  class="form-textarea" 
                  placeholder="Any special requests, dietary restrictions, or custom decorations..."
                  style="width: 100%; border: 1px solid #f59e0b;"
                ></textarea>
              </div>

              <!-- Customer Information -->
              <div class="form-section">
                <h3>Customer Information</h3>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">First Name *</label>
                    <input type="text" class="form-input" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Last Name *</label>
                    <input type="text" class="form-input" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" class="form-input" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Phone *</label>
                    <input type="tel" class="form-input" required>
                  </div>
                </div>
              </div>

              <!-- Pickup Information -->
              <div class="form-section">
                <h3>Pickup Details</h3>
                <div class="form-grid">
                  <div class="form-group">
                    <label class="form-label">Pickup Date *</label>
                    <input type="date" class="form-input" required>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Pickup Time *</label>
                    <select class="form-select" required>
                      <option value="">Select time</option>
                      <option value="08:00">8:00 AM</option>
                      <option value="08:30">8:30 AM</option>
                      <option value="09:00">9:00 AM</option>
                      <option value="09:30">9:30 AM</option>
                      <option value="10:00">10:00 AM</option>
                      <option value="10:30">10:30 AM</option>
                      <option value="11:00">11:00 AM</option>
                      <option value="11:30">11:30 AM</option>
                      <option value="12:00">12:00 PM</option>
                      <option value="12:30">12:30 PM</option>
                      <option value="13:00">1:00 PM</option>
                      <option value="13:30">1:30 PM</option>
                      <option value="14:00">2:00 PM</option>
                      <option value="14:30">2:30 PM</option>
                      <option value="15:00">3:00 PM</option>
                      <option value="15:30">3:30 PM</option>
                      <option value="16:00">4:00 PM</option>
                      <option value="16:30">4:30 PM</option>
                      <option value="17:00">5:00 PM</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Order Summary -->
          <div class="content-card order-summary">
            <div class="card-header">
              <h2>Order Summary</h2>
              <p class="card-description">Review your items</p>
            </div>
            <div class="card-content">
              <div class="summary-item">
                <span class="summary-name">Sourdough Bread × 2</span>
                <span class="summary-price">$17.00</span>
              </div>
              <div class="summary-item">
                <span class="summary-name">Chocolate Chip Cookies × 1</span>
                <span class="summary-price">$12.00</span>
              </div>
              <div class="summary-item">
                <span class="summary-name">Subtotal</span>
                <span class="summary-price">$29.00</span>
              </div>
              <div class="summary-item">
                <span class="summary-name">Tax (8.5%)</span>
                <span class="summary-price">$2.47</span>
              </div>
              <div class="summary-item">
                <span class="summary-name">Total</span>
                <span class="summary-price">$31.47</span>
              </div>
              
              <button class="btn-primary" style="margin-top: 24px;">
                <img src="media/cart2.png" alt="Cart" width="25" height="25" style="vertical-align:middle;"> Place Order
              </button>
              
              <p style="font-size: 0.75rem; color: #6b7280; text-align: center; margin-top: 16px;">
                You will receive a confirmation email with pickup details
              </p>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script>
      function toggleSelect(card) {
        card.classList.toggle('selected');
      }
    </script>
  </body>
</html>
