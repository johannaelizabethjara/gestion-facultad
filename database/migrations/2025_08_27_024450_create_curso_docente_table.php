<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('curso_docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained();
            $table->foreignId('docente_id')->constrained();
            $table->timestamps();
            $table->unique(['curso_id', 'docente_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('curso_docente');
    }
};
