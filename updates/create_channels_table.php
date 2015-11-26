<?php namespace IDesigning\Feedback\Updates;

use Illuminate\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateChannelsTable extends Migration
{

    public function up()
    {
        Schema::create('grohman_feedback_channels', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->string('code')
                ->unique()
                ->index();
            $table->boolean('prevent_save_database');

            $table->string('method');
            $table->string('method_data')->nullable();
        });

        \DB::table('grohman_feedback_channels')->insert([
            'name' => 'Default',
            'code' => 'default',
            'method' => 'email',
            'prevent_save_database' => false
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('grohman_feedback_channels');
    }

}
