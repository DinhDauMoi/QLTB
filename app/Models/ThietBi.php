<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThietBi extends Model
{
    use HasFactory;

    protected $table = 'thiet_bi';
    protected $fillable = ['kho_id', 'ngay', 'imei'];

    // Mỗi thiết bị thuộc về một kho
    public function kho()
    {
        return $this->belongsTo(Kho::class, 'kho_id');
    }
}