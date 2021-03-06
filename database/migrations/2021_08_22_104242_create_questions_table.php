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
            $table->uuid("questionId")->primary();
            $table->text("questionContent");
            $table->integer("typeId");
            $table->tinyInteger("status");
            $table
                ->timestamp("createdAt")
                ->default(DB::raw("CURRENT_TIMESTAMP"));
            $table
                ->timestamp("updatedAt")
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
