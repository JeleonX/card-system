<?php
use Illuminate\Database\Seeder; class CardsSeeder extends Seeder { public function run() { $spf36aa8 = \App\User::first()->id; \App\Card::insert(array(array('user_id' => $spf36aa8, 'product_id' => 1, 'card' => '11111', 'type' => \App\Card::TYPE_ONETIME, 'status' => \App\Card::STATUS_NORMAL, 'count_sold' => 0, 'count_all' => 1), array('user_id' => $spf36aa8, 'product_id' => 1, 'card' => '11112', 'type' => \App\Card::TYPE_ONETIME, 'status' => \App\Card::STATUS_SOLD, 'count_sold' => 1, 'count_all' => 1), array('user_id' => $spf36aa8, 'product_id' => 1, 'card' => '11113', 'type' => \App\Card::TYPE_ONETIME, 'status' => \App\Card::STATUS_NORMAL, 'count_sold' => 0, 'count_all' => 1), array('user_id' => $spf36aa8, 'product_id' => 2, 'card' => '123456', 'type' => \App\Card::TYPE_REPEAT, 'status' => \App\Card::STATUS_SOLD, 'count_sold' => 2, 'count_all' => 100))); } }