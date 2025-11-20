<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @extends('fw')
    <title>Document</title>
</head>

<table collapse="1" style="text-align: center;width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        border: 1px solid;
        border-collapse: collapse;">
    <thead>
        <tr style="border: 1px solid;">
            <th style="border: 1px solid;">Tên Máy</th>
            <th style="border: 1px solid;">Tổng thiết bị</th>
            <th style="border: 1px solid;">Còn Hạn (> 30 ngày)</th>
            <th style="border: 1px solid;">Sắp hết hạn (< 30 ngày)</th>
        </tr>
    </thead>
    <tbody>
        <tr style="border: 1px solid;">
            <td style="border: 1px solid;"><b>B300</b></td>
            <td style="border: 1px solid;">{{ $data['tong'] }}</td>
            <td style="border: 1px solid;">{{ $data['con_han_tong'] }}</td>
            <td style="border: 1px solid;">{{ $data['sap_het_han_tong'] }}</td>
        </tr>
    </tbody>
</table>

<h3># Chi tiết</h3>

<table style="text-align: center;width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        border: 1px solid;
        border-collapse: collapse;">
    <thead>
        <tr style="border: 1px solid;">
            <th style="border: 1px solid;">Bộ phận</th>
            <th style="border: 1px solid;">Số lượng</th>
            <th style="border: 1px solid;">Còn hạn (> 30 ngày)</th>
            <th style="border: 1px solid;">Sắp hết hạn (< 30 ngày)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data['khoData'] as $kho)
        <tr style="border: 1px solid;">
            <td style="border: 1px solid;">{{ $kho['ten_kho'] }}</td>
            <td style="border: 1px solid;">{{ $kho['so_luong'] }}</td>
            <td style="border: 1px solid;">{{ $kho['con_han'] }}</td>
            <td style="border: 1px solid;">{{ $kho['sap_het_han'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


</body>

</html>