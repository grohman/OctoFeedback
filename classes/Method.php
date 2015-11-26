<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 28/06/15
 * Time: 10:19
 */

namespace Grohman\Feedback\Classes;


use Grohman\Feedback\Models\Channel;

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