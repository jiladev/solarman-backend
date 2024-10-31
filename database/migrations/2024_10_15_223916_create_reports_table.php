<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->float('consume_kv_coop');
            $table->float('consume_kv_copel');
            $table->float('consume_kv_copel_final');
            $table->float('consume_kv_coop_final');
            $table->float('public_light');
            $table->float('ult_fatura_copel');
            $table->float('min_tax');
            $table->float('fasic_value');
            $table->float('percentage_value');
            $table->float('taxa_tusd');
            $table->float('discount');
            $table->float('final_value_coop');
            $table->float('discount_monthly');
            $table->float('discount_percentage');
            $table->softDeletes();
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
        Schema::dropIfExists('reports');
    }
}
