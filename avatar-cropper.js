// Using Cropper.js (https://fengyuanchen.github.io/cropperjs/)

document.addEventListener('DOMContentLoaded', function () {
  const avatar = document.querySelector('.profile-avatar');
  const editBtn = document.createElement('button');
  editBtn.type = 'button';
  editBtn.className = 'avatar-edit-btn';
  editBtn.title = 'Edit profile image';
  editBtn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="color: #ffffff;" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
  avatar.appendChild(editBtn);

  // Upload modal
  const uploadProfile = `
    <div class="avatar-modal" id="avatarModal">
      <div class="avatar-modal-content">
        <div class="avatar-modal-header">
          <h2 class="avatar-modal-title">Update Profile Image</h2>
          <button type="button" id="avatarModalClose" class="close-btn">&times;</button>
        </div>
        <form class="avatar-modal-form" style="padding:1.5rem;">
          <div class="form-group" style="margin-bottom:1.5rem;">
            <label class="form-label" style="display:block;margin-bottom:0.5rem;font-weight:500;color:#374151;">Upload Image (JPEG only)</label>
            <div class="file-upload">
              <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:2.5rem;height:2.5rem;color:#9ca3af;margin:0 auto 1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/></svg>
              <p class="upload-text" style="color:#6b7280;margin-bottom:0.5rem;">Drag and drop a JPEG image, or</p>
              <button type="button" class="browse-btn" id="avatarBrowseBtn" style="background:linear-gradient(135deg,#fcd34d,#f59e0b);color:white;border:none;padding:0.5rem 1rem;border-radius:0.5rem;cursor:pointer;">Browse Files</button>
              <input type="file" id="avatarInput" accept="image/jpeg" style="display:none;">
              <p class="upload-note" style="font-size:0.75rem;color:#80858f;margin-top:0.5rem;">Max size: 2MB</p>
            </div>
          </div>
          <div id="avatarCropContainer" style="margin:10px 0;"></div>
          <div class="form-actions" style="display:flex;gap:1rem;padding-top:1rem;">
            <button type="button" id="avatarSaveBtn" class="btn-primary" style="display:none;flex:1;">Save</button>
            <button type="button" id="avatarRemoveBtn" class="btn-secondary danger">Remove Image</button>
          </div>
        </form>
      </div>
    </div>
  `;
  document.body.insertAdjacentHTML('beforeend', uploadProfile);

  const modal = document.getElementById('avatarModal');
  const closeBtn = document.getElementById('avatarModalClose');
  const input = document.getElementById('avatarInput');
  const browseBtn = document.getElementById('avatarBrowseBtn');
  const cropContainer = document.getElementById('avatarCropContainer');
  const saveBtn = document.getElementById('avatarSaveBtn');
  const removeBtn = document.getElementById('avatarRemoveBtn');
  let cropper = null;

  // Show modal
  editBtn.addEventListener('click', function () {
    modal.style.display = 'flex';
    // Show remove button if avatar image exists
    if (avatar.querySelector('img')) {
      removeBtn.style.display = 'inline-block';
    } else {
      removeBtn.style.display = 'none';
    }
    saveBtn.style.display = 'none';
    cropContainer.innerHTML = '';
    input.value = '';
  });

  // Browse button triggers file input
  browseBtn.addEventListener('click', function (e) {
    e.preventDefault();
    input.click();
  });

  // Drag and drop for file-upload area
  const fileUploadDiv = modal.querySelector('.file-upload');
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

  // Close modal
  closeBtn.onclick = function () {
    modal.style.display = 'none';
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  };
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = 'none';
      if (cropper) {
        cropper.destroy();
        cropper = null;
      }
    }
  };

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
      cropContainer.innerHTML = `<img id='avatarCropImg' src='${evt.target.result}' style='max-width:100%;max-height:300px;'>`;
      const img = document.getElementById('avatarCropImg');
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

  // Save cropped image
  saveBtn.addEventListener('click', function () {
    if (!cropper) return;
    cropper.getCroppedCanvas({ width: 300, height: 300 }).toBlob(function (blob) {
      const formData = new FormData();
      formData.append('profile_image', blob, 'profile.jpg');
      formData.append('upload_profile_image', '1');
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
    }, 'image/jpeg');
  });

  // Remove image
  removeBtn.addEventListener('click', function () {
    if (!confirm('Are you sure you want to remove your profile image?')) return;
    const formData = new FormData();
    formData.append('remove_profile_image', '1');
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
  });
});
