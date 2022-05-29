<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
     public $table = "statuses";
        public $timestamps = false;
        protected $fillable = [
        'status',
    ];

   public function get_proposal()
    {
        return $this->hasMany(Proposal::class, 'id_status', 'id');
    }

}
