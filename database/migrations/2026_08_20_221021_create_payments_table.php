<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('merchant_id')
                ->constrained('merchants')
                ->restrictOnDelete();

            $table->string('external_id');
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3);
            $table->string('status')->default(PaymentStatus::PENDING->value);
            $table->timestampTz('scheduled_at')->nullable();
            $table->timestamps();

            $table->unique(['merchant_id', 'external_id']);
            $table->index(['merchant_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
