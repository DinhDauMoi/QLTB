<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @extends('fw')
    <title>Trang chủ</title>
</head>

<body>
    <style>
        /* From Uiverse.io by kennyotsu-monochromia */
        body {
            width: 100%;
            height: 100%;
            --color: #E1E1E1;
            background-color: #F3F3F3;
            background-image: linear-gradient(0deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent),
                linear-gradient(90deg, transparent 24%, var(--color) 25%, var(--color) 26%, transparent 27%, transparent 74%, var(--color) 75%, var(--color) 76%, transparent 77%, transparent);
            background-size: 55px 55px;
        }

        .file-upload {
            display: inline-block;
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .file-upload input[type="file"] {
            display: none;
            /* Ẩn input gốc */
        }

        .file-upload i {
            font-size: 2.5em;
            color: black;
            transition: 0.2s;
        }

        .file-upload:hover i {
            color: black;
            transform: scale(1.1);
        }

        .file-name {
            font-size: 0.9em;
            color: #333;
            word-break: break-all;
            /* Đảm bảo tên file dài không làm vỡ layout */
        }
    </style>
    <div class="container-fluid py-3">
        <!-- ======== HEADER ======== -->
        <div class="d-flex flex-wrap justify-content-between align-items-center border-black border-bottom pb-2 mb-3">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <a class="navbar-brand" href="/"><img src="/img/favicon_fpt.png" alt="Logo" width="60" height="44" class="d-inline-block align-text-top"></a>
                <h4 class="m-3">QUẢN LÝ MÁY IN NHIỆT</h4>
            </div>

            <div class="d-flex flex-nowrap justify-content-end align-items-center gap-2 flex-grow-1">
                <input type="text" class="form-control text-center fw-bold border border-black"
                    value="ĐANG QUẢN LÝ {{$so_luong}} THIẾT BỊ"
                    readonly style="max-width: 280px;">
                <a href="{{ route('export') }}" class="btn btn-primary">Xuất Dữ liệu</a>
                <a href="{{route('in_all_qr')}}" class="btn btn-primary">In Tất Cả QR</a>
                <button class="btn btn-outlight"><i style="font-size: 2em;" class="fa-solid fa-right-from-bracket"></i></button>
            </div>
        </div>

        <!-- ======== MAIN CONTENT ======== -->
        <div class="row g-3">
            <!-- BÊN TRÁI -->
            <div class="col-lg-4">
                <form action="{{route('them_thiet_bi')}}" method="post">
                    @csrf
                    <div class="p-3 border border-black rounded h-800">
                        <!-- Form thêm -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bộ Phận</label>
                            <select required name="kho" class="form-select border border-black">
                                <option selected disabled>Chọn Bộ Phận</option>
                                @foreach($kho as $item)
                                <option value="{{$item->id}}">{{$item->ten_kho}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">IMEI</label>
                            <input required name="imei" type="text" class="form-control border border-black" placeholder="Nhập IMEI">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ngày Bảo Hành</label>
                            <input required name="ngay" type="date" class="form-control border border-black">
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </form>
                <!-- Tải dữ liệu -->
                <div class="border border-black rounded p-3 text-center mt-3">
                    <form action="{{ route('import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <label class="file-upload">
                            <input required type="file" name="file" id="fileInput" name="file" accept=".xlsx,.xls">
                            <i class="fa-solid fa-file-import"></i>
                            <div>Chọn tệp</div>
                        </label>
                        <div id="fileName" class="file-name mt-2"></div>
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary">Đẩy Dữ Liệu</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- BÊN PHẢI -->
            <div class="col-lg-8">
                <div class="p-3 border border-black rounded h-100">
                    <!-- Thanh tìm kiếm -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <div class="input-group" style="max-width: 90%;">
                            <input id="searchInput" type="text" class="form-control border border-black" placeholder="Tìm kiếm">
                        </div>
                        <button id="scanButton" class="btn btn-outline" type="button" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                            <i style="font-size: 2em;" class="fa-solid fa-qrcode"></i>
                        </button>

                        <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrScannerModalLabel">Quét Mã QR</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        {{-- Vùng hiển thị luồng camera --}}
                                        <video id="qrScannerVideo" style="width: 100%; max-height: 300px;"></video>

                                        {{-- Vùng hiển thị kết quả (tùy chọn) --}}
                                        <p id="scanResult" class="mt-3 text-success" style="display: none;"></p>

                                        {{-- Lỗi (nếu camera không khả dụng) --}}
                                        <p id="scanError" class="mt-3 text-danger"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bảng thiết bị -->
                    <div class="table-responsive border border-black" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                        <table id="thietBiTable" class="table table-dark table-hover text-center align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Tên máy</th>
                                    <th>Bộ Phận</th>
                                    <th>IMEI</th>
                                    <th>Ngày Nhận</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($thiet_bi as $item)
                                <tr>
                                    <td>Máy {{$item->id}}</td>
                                    <td>{{$item->kho->ten_kho}}</td>
                                    <td>{{$item->imei}}</td>
                                    <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        <a href="{{route('chi_tiet',['id'=>$item->id])}}" class="btn btn-sm btn-primary me-1">Chi Tiết</a>
                                        <button onclick="confirmDelete('{{$item->id}}')" class="btn btn-sm btn-danger">Xóa</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('fileInput').addEventListener('change', function(event) {
        const fileNameDiv = document.getElementById('fileName');
        const file = event.target.files[0]; // Lấy file đầu tiên được chọn
        if (file) {
            fileNameDiv.textContent = `Tệp đã chọn: ${file.name}`;
        } else {
            fileNameDiv.textContent = ''; // Xóa tên file nếu không có file nào được chọn
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ... (Các khai báo const giữ nguyên) ...
        const modalElement = document.getElementById('qrScannerModal');
        const errorElement = document.getElementById('scanError');
        let html5QrCode = null;

        // --- Hàm xử lý khi quét thành công (Giữ nguyên) ---
        const onScanSuccess = (decodedText, decodedResult) => {
            // ... (Logic cắt chuỗi và chuyển hướng giữ nguyên) ...
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    console.log("Quét thành công: " + decodedText);
                    const parts = decodedText.split('-');
                    if (parts.length >= 3) {
                        const idCanLay = parts[0];
                        document.getElementById('scanResult').textContent = `Đã quét ID: ${idCanLay}. Đang chuyển hướng...`;
                        document.getElementById('scanResult').style.display = 'block';

                        const detailRouteBase = "{{ route('chi_tiet', ['id' => ':id']) }}";
                        const redirectUrl = detailRouteBase.replace(':id', idCanLay);
                        window.location.href = redirectUrl;
                    } else {
                        document.getElementById('scanError').textContent = 'Mã QR không hợp lệ (Không đúng định dạng).';
                        document.getElementById('scanError').style.display = 'block';
                    }
                }).catch(err => {
                    console.error("Lỗi khi dừng camera:", err);
                });
            }
        };

        // --- Khởi tạo Scanner khi Modal được mở (Phần sửa lỗi) ---
        modalElement.addEventListener('shown.bs.modal', () => {
            if (!html5QrCode) {
                // Khởi tạo lại Html5Qrcode nếu chưa có (rất quan trọng)
                html5QrCode = new Html5Qrcode("qrScannerVideo");
            }

            // Cấu hình quét
            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            };

            // --- Cấu hình CAMERA MỚI (Linh hoạt hơn) ---
            const cameraConfig = {
                facingMode: "environment"
            }; // Bỏ 'exact'

            html5QrCode.start(
                cameraConfig,
                config,
                onScanSuccess,
                (errorMessage) => {
                    // Xử lý lỗi trong quá trình quét (thường là lỗi nhỏ)
                }
            ).catch((err) => {
                // Lỗi LỚN: Khởi tạo camera thất bại
                console.error("Lỗi khởi tạo camera chi tiết:", err);

                let errorMsg = 'Lỗi truy cập camera.';

                // Kiểm tra các lỗi phổ biến
                if (err.name === 'NotAllowedError') {
                    errorMsg = 'Truy cập bị từ chối. Vui lòng cho phép camera trong cài đặt trình duyệt.';
                } else if (err.name === 'NotFoundError') {
                    errorMsg = 'Không tìm thấy camera sau. Đang thử camera trước.';

                    // Thử lại với camera trước
                    html5QrCode.start({
                        facingMode: "user"
                    }, config, onScanSuccess, (e) => {}).catch((e2) => {
                        errorElement.textContent = 'Không tìm thấy thiết bị camera nào.';
                    });
                    return; // Thoát khỏi hàm để không hiển thị lỗi ban đầu
                } else if (err.name === 'SecurityError') {
                    errorMsg = 'Bảo mật: Chức năng camera chỉ hoạt động trên HTTPS hoặc localhost.';
                } else if (err.name === 'OverconstrainedError') {
                    errorMsg = 'Lỗi cấu hình camera (Thử lại trên thiết bị khác).';
                }

                errorElement.textContent = errorMsg;
                // Hiển thị alert chung, nhưng lỗi chi tiết đã có trong Console
                alert(errorMsg);
            });
        });

        // --- Dừng Scanner khi Modal được đóng (Giữ nguyên) ---
        modalElement.addEventListener('hidden.bs.modal', () => {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().catch(err => {
                    console.error("Lỗi khi dừng camera:", err);
                });
            }
            // Xóa thông báo lỗi và kết quả
            document.getElementById('scanResult').style.display = 'none';
            errorElement.textContent = '';
        });
    });
</script>
</html>