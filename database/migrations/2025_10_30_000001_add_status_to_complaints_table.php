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
		if (! Schema::hasTable('complaints')) {
			return;
		}

		if (! Schema::hasColumn('complaints', 'status')) {
			Schema::table('complaints', function (Blueprint $table) {
				// If the reference column exists, place status after it for readability; otherwise just add it.
				if (Schema::hasColumn('complaints', 'reference')) {
					$table->string('status')->default('pending')->after('reference');
				} else {
					$table->string('status')->default('pending');
				}
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasTable('complaints') && Schema::hasColumn('complaints', 'status')) {
			Schema::table('complaints', function (Blueprint $table) {
				$table->dropColumn('status');
			});
		}
	}
};

