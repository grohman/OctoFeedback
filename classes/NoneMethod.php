<?php namespace IDesigning\Feedback\Classes;


use IDesigning\Feedback\Models\Channel;

class NoneMethod implements Method
{

    public function boot()
    {
        // none
    }

    public function send($methodData, $data, Channel $channel)
    {
        // none
    }

}