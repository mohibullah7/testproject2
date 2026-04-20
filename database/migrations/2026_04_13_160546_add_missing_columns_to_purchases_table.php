<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
        $table->integer('quantity')->default(1);
        $table->decimal('total_amount', 10, 2);
        $table->timestamp('completed_at')->nullable();
    });
}

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn(['status', 'quantity', 'total_amount', 'completed_at']);
    });
}
};
