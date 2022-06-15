<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table= 'thuoc';

    /**
     * The attributes that are mass assignable.
     *
     * @var <array></array>
     */
    protected $fillable = [
        // 'TenThuoc', 'producer_unit_id', 'drug_group_id'
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

    public function druggroup() {
        return $this->belongsTo(DrugGroup::class);
    }

    public function productunit() {
        return $this->belongsTo(ProductUnit::class);
    }

    public function producerunit() {
        return $this->belongsTo(ProducerUnit::class);
    }

    public function receiptdetail() {
        return $this->belongsTo(ReceiptDetail::class);
    }

    public static function boot()
    {
        parent::boot();
        parent::deleting(function ($drug) { // before delete() method call this
            $drug->receiptdetail()->delete();
        });
    }
}
