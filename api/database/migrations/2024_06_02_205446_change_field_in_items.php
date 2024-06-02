<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('items', static function (Blueprint $table) {
            $table->dropColumn('purchase_date');

            $table->date('purchased_at')
                ->after('notes')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('items', static function (Blueprint $table) {
            $table->dropColumn('purchased_at');

            $table->date('purchase_date')
                ->after('notes')
                ->nullable();
        });
    }
};
