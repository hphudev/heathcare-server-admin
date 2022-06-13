<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('hoadon', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->date('NgayThanhToan');
            $table->string('TinhTrang')->default("Đang chờ");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("hoadon");
    }
}
