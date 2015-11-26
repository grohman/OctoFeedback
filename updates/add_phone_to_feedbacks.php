<?php namespace IDesigning\Feedback\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class AddPhoneToFeedbacks extends Migration
{

    public function up()
    {
        Schema::dropIfExists('ebussola_feedback_feedbacks');
        Schema::dropIfExists('ebussola_feedback_channels');


        Schema::table('grohman_feedback_feedbacks', function(Blueprint $table)
        {
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->after('email');
            $table->string('file')->nullable()->after('phone');
        });
    }

    public function down()
    {
        Schema::table('grohman_feedback_feedbacks', function(Blueprint $table){
            $table->dropColumn('phone');
            $table->dropColumn('file');
        });
    }

}
