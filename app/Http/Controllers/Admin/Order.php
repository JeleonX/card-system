<?php
namespace App\Http\Controllers\Admin; use App\Library\FundHelper; use App\Library\Helper; use Carbon\Carbon; use Illuminate\Database\Eloquent\Relations\Relation; use Illuminate\Http\Request; use App\Http\Controllers\Controller; use App\Library\Response; use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Log; class Order extends Controller { public function delete(Request $spfb41ce) { $this->validate($spfb41ce, array('ids' => 'required|string', 'income' => 'required|integer', 'balance' => 'required|integer')); $sp87851e = $spfb41ce->post('ids'); $spdb9bbf = (int) $spfb41ce->post('income'); $spb7a3e8 = (int) $spfb41ce->post('balance'); \App\Order::whereIn('id', explode(',', $sp87851e))->chunk(100, function ($sp1c64e3) use($spdb9bbf, $spb7a3e8) { foreach ($sp1c64e3 as $spbaa1fa) { $spbaa1fa->cards()->detach(); try { if ($spdb9bbf) { $spbaa1fa->fundRecord()->delete(); } if ($spb7a3e8) { $sp836da4 = \App\User::lockForUpdate()->firstOrFail(); $sp836da4->m_all -= $spbaa1fa->income; $sp836da4->saveOrFail(); } $spbaa1fa->delete(); } catch (\Exception $sp4b79b8) { } } }); return Response::success(); } function freeze(Request $spfb41ce) { $this->validate($spfb41ce, array('ids' => 'required|string')); $sp87851e = explode(',', $spfb41ce->post('ids')); $sp8560b4 = $spfb41ce->post('reason'); $sp2150fd = 0; $sp865f11 = 0; foreach ($sp87851e as $sp406042) { $sp2150fd++; if (FundHelper::orderFreeze($sp406042, $sp8560b4)) { $sp865f11++; } } return Response::success(array($sp2150fd, $sp865f11)); } function unfreeze(Request $spfb41ce) { $this->validate($spfb41ce, array('ids' => 'required|string')); $sp87851e = explode(',', $spfb41ce->post('ids')); $sp2150fd = 0; $sp865f11 = 0; $sp0b0163 = \App\Order::STATUS_FROZEN; foreach ($sp87851e as $sp406042) { $sp2150fd++; if (FundHelper::orderUnfreeze($sp406042, '后台操作', null, $sp0b0163)) { $sp865f11++; } } return Response::success(array($sp2150fd, $sp865f11, $sp0b0163)); } function set_paid(Request $spfb41ce) { $this->validate($spfb41ce, array('id' => 'required|integer')); $sp1e9761 = $spfb41ce->post('id', ''); $spd94f0a = $spfb41ce->post('trade_no', ''); if (strlen($spd94f0a) < 1) { return Response::forbidden('请输入支付系统内单号'); } $spbaa1fa = \App\Order::findOrFail($sp1e9761); if ($spbaa1fa->status !== \App\Order::STATUS_UNPAY) { return Response::forbidden('只能操作未支付订单'); } $spd91b14 = 'Admin.SetPaid'; $sp6fdb49 = $spbaa1fa->order_no; $sp06731b = $spbaa1fa->paid; try { Log::debug($spd91b14 . " shipOrder start, order_no: {$sp6fdb49}, amount: {$sp06731b}, trade_no: {$spd94f0a}"); (new \App\Http\Controllers\Shop\Pay())->shipOrder($spfb41ce, $sp6fdb49, $sp06731b, $spd94f0a); Log::debug($spd91b14 . ' shipOrder end, order_no: ' . $sp6fdb49); $sp865f11 = true; $sp4c4581 = '发货成功'; } catch (\Exception $sp4b79b8) { $sp865f11 = false; $sp4c4581 = $sp4b79b8->getMessage(); Log::error($spd91b14 . ' shipOrder Exception: ' . $sp4b79b8->getMessage()); } $spbaa1fa = \App\Order::with(array('pay' => function (Relation $sp61dd0f) { $sp61dd0f->select(array('id', 'name')); }, 'card_orders.card' => function (Relation $sp61dd0f) { $sp61dd0f->select(array('id', 'card')); }))->findOrFail($sp1e9761); if ($spbaa1fa->status === \App\Order::STATUS_PAID) { if ($spbaa1fa->product->delivery === \App\Product::DELIVERY_MANUAL) { $sp865f11 = true; $sp4c4581 = '已标记为付款成功<br>当前商品为手动发货商品, 请手动进行发货。'; } else { $sp865f11 = false; $sp4c4581 = '已标记为付款成功, <br>但是买家库存不足, 发货失败, 请稍后尝试手动发货。'; } } return Response::success(array('code' => $sp865f11 ? 0 : -1, 'msg' => $sp4c4581, 'order' => $spbaa1fa)); } }