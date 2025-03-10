<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $table = 'perizinan';
    protected $guarded = ['id'];
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'keterangan',
        'keterangan_ditolak',
        'bukti_path',
        'status',
        'creted_at'
    ];

    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
}
