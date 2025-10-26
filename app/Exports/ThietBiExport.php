<?php

namespace App\Exports;

use App\Models\ThietBi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ThietBiExport implements FromCollection, WithHeadings, WithMapping
{
    private $count = 0; // Khởi tạo biến đếm STT

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Eager load mối quan hệ 'kho' để lấy tên kho
        return ThietBi::with('kho')->get();
    }

    /**
     * Định nghĩa tiêu đề cột (Hàng đầu tiên của Excel)
     * @return array
     */
    public function headings(): array
    {
        return [
            'STT',
            'Tên Máy',
            'Bộ Phận',
            'IMEI',
        ];
    }

    /**
     * Map dữ liệu từ mỗi model sang các cột trong Excel
     * @param mixed $thietBi
     * @return array
     */
    public function map($thietBi): array
    {
        // Tăng STT
        $this->count++;

        return [
            $this->count, // STT
            $thietBi->id, // ID
            $thietBi->kho->ten_kho ?? 'Không xác định', // Tên Kho (Giả định quan hệ là 'kho' và cột tên là 'ten_kho')
            $thietBi->imei, // IMEI
        ];
    }
}
