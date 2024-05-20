<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                NestedSet::columns($table);
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('type');
                $table->string('identifier')->unique()->nullable();
                $table->boolean('active')->default(false);
                $table->json('meta_data')->nullable();
                $table->json('meta_tags')->nullable();
                $table->timestamps();

            });
        }

        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                NestedSet::columns($table);
                $table->nullableMorphs('menuable');
                $table->string('name');
                $table->string('type')->nullable();
                $table->string('location')->nullable();
                $table->string('url')->nullable();
                $table->string('route')->nullable();
                $table->boolean('active')->default(true);
                $table->json('meta_data')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blocks')) {
            Schema::create('blocks', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('type');
                $table->json('meta_data')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blockables')) {
            Schema::create('blockables', function (Blueprint $table) {
                $table->id();
                $table->foreignId('block_id')->constrained('blocks')->cascadeOnDelete();
                $table->morphs('blockable');
                $table->string('location');
                $table->integer('ord')->default(0);
                $table->timestamps();
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
        Schema::dropIfExists('blockables');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('pages');
    }
};
