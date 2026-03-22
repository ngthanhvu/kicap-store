document.addEventListener('DOMContentLoaded', function () {
    let maxQuantity = window.maxQuantity || 1;
    let selectedPrice = window.selectedPrice || 0;
    const mainImage = document.getElementById('mainImage');
    const mainImageFrame = document.getElementById('mainImageFrame');
    const lightbox = document.getElementById('imageLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxClose = document.getElementById('lightboxClose');

    window.changeImage = function (img) {
        if (mainImage) {
            mainImage.src = img.src;
        }
        document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
        img.classList.add('active');
    }

    function openLightbox() {
        if (!mainImage || !lightbox || !lightboxImage) return;

        lightboxImage.src = mainImage.src;
        lightbox.classList.add('show');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox || !lightboxImage) return;

        lightbox.classList.remove('show');
        lightbox.setAttribute('aria-hidden', 'true');
        lightboxImage.src = '';
        document.body.style.overflow = '';
    }

    if (mainImageFrame) {
        mainImageFrame.addEventListener('click', openLightbox);
    }

    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }

    if (lightbox) {
        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && lightbox?.classList.contains('show')) {
            closeLightbox();
        }
    });

    window.selectVariant = function (name, price, quantity, variantId) {
        selectedPrice = price;
        document.getElementById('productPrice').textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);

        maxQuantity = quantity;
        document.getElementById('stockQuantity').textContent = quantity;
        document.getElementById('stockStatus').textContent = quantity > 0 ? 'Còn hàng' : 'Hết hàng';
        document.getElementById('stockStatus').className = quantity > 0 ? 'text-success' : 'text-danger';

        document.getElementById('variantIdInput').value = variantId;
        document.getElementById('priceInput').value = price;

        document.getElementById('quantity').value = 1;
        document.getElementById('quantityInput').value = 1;

        document.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    window.changeQuantity = function (amount) {
        let qtyInput = document.getElementById('quantity');
        let qtyCart = document.getElementById('quantityInput');
        let qty = parseInt(qtyInput.value) + amount;

        if (isNaN(qty) || qty < 1) qty = 1;
        if (qty > maxQuantity) qty = maxQuantity;

        qtyInput.value = qty;
        qtyCart.value = qty;
    }

    window.updateQuantity = function () {
        let qtyInput = document.getElementById('quantity');
        let qtyCart = document.getElementById('quantityInput');
        let qty = parseInt(qtyInput.value);

        if (isNaN(qty) || qty < 1) qty = 1;
        else if (qty > maxQuantity) qty = maxQuantity;

        qtyInput.value = qty;
        qtyCart.value = qty;
    }

    window.setRating = function (rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('.star-rating .fa-star');
        stars.forEach(star => {
            star.classList.remove('checked');
            if (star.getAttribute('data-rating') <= rating) {
                star.classList.add('checked');
            }
        });
    }

    window.toggleLike = async function (button, ratingId) {
        try {
            const response = await fetch(`/ratings/${ratingId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                }
            });

            const data = await response.json();
            if (data.success) {
                button.querySelector('.like-count').textContent = data.likes_count;
            } else {
                alert(data.message || 'Đã xảy ra lỗi!');
            }
        } catch (err) {
            alert('Lỗi khi gọi API');
        }
    }

    window.deleteRating = async function (ratingId) {
        if (!confirm('Bạn có chắc muốn xóa bình luận này không?')) return;

        try {
            const response = await fetch(`/ratings/${ratingId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                }
            });

            const data = await response.json();
            if (data.success) {
                document.getElementById(`rating-${ratingId}`).remove();
                alert(data.message);
            } else {
                alert(data.message || 'Đã xảy ra lỗi!');
            }
        } catch (err) {
            alert('Lỗi khi xóa bình luận');
        }
    }

    // Lọc đánh giá theo số sao
    const filterButtons = document.querySelectorAll('#rating-filters button');
    const ratingItems = document.querySelectorAll('#ratings-list .rating-item');

    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const star = this.getAttribute('data-star');
            ratingItems.forEach(item => {
                const itemStar = item.getAttribute('data-star');
                item.style.display = (star === 'all' || itemStar === star) ? 'block' : 'none';
            });
        });
    });
});
