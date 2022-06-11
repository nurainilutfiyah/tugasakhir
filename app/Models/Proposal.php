<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

       public $table = "proposals";
        // public $timestamps = false;
        protected $fillable = [
        'judul',
        'id_dinas',
        'id_perusahaan',
        'file',
        'tanggal',
        'id_status',
    ];

    public function get_status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id');
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
