<?php

namespace App\Http\Controllers;

use App\Exports\ThietBiExport;
use App\Imports\ThietBiImport;
use App\Mail\WeeklyReportMail;
use App\Models\Kho;
use App\Models\LichSu;
use App\Models\ThietBi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        return view('chi_tiet_thiet_bi', ['chi_tiet' => $chi_tiet, 'kho' => $kho, 'lich_su' => $lich_su, 'so_luong' => $so_luong]);
    }
    public function Dang_Nhap()
    {
        return view('dang_nhap');
    }
    public function Xu_Ly_Dang_Nhap(Request $request)
    {
        $username = $request->input('ten_dang_nhap');
        $password = $request->input('mat_khau');

        $validUser = config('login.user');
        $hashedPassword = config('login.password');

        if ($username == $validUser && $password == $hashedPassword) {
            session(['user' => $username]);
            return redirect('/');
        }
        return back()->with('error', 'Đăng nhập thất bại !!!');
    }
    public function Dang_xuat()
    {
        session()->forget('user');
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ThietBiImport, $request->file('file')); // ✅

        return back()->with('success', 'Import thành công!');
    }
    public function exportExcel()
    {
        // Tên file khi tải xuống
        $fileName = 'danh_sach_thiet_bi_' . time() . '.xlsx';

        return Excel::download(new ThietBiExport, $fileName);
    }
    public function kiemTra($id)
    {
        $exists = ThietBi::where('id', $id)->exists();
        return response()->json(['exists' => $exists]);
    }
    public function In_QR_Khoang(Request $request)
    {
        $startId = $request->start_id;
        $endId = $request->end_id;

        // Kiểm tra hợp lệ
        if (!$startId || !$endId || $startId > $endId) {
            return back()->with('error', 'Khoảng cách chọn in không hợp lệ!');
        }

        // Lọc thiết bị theo ID trong khoảng
        $thiet_bi = ThietBi::whereBetween('id', [$startId, $endId])->get();

        if ($thiet_bi->isEmpty()) {
            return back()->with('error', 'Không có thiết bị nào trong khoảng ID này.');
        }

        // Xuất PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf_qr_all', [
            'thiet_bi' => $thiet_bi,
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);

        return $pdf->download("qr_tu_{$startId}_den_{$endId}.pdf");
    }
    public function noi_dung_mail()
    {
        return view('noi_dung_mail');
    }
    public function bao_cao_mail()
    {
        $tenKho = [
            1 => 'Kho 1',
            2 => 'Kho 2',
            3 => 'Kho 3',
            4 => 'LCNB',
            5 => 'Kiểm kê',
            6 => 'Điều Vận',
            7 => 'Vaccine',
            8 => 'Nhập',
        ];

        // Tổng số máy
        $tong = ThietBi::count();

        // Tổng còn hạn, tổng sắp hết hạn
        $con_han_tong = ThietBi::where('ngay', '>', now()->addDays(30))->count();

        $sap_het_han_tong = ThietBi::where('ngay', '<=', now()->addDays(30))->count();

        // Chi tiết từng kho
        $khoData = [];

        foreach ($tenKho as $id => $name) {

            $query = ThietBi::where('kho_id', $id);

            $khoData[] = [
                'ten_kho' => $name,
                'so_luong' => $query->count(),
                'con_han' => (clone $query)->where('ngay', '>', now()->addDays(30))->count(),
                'sap_het_han' => (clone $query)->where('ngay', '<=', now()->addDays(30))->count(),
            ];
        }

        $report = [
            'tong' => $tong,
            'con_han_tong' => $con_han_tong,
            'sap_het_han_tong' => $sap_het_han_tong,
            'khoData' => $khoData
        ];


        Mail::to([
            'dinhdaumoi22@gmail.com',
            'Phamtruc.1991@gmail.com'
        ])->send(new WeeklyReportMail($report));

        return redirect('/');
    }
}
