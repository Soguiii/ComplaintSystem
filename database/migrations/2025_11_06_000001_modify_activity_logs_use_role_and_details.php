<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            // Remove user_id FK and column if present (we will use role instead)
            if (Schema::hasColumn('activity_logs', 'user_id')) {
                // drop foreign key if exists
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // ignore if foreign key not present
                }
                $table->dropColumn('user_id');
            }

            if (!Schema::hasColumn('activity_logs', 'role')) {
                $table->string('role')->nullable()->after('id');
            }

            if (!Schema::hasColumn('activity_logs', 'details')) {
                $table->text('details')->nullable()->after('action');
            }

            // Remove metadata column (legacy) if present
            if (Schema::hasColumn('activity_logs', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->after('id');
            }

            if (Schema::hasColumn('activity_logs', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('activity_logs', 'details')) {
                $table->dropColumn('details');
            }

            if (!Schema::hasColumn('activity_logs', 'metadata')) {
                $table->json('metadata')->nullable()->after('user_agent');
            }
        });
    }
};
