<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:sans-serif;background:#f9f9f9;margin:0;padding:0}.wrap{max-width:600px;margin:40px auto;background:#fff;border-radius:8px;overflow:hidden}.header{background:#065f46;padding:24px 32px;color:#fff}.header h1{margin:0;font-size:22px}.body{padding:32px}.footer{padding:16px 32px;font-size:12px;color:#999;border-top:1px solid #eee}</style>
</head>
<body>
<div class="wrap">
  <div class="header"><h1>Welcome to IPure Herbs!</h1></div>
  <div class="body">
    <p>Hi {{ $customer->name }},</p>
    <p>Thank you for creating an account with <strong>IPure Herbs</strong>. We're delighted to have you as part of our wellness community.</p>
    <p>As a welcome gift, your <strong>first order gets 20% off</strong> automatically at checkout — no coupon needed.</p>
    <p>Explore our range of Ayurvedic and herbal products:</p>
    <p style="text-align:center;margin:32px 0">
      <a href="{{ config('app.url') }}" style="background:#065f46;color:#fff;padding:12px 28px;border-radius:6px;text-decoration:none;font-weight:600">Shop Now</a>
    </p>
    <p>If you have any questions, just reply to this email.</p>
    <p>Warm regards,<br>The IPure Herbs Team</p>
  </div>
  <div class="footer">© {{ date('Y') }} IPure Herbs. All rights reserved.</div>
</div>
</body>
</html>
