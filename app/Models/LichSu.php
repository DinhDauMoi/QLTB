<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSu extends Model
{
    use HasFactory;

    protected $table = 'lich_su';

    protected $fillable = [
        'thiet_bi_id',
        'kho_id',
        'imei',
        'ngay',
        'hanh_dong'
    ];

    // Quan hệ với thiết bị
    public function thietBi()
    {
        return $this->belongsTo(ThietBi::class, 'thiet_bi_id');
    }
    public function lskho()
    {
        return $this->belongsTo(Kho::class, 'kho_id');
    }
}
