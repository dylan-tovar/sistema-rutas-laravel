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
    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        $table->foreignId('address_id')->after('user_id')->constrained()->onDelete('cascade');
        $table->string('status')->after('address_id');
        $table->text('description')->nullable()->after('status');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropForeign(['address_id']);
        $table->dropColumn(['user_id', 'address_id', 'status', 'description']);
    });
}

};
