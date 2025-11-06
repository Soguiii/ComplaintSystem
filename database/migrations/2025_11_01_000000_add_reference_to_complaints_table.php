<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		if (! Schema::hasTable('complaints')) {
			return;
		}

		if (! Schema::hasColumn('complaints', 'reference')) {
			Schema::table('complaints', function (Blueprint $table) {
				$table->string('reference')->nullable()->after('contact')->unique();
			});
		}
	}

	public function down()
	{
		if (Schema::hasTable('complaints') && Schema::hasColumn('complaints', 'reference')) {
			Schema::table('complaints', function (Blueprint $table) {
				$table->dropColumn('reference');
			});
		}
	}
};

