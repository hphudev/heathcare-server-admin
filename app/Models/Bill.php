<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hoadon';

    /**
     * The attributes that are mass assignable.
     *
     * @var <array></array>
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function detailbill()
    {
        return $this->hasMany(DetailBill::class);
    }

    public function shippingdetail() {
        return $this->hasOne(ShippingDetail::class);
    }

    public static function boot()
    {
        parent::boot();
        parent::deleting(function ($bill) { // before delete() method call this
            $bill->detailbill()->delete();
            $bill->shippingdetail()->delete();
        });
    }
}
