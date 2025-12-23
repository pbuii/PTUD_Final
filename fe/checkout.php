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
        word-break: break-word;
        white-space: normal;
        line-height: 1.5;
        display: block;
        margin-top: 4px;
        font-size: 0.85rem;
        color: #333;
    }

    /* Validation Styles */
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
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
                                <input type="text" class="form-control required" name="fullname" placeholder="Nhập họ tên" required>
                                <div class="invalid-feedback">Họ tên không được chứa số hoặc ký tự đặc biệt.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Số điện thoại</label>
                                <input type="tel" class="form-control required phone-check" name="phone" placeholder="Nhập số điện thoại" required>
                                <div class="invalid-feedback">Số điện thoại phải bắt đầu bằng 0 và có 10-11 chữ số.</div>
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
                                <input type="text" class="form-control required" name="address" placeholder="Số nhà, tên đường..." required>
                                <div class="invalid-feedback">Vui lòng nhập địa chỉ cụ thể.</div>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const API_BASE = 'http://localhost/PTUD_Final/public';
    const DEFAULT_SHIPPING = <?php echo (int)$shipping_fee; ?>;

    let cart = null;           
    let subtotal = 0;
    let shippingFee = DEFAULT_SHIPPING;
    let discountAmount = 0;    
    let discountCodeApplied = '';
    let previousDiscountAmount = 0;
    let previousDiscountCode = ''; 
    let buyNowMode = false;
    let buyNowSkuId = null;
    let buyNowQuantity = 1;
    let buyNowProductId = null;
    let buyNowProductName = '';
    let buyNowPrice = 0;
    let buyNowSkuCode = '';
    let buyNowSizeName = '';
    let buyNowColorName = '';

    function vnd(n) { return Number(n || 0).toLocaleString('vi-VN') + '₫'; }

    // === HÀM VALIDATION ===
    function isValidVietnameseName(name) {
        return /^[A-Za-zÀ-ỹà-ỹ\s\-\\.]+$/u.test(name);
    }

    function isValidPhone(phone) {
        return /^0\d{9,10}$/.test(phone);
    }

    function checkCheckoutInput(input) {
        const value = input.value.trim();
        let isValid = true;
        let errorMsgElement = input.nextElementSibling;

        if (value === '') {
            isValid = false;
            errorMsgElement.textContent = "Trường này không được để trống.";
        } else if (input.name === 'fullname' && !isValidVietnameseName(value)) {
            isValid = false;
            errorMsgElement.textContent = "Họ tên không được chứa số hoặc ký tự đặc biệt.";
        } else if (input.classList.contains('phone-check') && !isValidPhone(value)) {
            isValid = false;
            errorMsgElement.textContent = "Số điện thoại phải bắt đầu bằng 0 và có 10-11 chữ số.";
        }

        if (!isValid) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
        return isValid;
    }

    function validateCheckoutForm() {
        const inputs = document.querySelectorAll('#checkoutForm .required');
        let allValid = true;
        
        inputs.forEach(input => {
            const val = input.value.trim();
            if (val === '') {
                allValid = false;
            } else if (input.name === 'fullname' && !isValidVietnameseName(val)) {
                allValid = false;
            } else if (input.classList.contains('phone-check') && !isValidPhone(val)) {
                allValid = false;
            }
        });
        
        const province = document.getElementById('province').value;
        const district = document.getElementById('district').value;
        const ward = document.getElementById('ward').value;
        
        if (!province || !district || !ward) {
            allValid = false;
        }
        
        const btn = document.getElementById('btnPlaceOrder');
        if (allValid && (cart?.items?.length > 0 || buyNowMode)) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
        
        return allValid;
    }

    // === KHỞI TẠO KHI TRANG LOAD ===
    $(document).ready(function() {
        console.log('🚀 Bắt đầu khởi tạo trang checkout...');
        
        // 1. Load danh sách tỉnh/thành
        $.getJSON('https://provinces.open-api.vn/api/?depth=1', function(data) {
            console.log('✅ Đã load được', data.length, 'tỉnh/thành');
            $.each(data, function(k, v) {
                $('#province').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
            });
            
            // 2. SAU KHI LOAD XONG MỚI GẮN SỰ KIỆN
            initLocationEvents();
        }).fail(function() {
            console.error('❌ Lỗi load API tỉnh/thành');
            alert('Không thể tải danh sách địa chỉ. Vui lòng thử lại!');
        });

        // 3. Gắn sự kiện validation cho input
        const inputs = document.querySelectorAll('#checkoutForm .required');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                checkCheckoutInput(input);
                validateCheckoutForm();
            });
            
            input.addEventListener('input', () => {
                if (input.classList.contains('is-invalid')) {
                    input.classList.remove('is-invalid');
                }
                validateCheckoutForm();
            });
        });
        
        // 4. Kiểm tra mode MUA NGAY hoặc load giỏ hàng bình thường
        if (checkBuyNowMode()) {
            console.log('🛒 Chế độ: MUA NGAY');
            $('h2').text('MUA NGAY - Hoàn tất đơn hàng');
            $('#orderCount').parent().prepend('<span class="badge bg-warning text-dark me-2">MUA NGAY</span>');
            loadBuyNowProduct();
        } else {
            console.log('🛒 Chế độ: Giỏ hàng thông thường');
            loadNormalCart();
        }
    });

    // === HÀM GẮN SỰ KIỆN ĐỊA CHỈ (Chỉ gọi SAU KHI load xong tỉnh/thành) ===
    function initLocationEvents() {
        console.log('📍 Đang gắn sự kiện cho dropdown địa chỉ...');
        
        // Sự kiện chọn Tỉnh/Thành
        $('#province').on('change', function() {
            const code = $(this).val();
            const name = $(this).find('option:selected').data('name');
            console.log('🏙️ Đã chọn tỉnh:', name, '(code:', code + ')');
            
            // Reset district và ward
            $('#district').html('<option value="">Chọn Quận/Huyện</option>').prop('disabled', true);
            $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
            $('#district_text, #ward_text').val('');
            
            if (!code) {
                $('#province_text').val('');
                $('#location-badge').removeClass('text-danger text-success')
                    .html('<i class="fas fa-info-circle me-1"></i> Vui lòng chọn khu vực giao hàng');
                validateCheckoutForm();
                return;
            }
            
            $('#province_text').val(name);
            
            // Load danh sách quận/huyện
            $.getJSON(`https://provinces.open-api.vn/api/p/${code}?depth=2`, function(data) {
                console.log('✅ Đã load', data.districts.length, 'quận/huyện');
                if (data.districts && data.districts.length > 0) {
                    $.each(data.districts, function(k, v) {
                        $('#district').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
                    });
                    $('#district').prop('disabled', false);
                    
                    $('#location-badge').removeClass('text-danger text-success')
                        .html(`<i class="fas fa-map-marker-alt me-1"></i> ${name} → Chọn Quận/Huyện`);
                }
                validateCheckoutForm();
            }).fail(function() {
                console.error('❌ Lỗi load quận/huyện');
            });
        });

        // Sự kiện chọn Quận/Huyện
        $('#district').on('change', function() {
            const code = $(this).val();
            const name = $(this).find('option:selected').data('name');
            console.log('🏘️ Đã chọn quận:', name);
            
            $('#ward').html('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);
            $('#ward_text').val('');
            
            if (!code) {
                $('#district_text').val('');
                const provinceName = $('#province_text').val();
                $('#location-badge').removeClass('text-danger text-success')
                    .html(`<i class="fas fa-map-marker-alt me-1"></i> ${provinceName} → Chọn Quận/Huyện`);
                validateCheckoutForm();
                return;
            }
            
            $('#district_text').val(name);
            
            // Load danh sách phường/xã
            $.getJSON(`https://provinces.open-api.vn/api/d/${code}?depth=2`, function(data) {
                console.log('✅ Đã load', data.wards.length, 'phường/xã');
                if (data.wards && data.wards.length > 0) {
                    $.each(data.wards, function(k, v) {
                        $('#ward').append(`<option value="${v.code}" data-name="${v.name}">${v.name}</option>`);
                    });
                    $('#ward').prop('disabled', false);
                    
                    const provinceName = $('#province_text').val();
                    $('#location-badge').removeClass('text-danger text-success')
                        .html(`<i class="fas fa-map-marker-alt me-1"></i> ${provinceName} → ${name} → Chọn Phường/Xã`);
                }
                validateCheckoutForm();
            }).fail(function() {
                console.error('❌ Lỗi load phường/xã');
            });
        });

        // Sự kiện chọn Phường/Xã
        $('#ward').on('change', function() {
            const name = $(this).find('option:selected').data('name');
            console.log('🏡 Đã chọn phường:', name);
            
            if (!name) {
                $('#ward_text').val('');
            } else {
                $('#ward_text').val(name);
                
                const provinceName = $('#province_text').val();
                const districtName = $('#district_text').val();
                $('#location-badge').removeClass('text-danger')
                    .addClass('text-success')
                    .html(`<i class="fas fa-check-circle me-1"></i> ${name}, ${districtName}, ${provinceName}`);
            }
            
            validateCheckoutForm();
        });
        
        console.log('✅ Đã gắn xong tất cả sự kiện địa chỉ');
    }

    // === LOGIC SỔ ĐỊA CHỈ ===
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
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
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
        console.log('📮 Đang điền địa chỉ từ sổ:', {tinh: t, quan: q, xa: x});
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
                        setTimeout(() => validateCheckoutForm(), 500);
                    }, 1000);
                }
            }, 1000);
        }
    }

    function checkBuyNowMode() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('buy_now') === '1') {
            buyNowMode = true;
            buyNowSkuId = parseInt(urlParams.get('sku_id')) || null;
            buyNowQuantity = parseInt(urlParams.get('quantity')) || 1;
            buyNowProductId = parseInt(urlParams.get('product_id')) || null;
            buyNowProductName = decodeURIComponent(urlParams.get('product_name') || 'Sản phẩm');
            buyNowPrice = parseFloat(urlParams.get('price')) || 0;
            
            if (!buyNowSkuId) {
                alert('Thông tin sản phẩm không hợp lệ.');
                window.location.href = 'shop.php';
                return false;
            }
            return true;
        }
        return false;
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

    async function loadBuyNowProduct() {
        try {
            const item = {
                ten_san_pham: buyNowProductName,
                ma_sku: buyNowSkuCode || ('SKU-' + buyNowSkuId),
                so_luong: buyNowQuantity,
                gia_ban: buyNowPrice,
                thanh_tien: buyNowPrice * buyNowQuantity,
                ten_kich_co: buyNowSizeName,
                ten_mau: buyNowColorName
            };
            
            if (buyNowProductId) {
                try {
                    const res = await fetch(`${API_BASE}/api/san-pham/${buyNowProductId}`, { 
                        credentials: 'include' 
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (data.ok && data.san_pham) {
                            item.ten_san_pham = data.san_pham.ten_san_pham || buyNowProductName;
                        }
                    }
                } catch (e) {
                    console.error('Không thể lấy thông tin chi tiết sản phẩm:', e);
                }
            }
            
            document.getElementById('orderCount').innerText = '1';
            
            let metaInfo = '';
            if (buyNowSizeName || buyNowColorName) {
                metaInfo = `${buyNowSizeName ? `Size: ${buyNowSizeName}` : ''}${buyNowSizeName && buyNowColorName ? ' | ' : ''}${buyNowColorName ? `Màu: ${buyNowColorName}` : ''}`;
            }
            
            document.getElementById('orderItems').innerHTML = `
                <div class="product-item">
                    <div class="flex-grow-1">
                        <div class="product-name">${item.ten_san_pham} <span class="text-muted">x${item.so_luong}</span></div>
                        <div class="product-meta small text-secondary">
                            ${item.ma_sku ? `SKU: ${item.ma_sku}` : ''}${item.ma_sku && metaInfo ? ' | ' : ''}${metaInfo}
                        </div>
                    </div>
                    <div class="product-price">${vnd(item.thanh_tien)}</div>
                </div>
            `;
            
            cart = { items: [item], tam_tinh: item.thanh_tien };
            subtotal = item.thanh_tien;
            updateUI();
            document.getElementById('btnPlaceOrder').disabled = false;
            
        } catch (e) {
            console.error('Lỗi khi load sản phẩm mua ngay:', e);
            alert('Không thể load thông tin sản phẩm');
            window.location.href = 'shop.php';
        }

        setTimeout(() => validateCheckoutForm(), 100);
    }

    async function loadNormalCart() {
        const res = await fetch(`${API_BASE}/api/gio-hang`, { credentials: 'include' });
        const data = await res.json();
        if (res.status === 401) { 
            window.location.href = 'login.php'; 
            return; 
        }
        if (!data.ok) return;

        cart = data;
        subtotal = Number(data.tam_tinh || 0);
        document.getElementById('orderCount').innerText = data.items.length;
        document.getElementById('orderItems').innerHTML = data.items.map(it => `
            <div class="product-item">
                <div class="flex-grow-1">
                    <div class="product-name">${it.ten_san_pham} <span class="text-muted">x${it.so_luong}</span></div>
                    <div class="product-meta small text-secondary">
                        ${it.ma_sku ? `SKU: ${it.ma_sku} | ` : ''}${it.ten_kich_co || ''} ${it.ten_mau ? `| Màu: ${it.ten_mau}` : ''}
                    </div>
                </div>
                <div class="product-price">${vnd(it.thanh_tien)}</div>
            </div>
        `).join('');
        
        updateUI();
        document.getElementById('btnPlaceOrder').disabled = data.items.length === 0;
        setTimeout(() => validateCheckoutForm(), 100);
    }

    // === XỬ LÝ SUBMIT FORM ===
    document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateCheckoutForm()) {
            const firstError = document.querySelector('#checkoutForm .is-invalid');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                alert('Vui lòng chọn đầy đủ địa chỉ giao hàng!');
            }
            return;
        }

        const btn = document.getElementById('btnPlaceOrder');
        const originalText = btn.innerHTML;
        const street = document.querySelector('[name="address"]').value.trim();
        const ward = $('#ward_text').val();
        const district = $('#district_text').val();
        const province = $('#province_text').val();

        if (!ward || !district || !province) { 
            alert('Vui lòng chọn đầy đủ địa chỉ'); 
            return; 
        }
        
        const fullname = document.querySelector('[name="fullname"]').value.trim();
        const phone = document.querySelector('[name="phone"]').value.trim();
        
        if (!isValidVietnameseName(fullname)) {
            alert('Họ tên không được chứa số hoặc ký tự đặc biệt.');
            return;
        }
        
        if (!isValidPhone(phone)) {
            alert('Số điện thoại phải bắt đầu bằng 0 và có 10-11 chữ số.');
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang đặt hàng...';

        const payload = {
            nguoi_nhan: fullname,
            sdt_nguoi_nhan: phone,
            dia_chi_giao_hang: `${street}, ${ward}, ${district}, ${province}`,
            ghi_chu: document.querySelector('[name="note"]').value.trim(),
            phi_van_chuyen: shippingFee,
            giam_gia: discountAmount,
            ma_khuyen_mai: discountCodeApplied
        };
        
        try {
            let endpoint, method;
            
            if (buyNowMode) {
                endpoint = `${API_BASE}/api/don-hang/buy-now`;
                method = 'POST';
                payload.sku_id = buyNowSkuId;
                payload.so_luong = buyNowQuantity;
            } else {
                endpoint = `${API_BASE}/api/don-hang`;
                method = 'POST';
            }
            
            const res = await fetch(endpoint, {
                method: method,
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
                    <p>Tổng thanh toán: <strong>${vnd(data.tong_tien)}</strong></p>
                    <a href="shop.php" class="btn btn-dark mt-2">Tiếp tục mua sắm</a>
                    <a href="profile/orders.php" class="btn btn-outline-dark mt-2 ms-2">Xem đơn hàng</a>
                `).show();
            } else {
                alert(data.error || 'Đặt hàng thất bại');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (err) { 
            console.error('Lỗi hệ thống:', err);
            alert('Lỗi hệ thống'); 
            btn.disabled = false; 
            btn.innerHTML = originalText; 
        }
    });

    // === ÁP DỤNG MÃ GIẢM GIÁ ===
    document.getElementById('applyDiscount').addEventListener('click', async function() {
        const code = (document.getElementById('discountInput').value || '').trim().toUpperCase();
        if (!code) { 
            alert('Vui lòng nhập mã'); 
            return; 
        }
        
        // Lưu trạng thái hiện tại trước khi thay đổi
        const currentDiscount = discountAmount;
        const currentCode = discountCodeApplied;
        
        const btn = this;
        btn.disabled = true;
        btn.innerText = 'Đang kiểm tra...';
        
        try {
            const res = await fetch('check_voucher.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code: code, total: subtotal })
            });
            
            const data = await res.json();
            
            if (data.status) {
                // Nếu mã mới hợp lệ, lưu trạng thái cũ để backup
                previousDiscountAmount = currentDiscount;
                previousDiscountCode = currentCode;
                
                // Cập nhật với mã mới
                discountAmount = Number(data.discount);
                discountCodeApplied = data.code;
                updateUI();
                
                alert('Áp dụng thành công: ' + data.message);
            } else {
                // Nếu mã mới KHÔNG hợp lệ
                if (currentCode) {
                    // Nếu có mã valid trước đó: khôi phục và hiển thị thông báo có phần "Tự động áp dụng mã trước đó"
                    discountAmount = currentDiscount;
                    discountCodeApplied = currentCode;
                    updateUI();
                    document.getElementById('discountInput').value = currentCode;
                    
                    alert(data.message + '. Tự động áp dụng mã giảm giá trước đó.');
                } else {
                    // Nếu CHƯA có mã valid trước đó: chỉ báo lỗi đơn giản, xoá ô input
                    discountAmount = 0;
                    discountCodeApplied = '';
                    updateUI();
                    document.getElementById('discountInput').value = '';
                    
                    alert(data.message); // Chỉ hiển thị lỗi đơn giản
                }
            }
        } catch (e) { 
            // Nếu có lỗi kết nối
            if (currentCode) {
                // Có mã valid trước đó: khôi phục
                discountAmount = currentDiscount;
                discountCodeApplied = currentCode;
                updateUI();
                document.getElementById('discountInput').value = currentCode;
                
                alert('Lỗi kết nối voucher. Đã khôi phục mã giảm giá trước đó.');
            } else {
                // Chưa có mã valid: xoá ô input
                discountAmount = 0;
                discountCodeApplied = '';
                updateUI();
                document.getElementById('discountInput').value = '';
                
                alert('Lỗi kết nối voucher.');
            }
        } finally { 
            btn.disabled = false; 
            btn.innerText = 'Áp dụng'; 
        }
    });
</script>
<?php include 'footer.php'; ?>