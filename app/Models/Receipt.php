<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'phieunhapkho';

    /**
     * The attributes that are mass assignable.
     *
     * @var <array></array>
     */
    protected $fillable = [
        // 'receipt_id', 'drug_id', 'SoLuong'
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

    public function receiptdetail()
    {
        return $this->hasMany(ReceiptDetail::class);
    }

    public function shippingdetail() {
        return $this->hasOne(ShippingDetail::class);
    }

    // public static function boot()
    // {
    //     parent::boot();
    //     parent::deleting(function ($receipt) { // before delete() method call this
    //         $receipt->receiptdetail()->delete();
    //         $receipt->shippingdetail()->delete();
    //         // do the rest of the cleanup...
    //     });
    // }
}
