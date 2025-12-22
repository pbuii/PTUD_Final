<?php
// profile/address.php - Trang quản lý địa chỉ
if (session_status() === PHP_SESSION_NONE) session_start();

$API_BASE = 'http://localhost/PTUD_Final/public';
$cookie = session_name() . '=' . session_id();
session_write_close();

// Lấy thông tin user
$ch = curl_init($API_BASE . '/api/auth/me');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_COOKIE => $cookie,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 3,
]);

$res  = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($res ?: '', true);

if ($http !== 200 || !($data['ok'] ?? false) || !($data['authenticated'] ?? false)) {
    header('Location: ../login.php');
    exit();
}

$user = $data['nguoi_dung'];
?>

<?php include '../header.php'; ?>
<link rel="stylesheet" href="../assets/css/profile.css">

<main class="bg-light py-4 py-lg-5">
    <div class="container">
        <div class="row g-4">
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php include 'sidebar.php'; ?>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-card">
                    
                    <!-- Header with Add Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h4 class="mb-0">Sổ địa chỉ</h4>
                        <button class="btn btn-sm btn-dark" 
                                data-bs-toggle="modal" 
                                data-bs-target="#addAddressModal">
                            <i class="bi bi-plus-lg me-1"></i>Thêm mới
                        </button>
                    </div>
                    
                    <!-- Address List -->
                    <div class="row g-3" id="addressList">
                        <div class="col-12 text-center py-4">
                            <div class="spinner-border text-dark" role="status"></div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</main>

<!-- Modal: Add/Edit Address -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <div class="row g-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label">Tên người nhận</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="ten_nguoi_nhan"
                                   required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" 
                                   class="form-control" 
                                   name="so_dien_thoai"
                                   required>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" 
                                    name="tinh_thanh" 
                                    id="provinceSelect"
                                    required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Quận/Huyện</label>
                            <select class="form-select" 
                                    name="quan_huyen"
                                    id="districtSelect"
                                    required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label">Phường/Xã</label>
                            <select class="form-select" 
                                    name="phuong_xa"
                                    id="wardSelect"
                                    required>
                                <option value="">Chọn...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="dia_chi_cu_the"
                                   placeholder="Số nhà, tên đường..." 
                                   required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       name="mac_dinh"
                                       id="defaultAddress">
                                <label class="form-check-label" for="defaultAddress">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-dark" onclick="saveAddress()">Lưu địa chỉ</button>
            </div>
        </div>
    </div>
</div>

<script>
// Load addresses on page load
document.addEventListener('DOMContentLoaded', async () => {
  loadAddresses();
  bindLocationEventsOnce();
  await loadProvinces();
});
let editingId = null;

// Load danh sách địa chỉ
async function loadAddresses() {
    const addressList = document.getElementById('addressList');
    
    try {
        const response = await fetch('<?php echo $API_BASE; ?>/api/dia-chi', { 
            credentials: 'include' 
        });
        const addresses = await response.json();
        
        if (addresses && addresses.length > 0) {
            addressList.innerHTML = '';
            addresses.forEach(address => {
                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `
                    <div class="card h-100 shadow-sm border-0 bg-white">
                        <div class="card-body d-flex flex-column">
                        ${address.mac_dinh ? '<span class="badge bg-dark mb-2 align-self-start">Mặc định</span>' : ''}
                        <h6 class="card-title fw-bold">${address.ten_nguoi_nhan}</h6>
                        <p class="card-text small text-secondary mb-3">
                            <i class="bi bi-telephone me-1"></i> ${address.so_dien_thoai}<br>
                            <i class="bi bi-geo-alt me-1"></i> ${address.dia_chi_cu_the}, ${address.phuong_xa}, ${address.quan_huyen}, ${address.tinh_thanh}
                        </p>

                        <!-- đẩy nút xuống đáy -->
                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-sm btn-outline-dark" onclick="editAddress(${address.id})">Sửa</button>
                            ${!address.mac_dinh ? `<button class="btn btn-sm btn-outline-danger" onclick="deleteAddress(${address.id})">Xóa</button>` : ''}
                        </div>
                        </div>
                    </div>
                `;
                addressList.appendChild(col);
            });
        } else {
            addressList.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-light border text-center py-4">
                        <i class="bi bi-geo-alt fs-1 text-muted d-block mb-2"></i>
                        <p class="mb-0">Chưa có địa chỉ nào</p>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error loading addresses:', error);
        addressList.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger text-center">Không thể tải danh sách địa chỉ</div>
            </div>
        `;
    }
}

// LOCATION DROPDOWNS (Provinces API)

async function loadProvinces(selectedName = '') {
  const provinceSelect = document.getElementById('provinceSelect');
  const districtSelect = document.getElementById('districtSelect');
  const wardSelect     = document.getElementById('wardSelect');

  // reset
  provinceSelect.innerHTML = `<option value="">Chọn...</option>`;
  districtSelect.innerHTML = `<option value="">Chọn...</option>`;
  wardSelect.innerHTML     = `<option value="">Chọn...</option>`;
  districtSelect.disabled = true;
  wardSelect.disabled = true;

  const res = await fetch('https://provinces.open-api.vn/api/?depth=1');
  const data = await res.json();

  data.forEach(p => {
    // value = p.name để khi edit set value theo text sẽ match
    const opt = document.createElement('option');
    opt.value = p.name;
    opt.textContent = p.name;
    opt.dataset.code = p.code;
    provinceSelect.appendChild(opt);
  });

  // auto select nếu có
  if (selectedName) {
    provinceSelect.value = selectedName;
    if (provinceSelect.value === selectedName) {
      await loadDistrictsByProvinceSelected('', provinceSelect); // load district list
    }
  }
}

async function loadDistrictsByProvinceSelected(selectedDistrictName = '', provinceSelectEl = null) {
  const provinceSelect = provinceSelectEl || document.getElementById('provinceSelect');
  const districtSelect = document.getElementById('districtSelect');
  const wardSelect     = document.getElementById('wardSelect');

  districtSelect.innerHTML = `<option value="">Chọn...</option>`;
  wardSelect.innerHTML     = `<option value="">Chọn...</option>`;
  wardSelect.disabled = true;

  const selectedOpt = provinceSelect.options[provinceSelect.selectedIndex];
  const provinceCode = selectedOpt?.dataset?.code;

  if (!provinceCode) {
    districtSelect.disabled = true;
    return;
  }

  const res = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
  const data = await res.json();

  (data.districts || []).forEach(d => {
    const opt = document.createElement('option');
    opt.value = d.name;            // value = name (khớp DB)
    opt.textContent = d.name;
    opt.dataset.code = d.code;     // code để load wards
    districtSelect.appendChild(opt);
  });

  districtSelect.disabled = false;

  if (selectedDistrictName) {
    districtSelect.value = selectedDistrictName;
    if (districtSelect.value === selectedDistrictName) {
      await loadWardsByDistrictSelected('', districtSelect);
    }
  }
}

async function loadWardsByDistrictSelected(selectedWardName = '', districtSelectEl = null) {
  const districtSelect = districtSelectEl || document.getElementById('districtSelect');
  const wardSelect     = document.getElementById('wardSelect');

  wardSelect.innerHTML = `<option value="">Chọn...</option>`;

  const selectedOpt = districtSelect.options[districtSelect.selectedIndex];
  const districtCode = selectedOpt?.dataset?.code;

  if (!districtCode) {
    wardSelect.disabled = true;
    return;
  }

  const res = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
  const data = await res.json();

  (data.wards || []).forEach(w => {
    const opt = document.createElement('option');
    opt.value = w.name;           // value = name (khớp DB)
    opt.textContent = w.name;
    opt.dataset.code = w.code;
    wardSelect.appendChild(opt);
  });

  wardSelect.disabled = false;

  if (selectedWardName) {
    wardSelect.value = selectedWardName;
  }
}

// Gắn event cascade 1 lần
function bindLocationEventsOnce() {
  const provinceSelect = document.getElementById('provinceSelect');
  const districtSelect = document.getElementById('districtSelect');

  // tránh bind nhiều lần
  if (provinceSelect.dataset.bound === '1') return;
  provinceSelect.dataset.bound = '1';

  provinceSelect.addEventListener('change', async () => {
    await loadDistrictsByProvinceSelected();
  });

  districtSelect.addEventListener('change', async () => {
    await loadWardsByDistrictSelected();
  });
}

// Save address
async function saveAddress() {
    const form = document.getElementById('addressForm');
    
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    const data = {
        ten_nguoi_nhan: formData.get('ten_nguoi_nhan'),
        so_dien_thoai: formData.get('so_dien_thoai'),
        tinh_thanh: formData.get('tinh_thanh'),
        quan_huyen: formData.get('quan_huyen'),
        phuong_xa: formData.get('phuong_xa'),
        dia_chi_cu_the: formData.get('dia_chi_cu_the'),
        mac_dinh: formData.get('mac_dinh') ? true : false
    };
    
    try {
        const url = editingId
            ? `<?php echo $API_BASE; ?>/api/dia-chi/${editingId}`
            : `<?php echo $API_BASE; ?>/api/dia-chi`;

            const method = editingId ? 'PUT' : 'POST';

            const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
            credentials: 'include'
            });

        
        const result = await response.json();
        
        if (response.ok && result.ok) {
            alert(editingId ? 'Đã cập nhật địa chỉ!' : 'Đã lưu địa chỉ thành công!');
            bootstrap.Modal.getInstance(document.getElementById('addAddressModal')).hide();
            form.reset();
            editingId = null;
            document.querySelector('#addAddressModal .modal-title').textContent = 'Thêm địa chỉ mới';
            loadAddresses();
        } else {
            alert('Lỗi: ' + (result.message || 'Không thể lưu địa chỉ'));
        }
    } catch (error) {
        console.error(error);
        alert('Có lỗi xảy ra khi lưu địa chỉ');
    }
}

// Edit address (placeholder)
async function editAddress(id) {
  try {
    const res = await fetch(`<?php echo $API_BASE; ?>/api/dia-chi/${id}`, {
      credentials: 'include'
    });

    const jsonData = await res.json();

    if (!res.ok || !jsonData.ok) {
      throw new Error(jsonData.message || 'Không tải được địa chỉ');
    }

    const a = jsonData.data;
    const form = document.getElementById('addressForm');

    form.querySelector('[name="ten_nguoi_nhan"]').value = a.ten_nguoi_nhan || '';
    form.querySelector('[name="so_dien_thoai"]').value = a.so_dien_thoai || '';
    form.querySelector('[name="tinh_thanh"]').value = a.tinh_thanh || '';
    form.querySelector('[name="quan_huyen"]').value = a.quan_huyen || '';
    form.querySelector('[name="phuong_xa"]').value = a.phuong_xa || '';
    form.querySelector('[name="dia_chi_cu_the"]').value = a.dia_chi_cu_the || '';
    document.getElementById('defaultAddress').checked = !!a.mac_dinh;

    editingId = id;
    document.querySelector('#addAddressModal .modal-title').textContent = 'Cập nhật địa chỉ';

    new bootstrap.Modal(document.getElementById('addAddressModal')).show();
  } catch (e) {
    alert('Lỗi: ' + e.message);
  }
}

// Delete address
async function deleteAddress(id) {
    if (!confirm('Bạn có chắc muốn xóa địa chỉ này?')) return;
    
    try {
        const response = await fetch(`<?php echo $API_BASE; ?>/api/dia-chi/${id}`, {
            method: 'DELETE',
            credentials: 'include'
        });
        
        if (response.ok) {
            alert('Đã xóa địa chỉ');
            loadAddresses();
        } else {
            alert('Không thể xóa địa chỉ');
        }
    } catch (error) {
        console.error(error);
        alert('Có lỗi xảy ra khi xóa địa chỉ');
    }
}
document.getElementById('addAddressModal').addEventListener('hidden.bs.modal', () => {
  editingId = null;
  document.querySelector('#addAddressModal .modal-title').textContent = 'Thêm địa chỉ mới';
  document.getElementById('addressForm').reset();
});
</script>

<?php include '../footer.php'; ?>