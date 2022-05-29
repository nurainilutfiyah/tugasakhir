<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    public $table = "laporans";
    public $timestamps = false;
    protected $filliable = [
        'judul',
        'id_dinas',
        'id_perusahaan',
        'file',
        'tanggal',
        'id_status',
        'keterangan',
    ];

     public function get_status()
    {
        return $this->belongsTo(Status_laporan::class, 'id_status', 'id');
    }

     public function get_user()
    {
        return $this->belongsTo(User::class, 'id_dinas', 'id');
    }

     public function get_perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id');
    }
}
