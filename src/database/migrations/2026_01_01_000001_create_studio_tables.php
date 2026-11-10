<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });

        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('category');            // console | jeu | accessoire
            $t->string('platform')->nullable(); // PS5, PS4...
            $t->text('description')->nullable();
            $t->decimal('price_usd', 10, 2);   // le prix en FC est calculé avec le taux
            $t->unsignedInteger('stock')->default(0);
            $t->string('image')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('equipment', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type');                // PS5 | PS4
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('incidents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $t->string('description');
            $t->date('reported_on');
            $t->boolean('resolved')->default(false);
            $t->timestamps();
        });

        Schema::create('reservations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $t->dateTime('starts_at');
            $t->dateTime('ends_at');
            $t->unsignedTinyInteger('duration_hours');
            $t->string('status')->default('pending'); // pending|confirmed|refused|cancelled|done
            $t->unsignedBigInteger('total_fc');
            $t->boolean('paid')->default(false);
            $t->string('note')->nullable();
            $t->timestamps();
            $t->index(['equipment_id', 'starts_at']);
        });

        Schema::create('tournaments', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('game');
            $t->dateTime('starts_at');
            $t->unsignedBigInteger('fee_fc')->default(0);
            $t->unsignedSmallInteger('max_players')->default(16);
            $t->text('description')->nullable();
            $t->string('status')->default('open'); // open|closed|finished
            $t->timestamps();
        });

        Schema::create('tournament_registrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('gamer_tag');
            $t->string('status')->default('pending'); // pending|confirmed|cancelled
            $t->boolean('paid')->default(false);
            $t->unsignedSmallInteger('rank')->nullable();
            $t->timestamps();
            $t->unique(['tournament_id', 'user_id']);
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('status')->default('pending'); // pending|paid|prepared|delivered|cancelled
            $t->string('delivery_method');            // pickup | delivery
            $t->string('address')->nullable();
            $t->string('phone');
            $t->unsignedBigInteger('total_fc');
            $t->boolean('paid')->default(false);
            $t->timestamps();
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('name');
            $t->unsignedBigInteger('unit_price_fc');
            $t->unsignedInteger('quantity');
            $t->timestamps();
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->morphs('payable');
            $t->string('method');                     // mpesa|airtel|orange|cash
            $t->string('phone')->nullable();
            $t->unsignedBigInteger('amount_fc');
            $t->string('reference')->unique();
            $t->string('status')->default('success'); // success|failed
            $t->timestamps();
        });

        Schema::create('expenses', function (Blueprint $t) {
            $t->id();
            $t->string('label');
            $t->string('category');
            $t->unsignedBigInteger('amount_fc');
            $t->date('spent_on');
            $t->timestamps();
        });

        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('type')->default('review');    // review | complaint
            $t->unsignedTinyInteger('rating')->nullable();
            $t->text('message');
            $t->text('response')->nullable();
            $t->timestamps();
        });

        Schema::create('announcements', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->text('body');
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['announcements','reviews','expenses','payments','order_items','orders',
                  'tournament_registrations','tournaments','reservations','incidents',
                  'equipment','products','settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
