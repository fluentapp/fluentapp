<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('daily_hash', function (Blueprint $table) {
            $table->id();
            $table->string('hash');
            $table->timestamps();
        });


        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('fqdn')->unique();
            $table->string('timezone')->default('UTC');
            $table->boolean('active');
            $table->timestamps();
        });

        Schema::create('users_sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('site_id');
            $table->enum('role', ['admin', 'viewer']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('site_id')->references('id')->on('sites');
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->nullable();
            $table->string('event')->nullable();
            $table->string('entry_page')->nullable();
            $table->string('exit_page')->nullable();
            $table->string('referrer')->nullable();
            $table->string('referrer_domain')->nullable();
            $table->string('hash')->nullable();
            $table->string('device_type')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('resolution')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->boolean('is_bounced')->nullable()->default(1);
            $table->decimal('duration')->default(0);
            $table->bigInteger('site_id', false, true);
            $table->foreign('site_id')->references('id')->on('sites');
            $table->timestamps();
        });

        Schema::create('pageviews', function (Blueprint $table) {
            $table->bigInteger('event_id', false, true);
            $table->string('page')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_hash');
        Schema::dropIfExists('users_sites');
        Schema::dropIfExists('sites');
        Schema::dropIfExists('pageviews');
        Schema::dropIfExists('events');
    }
};
