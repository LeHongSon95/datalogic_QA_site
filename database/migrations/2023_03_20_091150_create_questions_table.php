<?php

use App\Models\Answer;
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
        Schema::create('questions', function (Blueprint $table) {
	        $table->id();
	        $table->string('title', 256);
	        $table->foreignIdFor(Answer::class, 'answer_id')->constrained('answers')->onDelete('NO ACTION');
	        $table->longText('note')->nullable();
	        $table->integer('created_by');
	        $table->integer('updated_by');
	        $table->timestamps();
	        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
