<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('execution_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->float('interview_grade', 3, 1, true)->default(0);
            $table->timestamp('interview_start_at')->nullable();
            $table->timestamp('interview_finish_at')->nullable();
            $table->json('interview_snapshot')->nullable();
            $table->float('homework_grade', 3, 1, true)->default(0);
            $table->timestamp('homework_start_at')->nullable();
            $table->timestamp('homework_finish_at')->nullable();
            $table->text('homework_solution')->nullable();
            $table->json('homework_snapshot')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
