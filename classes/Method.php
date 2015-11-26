<?php namespace IDesigning\Feedback\Classes;


use IDesigning\Feedback\Models\Channel;

interface Method
{

    /**
     * Used to register new form fields to Channel.
     * Modify and prepare Channel model.
     *
     * @return void
     */
    public function boot();

    /**
     * @param array   $methodData
     * @param array   $data
     * @param Channel $channel
     * @return mixed
     */
    public function send($methodData, $data, Channel $channel);

}