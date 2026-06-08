<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:sans-serif;background:#f9f9f9;margin:0;padding:0}.wrap{max-width:600px;margin:40px auto;background:#fff;border-radius:8px;overflow:hidden}.header{background:#065f46;padding:24px 32px;color:#fff}.header h1{margin:0;font-size:22px}.body{padding:32px}.footer{padding:16px 32px;font-size:12px;color:#999;border-top:1px solid #eee}</style>
</head>
<body>
<div class="wrap">
  <div class="header"><h1>Your Order Is On Its Way! 🚚</h1></div>
  <div class="body">
    <p>Hi {{ $order->customerDisplayName() }},</p>
    <p>Great news! Your order <strong>{{ $order->order_number }}</strong> has been shipped.</p>
    @if($trackingNumber)
    <div style="background:#f0fdf4;border:1px solid #86efac;padding:16px;border-radius:6px;margin:20px 0">
      <strong>Tracking Number:</strong> {{ $trackingNumber }}
    </div>
    @endif
    <p>Shipping to: <strong>{{ $order->shipping_name }}</strong>, {{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
    <p>Estimated delivery: <strong>3–7 business days</strong></p>
    <p>Warm regards,<br>The IPure Herbs Team</p>
  </div>
  <div class="footer">© {{ date('Y') }} IPure Herbs. Order # {{ $order->order_number }}</div>
</div>
</body>
</html>
