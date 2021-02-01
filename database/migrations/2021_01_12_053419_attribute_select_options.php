<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AttributeSelectOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('attribute_select_options', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->json('content');
            $table->integer('attribute_id')->unsigned();
            $table->string('entity_type');
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('attribute_select_values')
                ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_select_options');
    }
}
