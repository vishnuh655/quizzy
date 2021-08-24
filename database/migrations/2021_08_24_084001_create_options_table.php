<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("options", function (Blueprint $table) {
            $table->uuid("optionId")->primary();
            $table
                ->foreignUuid("questionId")
                ->references("questionId")
                ->on("questions");
            $table->text("optionContent");
            $table->boolean("isAnswer");
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
        Schema::dropIfExists("options");
    }
}
