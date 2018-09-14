<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_statuses', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('branch_id');
			$table->integer('shipment_id');
			$table->string('status');
			$table->text('remark')->nullable();
			$table->string('location')->nullable();
			$table->softDeletes();
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
        Schema::dropIfExists('shipment_statuses');
    }
}
