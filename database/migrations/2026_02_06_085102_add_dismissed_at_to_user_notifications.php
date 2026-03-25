<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('user_notifications', 'dismissed_at')) {
                $table->timestamp('dismissed_at')->nullable()->after('read_at');
            }
            
            if (!Schema::hasColumn('user_notifications', 'email_sent')) {
                $table->boolean('email_sent')->default(false)->after('dismissed_at');
            }
            
            if (!Schema::hasColumn('user_notifications', 'email_sent_at')) {
                $table->timestamp('email_sent_at')->nullable()->after('email_sent');
            }
        });
    }

    public function down()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropColumn(['dismissed_at', 'email_sent', 'email_sent_at']);
        });
    }
};