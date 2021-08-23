<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("questions", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->text("content");
            $table->integer("type_id");
            $table->tinyInteger("status");
            $table
                ->timestamp("created_at")
                ->default(DB::raw("CURRENT_TIMESTAMP"));
            $table
                ->timestamp("updated_at")
                ->default(DB::raw("CURRENT_TIMESTAMP"));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("questions");
    }
}
