// Using Cropper.js for product image upload (adapted from avatar-cropper.js)

document.addEventListener('DOMContentLoaded', function () {
  const addProductBtn = document.querySelector('.add-action .btn');
  const uploadModal = document.getElementById('uploadModal');
  const modalForm = uploadModal.querySelector('.modal-form');

  // Insert image upload modal
  const imageUpload = `
    <div class="image-upload-group">
      <label class="form-label" style="display:block;margin-bottom:0.5rem;font-weight:500;color:#374151;">Upload Product Image (JPEG only)</label>
      <div class="file-upload">
        <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:2.5rem;height:2.5rem;color:#9ca3af;margin:0 auto 1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/></svg>
        <p class="upload-text" style="color:#6b7280;margin-bottom:0.5rem;">Drag and drop a JPEG image, or</p>
        <button type="button" class="browse-btn" id="productBrowseBtn" style="background:linear-gradient(135deg,#fcd34d,#f59e0b);color:white;border:none;padding:0.5rem 1rem;border-radius:0.5rem;cursor:pointer;">Browse Files</button>
        <input type="file" id="productImageInput" accept="image/jpeg" style="display:none;">
        <p class="upload-note" style="font-size:0.75rem;color:#80858f;margin-top:0.5rem;">Max size: 2MB</p>
      </div>
      <div id="productCropContainer" style="margin:10px 0;"></div>
      <div class="form-actions" style="display:flex;gap:1rem;padding-top:1rem;">
        <button type="button" id="productSaveBtn" class="btn-primary" style="display:none;flex:1;">Save Image</button>
      </div>
    </div>
  `;

  // To make upload modal appear below the description textarea
  const textareas = modalForm.querySelectorAll('textarea');
  let insertAfterElem = null;
  if (textareas.length > 0) {
    insertAfterElem = textareas[textareas.length - 1];
  }
  if (insertAfterElem && insertAfterElem.parentNode) {
    insertAfterElem.insertAdjacentHTML('afterend', imageUpload);
  } else {
    // fallback: append at end
    modalForm.insertAdjacentHTML('beforeend', imageUpload);
  }

  const input = document.getElementById('productImageInput');
  const browseBtn = document.getElementById('productBrowseBtn');
  const cropContainer = document.getElementById('productCropContainer');
  const saveBtn = document.getElementById('productSaveBtn');
  let cropper = null;
  let croppedBlob = null;

  // Browse button triggers file input
  browseBtn.addEventListener('click', function (e) {
    e.preventDefault();
    input.click();
  });

  // Drag and drop for file-upload area
  const fileUploadDiv = modalForm.querySelector('.file-upload');
  fileUploadDiv.addEventListener('dragover', function (e) {
    e.preventDefault();
    fileUploadDiv.style.borderColor = '#f59e0b';
  });
  fileUploadDiv.addEventListener('dragleave', function (e) {
    e.preventDefault();
    fileUploadDiv.style.borderColor = '#9ca3af';
  });
  fileUploadDiv.addEventListener('drop', function (e) {
    e.preventDefault();
    fileUploadDiv.style.borderColor = '#9ca3af';
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
      const img = document.getElementById('productCropImg');
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

  // Save cropped image (for add product: attach to form, not AJAX)
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
      alert('Image ready! Proceed to upload your product.');
    }, 'image/jpeg');
  });

  // Remove image
  /*removeBtn.addEventListener('click', function () {
    if (!confirm('Are you sure you want to remove the product image?')) return;
    const formData = new FormData();
    formData.append('remove_product_image', '1');
    fetch(window.location.href, {
      method: 'POST',
      body: formData
    })
      .then(r => r.text())
      .then(html => {
        document.open();
        document.write(html);
        document.close();
      });
  });*/
});