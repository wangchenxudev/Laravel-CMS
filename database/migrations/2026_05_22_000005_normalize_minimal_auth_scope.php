<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->where('role', 'root')->update([
            'role' => 'admin',
        ]);

        DB::table('users')->whereNotIn('role', ['user', 'admin'])->update([
            'role' => 'user',
        ]);

        if (Schema::hasColumn('users', 'admin_upgrade_requested_at')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('admin_upgrade_requested_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
