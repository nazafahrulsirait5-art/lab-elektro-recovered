/**
 * E-Lab Elektro Core Frontend Logic
 * Handles AJAX interactions and UI transitions
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('E-Lab Elektro Frontend Loaded');

    // 1. Tooltips Initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // 2. AI Chat Form Handler (AJAX)
    const chatForm = document.getElementById('aiChatForm');
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const messageInput = document.getElementById('aiMessage');
            const message = messageInput.value;
            const chatWrapper = document.getElementById('chatWrapper');

            if (!message) return;

            // Append User Message
            chatWrapper.innerHTML += `
                <div class="d-flex justify-content-end mb-3">
                    <div class="bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 80%;">
                        ${message}
                    </div>
                </div>
            `;
            
            messageInput.value = '';

            // Loading Indicator
            const loadingId = 'loading-' + Date.now();
            chatWrapper.innerHTML += `
                <div id="${loadingId}" class="d-flex mb-3">
                    <div class="bg-light p-3 rounded-3 shadow-sm text-muted">
                        <i class="fas fa-circle-notch fa-spin me-2"></i> Asisten sedang berpikir...
                    </div>
                </div>
            `;

            // Scroll to bottom
            chatWrapper.scrollTop = chatWrapper.scrollHeight;

            // AJAX Request to AiHandler
            fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    'message': message
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(loadingId).remove();
                
                chatWrapper.innerHTML += `
                    <div class="d-flex mb-3">
                        <div class="bg-white border p-3 rounded-3 shadow-sm" style="max-width: 80%;">
                            <div class="small fw-bold text-primary mb-1">${data.persona}</div>
                            ${data.response}
                            <div class="text-muted" style="font-size: 10px; margin-top: 5px;">${data.timestamp}</div>
                        </div>
                    </div>
                `;
                chatWrapper.scrollTop = chatWrapper.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById(loadingId).innerHTML = 'Oops, terjadi kesalahan koneksi.';
            });
        });
    }

    // 3. LabCart System
    const cartKey = 'lab_cart_items';
    let cartItems = JSON.parse(localStorage.getItem(cartKey)) || [];

    function saveCart() {
        localStorage.setItem(cartKey, JSON.stringify(cartItems));
        renderCart();
    }

    function renderCart() {
        const badge = document.getElementById('cartBadgeCount');
        if (badge) badge.textContent = cartItems.length;

        const tableBody = document.getElementById('cartTableBody');
        const hiddenInputs = document.getElementById('cartHiddenInputs');
        const btnCheckout = document.getElementById('btnCheckout');

        if (!tableBody || !hiddenInputs || !btnCheckout) return;

        tableBody.innerHTML = '';
        hiddenInputs.innerHTML = '';

        if (cartItems.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        <i class="fas fa-box-open fa-2x mb-3 text-light"></i>
                        <p class="mb-0">Keranjang masih kosong</p>
                    </td>
                </tr>
            `;
            btnCheckout.disabled = true;
            return;
        }

        btnCheckout.disabled = false;

        cartItems.forEach((item, index) => {
            // Render Table Row
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="fw-bold">${item.nama}</td>
                <td>
                    <div class="input-group input-group-sm mx-auto" style="width: 120px;">
                        <button class="btn btn-outline-secondary btn-decrease" type="button" data-index="${index}">-</button>
                        <input type="number" class="form-control text-center input-qty" value="${item.jumlah}" min="1" max="${item.stok}" data-index="${index}" readonly>
                        <button class="btn btn-outline-secondary btn-increase" type="button" data-index="${index}">+</button>
                    </div>
                </td>
                <td class="text-center"><span class="badge bg-light text-dark border">${item.stok} Tersedia</span></td>
                <td class="text-end">
                    <button class="btn btn-sm btn-light text-danger btn-remove" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(tr);

            // Render Hidden Inputs for Form
            hiddenInputs.innerHTML += `
                <input type="hidden" name="id_alat[]" value="${item.id}">
                <input type="hidden" name="jumlah_pinjam[]" value="${item.jumlah}">
            `;
        });

        // Attach Event Listeners
        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = this.getAttribute('data-index');
                if (cartItems[idx].jumlah > 1) {
                    cartItems[idx].jumlah--;
                    saveCart();
                }
            });
        });

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = this.getAttribute('data-index');
                if (cartItems[idx].jumlah < cartItems[idx].stok) {
                    cartItems[idx].jumlah++;
                    saveCart();
                } else {
                    alert('Maksimal stok tercapai!');
                }
            });
        });

        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = this.getAttribute('data-index');
                cartItems.splice(idx, 1);
                saveCart();
            });
        });
    }

    // Initialize cart render on load
    renderCart();

    // Attach Add to Cart event for any btn-add-to-cart in the page
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');
            const stok = parseInt(this.getAttribute('data-stok'));

            if (stok <= 0) {
                alert('Stok alat habis!');
                return;
            }

            // Check if item already exists in cart
            const existingItem = cartItems.find(i => i.id === id);
            if (existingItem) {
                if (existingItem.jumlah < stok) {
                    existingItem.jumlah++;
                    alert('Alat sudah ada di keranjang. Jumlah +1');
                } else {
                    alert('Stok alat maksimal sudah di keranjang!');
                }
            } else {
                cartItems.push({
                    id: id,
                    nama: nama,
                    stok: stok,
                    jumlah: 1
                });
                alert('Alat berhasil ditambahkan ke keranjang!');
            }
            saveCart();
            
            // Pop out the cart modal optionally
            // const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            // cartModal.show();
        });
    });
});
