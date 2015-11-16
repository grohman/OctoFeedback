<?php

Route::post('/feedback/frame_fallback', function(){
    $component = new Grohman\Feedback\Components\Feedback;
    return $component->onSend();
});