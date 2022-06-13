<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductUnit extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table= 'donvitinh';

    /**
     * The attributes that are mass assignable.
     *
     * @var <array></array>
     */
    protected $fillable = [
        'TenDonViTinh'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function drug() {
        return $this->hasMany(Drug::class);
    }
}
