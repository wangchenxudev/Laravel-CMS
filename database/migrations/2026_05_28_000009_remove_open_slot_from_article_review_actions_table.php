<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('article_review_actions', function (Blueprint $table): void {
            $table->dropUnique('article_review_actions_article_id_open_slot_unique');
            $table->dropColumn(['is_open', 'open_slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_review_actions', function (Blueprint $table): void {
            $table->boolean('is_open')->default(false)->after('reason');
            $table->string('open_slot')->nullable()->after('is_open');
            $table->unique(['article_id', 'open_slot']);
        });
    }
};
