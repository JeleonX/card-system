<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; use Illuminate\Support\Facades\DB; class CreateProductsTable extends Migration { public function up() { Schema::create('products', function (Blueprint $sp0058d3) { $sp0058d3->increments('id'); $sp0058d3->integer('user_id')->index(); $sp0058d3->integer('category_id')->index(); $sp0058d3->string('name'); $sp0058d3->longText('description'); $sp0058d3->integer('sort')->default(1000); $sp0058d3->integer('buy_min')->default(1); $sp0058d3->integer('buy_max')->default(10); $sp0058d3->integer('count_sold')->default(0); $sp0058d3->integer('count_all')->default(0); $sp0058d3->integer('count_warn')->default(0); $sp0058d3->boolean('support_coupon')->default(false); $sp0058d3->string('password')->nullable(); $sp0058d3->boolean('password_open')->default(false); $sp0058d3->integer('cost')->default(0); $sp0058d3->integer('price'); $sp0058d3->text('price_whole')->nullable(); $sp0058d3->text('instructions')->nullable(); $sp0058d3->text('fields')->nullable(); $sp0058d3->boolean('enabled'); $sp0058d3->tinyInteger('inventory')->default(\App\User::INVENTORY_AUTO); $sp0058d3->tinyInteger('fee_type')->default(\App\User::FEE_TYPE_AUTO); $sp0058d3->tinyInteger('delivery')->default(\App\Product::DELIVERY_AUTO); $sp0058d3->timestamps(); }); } public function down() { Schema::dropIfExists('goods'); } }