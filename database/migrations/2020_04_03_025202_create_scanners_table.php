<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scanners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('scan_url');
            $table->string('project_id');
            $table->string('scan_scanid');
            $table->string('vul_status')->nullable();
            $table->string('total_vul')->nullable();
            $table->string('high_vul')->nullable();
            $table->string('medium_vul')->nullable();
            $table->string('low_vul')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `scanners` CHANGE `id` `id` BINARY(16)  NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scanners');
    }
}
