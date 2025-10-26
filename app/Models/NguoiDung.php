<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    use HasFactory;

    protected $table = 'nguoi_dung'; // tên bảng
    protected $fillable = ['ten_dang_nhap', 'mat_khau']; // cột cho phép ghi
}