<?php namespace IDesigning\Feedback\Updates;

use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class AddExtraToFeedbacks extends Migration
{

    public function up()
    {
        Schema::table('idesigning_feedback_feedbacks', function(Blueprint $table){
            $table->text('extra')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('idesigning_feedback_feedbacks', function(Blueprint $table){
            $table->dropColumn('extra');
        });
    }

}
