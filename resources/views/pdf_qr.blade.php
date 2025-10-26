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
        <tr>
            @foreach (range(1, 6) as $i)
            <td style="padding: 5px; border: 1px solid #000; vertical-align: middle;">
                <div class="qr-container" style="margin-bottom: 3px;">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(
    QrCode::format('svg')
        ->size(100)
        ->encoding('UTF-8')
        ->generate($get_data_qr->id . '-' . $get_data_qr->kho->ten_kho . '-' . $get_data_qr->imei)
) }}">
                </div>
                <div class="info" style="line-height: 1.1; font-size: 14px; margin: 0;">
                    <p style="margin: 2px 0;">Máy {{ $get_data_qr->id }} - {{ $get_data_qr->kho->ten_kho }}</p>
                    <p style="margin: 2px 0;">{{ $get_data_qr->imei }}</p>
                </div>
            </td>
            @endforeach
        </tr>
    </table>
</body>

</html>