<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->unsignedBigInteger('model_id');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('history_id')->unique();
            $table->foreign('history_id')->references('id')->on('histories');
            $table->decimal('amount', 15, 2); // Số tiền thanh toán, ví dụ: 223400.00
            $table->string('payment_method'); 
            $table->string('reference_code')->nullable(); // Tham chiếu, có thể không có giá trị
            $table->timestamp('recorded_at'); // Ngày ghi nhận
            $table->text('detail')->nullable();
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
        Schema::dropIfExists('payment_records');
    }
}
