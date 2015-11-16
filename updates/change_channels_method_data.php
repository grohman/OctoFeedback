<?php namespace Grohman\Feedback\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class ChangeChannelsMethodData extends Migration
{

    public function up()
    {
        Schema::table('grohman_feedback_channels', function(Blueprint $table)
        {
            $table->text('method_data')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('grohman_feedback_channels', function(Blueprint $table)
        {
            $table->string('method_data')->nullable()->change();
        });
    }

}
