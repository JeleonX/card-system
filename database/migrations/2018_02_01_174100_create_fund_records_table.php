<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class CreateFundRecordsTable extends Migration { public function up() { Schema::create('fund_records', function (Blueprint $sp0058d3) { $sp0058d3->increments('id'); $sp0058d3->integer('user_id')->index(); $sp0058d3->integer('type')->default(\App\FundRecord::TYPE_OUT); $sp0058d3->integer('amount'); $sp0058d3->integer('balance')->default(0); $sp0058d3->integer('order_id')->nullable(); $sp0058d3->string('withdraw_id')->nullable(); $sp0058d3->string('remark')->nullable(); $sp0058d3->timestamps(); }); DB::unprepared('ALTER TABLE `fund_records` CHANGE COLUMN `created_at` `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP;'); } public function down() { Schema::dropIfExists('fund_records'); } }