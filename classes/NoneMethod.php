<?php
/**
 * Created by PhpStorm.
 * User: Leonardo Shinagawa
 * Date: 28/06/15
 * Time: 10:22
 */

namespace Grohman\Feedback\Classes;


use Grohman\Feedback\Models\Channel;

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