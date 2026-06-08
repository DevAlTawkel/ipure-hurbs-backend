<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:sans-serif;background:#f9f9f9;margin:0;padding:0}.wrap{max-width:600px;margin:40px auto;background:#fff;border-radius:8px;overflow:hidden}.header{background:#065f46;padding:24px 32px;color:#fff}.header h1{margin:0;font-size:22px}.body{padding:32px}table{width:100%;border-collapse:collapse}td,th{padding:10px 8px;text-align:left;border-bottom:1px solid #eee}th{background:#f3f4f6;font-size:13px}.total-row td{font-weight:700;font-size:15px}.footer{padding:16px 32px;font-size:12px;color:#999;border-top:1px solid #eee}</style>
</head>
<body>
<div class="wrap">
  <div class="header"><h1>Order Confirmed ✓</h1></div>
  <div class="body">
    <p>Hi {{ $order->customerDisplayName() }},</p>
    <p>Your order <strong>{{ $order->order_number }}</strong> has been confirmed and is being prepared.</p>

    <h3 style="margin-top:28px">Order Summary</h3>
    <table>
      <thead><tr><th>Product</th><th>Qty</th><th>Price</th></tr></thead>
      <tbody>
        @foreach($order->items as $item)
        <tr>
          <td>{{ $item->product_name }}<br><small style="color:#6b7280">SKU: {{ $item->product_sku }}</small></td>
          <td>{{ $item->qty }}</td>
          <td>${{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr><td colspan="2">Subtotal</td><td>${{ number_format($order->subtotal, 2) }}</td></tr>
        @if($order->discount_amount > 0)
        <tr><td colspan="2">Discount ({{ $order->discount_reason }})</td><td style="color:#059669">-${{ number_format($order->discount_amount, 2) }}</td></tr>
        @endif
        <tr><td colspan="2">Shipping</td><td>{{ $order->shipping_charge > 0 ? '$'.number_format($order->shipping_charge,2) : 'FREE' }}</td></tr>
        <tr class="total-row"><td colspan="2">Total</td><td>${{ number_format($order->total, 2) }}</td></tr>
      </tfoot>
    </table>

    <h3 style="margin-top:28px">Shipping To</h3>
    <p style="margin:0">{{ $order->shipping_name }}<br>{{ $order->shipping_line1 }}, {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_pincode }}</p>

    <p style="margin-top:28px">We'll send you another email when your order ships.</p>
    <p>Warm regards,<br>The IPure Herbs Team</p>
  </div>
  <div class="footer">© {{ date('Y') }} IPure Herbs. Order # {{ $order->order_number }}</div>
</div>
</body>
</html>
