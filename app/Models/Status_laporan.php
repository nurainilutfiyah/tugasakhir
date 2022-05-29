<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status_laporan extends Model
{
    use HasFactory;
     public $table = "status_laporans";
        public $timestamps = false;
        protected $fillable = [
        'status',
    ];

   public function get_laporan()
    {
        return $this->hasMany(Laporan::class, 'id_status', 'id');
    }

}
