<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kho extends Model
{
    use HasFactory;

    protected $table = 'kho';
    protected $fillable = ['ten_kho'];

    // Một kho có nhiều thiết bị
    public function thietBi()
    {
        return $this->hasMany(ThietBi::class, 'kho_id');
    }
    public function lichSu()
    {
        return $this->hasMany(LichSu::class, 'kho_id');
    }
}
