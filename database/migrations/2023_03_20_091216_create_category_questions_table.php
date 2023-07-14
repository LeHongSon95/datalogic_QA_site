<?php

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_questions', function (Blueprint $table) {
	        $table->id();
	        $table->foreignIdFor(Category::class, 'category_id')->constrained('categories')->onDelete('NO ACTION');
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
        Schema::dropIfExists('category_questions');
    }
}
