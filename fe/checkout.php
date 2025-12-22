<?php
declare(strict_types=1);

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/PTUD_Final',
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start();

if (!isset($_SESSION['nguoi_dung_id'])) {
  header('Location: login.php');
  exit;
}

$shipping_fee = 30000; 
$current_page = "Hoàn tất đơn hàng";
include 'header.php'; 
?>

<style>
    .form-label { font-weight: 600; font-size: 0.9rem; }
    .required::after { content: " *"; color: #dc3545; }
    
    .product-item { 
        display: flex; 
        align-items: center; 
        padding: 10px 0; 
        border-bottom: 1px dashed #dee2e6; 
    }
    .product-item:last-child { border-bottom: none; }
    .product-name { font-weight: 600; font-size: 0.95rem; line-height: 1.2; }
    .product-meta { font-size: 0.85rem; color: #6c757d; margin-top: 4px; }
    .product-price { font-weight: 600; font-size: 0.95rem; }

    .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
    .summary-total { 
        border-top: 2px solid #000; 
        padding-top: 15px; 
        margin-top: 15px; 
        font-weight: 700; 
        font-size: 1.2rem; 
        display: flex; 
        justify-content: space-between; 
    }

    .location-badge {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #000;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
    }

    /* --- Tối ưu Modal Sổ địa chỉ chuẩn xác --- */
    #modalAddressBook .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    #modalAddressBook .modal-header {
        border-bottom: 1px solid #f1f1f1;
        padding: 1.25rem 1.5rem;
    }
    #addressBookList {
        max-height: 450px;
        overflow-y: auto;
        overflow-x: hidden; /* Chặn tuyệt đối cuộn ngang */
        padding: 10px 0;
        width: 100%;
    }
    .btn-select-address {
        border: 1px solid #eee !important;
        margin: 8px 15px; /* Giữ khoảng cách với mép Popup */
        border-radius: 12px !important;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #fff;
        display: block;
        padding: 15px;
        text-decoration: none;
        color: inherit;
        /* Quan trọng: Ép chiều dài khung vừa khít với Popup */
        max-width: calc(100% - 30px); 
        box-sizing: border-box;
    }
    .btn-select-address:hover {
        border-color: #000 !important;
        background-color: #f9f9f9 !important;
        transform: translateY(-2px);
    }
    .address-icon {
        min-width: 32px;
        height: 32px;
        background: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #666;
    }
    .address-detail-text {
        word-break: break-word; /* Tự động xuống hàng khi text dài */
        white-space: normal;
        line-height: 1.5;
        display: block;
        margin-top: 4px;
        font-size: 0.85rem;
        color: #333;
    }
</style>

<main class="container py-5">
    <div class="row">
        <div class="col-12 mb-4 text-center">
            <h2 class="fw-bold text-uppercase">Hoàn tất đơn hàng</h2>
        </div>
    </div>

    <div id="alertSuccess" class="alert alert-success shadow-sm" style="display:none;"></div>
    <div id="alertError" class="alert alert-danger shadow-sm" style="display:none;"></div>

    <form id="checkoutForm">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0"><i class="fas fa-map-marker-alt me-2"></i>Thông tin vận chuyển</h5>
                            <button type="button" class="btn btn-sm btn-dark px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalAddressBook">
                                <i class="fas fa-address-book me-1"></i> Chọn từ Sổ địa chỉ
                            </button>
                        </div>
                        
                        <div class="location-badge" id="location-badge">
                            <i class="fas fa-info-circle me-1"></i> Vui lòng chọn khu vực giao hàng bên dưới
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required">Họ và tên</label>
                                <input type="text" class="form-control" name="fullname" placeholder="Nhập họ tên" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Số điện thoại</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Nhập số điện thoại" required>
                            </div>
                            
                            <input type="hidden" name="province_text" id="province_text">
                            <input type="hidden" name="district_text" id="district_text">
                            <input type="hidden" name="ward_text" id="ward_text">

                            <div class="col-md-4">
                                <label class="form-label required">Tỉnh / Thành</label>
                                <select class="form-select" id="province" required>
                                    <option value="">Chọn Tỉnh/Thành</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Quận / Huyện</label>
                                <select class="form-select" id="district" required disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Phường / Xã</label>
                                <select class="form-select" id="ward" required disabled>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label required">Địa chỉ cụ thể</label>
                                <input type="text" class="form-control" name="address" placeholder="Số nhà, tên đường..." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú đơn hàng</label>
                                <textarea class="form-control" name="note" rows="2" placeholder="Ví dụ: Giao giờ hành chính..."></textarea>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5 class="fw-bold mb-3">Phương thức thanh toán</h5>
                        <div class="form-check p-3 border rounded mb-2 bg-light">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="cod" checked>
                            <label class="form-check-label fw-bold" for="cod">
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Đơn hàng của bạn (<span id="orderCount">0</span>)</h5>
                        <div id="orderItems" class="mb-4" style="max-height: 400px; overflow-y: auto;">
                            <p class="text-center text-muted my-3">Đang tải giỏ hàng...</p>
                        </div>

                        <div class="input-group mb-4">
                            <input type="text" class="form-control" id="discountInput" placeholder="Mã giảm giá">
                            <button class="btn btn-dark" type="button" id="applyDiscount">Áp dụng</button>
                        </div>

                        <div class="summary-row">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold" id="subtotalText">0₫</span>
                        </div>
                        <div class="summary-row text-success" id="discountRow" style="display: none;">
                            <span><i class="fas fa-ticket-alt me-1"></i>Giảm giá</span>
                            <span class="fw-bold" id="discountText">-0₫</span>
                        </div>
                        <div class="summary-row">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="fw-bold" id="shippingText"><?php echo number_format($shipping_fee, 0, ',', '.'); ?>₫</span>
                        </div>
                        <div class="summary-total">
                            <span>Tổng cộng</span>
                            <span class="text-danger" id="final-total">0₫</span>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 mt-4 fw-bold text-uppercase" id="btnPlaceOrder" disabled>
                            Đặt hàng ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

<div class="modal fade" id="modalAddressBook" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-address-book me-2"></i>Địa chỉ đã lưu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="addressBookList" class="list-group list-group-flush">
                    <div class="p-5 text-center text-muted small">Đang tải sổ địa chỉ...</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light w-100 rounded-pill" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const API_BASE = 'http://localhost/PTUD_Final/public';
    const DEFAULT_SHIPPING = <?php echo (int)$shipping_fee; ?>;

    let cart = null;           
    let subtotal = 0;
    let shippingFee = DEFAULT_SHIPPING;
    let discountAmount = 0;    
    let discountCodeApplied = '';
    let buyNowMode = false;
    let buyNowSkuId = null;
    let buyNowQuantity = 1;
    let buyNowProductName = '';
    let buyNowPrice = 0;

    function vnd(n) { return Number(n || 0).toLocaleString('vi-VN') + '₫'; }

    $(document).ready(function() {
        $.getJSON('https://provinces.open-api.vn/api/?depth=1', function(data) {
            $.each(data, function(k, v) {
                $('#province').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
            });
        });

        $('#province').change(function() {
            let code = $(this).val();
            let name = $(this).find('option:selected').data('name');
            $('#province_text').val(name || '');
            if(name) $('#location-badge').html(`<i class="fas fa-map-marker-alt me-2"></i>Giao tới: <strong>${name}</strong>`);
            
            $('#district').html('<option value="">Chọn Quận/Huyện</option>').prop('disabled', true);
            $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
            
            if (code) {
                $.getJSON(`https://provinces.open-api.vn/api/p/${code}?depth=2`, function(data) {
                    $.each(data.districts, function(k, v) {
                        $('#district').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
                    });
                    $('#district').prop('disabled', false);
                });
            }
        });

        $('#district').change(function() {
            let code = $(this).val();
            $('#district_text').val($(this).find('option:selected').data('name') || '');
            $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
            if (code) {
                $.getJSON(`https://provinces.open-api.vn/api/d/${code}?depth=2`, function(data) {
                    $.each(data.wards, function(k, v) {
                        $('#ward').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
                    });
                    $('#ward').prop('disabled', false);
                });
            }
        });

        $('#ward').change(function() {
            $('#ward_text').val($(this).find('option:selected').data('name') || '');
        });
    });

    // --- 2. LOGIC SỔ ĐỊA CHỈ (AUTO-FILL & NO OVERFLOW) ---
    $('#modalAddressBook').on('show.bs.modal', async function () {
        const wrap = document.getElementById('addressBookList');
        try {
            const res = await fetch(`${API_BASE}/api/dia-chi`, { credentials: 'include' });
            const list = await res.json();

            if (!list || list.length === 0) {
                wrap.innerHTML = '<div class="p-5 text-center">Bạn chưa có địa chỉ lưu sẵn.</div>';
                return;
            }

            wrap.innerHTML = list.map(a => `
                <div class="list-group-item list-group-item-action btn-select-address" 
                   data-full="${a.ten_nguoi_nhan}" data-phone="${a.so_dien_thoai}"
                   data-tinh="${a.tinh_thanh}" data-quan="${a.quan_huyen}"
                   data-xa="${a.phuong_xa}" data-detail="${a.dia_chi_cu_the}">
                    <div class="d-flex align-items-start w-100">
                        <div class="address-icon"><i class="fas fa-home mt-1" style="font-size: 0.8rem;"></i></div>
                        <div class="flex-grow-1" style="min-width: 0;"> <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-truncate">${a.ten_nguoi_nhan}</span>
                                ${a.mac_dinh ? '<span class="badge bg-dark" style="font-size: 0.65rem;">Mặc định</span>' : ''}
                            </div>
                            <div class="small text-secondary mb-1"><i class="fas fa-phone-alt me-1" style="font-size:0.75rem;"></i>${a.so_dien_thoai}</div>
                            <div class="address-detail-text">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                ${a.dia_chi_cu_the}, ${a.phuong_xa}, ${a.quan_huyen}, ${a.tinh_thanh}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            $('.btn-select-address').off('click').on('click', function() {
                const d = $(this).data();
                $('[name="fullname"]').val(d.full);
                $('[name="phone"]').val(d.phone);
                $('[name="address"]').val(d.detail);
                fillLocationByText(d.tinh, d.quan, d.xa);
                bootstrap.Modal.getInstance(document.getElementById('modalAddressBook')).hide();
            });
        } catch (e) {
            wrap.innerHTML = '<div class="p-5 text-center text-danger">Lỗi tải địa chỉ.</div>';
        }
    });

    async function fillLocationByText(t, q, x) {
        const pOpt = $(`#province option`).filter(function() { return $(this).text() === t; });
        if (pOpt.length) {
            $('#province').val(pOpt.val()).trigger('change');
            setTimeout(() => {
                const dOpt = $(`#district option`).filter(function() { return $(this).text() === q; });
                if (dOpt.length) {
                    $('#district').val(dOpt.val()).trigger('change');
                    setTimeout(() => {
                        const wOpt = $(`#ward option`).filter(function() { return $(this).text() === x; });
                        if (wOpt.length) $('#ward').val(wOpt.val()).trigger('change');
                    }, 800);
                }
            }, 800);
        }
    }

    async function loadCartForCheckout() {
        const res = await fetch(`${API_BASE}/api/gio-hang`, { credentials: 'include' });
        const data = await res.json();
        if (res.status === 401) { window.location.href = 'login.php'; return; }
        if (!data.ok) return;

        cart = data;
        subtotal = Number(data.tam_tinh || 0);
        document.getElementById('orderCount').innerText = data.items.length;
        document.getElementById('orderItems').innerHTML = data.items.map(it => `
            <div class="product-item">
                <div class="flex-grow-1">
                    <div class="product-name">${it.ten_san_pham} <span class="text-muted">x${it.so_luong}</span></div>
                    <div class="product-meta small text-secondary">
                        ${it.ma_sku ? `SKU: ${it.ma_sku} | ` : ''} ${it.ten_kich_co || ''} ${it.ten_mau || ''}
                    </div>
                </div>
                <div class="product-price">${vnd(it.thanh_tien)}</div>
            </div>
        `).join('');
        updateUI();
        document.getElementById('btnPlaceOrder').disabled = data.items.length === 0;
    }

    function updateUI() {
        document.getElementById('subtotalText').innerText = vnd(subtotal);
        if (discountAmount > 0) {
            $('#discountRow').show();
            $('#discountText').text('-' + vnd(discountAmount));
        } else {
            $('#discountRow').hide();
        }
        document.getElementById('final-total').innerText = vnd(subtotal + shippingFee - discountAmount);
    }

    document.getElementById('applyDiscount').addEventListener('click', async function() {
        const code = (document.getElementById('discountInput').value || '').trim().toUpperCase();
        if (!code) { alert('Vui lòng nhập mã'); return; }
        const btn = this;
        btn.disabled = true;
        btn.innerText = '...';
        try {
            const res = await fetch('check_voucher.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code: code, total: subtotal })
            });
            const data = await res.json();
            if (data.status) {
                discountAmount = Number(data.discount);
                discountCodeApplied = data.code;
                updateUI();
                alert('Áp dụng thành công: ' + data.message);
            } else { alert(data.message); }
        } catch (e) { alert('Lỗi kết nối voucher'); }
        finally { btn.disabled = false; btn.innerText = 'Áp dụng'; }
    });

    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnPlaceOrder');
        const originalText = btn.innerHTML;
        const street = document.querySelector('[name="address"]').value.trim();
        const ward = $('#ward_text').val();
        const district = $('#district_text').val();
        const province = $('#province_text').val();
        if (!ward || !district || !province) { alert('Vui lòng chọn đầy đủ địa chỉ'); return; }
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang đặt hàng...';
        const payload = {
            nguoi_nhan: document.querySelector('[name="fullname"]').value.trim(),
            sdt_nguoi_nhan: document.querySelector('[name="phone"]').value.trim(),
            dia_chi_giao_hang: `${street}, ${ward}, ${district}, ${province}`,
            ghi_chu: document.querySelector('[name="note"]').value.trim(),
            phi_van_chuyen: shippingFee,
            giam_gia: discountAmount,
            ma_khuyen_mai: discountCodeApplied
        };
        try {
            const res = await fetch(`${API_BASE}/api/don-hang`, {
                method: 'POST',
                credentials: 'include',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.ok) {
                $('#checkoutForm').hide();
                $('#alertSuccess').html(`
                    <h4 class="fw-bold">Đặt hàng thành công!</h4>
                    <p>Mã đơn hàng của bạn là: <strong>${data.ma_don_hang}</strong></p>
                    <a href="index.php" class="btn btn-dark mt-2">Tiếp tục mua sắm</a>
                `).show();
            } else {
                alert(data.error || 'Đặt hàng thất bại');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (err) { alert('Lỗi hệ thống'); btn.disabled = false; btn.innerHTML = originalText; }
    });

    loadCartForCheckout();
</script>

<?php include 'footer.php'; ?>