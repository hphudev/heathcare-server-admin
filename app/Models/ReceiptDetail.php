<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    use HasFactory;
    use HasFactory;
    /**
    * The table associated with the model.
    *
    * @var string
   */
   protected $table= 'ctphieunhap';

   /**
    * The attributes that are mass assignable.
    *
    * @var <array></array>
    */
   protected $fillable = [
       'receipt_id', 'drug_id', 'SoLuong', 'created_at', 'updated_at'
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
       return $this->hasOne(Drug::class);
   }

   public function receipt() {
       return $this->belongsTo(Receipt::class);
   }
}
