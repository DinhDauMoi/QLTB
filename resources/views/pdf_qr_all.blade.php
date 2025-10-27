<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tải Mã QR Code</title>
    @extends('fw')
</head>

<body style="font-family: 'DejaVu Sans', sans-serif;">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000 !important;
            /* giữ viền */
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        img {
            display: block;
            margin: 0 auto;
        }

        p {
            margin: 2px 0;
            font-size: 13px;
            line-height: 1.1;
        }

        /* Bắt Dompdf vẽ border đúng */
        table,
        tr,
        td {
            border-color: #000 !important;
            border-width: 1px !important;
            border-style: solid !important;
        }
    </style>
    <table class="table" border="1" style="border-collapse: collapse; width: 100%; text-align: center;">

        {{-- Khởi tạo hàng đầu tiên --}}
        <tr>

            @foreach ($thiet_bi as $item)

            {{-- 1. Đóng hàng cũ và mở hàng mới sau mỗi 6 lần lặp --}}
            {{-- Điều kiện: Nếu index hiện tại chia hết cho 6 VÀ index đó không phải là 0 --}}
            @if ($loop->index > 0 && $loop->index % 6 === 0)
        </tr>
        <tr> {{-- Bắt đầu hàng mới --}}
            @endif

            {{-- 2. Mã QR và thông tin của từng thiết bị --}}
            <td style="padding: 5px; border: 1px solid #000; vertical-align: middle; width: 16.66%;">

                {{-- Đặt width: 16.66% cho TD để đảm bảo 6 cột vừa vặn (100% / 6) --}}

                <div class="qr-container" style="margin-bottom: 3px;">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(
                        QrCode::format('svg')
                            ->size(100)
                            ->encoding('UTF-8')
                            ->generate('Máy '.$item->id . '-' . 'TSCT' . '-' . $item->imei)
                    ) }}" style="width: 100px; height: 100px;">
                    {{-- Thêm style cho img để kiểm soát kích thước tuyệt đối (tốt cho PDF) --}}
                </div>

                <div class="info" style="line-height: 1.1; font-size: 10px; margin: 0;">
                    {{-- Giảm font-size xuống 10px hoặc thấp hơn (phù hợp cho in ấn nhỏ) --}}
                    <p style="margin: 2px 0;">Máy {{$item->id . '-' . 'TSCT'}}</p>
                    <p style="margin: 2px 0;">{{ $item->imei }}</p>
                </div>

            </td>

            @endforeach

        </tr>
        {{-- Đóng hàng cuối cùng --}}

    </table>
</body>

</html>