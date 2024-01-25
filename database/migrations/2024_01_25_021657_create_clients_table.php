<?php

declare(strict_types=1);

use App\Enums\Identifiers\IdentifierType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->engine('InnoDB');
            $table->uuid('id')->primary();
            $table->enum('type', IdentifierType::getType())->default(IdentifierType::CPF);
            $table->string('identifier');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['type', 'identifier', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
