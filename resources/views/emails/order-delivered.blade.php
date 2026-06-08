<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:sans-serif;background:#f9f9f9;margin:0;padding:0}.wrap{max-width:600px;margin:40px auto;background:#fff;border-radius:8px;overflow:hidden}.header{background:#065f46;padding:24px 32px;color:#fff}.header h1{margin:0;font-size:22px}.body{padding:32px}.footer{padding:16px 32px;font-size:12px;color:#999;border-top:1px solid #eee}</style>
</head>
<body>
<div class="wrap">
  <div class="header"><h1>Order Delivered! How Was It? ⭐</h1></div>
  <div class="body">
    <p>Hi {{ $order->customerDisplayName() }},</p>
    <p>Your order <strong>{{ $order->order_number }}</strong> has been delivered. We hope you love your IPure Herbs products!</p>
    <p>We'd love to hear your feedback. Leaving a review helps other customers and takes just 30 seconds.</p>
    <p style="text-align:center;margin:32px 0">
      <a href="{{ config('app.url') }}/orders/{{ $order->order_number }}" style="background:#065f46;color:#fff;padding:12px 28px;border-radius:6px;text-decoration:none;font-weight:600">Leave a Review</a>
    </p>
    <p>Warm regards,<br>The IPure Herbs Team</p>
  </div>
  <div class="footer">© {{ date('Y') }} IPure Herbs. Order # {{ $order->order_number }}</div>
</div>
</body>
</html>
