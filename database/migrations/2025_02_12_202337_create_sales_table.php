<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_session_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('correlative')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('partial_payment', 10, 2);
            $table->enum('type', config('constants.SALE_TYPES'))->default(config('constants.SALE_TYPES.SALE_TYPE_CASH'));
            $table->enum('payment_method', config('constants.PAYMENT_METHODS'))->default(config('constants.PAYMENT_METHODS.PAYMENT_METHOD_CASH'));
            $table->boolean('bring_container')->default(false);
            $table->integer('total_count_containers')->default(0);
            $table->boolean('canceled')->default(false);
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
        Schema::dropIfExists('sales');
    }
}
