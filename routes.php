<?php

Route::post('/feedback/frame_fallback', function(){
    $component = new IDesigning\Feedback\Components\Feedback;
    return $component->onSend();
});