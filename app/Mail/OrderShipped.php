<?php
namespace App\Mail; use Illuminate\Bus\Queueable; use Illuminate\Mail\Mailable; use Illuminate\Queue\SerializesModels; use Illuminate\Contracts\Queue\ShouldQueue; class OrderShipped extends Mailable { use Queueable, SerializesModels; public $order; public $card_msg; public $cards_txt; public function __construct($spbaa1fa, $spbdf06d, $sp8b716e) { $this->order = $spbaa1fa; $this->card_msg = $spbdf06d; $this->cards_txt = $sp8b716e; } public function build() { return $this->subject('订单提醒(#' . $this->order->order_no . ')-' . config('app.name'))->view('emails.order'); } }