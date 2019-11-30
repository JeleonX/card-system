<?php
namespace App\Http\Controllers\Shop; use App\Category; use App\Product; use App\Library\Response; use Carbon\Carbon; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Coupon extends Controller { function info(Request $spfb41ce) { $spf9ba01 = (int) $spfb41ce->post('category_id', -1); $sp2fece4 = (int) $spfb41ce->post('product_id', -1); $spe30c7d = $spfb41ce->post('coupon'); if (!$spe30c7d) { return Response::fail('请输入优惠券'); } if ($spf9ba01 > 0) { $sp6ebb48 = Category::findOrFail($spf9ba01); $sp1e2a07 = $sp6ebb48->user_id; } elseif ($sp2fece4 > 0) { $spae5d03 = Product::findOrFail($sp2fece4); $sp1e2a07 = $spae5d03->user_id; } else { return Response::fail('请先选择分类或商品'); } $sp832c5d = \App\Coupon::where('user_id', $sp1e2a07)->where('coupon', $spe30c7d)->where('expire_at', '>', Carbon::now())->whereRaw('`count_used`<`count_all`')->get(); foreach ($sp832c5d as $spe30c7d) { if ($spe30c7d->category_id === -1 || $spe30c7d->category_id === $spf9ba01 && ($spe30c7d->product_id === -1 || $spe30c7d->product_id === $sp2fece4)) { $spe30c7d->setVisible(array('discount_type', 'discount_val')); return Response::success($spe30c7d); } } return Response::fail('您输入的优惠券信息无效<br>如果没有优惠券请不要填写'); } }