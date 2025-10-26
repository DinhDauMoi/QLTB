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
Route::get("/",[QuanLyController::class,'Trang_Chu'])->name('trang_chu');
Route::get("/chi_tiet_thiet_bi/{id}", [QuanLyController::class, 'Chi_Tiet_Thiet_Bi'])->name('chi_tiet');
Route::post("/them_thiet_bi", [QuanLyController::class, 'Them_Thiet_Bi'])->name('them_thiet_bi');
Route::post("/thay_do_thiet_bi/{id}", [QuanLyController::class, 'Thay_Doi_Thiet_Bi'])->name('thay_doi_thiet_bi');
Route::get("/xoa_thiet_bi/{id}", [QuanLyController::class, 'Xoa_Thiet_Bi'])->name('xoa_thiet_bi');
Route::get('/generate-pdf/{id}', [QuanLyController::class, 'generatePDF'])->name('generate_pdf');
Route::get('/tai_all_qr', [QuanLyController::class, 'In_All_QR'])->name('in_all_qr');
Route::post('/import', [QuanLyController::class, 'import'])->name('import');
Route::get('/export', [QuanLyController::class, 'exportExcel'])->name('export');
// postgresql://postgres:[YOUR_PASSWORD]@db.poftvknuyyheeiywkkto.supabase.co:5432/postgres