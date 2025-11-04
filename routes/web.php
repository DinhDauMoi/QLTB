<?php

use App\Http\Controllers\QuanLyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', [PrinterController::class, 'dang_nhap'])->name('dang_nhap')->middleware('guest');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get("/dang_nhap", [QuanLyController::class, 'Dang_Nhap'])->name('dang_nhap');
Route::post("/xu_ly_dang_nhap", [QuanLyController::class, 'Xu_Ly_Dang_Nhap'])->name('xu_lu_dang_nhap');
Route::get("/dang_xuat", [QuanLyController::class, 'Dang_xuat'])->name('dang_xuat');

Route::get("/",[QuanLyController::class,'Trang_Chu'])->name('trang_chu')->middleware('checkLogin');
Route::get("/chi_tiet_thiet_bi/{id}", [QuanLyController::class, 'Chi_Tiet_Thiet_Bi'])->name('chi_tiet')->middleware('checkLogin');
Route::post("/them_thiet_bi", [QuanLyController::class, 'Them_Thiet_Bi'])->name('them_thiet_bi')->middleware('checkLogin');
Route::post("/thay_do_thiet_bi/{id}", [QuanLyController::class, 'Thay_Doi_Thiet_Bi'])->name('thay_doi_thiet_bi')->middleware('checkLogin');
Route::get("/xoa_thiet_bi/{id}", [QuanLyController::class, 'Xoa_Thiet_Bi'])->name('xoa_thiet_bi')->middleware('checkLogin');
Route::get('/generate-pdf/{id}', [QuanLyController::class, 'generatePDF'])->name('generate_pdf')->middleware('checkLogin');
Route::get('/tai_all_qr', [QuanLyController::class, 'In_All_QR'])->name('in_all_qr')->middleware('checkLogin');
Route::post('/import', [QuanLyController::class, 'import'])->name('import')->middleware('checkLogin');
Route::get('/export', [QuanLyController::class, 'exportExcel'])->name('export')->middleware('checkLogin');
Route::get('/kiem_tra_thiet_bi/{id}', [QuanLyController::class, 'kiemTra'])->middleware('checkLogin');
Route::get('/in_qr_theo_id', [QuanLyController::class, 'In_QR_Khoang'])->name('in_qr_theo_id')->middleware('checkLogin');

//if0_40257662 p4qWTBvopGU5w