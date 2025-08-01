<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Customers | BakeJourney</title>
    <link rel="stylesheet" href="bakercustomermngmt.css">
</head>

<?php include 'bakernavbar.php'; ?>

<body>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Filter Tabs -->
         <div class="add-product-section">
            <div class="add-container">
                <div class="add-action">
                    <button onclick="toggleUploadModal()" class="btn">
                        + Add product
                    </button> Got something new cooking? Add it here!
                </div>
            </div>
        </div>
        <div class="filter-section">
            <div class="search-box">
                <input type="search" placeholder="Search or filter products..." class="search-input">
            </div>
            <div class="filter-tabs">
                <button onclick="filterProducts('all')" class="filter-btn active">All Products</button>
                <button onclick="filterProducts('breads')" class="filter-btn">Breads</button>
                <button onclick="filterProducts('cakes')" class="filter-btn">Cakes</button>
                <button onclick="filterProducts('brownies')" class="filter-btn">Brownies</button>
                <button onclick="filterProducts('pastries')" class="filter-btn">Pastries</button>
                <button onclick="filterProducts('cookies')" class="filter-btn">Cookies</button>
                <button onclick="filterProducts('crackers')" class="filter-btn">Crackers</button>
                <button onclick="filterProducts('candy')" class="filter-btn">Candy</button>
                <button onclick="filterProducts('pudding')" class="filter-btn">Pudding</button>
                <button onclick="filterProducts('piestarts')" class="filter-btn">Pies & Tarts</button>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            <!-- Sample Product 1 -->
            <div class="product-card" data-category="breads">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1549931319-a545dcf3bc73?w=400&h=300&fit=crop" alt="Artisan Sourdough" class="product-image">
                    <div class="product-actions">
                        <button onclick="editProduct(1)" class="action-btn edit-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteProduct(1)" class="action-btn delete-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-header">
                        <h3 class="product-title">Artisan Sourdough</h3>
                        <span class="product-price">$12.00</span>
                    </div>
                    <p class="product-description">48-hour fermented sourdough with perfect crust and soft interior</p>
                    
                    <div class="engagement-actions">
                        <div class="social-actions">
                            <button onclick="toggleLike(this, 1)" class="social-btn like-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="like-count">24</span>
                            </button>
                            <button onclick="toggleComments(1)" class="social-btn comment-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>5</span>
                            </button>
                        </div>
                        <button onclick="toggleSave(this, 1)" class="social-btn save-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>

                    <div id="comments-1" class="comments-section hidden">
                        <div class="comments-list">
                            <div class="comment">
                                <span class="comment-author">Emily R.</span>
                                <span class="comment-text">Amazing bread! Perfect crust every time.</span>
                            </div>
                            <div class="comment">
                                <span class="comment-author">Mike T.</span>
                                <span class="comment-text">Best sourdough in town!</span>
                            </div>
                        </div>
                        <div class="comment-form">
                            <input type="text" placeholder="Add a comment..." class="comment-input">
                            <button class="comment-submit">Post</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sample Product 2 -->
            <div class="product-card" data-category="cakes">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400&h=300&fit=crop" alt="Chocolate Birthday Cake" class="product-image">
                    <div class="product-actions">
                        <button onclick="editProduct(2)" class="action-btn edit-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteProduct(2)" class="action-btn delete-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-header">
                        <h3 class="product-title">Chocolate Birthday Cake</h3>
                        <span class="product-price">$45.00</span>
                    </div>
                    <p class="product-description">Rich chocolate cake with buttercream frosting and custom decorations</p>
                    
                    <div class="engagement-actions">
                        <div class="social-actions">
                            <button onclick="toggleLike(this, 2)" class="social-btn like-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="like-count">38</span>
                            </button>
                            <button onclick="toggleComments(2)" class="social-btn comment-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>12</span>
                            </button>
                        </div>
                        <button onclick="toggleSave(this, 2)" class="social-btn save-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>

                    <div id="comments-2" class="comments-section hidden">
                        <div class="comments-list">
                            <div class="comment">
                                <span class="comment-author">Sarah L.</span>
                                <span class="comment-text">Perfect for my daughter's birthday!</span>
                            </div>
                        </div>
                        <div class="comment-form">
                            <input type="text" placeholder="Add a comment..." class="comment-input">
                            <button class="comment-submit">Post</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sample Product 3 -->
            <div class="product-card" data-category="pastries">
                <div class="product-image-container">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?w=400&h=300&fit=crop" alt="Croissants" class="product-image">
                    <div class="product-actions">
                        <button onclick="editProduct(3)" class="action-btn edit-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteProduct(3)" class="action-btn delete-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="product-info">
                    <div class="product-header">
                        <h3 class="product-title">Butter Croissants</h3>
                        <span class="product-price">$3.50</span>
                    </div>
                    <p class="product-description">Flaky, buttery croissants made with French pastry techniques</p>
                    
                    <div class="engagement-actions">
                        <div class="social-actions">
                            <button onclick="toggleLike(this, 3)" class="social-btn like-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="like-count">18</span>
                            </button>
                            <button onclick="toggleComments(3)" class="social-btn comment-btn">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>7</span>
                            </button>
                        </div>
                        <button onclick="toggleSave(this, 3)" class="social-btn save-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>

                    <div id="comments-3" class="comments-section hidden">
                        <div class="comments-list">
                            <div class="comment">
                                <span class="comment-author">David K.</span>
                                <span class="comment-text">Best croissants outside of Paris!</span>
                            </div>
                        </div>
                        <div class="comment-form">
                            <input type="text" placeholder="Add a comment..." class="comment-input">
                            <button class="comment-submit">Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="no-results-message" style="display:none; text-align:center; color:#f59e0b; font-weight:600; margin:32px 0;">
            No products found.
        </div>
    </main>

    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add New Product</h2>
                <button onclick="toggleUploadModal()" class="close-btn">&times;</button>
            </div>
            
            <form class="modal-form">
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" class="form-input" placeholder="Enter product name">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option value="">Select category</option>
                        <option value="breads">Breads</option>
                        <option value="cakes">Cakes</option>
                        <option value="pastries">Pastries</option>
                        <option value="cookies">Cookies</option>
                        <option value="brownies">Brownies</option>
                        <option value="crackers">Crackers</option>
                        <option value="candy">Candy</option>
                        <option value="pudding">Pudding</option>
                        <option value="piestarts">Pies & Tarts</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <div class="price-input-container">
                        <span class="price-symbol">$</span>
                        <input type="number" step="0.01" class="form-input price-input" placeholder="0.00">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" placeholder="Describe your product..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Media Upload</label>
                    <div class="file-upload">
                        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="upload-text">Drag and drop images or videos, or</p>
                        <button type="button" class="browse-btn">Browse Files</button>
                        <p class="upload-note">Supports: JPG, PNG, MP4, MOV (Max 10MB)</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="toggleUploadModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Product</h2>
                <button onclick="closeEditModal()" class="close-btn">&times;</button>
            </div>
            
            <form class="modal-form">
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" id="editProductName" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select id="editProductCategory" class="form-select">
                        <option value="breads">Breads</option>
                        <option value="cakes">Cakes</option>
                        <option value="pastries">Pastries</option>
                        <option value="cookies">Cookies</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Price</label>
                    <div class="price-input-container">
                        <span class="price-symbol">$</span>
                        <input type="number" step="0.01" id="editProductPrice" class="form-input price-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="editProductDescription" class="form-textarea"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" onclick="closeEditModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <?php include 'globalfooter.php'; ?>

    <script>
        // Modal Functions
        function toggleUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.toggle('active');
        }

        function editProduct(productId) {
            const modal = document.getElementById('editModal');
            modal.classList.add('active');
            
            // Pre-fill form with product data
            const productData = {
                1: { name: 'Artisan Sourdough', category: 'breads', price: '12.00', description: '48-hour fermented sourdough with perfect crust and soft interior' },
                2: { name: 'Chocolate Birthday Cake', category: 'cakes', price: '45.00', description: 'Rich chocolate cake with buttercream frosting and custom decorations' },
                3: { name: 'Butter Croissants', category: 'pastries', price: '3.50', description: 'Flaky, buttery croissants made with French pastry techniques' }
            };
            
            const data = productData[productId];
            if (data) {
                document.getElementById('editProductName').value = data.name;
                document.getElementById('editProductCategory').value = data.category;
                document.getElementById('editProductPrice').value = data.price;
                document.getElementById('editProductDescription').value = data.description;
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('active');
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                // Find and remove the product card
                const productCards = document.querySelectorAll('.product-card');
                productCards.forEach(card => {
                    const img = card.querySelector('img');
                    if ((productId === 1 && img.alt.includes('Sourdough')) ||
                        (productId === 2 && img.alt.includes('Chocolate')) ||
                        (productId === 3 && img.alt.includes('Croissants'))) {
                        card.classList.add('fade-out');
                        setTimeout(() => {
                            card.remove();
                        }, 300);
                    }
                });
                alert('Product deleted successfully!');
            }
        }

        // Filter Functions
        function filterProducts(category) {
            const products = document.querySelectorAll('.product-card');
            const buttons = document.querySelectorAll('.filter-btn');
            const noResults = document.getElementById('no-results-message');
            let visibleCount = 0;
            
            // Update active button
            buttons.forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Filter products
            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = 'block';
                    product.classList.add('fade-in');
                } else {
                    product.style.display = 'none';
                }
            });
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        }

        // Search Functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchValue = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            const noResults = document.getElementById('no-results-message');
            let visibleCount = 0;

            products.forEach(product => {
                const title = product.querySelector('.product-title').textContent.toLowerCase();
                const desc = product.querySelector('.product-description').textContent.toLowerCase();
                if (title.includes(searchValue) || desc.includes(searchValue)) {
                    product.style.display = 'block';
                    visibleCount++;
                } else {
                    product.style.display = 'none';
                }
            });
            if (noResults) {
                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });

        // Social Functions
        function toggleLike(button, productId) {
            const likeIcon = button.querySelector('svg');
            const likeCount = button.querySelector('.like-count');
            const isLiked = button.classList.contains('liked');
            
            if (isLiked) {
                button.classList.remove('liked');
                likeIcon.setAttribute('fill', 'none');
                likeCount.textContent = parseInt(likeCount.textContent) - 1;
            } else {
                button.classList.add('liked');
                likeIcon.setAttribute('fill', 'currentColor');
                likeCount.textContent = parseInt(likeCount.textContent) + 1;
                button.classList.add('heart-animation');
                setTimeout(() => button.classList.remove('heart-animation'), 300);
            }
        }

        function toggleSave(button, productId) {
            const saveIcon = button.querySelector('svg');
            const isSaved = button.classList.contains('saved');
            
            if (isSaved) {
                button.classList.remove('saved');
                saveIcon.setAttribute('fill', 'none');
            } else {
                button.classList.add('saved');
                saveIcon.setAttribute('fill', 'currentColor');
                button.classList.add('save-animation');
                setTimeout(() => button.classList.remove('save-animation'), 300);
            }
        }

        function toggleComments(productId) {
            const commentsSection = document.getElementById(`comments-${productId}`);
            commentsSection.classList.toggle('hidden');
            
            if (!commentsSection.classList.contains('hidden')) {
                commentsSection.classList.add('fade-in');
            }
        }

        // Form submission handlers
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Product saved successfully! (This is a demo)');
                toggleUploadModal();
                closeEditModal();
            });
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    </script>
</body>
</html>