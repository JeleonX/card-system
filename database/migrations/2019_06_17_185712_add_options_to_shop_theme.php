<?php
use Illuminate\Support\Facades\Schema; use Illuminate\Database\Schema\Blueprint; use Illuminate\Database\Migrations\Migration; class AddOptionsToShopTheme extends Migration { public function up() { if (!Schema::hasColumn('shop_themes', 'options')) { Schema::table('shop_themes', function (Blueprint $sp0058d3) { $sp0058d3->text('options')->nullable()->after('description'); }); } } public function down() { } }