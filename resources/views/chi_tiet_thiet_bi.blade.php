<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @extends('fw')
    <title>Trang chủ</title>
</head>

<body class="d-flex flex-column min-vh-100">
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
    </style>
    <div class="container-fluid py-3">
        <!-- ======== HEADER ======== -->
        <div class="d-flex flex-wrap justify-content-between align-items-center border-black border-bottom pb-2 mb-3">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <a class="navbar-brand" href="/"><img src="/img/favicon_fpt.png" alt="Logo" width="60" height="44" class="d-inline-block align-text-top"></a>
                <a class="text-black m-3" style="font-size: 20px;" href="{{route('trang_chu')}}"><i class="fa-solid fa-arrow-left"></i></a>
                <h4 class="m-2">CHI TIẾT MÁY IN NHIỆT SỐ {{$chi_tiet->id}}</h4>
            </div>

            <!-- <div class="container-responsive d-flex justify-content-start align-items-center gap-2 w-100" style="flex-wrap: wrap !important;">

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
            </div> -->
        </div>

        <!-- ======== MAIN CONTENT ======== -->
        <div class="row g-3">
            <!-- BÊN TRÁI -->
            <div class="col-lg-7">
                <form action="{{route('thay_doi_thiet_bi',['id'=>$chi_tiet->id])}}" method="post">
                    @csrf
                    <div class="p-3 border border-black rounded h-800">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Bộ Phận</label>
                                    <select name="kho" class="form-select border border-black">
                                        <!-- Hiển thị kho hiện tại -->
                                        <option selected value="{{ $chi_tiet->kho->id }}">{{ $chi_tiet->kho->ten_kho }}</option>

                                        <!-- Hiển thị các kho còn lại -->
                                        @foreach($kho as $item)
                                        @if($item->id != $chi_tiet->kho->id)
                                        <option value="{{ $item->id }}">{{ $item->ten_kho }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">IMEI</label>
                                    <input name="imei" value="{{$chi_tiet->imei}}" type="text" class="form-control border border-black" placeholder="Nhập IMEI">
                                </div>
                                <div style="display: none;" class="mb-3">
                                    <label class="form-label fw-semibold">ID</label>
                                    <input name="id" value="{{$chi_tiet->id}}" type="text" class="form-control border border-black">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Ngày Bảo Hành</label>
                                    <input readonly name="ngay" value="{{$chi_tiet->ngay}}" type="date" class="form-control border border-black">
                                </div>
                                <div hidden class="mb-3">
                                    <label class="form-label fw-semibold">Lịch Sử</label>
                                    <input name="ls_ngay" value="{{$chi_tiet->created_at}}" type="date" class="form-control border border-black">
                                </div>
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary">Thay Đổi</button>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div style="display: grid; place-items: center; height: 100%;">
                                    {!! QrCode::encoding('UTF-8')->size(200)->generate('Máy '.$chi_tiet->id . '-' . 'TSCT' . '-' . $chi_tiet->imei) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Tải dữ liệu -->
                <div class="border border-black rounded p-3 text-center mt-3">
                    <div class="d-grid mt-2">
                        <a class="btn btn-sm btn-primary" href="{{ route('generate_pdf',['id'=>$chi_tiet->id]) }}">In Mã QR</a>
                    </div>
                    <div class="d-grid mt-2">
                        <button onclick="confirmDelete('{{ $chi_tiet->id }}')" class="btn btn-sm btn-danger">Xóa Thiết Bị</button>
                    </div>
                </div>
            </div>


            <!-- BÊN PHẢI -->
            <div class="col-lg-5">
                <div class="p-3 border border-black rounded h-100">
                    <!-- Thanh tìm kiếm -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <input type="text" class="form-control text-center fw-bold border border-black"
                            value="Lịch Sử"
                            readonly style="max-width: auto;">
                    </div>

                    <!-- Bảng thiết bị -->
                    <div class="table-responsive border border-black" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                        <table class="table table-dark table-hover text-center align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>STT</th>
                                    <th>Bộ Phận</th>
                                    <th>IMEI</th>
                                    <th>Ngày Nhận</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lich_su as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->lskho->ten_kho}}</td>
                                    <td>{{$item->imei}}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->ngay)->format('d-m-Y') }}</td>
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
<footer class="text-center py-2 bg-light border-top mt-auto">
    © 2025 Dinh Dau Moi
</footer>

</html>