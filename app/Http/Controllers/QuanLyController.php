<?php

namespace App\Http\Controllers;

use App\Exports\ThietBiExport;
use App\Imports\ThietBiImport;
use App\Models\Kho;
use App\Models\LichSu;
use App\Models\ThietBi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class QuanLyController extends Controller
{
    public function Trang_Chu()
    {
        $so_luong = ThietBi::count();
        $thiet_bi = ThietBi::all();
        $kho = Kho::all();
        return view('trang_chu', ['kho' => $kho, 'thiet_bi' => $thiet_bi, 'so_luong' => $so_luong]);
    }
    public function Chi_Tiet_Thiet_Bi($id)
    {
        $so_luong = ThietBi::count();
        $lich_su = LichSu::where('thiet_bi_id', $id)->orderBy('id', 'desc')->get();
        $chi_tiet = ThietBi::where('id', $id)->first();
        $kho = Kho::all();
        return view('chi_tiet_thiet_bi', ['chi_tiet' => $chi_tiet, 'kho' => $kho, 'lich_su' => $lich_su,'so_luong'=>$so_luong]);
    }
    public function Dang_Nhap()
    {
        return view('dang_nhap');
    }
    public function Them_Thiet_Bi(Request $request)
    {
        if ($request->kho == "" || $request->imei == "" || $request->ngay == "") {
            return back()->with('error', 'Vui lòng điền đầy đủ thông tin');
        }
        $thiet_bi = ThietBi::where('imei', $request->imei)->first();
        if (!$thiet_bi) {
            $TB = ThietBi::create([
                'kho_id' => $request->kho,
                'ngay' => $request->ngay,
                'imei' => $request->imei,
            ]);
            LichSu::create([
                'thiet_bi_id' => $TB->id,
                'kho_id' => $request->kho,
                'imei' => $request->imei,
                'ngay' => Carbon::now(),
            ]);
            return back()->with('success', "");
        } else {
            return back()->with('error', 'Đã tồn tại Imei này');
        }
    }
    public function Xoa_Thiet_Bi($id)
    {
        ThietBi::where('id', $id)->delete();
        return redirect('/')->with('success', "");
    }
    public function Thay_Doi_Thiet_Bi(Request $request, $id)
    {
        // Lấy bản ghi thiết bị hiện tại
        $thietBi = ThietBi::findOrFail($id);

        // So sánh dữ liệu từ request với dữ liệu hiện tại
        $data = [
            'kho_id' => $request->kho,
            'ngay' => $request->ngay,
            'imei' => $request->imei,
        ];

        // Kiểm tra xem có thay đổi nào không
        $hasChanges = false;
        foreach ($data as $key => $value) {
            if ($thietBi->$key != $value) {
                $hasChanges = true;
                break;
            }
        }

        // Nếu không có thay đổi, trả về mà không làm gì
        if (!$hasChanges) {
            return back()->with('error', 'Không có thay đổi nào được thực hiện');
        }
        $imei = ThietBi::where('imei', $request->imei)
            ->where('id', '!=', $id)
            ->first();
        if (!$imei) {
            ThietBi::where('id', $id)->update([
                'kho_id' => $request->kho,
                'ngay' => $request->ngay,
                'imei' => $request->imei,
            ]);
            LichSu::create([
                'thiet_bi_id' => $request->id,
                'kho_id' => $request->kho,
                'imei' => $request->imei,
                'ngay' => Carbon::now(),
            ]);
            return back()->with('success', "");
        } else {
            return back()->with('error', 'Đã tồn tại Imei này');
        }
    }
    public function generatePDF($id)
    {
        $get_data_qr = ThietBi::where('id', $id)->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf_qr', [
            'get_data_qr' => $get_data_qr,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans', // Font hỗ trợ tiếng Việt
        ]);

        return $pdf->download('qr-thiet-bi.pdf');
    }

    public function In_All_QR()
    {
        $thiet_bi = ThietBi::all();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf_qr_all', [
            'thiet_bi' => $thiet_bi,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans', // font hỗ trợ tiếng Việt
        ]);
        return $pdf->download('tat-ca-qr-thiet-bi.pdf');
    }
    public function import(Request $request)
    {
        // dd($request->file('file'));
        // Validate file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        $path = $request->file('file')->getRealPath();
        Excel::import(new ThietBiImport, $path);
        return back()->with('success', "");;
    }
    public function exportExcel()
    {
        // Tên file khi tải xuống
        $fileName = 'danh_sach_thiet_bi_' . time() . '.xlsx';

        return Excel::download(new ThietBiExport, $fileName);
    }
}
