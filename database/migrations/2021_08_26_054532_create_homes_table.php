<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('purpose');//rent -- buy
            $table->foreignId('customer_id')->constrained('users', 'id');
            $table->string('zip_code',10);
            $table->string('address');
            $table->foreignId('type_id')->nullable()->constrained('home_types', 'id');
            $table->unsignedBigInteger('price');
            $table->string('bedrooms'); // 1 - 2 - 3 - 4 - +4
            $table->string('bathrooms'); //  1 - 2 - 3 - +3
            $table->foreignId('condition_id')->constrained('home_conditions', 'id');
            $table->unsignedInteger('m_two')->nullable();
            $table->unsignedInteger('price_m_two')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homes');
    }
}
