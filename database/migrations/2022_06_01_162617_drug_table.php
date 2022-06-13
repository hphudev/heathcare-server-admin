<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DrugTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thuoc', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('drug_group_id');
            $table->string('TenThuoc', 100);
            $table->string('HinhAnh')->nullable();
            $table->date('HanSuDung');
            $table->bigInteger('GiaNhap');
            $table->bigInteger('GiaBan');
            $table->double('ChietKhau');
            $table->bigInteger('product_unit_id');
            $table->bigInteger('producer_unit_id');
            $table->string('MoTa');
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
        Schema::dropIfExists('thuoc');
    }
}
