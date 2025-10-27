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

        #reader {
            width: 400px;
        }

        .result {
            background-color: green;
            color: #fff;
            padding: 20px;
        }

        /* .row_d {
            display: flex;
        } */

        #reader__scan_region {
            background: white;
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

            <div class="container-responsive d-flex justify-content-start align-items-center gap-2 w-100" style="flex-wrap: wrap !important;">

                <div class="d-flex align-items-center justify-content-between gap-2 flex-grow-1" style="min-width: 300px;">

                    <input type="text"
                        class="form-control text-center fw-bold border border-black flex-grow-1"
                        value="ĐANG QUẢN LÝ {{ $so_luong }} THIẾT BỊ"
                        readonly
                        style="max-width: 280px; min-width: 200px;">

                    <a href="{{ route('dang_xuat') }}" class="btn btn-outline-dark" style="flex: 0 0 auto;">
                        <i class="fa-solid fa-right-from-bracket" style="font-size: 1.8em;"></i>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto flex-grow-0 w-100 w-md-auto">

                    <a href="{{ route('export') }}" class="btn btn-primary w-100 w-sm-auto">Xuất Dữ liệu</a>

                    <a href="{{ route('in_all_qr') }}" class="btn btn-primary w-100 w-sm-auto">In Tất Cả QR</a>
                </div>
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
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-grow-1 mb-3" style="min-width: 300px;">
                        <div class="input-group" style="max-width: 90%;">
                            <input id="searchInput" type="text" class="form-control border border-black" placeholder="Tìm kiếm">
                        </div>
                        <button id="scanButton" class="btn btn-outline" type="button" data-bs-toggle="modal" data-bs-target="#qrScannerModal">
                            <i style="font-size: 2em;" class="fa-solid fa-qrcode"></i>
                        </button>

                        <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered d-flex justify-content-center">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrScannerModalLabel">Quét Mã QR</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                                            <div id="reader"></div>
                                            <div style="padding: 30px; text-align: center;">
                                                <div id="result"></div>
                                            </div>
                                        </div>
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
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('chi_tiet', ['id' => $item->id]) }}" class="btn btn-sm btn-primary">
                                                Chi Tiết
                                            </a>
                                            <button onclick="confirmDelete('{{ $item->id }}')" class="btn btn-sm btn-danger">
                                                Xóa
                                            </button>
                                        </div>
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

</html>