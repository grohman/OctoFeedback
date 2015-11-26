<?php namespace IDesigning\Feedback\Updates;

use DB;
use Illuminate\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class Branding extends Migration
{

    public function up()
    {
        Schema::rename('grohman_feedback_feedbacks', 'idesigning_feedback_feedbacks');
        Schema::rename('grohman_feedback_channels', 'idesigning_feedback_channels');
        DB::table('system_settings')->where('item', '=', 'grohman_feedback_settings')->update(['item' => 'idesigning_feedback_settings']);
        DB::table('system_files')->where('attachment_type', '=', 'Grohman\Feedback\Models\Feedback')->update(['attachment_type' => 'IDesigning\Feedback\Models\Feedback']);
        DB::table('system_files')->where('attachment_type', '=', 'Grohman\Feedback\Models\Channel')->update(['attachment_type' => 'IDesigning\Feedback\Models\Channel']);
    }

    public function down()
    {
        Schema::rename('idesigning_feedback_feedbacks', 'grohman_feedback_feedbacks');
        Schema::rename('idesigning_feedback_channels', 'grohman_feedback_channels');
        DB::table('system_settings')->where('item', '=', 'idesigning_feedback_settings')->update(['item' => 'grohman_feedback_settings']);
        DB::table('system_files')->where('attachment_type', '=', 'IDesigning\Feedback\Models\Feedback')->update(['attachment_type' => 'Grohman\Feedback\Models\Feedback']);
        DB::table('system_files')->where('attachment_type', '=', 'IDesigning\Feedback\Models\Channel')->update(['attachment_type' => 'Grohman\Feedback\Models\Channel']);
    }

}
