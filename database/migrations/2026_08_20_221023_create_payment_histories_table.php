<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_histories', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->string('event');
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();

            $table->foreignUuid('attempt_id')
                ->nullable()
                ->constrained('payment_attempts')
                ->nullOnDelete();

            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index(['payment_id', 'created_at']);
            $table->index('event');
            $table->index('attempt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
