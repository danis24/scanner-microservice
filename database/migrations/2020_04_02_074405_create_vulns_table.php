<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVulnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vulns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('project_id');
            $table->string('scan_id');
            $table->string('vuln_id');
            $table->string('confidence')->nullable();
            $table->string('wascid')->nullable();
            $table->string('cweid')->nullable();
            $table->string('risk')->nullable();
            $table->string('reference')->nullable();
            $table->string('name')->nullable();
            $table->longText('solution')->nullable();
            $table->longText('param')->nullable();
            $table->longText('evidence')->nullable();
            $table->string('sourceid')->nullable();
            $table->string('pluginId')->nullable();
            $table->string('other')->nullable();
            $table->string('attack')->nullable();
            $table->string('messageId')->nullable();
            $table->string('method')->nullable();
            $table->string('alert')->nullable();
            $table->string('ids')->nullable();
            $table->string('description')->nullable();
            $table->string('req_res')->nullable();
            $table->string('note')->nullable();
            $table->string('rtt')->nullable();
            $table->string('tags')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('responseHeader')->nullable();
            $table->string('requestBody')->nullable();
            $table->string('responseBody')->nullable();
            $table->string('requestHeader')->nullable();
            $table->string('cookieParams')->nullable();
            $table->string('res_type')->nullable();
            $table->string('res_id')->nullable();
            $table->string('date_time')->nullable();
            $table->string('false_positive')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `vulns` CHANGE `id` `id` BINARY(16)  NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vulns');
    }
}
