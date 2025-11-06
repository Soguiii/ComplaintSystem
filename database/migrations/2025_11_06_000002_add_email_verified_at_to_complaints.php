<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('complaints') && ! Schema::hasColumn('complaints', 'email_verified_at')) {
            Schema::table('complaints', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email_verified');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('complaints') && Schema::hasColumn('complaints', 'email_verified_at')) {
            Schema::table('complaints', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }
    }
};
