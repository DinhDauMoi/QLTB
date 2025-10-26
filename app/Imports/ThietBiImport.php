<?php

namespace App\Imports;

use App\Models\ThietBi;
use App\Models\LichSu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
class ThietBiImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['imei']) || is_null($row['imei'])) {
            return null; // Trả về NULL để Laravel Excel bỏ qua hàng này
        }
        $excelSerialDate = $row['ngay']; // Đây là số 46296
        // Chuyển đổi số sê-ri Excel thành đối tượng DateTime
        $dateTimeObject = Date::excelToDateTimeObject($excelSerialDate);
        // Định dạng lại thành chuỗi 'YYYY-MM-DD' mà MySQL/DB hiểu
        $ngayHopLe = $dateTimeObject->format('Y-m-d');
        // Tạo bản ghi ThietBi
        $thietBi = ThietBi::create([
            'kho_id' => $row['kho_id'],
            'ngay' => $ngayHopLe,
            'imei' => $row['imei'],
        ]);

        // Tạo bản ghi LichSu
        LichSu::create([
            'thiet_bi_id' => $thietBi->id,
            'kho_id' => $row['kho_id'],
            'imei' => $row['imei'],
            'ngay' => Carbon::now(),
        ]);

        return $thietBi;
    }
    private function convertExcelDate($excelSerialDate)
    {
        // ... Logic chuyển đổi từ 46296 sang '2026-10-01'
        if (is_numeric($excelSerialDate) && $excelSerialDate > 1) {
             return Date::excelToDateTimeObject($excelSerialDate)->format('Y-m-d');
        }
        return $excelSerialDate;
    }
}
