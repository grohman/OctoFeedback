<?php namespace Grohman\Feedback\Updates;

use Grohman\Feedback\Models\Channel;

class SeedDefaultChannel extends \Seeder
{
    public function run()
    {
        Channel::create([
            'name' => 'Default',
            'code' => 'default',
            'method' => 'email',
            'prevent_save_database' => false
        ]);
    }
}