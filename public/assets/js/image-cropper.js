/**
 * Image Cropper — Cropper.js wrapper untuk crop gambar sebelum upload.
 *
 * Markup:
 *   <div class="image-cropper-wrap">
 *     <input type="file" accept="image/*" class="image-cropper-input" data-name="gambar">
 *     <input type="hidden" name="gambar_cropped">
 *     <div class="image-cropper-preview">
 *       <img src="" alt="">
 *     </div>
 *   </div>
 *
 * data-name    : nama field hidden untuk base64
 * data-ratio   : aspect ratio (default: 4/3). Gunakan "free" untuk bebas.
 * data-default : URL gambar default (edit mode)
 */
(function (window) {
  'use strict';

  function initImageCropper(root) {
    if (typeof Cropper === 'undefined') return;

    var wraps = (root || document).querySelectorAll('.image-cropper-wrap');

    wraps.forEach(function (wrap) {
      if (wrap.dataset.cropperReady) return;
      wrap.dataset.cropperReady = '1';

      var fileInput   = wrap.querySelector('.image-cropper-input');
      var hiddenInput = wrap.querySelector('input[type="hidden"]');
      var preview     = wrap.querySelector('.image-cropper-preview');
      var previewImg  = preview ? preview.querySelector('img') : null;

      if (!fileInput || !hiddenInput || !previewImg) return;

      var ratioAttr = fileInput.dataset.ratio;
      var aspectRatio = ratioAttr === 'free' ? NaN : (parseFloat(ratioAttr) || (4 / 3));
      var defaultSrc  = fileInput.dataset.default || '';

      var cropper   = null;
      var modal     = null;
      var modalImg  = null;

      if (defaultSrc) {
        previewImg.src = defaultSrc;
        preview.style.display = 'block';
      }

      function createModal() {
        modal = document.createElement('div');
        modal.className = 'cropper-modal-overlay';
        modal.innerHTML =
          '<div class="cropper-modal-dialog">' +
            '<div class="cropper-modal-header">' +
              '<strong>Crop Gambar</strong>' +
              '<button type="button" class="cropper-modal-close" aria-label="Tutup">&times;</button>' +
            '</div>' +
            '<div class="cropper-modal-body">' +
              '<img class="cropper-modal-img" alt="">' +
            '</div>' +
            '<div class="cropper-modal-footer">' +
              '<button type="button" class="btn btn-outline btn-sm cropper-btn-cancel">Batal</button>' +
              '<button type="button" class="btn btn-primary btn-sm cropper-btn-crop">Terapkan Crop</button>' +
            '</div>' +
          '</div>';
        document.body.appendChild(modal);
        modalImg = modal.querySelector('.cropper-modal-img');

        modal.querySelector('.cropper-modal-close').addEventListener('click', closeModal);
        modal.querySelector('.cropper-btn-cancel').addEventListener('click', closeModal);
        modal.querySelector('.cropper-btn-crop').addEventListener('click', applyCrop);
      }

      function openModal(src) {
        if (!modal) createModal();
        modalImg.src = src;
        modal.classList.add('is-open');

        if (cropper) { cropper.destroy(); cropper = null; }

        cropper = new Cropper(modalImg, {
          aspectRatio: aspectRatio,
          viewMode: 1,
          autoCropArea: 0.9,
          responsive: true,
          background: false,
        });
      }

      function closeModal() {
        if (cropper) { cropper.destroy(); cropper = null; }
        if (modal) modal.classList.remove('is-open');
        fileInput.value = '';
      }

      function applyCrop() {
        if (!cropper) return;

        var canvas = cropper.getCroppedCanvas({
          maxWidth: 1200,
          maxHeight: 1200,
          imageSmoothingQuality: 'high',
        });

        var base64 = canvas.toDataURL('image/jpeg', 0.85);
        hiddenInput.value = base64;

        previewImg.src = base64;
        preview.style.display = 'block';

        closeModal();
      }

      fileInput.addEventListener('change', function () {
        var file = fileInput.files && fileInput.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
          if (typeof Swal !== 'undefined') {
            Swal.fire('Format Tidak Valid', 'Pilih file gambar (JPG, PNG, dll).', 'error');
          } else {
            alert('Pilih file gambar.');
          }
          fileInput.value = '';
          return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
          openModal(e.target.result);
        };
        reader.readAsDataURL(file);
      });

      var removeBtn = wrap.querySelector('.image-cropper-remove');
      if (removeBtn) {
        removeBtn.addEventListener('click', function () {
          hiddenInput.value = '';
          previewImg.src = '';
          preview.style.display = 'none';
          fileInput.value = '';
        });
      }
    });
  }

  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onReady(function () {
    initImageCropper();
  });

  window.ImageCropper = { init: initImageCropper };

})(window);
