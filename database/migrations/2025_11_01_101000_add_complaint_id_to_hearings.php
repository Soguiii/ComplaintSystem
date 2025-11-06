<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		if (Schema::hasTable('hearings') && ! Schema::hasColumn('hearings', 'complaint_id')) {
			Schema::table('hearings', function (Blueprint $table) {
				$table->foreignId('complaint_id')->nullable()->constrained('complaints')->nullOnDelete()->after('id');
			});
		}
	}

	public function down()
	{
		if (Schema::hasTable('hearings') && Schema::hasColumn('hearings', 'complaint_id')) {
			Schema::table('hearings', function (Blueprint $table) {
				$table->dropForeign(['complaint_id']);
				$table->dropColumn('complaint_id');
			});
		}
	}
};

