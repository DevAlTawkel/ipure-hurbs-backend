<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();

            // Customer (null = guest)
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_email')->nullable();
            $table->string('guest_name')->nullable();
            $table->string('guest_phone', 20)->nullable();

            // Shipping address snapshot
            $table->string('shipping_name');
            $table->string('shipping_phone', 20);
            $table->string('shipping_line1');
            $table->string('shipping_line2')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state');
            $table->string('shipping_country', 2)->default('IN');
            $table->string('shipping_pincode', 10);

            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_reason')->nullable();
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Order status
            $table->string('status')->default('pending');
            // pending | confirmed | processing | shipped | delivered | cancelled | refunded

            // Payment
            $table->string('payment_method')->default('stripe');
            $table->string('payment_status')->default('pending');
            // pending | paid | failed | refunded
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('payment_status');
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
