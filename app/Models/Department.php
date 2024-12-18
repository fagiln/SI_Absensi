<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'department';
    protected $guarded = 'id';
    protected $fillable = [

        'kode_departemen',
        'nama_departemen'
    ];
public function karyawan(){
    return $this->hasMany(User::class);
}
    
}
