// Using Cropper.js for product image editing

// Expose a function to initialize the image edit field in the edit modal
window.initEditCropper = function (imageUrl) {
  const editModal = document.getElementById('editModal');
  const modalForm = editModal.querySelector('.modal-form');

  // Image edit modal
  const imageEdit = `
    <div class="image-edit-group">
      <label class="form-label">Edit Product Image (JPEG only)</label>
      <div class="file-edit">
        <svg class="edit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/></svg>
        <p class="edit-text">Drag and drop a JPEG image, or</p>
        <button type="button" class="browse-btn" id="productBrowseBtn" style="background:linear-gradient(135deg,#fcd34d,#f59e0b);color:white;border:none;padding:0.5rem 1rem;border-radius:0.5rem;cursor:pointer;">Browse Files</button>
        <input type="file" id="productImageInput" accept="image/jpeg" style="display:none;">
        <p class="edit-note">Max size: 2MB</p>
      </div>
      <div id="productCropContainer" style="margin:10px 0;"></div>
      <div class="form-actions">
        <button type="button" id="productSaveBtn" class="btn-primary image" style="display: none; flex: 1;">Save Image</button>
        <button type="button" id="productRemoveBtn" class="btn-secondary image" style="flex: 1;">Remove Image</button>
      </div>
    </div>
  `;

  // To make edit modal appear below the description textarea
  const textareas = modalForm.querySelectorAll('textarea');
  let insertAfterElem = null;
  if (textareas.length > 0) {
    insertAfterElem = textareas[textareas.length - 1];
  }
  if (insertAfterElem && insertAfterElem.parentNode) {
    insertAfterElem.insertAdjacentHTML('afterend', imageEdit);
  } else {
    // fallback: append at end
    modalForm.insertAdjacentHTML('beforeend', imageEdit);
  }

  const input = modalForm.querySelector('#productImageInput');
  const browseBtn = modalForm.querySelector('#productBrowseBtn');
  const cropContainer = modalForm.querySelector('#productCropContainer');
  const saveBtn = modalForm.querySelector('#productSaveBtn');
  const removeBtn = modalForm.querySelector('#productRemoveBtn');
  let cropper = null;
  let croppedBlob = null;

  // Show existing image in cropper if available
  if (imageUrl) {
    cropContainer.innerHTML = `<img id='productCropImg' src='${imageUrl}' style='max-width:100%;max-height:300px;'>`;
    const img = cropContainer.querySelector('#productCropImg');
    if (cropper) cropper.destroy();
    cropper = new Cropper(img, {
      aspectRatio: 1,
      viewMode: 1,
      minContainerWidth: 200,
      minContainerHeight: 200,
      autoCropArea: 1,
    });
    saveBtn.style.display = 'inline-block';
  }

  // Browse button triggers file input
  browseBtn.addEventListener('click', function (e) {
    e.preventDefault();
    input.click();
  });

  // Drag and drop for file-edit area
  const fileeditDiv = modalForm.querySelector('.file-edit');
  fileeditDiv.addEventListener('dragover', function (e) {
    e.preventDefault();
    fileeditDiv.classList.add('dragover');
  });
  fileeditDiv.addEventListener('dragleave', function (e) {
    e.preventDefault();
    fileeditDiv.classList.remove('dragover');
  });
  fileeditDiv.addEventListener('drop', function (e) {
    e.preventDefault();
    fileeditDiv.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file && file.type === 'image/jpeg') {
      input.files = e.dataTransfer.files;
      const event = new Event('change');
      input.dispatchEvent(event);
    } else {
      alert('Only JPEG images are allowed.');
    }
  });

  // File input change
  input.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.type !== 'image/jpeg') {
      alert('Only JPEG images are allowed.');
      return;
    }
    const reader = new FileReader();
    reader.onload = function (evt) {
      cropContainer.innerHTML = `<img id='productCropImg' src='${evt.target.result}' style='max-width:100%;max-height:300px;'>`;
      const img = cropContainer.querySelector('#productCropImg');
      if (cropper) cropper.destroy();
      cropper = new Cropper(img, {
        aspectRatio: 1,
        viewMode: 1,
        minContainerWidth: 200,
        minContainerHeight: 200,
        autoCropArea: 1,
      });
      saveBtn.style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
  });

  // Save cropped image (for edit product: attach to form, not AJAX)
  saveBtn.addEventListener('click', function () {
    if (!cropper) return;
    cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(function (blob) {
      croppedBlob = blob;
      // Remove any previous hidden input
      let existingInput = modalForm.querySelector('input[name="product_image_cropped"]');
      if (existingInput) existingInput.remove();
      // Create a hidden file input and append to form
      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.name = 'product_image_cropped';
      fileInput.style.display = 'none';
      // Use DataTransfer to set the file input's files
      const dt = new DataTransfer();
      const croppedFile = new File([blob], 'product.jpg', { type: 'image/jpeg' });
      dt.items.add(croppedFile);
      fileInput.files = dt.files;
      modalForm.appendChild(fileInput);
      // Unset remove flag if set
      const removeInput = modalForm.querySelector('#removeProductImage');
      if (removeInput) removeInput.value = '0';
      alert('Image ready! Proceed to edit your product.');
    }, 'image/jpeg');
  });

  // Remove image
  removeBtn.addEventListener('click', function () {
    if (!confirm('Are you sure you want to remove the product image?')) return;
    // Remove any file input for new image
    let existingInput = modalForm.querySelector('input[name="product_image_cropped"]');
    if (existingInput) existingInput.remove();
    // Set remove flag
    const removeInput = modalForm.querySelector('#removeProductImage');
    if (removeInput) removeInput.value = '1';
    alert('Image will be removed after you save changes.');
  });
};