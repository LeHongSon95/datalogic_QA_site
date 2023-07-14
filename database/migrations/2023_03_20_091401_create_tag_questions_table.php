<?php

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_questions', function (Blueprint $table) {
	        $table->id();
	        $table->foreignIdFor(Tag::class, 'tag_id')->constrained('tags')->onDelete('NO ACTION');
	        $table->foreignIdFor(Question::class, 'question_id')->constrained('questions')->onDelete('NO ACTION');
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
        Schema::dropIfExists('tag_questions');
    }
}
