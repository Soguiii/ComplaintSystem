<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		if (! Schema::hasTable('hearings')) {
			Schema::create('hearings', function (Blueprint $table) {
				$table->id();
				$table->string('title')->nullable();
				$table->string('type')->nullable();
				$table->string('complainant')->nullable();
				$table->string('contact')->nullable();
				$table->timestamp('scheduled_at')->nullable();
				$table->text('details')->nullable();
				$table->string('status')->default('scheduled');
				$table->timestamps();
			});
		}
	}

	public function down()
	{
		if (Schema::hasTable('hearings')) {
			Schema::dropIfExists('hearings');
		}
	}
};

