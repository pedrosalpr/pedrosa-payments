<?php

declare(strict_types=1);

use App\Enums\Payments\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('client_id');
            $table->text('description');
            $table->unsignedDouble('value', 10, 2);
            $table->unsignedTinyInteger('payment_method_id');
            $table->enum('status', Status::getType())->default(Status::PENDING);
            $table->date('due_date')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->unsignedFloat('tax');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
