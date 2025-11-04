<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('hearings', 'status_changed_at')) {
            Schema::table('hearings', function (Blueprint $table) {
                $table->timestamp('status_changed_at')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('hearings', 'status_changed_at')) {
            Schema::table('hearings', function (Blueprint $table) {
                $table->dropColumn('status_changed_at');
            });
        }
    }
};
