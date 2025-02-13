<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
    <title>微信支付</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="renderer" content="webkit">
    <link type="text/css" href="/plugins/css/wx_qr.css" rel="stylesheet">
    <script type="text/javascript" src="//ossweb-img.qq.com/images/js/jquery/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/plugins/js/qrcode.min.js"></script>
</head>
<body>
<div class="body">
    <h1 class="mod-title">
        <span class="ico-wechat"></span><span class="text">微信支付</span>
    </h1>
    <div class="mod-ct">
        <div class="order"></div>
        <div class="amount">￥{{ sprintf('%0.2f',$amount/100) }}</div>
        <div class="qr-image" id="qrcode"></div>
        <div id="open-app-container">
            <span style="display: block;margin-top: 24px">请截屏此界面或保存二维码，打开微信扫码，选择相册图片</span>
            <a style="padding:6px 34px;border:1px solid #e5e5e5;display: inline-block;margin-top: 8px" id="open-app"
               href="weixin://">点击打开微信</a>
        </div>
        <div class="detail" id="orderDetail">
            <dl class="detail-ct" style="display: none;">
                <dt>商品</dt>
                <dd id="storeName">{{ $name }}</dd>
                <!--dt>说明</dt>
                <dd id="productName">用户充值</dd-->
                <dt>订单号</dt>
                <dd id="billId">{{ $id }}</dd>
                <dt>时间</dt>
                <dd id="createTime"><?php echo date('Y-m-d H:i:s')?></dd>
            </dl>
            <a href="javascript:void(0)" class="arrow"><i class="ico-arrow"></i></a>
        </div>
        <div class="tip">
            <span class="dec dec-left"></span>
            <span class="dec dec-right"></span>
            <div class="ico-scan"></div>
            <div class="tip-text">
                <p>请使用微信扫一扫</p>
                <p>扫描二维码完成支付</p>
            </div>
        </div>
        <div class="tip-text">
        </div>
    </div>
    <div class="foot">
        <div class="inner">
            <!-- <p><?php echo SYS_NAME ?>, 有疑问请联系客服</p> -->
        </div>
    </div>
</div>

<script>
    var code_url = decodeURIComponent('{!! urlencode($qrcode) !!}');

    if (navigator.userAgent.match(/MicroMessenger/i) !== null && code_url.indexOf('http') === 0) {
        // 当前在微信内, URL直接跳转
        location.href = code_url;
    } else if (code_url === 'query') {
        // 支付成功跳转页面, 用这个页面来轮训
        $('.tip>.ico-scan').remove();
        $('.tip>.tip-text').html('<p>订单已支付, 正在处理...</p>');
        $('#open-app-container').html('<p></p>');
        $('#orderDetail>.detail-ct').show();
        $('#orderDetail>.arrow').remove();
    }
    else if (navigator.userAgent.match(/MicroMessenger/i) !== null && code_url.indexOf('weixin://') === -1) {
        // 当前在微信内, code_url是JSAPI参数
        $('.tip>.ico-scan').remove();
        $('.tip>.tip-text').html('<p>请在弹出的窗口完成支付</p>');
        $('#open-app-container').html('<p></p>');
        $('#orderDetail>.detail-ct').show();
        $('#orderDetail>.arrow').remove();

        function onBridgeReady() {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest', JSON.parse(code_url),
                function (res) {
                    if (res.err_msg === 'get_brand_wcpay_request:fail') {
                        $('.tip>.tip-text').html('<p>支付失败</p><p>' + res.err_desc + '</p>');
                        alert(res.err_desc)
                    } else if (res.err_msg === 'get_brand_wcpay_request:ok') {
                        //使用以上方式判断前端返回,微信团队郑重提示：
                        //res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
                        $('.tip>.tip-text').html('<p>订单已支付, 正在处理...</p>');
                    }
                });
        }

        if (typeof WeixinJSBridge === 'undefined') {
            if (document.addEventListener) {
                document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
            } else if (document.attachEvent) {
                document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
            }
        } else {
            onBridgeReady();
        }
    } else {
        // 普通扫码
        new QRCode('qrcode', {
            text: code_url,
            width: 230,
            height: 230,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
            title: '请使用微信扫一扫'
        });
    }


    // 订单详情
    var orderDetail = $('#orderDetail');
    orderDetail.find('.arrow').click(function (event) {
        if (orderDetail.hasClass('detail-open')) {
            orderDetail.find('.detail-ct').slideUp(500, function () {
                orderDetail.removeClass('detail-open');
            });
        } else {
            orderDetail.find('.detail-ct').slideDown(500, function () {
                orderDetail.addClass('detail-open');
            });
        }
    });

    $(document).ready(function () {
        var time = 4000, interval;

        function getData() {
            $.post('/api/qrcode/query/{!! $pay_id !!}', {
                    id: '{!! $id !!}',
                    t: Math.random()
                },
                function (r) {
                    clearInterval(interval);
                    $('.qr-image').remove();
                    $('.tip').html('<p style="font-size:24px">已支付，正在处理...</p>');
                    window.location = r.data;
                }, 'json');
        }

        (function run() {
            interval = setInterval(getData, time);
        })();
    });

    // call app
    if (navigator.userAgent.match(/(iPhone|iPod|Android|ios|SymbianOS)/i) !== null) {
        // 想跳转微信, 真的跳不过去啊, 傻吊微信
    } else {
        $('#open-app-container').hide();
    }
</script>
</body>
</html>
