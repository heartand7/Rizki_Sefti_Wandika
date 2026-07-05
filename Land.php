<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Land extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemilik',
        'nama_lahan',
        'lokasi_alamat',
        'deskripsi',
        'luas_meter',
        'keliling_meter',
        'koordinat_polygon',
    ];

    protected $casts = [
        'koordinat_polygon' => 'array',
        'luas_meter' => 'float',
        'keliling_meter' => 'float',
    ];
}