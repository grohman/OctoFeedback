<?php namespace Grohman\Feedback\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class RemoveFileFromFeedbacks extends Migration
{

    public function up()
    {
        Schema::table('grohman_feedback_feedbacks', function(Blueprint $table){
            $table->dropColumn('file');
        });
    }

    public function down()
    {
        Schema::table('grohman_feedback_feedbacks', function(Blueprint $table){
            $table->string('file');
        });
    }

}
