<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
	public function up()
	{
		// Populate missing reference values for existing complaints
		if (! Schema::hasTable('complaints')) {
			return;
		}

		$rows = DB::table('complaints')->whereNull('reference')->orWhere('reference', '')->get();
		foreach ($rows as $r) {
			$ref = null;
			do {
				$ref = 'CMP-'.date('Ymd').'-'.strtoupper(Str::random(6));
			} while (DB::table('complaints')->where('reference', $ref)->exists());

			DB::table('complaints')->where('id', $r->id)->update(['reference' => $ref]);
		}
	}

	public function down()
	{
		// do not remove populated references on rollback
	}
};

