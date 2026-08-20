<?php

declare(strict_types=1);

use App\Enums\PaymentScheduleFrequency;
use App\Enums\PaymentScheduleStatus;
use App\Enums\PaymentScheduleType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_schedules', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();

            $table->string('type');
            $table->timestampTz('scheduled_at');
            $table->string('frequency')->nullable();
            $table->unsignedInteger('interval')->nullable();
            $table->unsignedTinyInteger('day_of_month')->nullable();
            $table->string('timezone', 64)->default('UTC');
            $table->string('status')->default(PaymentScheduleStatus::ACTIVE->value);
            $table->timestampTz('ends_at')->nullable();
            $table->timestamps();

            $table->unique('payment_id');
            $table->index(['status', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_schedules');
    }
};
