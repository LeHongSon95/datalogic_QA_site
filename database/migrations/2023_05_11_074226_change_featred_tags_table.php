<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFeatredTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('featured_tags', function (Blueprint $table) {
            $table->dropForeign(['tag_id']);
            $table->dropColumn('tag_id');
            $table->string('title', 256);
            $table->tinyInteger('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('featured_tags', function (Blueprint $table) {
            $table->foreignIdFor(Tag::class, 'tag_id')->constrained('tags')->onDelete('NO ACTION');
            $table->dropColumn('title');
            $table->dropColumn('is_featured');
        });
    }
}
