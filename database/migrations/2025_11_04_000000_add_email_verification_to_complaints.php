<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		if (Schema::hasTable('complaints')) {
			Schema::table('complaints', function (Blueprint $table) {
				if (! Schema::hasColumn('complaints', 'verification_token')) {
					$table->string('verification_token')->nullable()->after('email');
				}
				if (! Schema::hasColumn('complaints', 'email_verified')) {
					$table->boolean('email_verified')->default(false)->after('verification_token');
				}
			});
		}
	}

	public function down()
	{
		if (Schema::hasTable('complaints')) {
			Schema::table('complaints', function (Blueprint $table) {
				if (Schema::hasColumn('complaints', 'verification_token')) {
					$table->dropColumn('verification_token');
				}
				if (Schema::hasColumn('complaints', 'email_verified')) {
					$table->dropColumn('email_verified');
				}
			});
		}
	}
};

