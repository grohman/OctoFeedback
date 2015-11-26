<?php namespace Grohman\Feedback\Updates;

use DB;

class SeedDefaultChannel extends \Seeder
{
    public function run()
    {
        DB::table('grohman_feedback_channels')->insert([
            'name' => 'Default',
            'code' => 'default',
            'method' => 'email',
            'prevent_save_database' => false
        ]);
    }
}