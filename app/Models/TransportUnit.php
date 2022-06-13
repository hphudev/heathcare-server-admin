<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportUnit extends Model
{
    use HasFactory;
     /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table= 'donvivanchuyen';

    /**
     * The attributes that are mass assignable.
     *
     * @var <array></array>
     */
    protected $fillable = [
        'TenDonViVanChuyen'
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

    public function shippingdetail() {
        return $this->hasMany(ShippingDetail::class);
    }
}
