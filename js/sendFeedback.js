$(function(){
    function sendFeedbackForm(form, successCallback, errorCallback) {
        if(typeof FormData === 'undefined') {
            // damn...
            var $ifr = $('<iframe>', {'name':'feedbackFrame'}).css('display', 'none').appendTo('body');
            if($(form).hasClass('falledback') == false) {
                $(form).attr({
                    action: '/feedback/frame_fallback',
                    target: 'feedbackFrame',
                    method: 'POST'
                }).addClass('falledback');
            }
            $(form).submit();
            $ifr.on('load', function(){
                $ifr.remove();
                successCallback('ok');
                $(form).removeClass('falledback').removeAttr('target').removeAttr('action');
            })
        } else {
            var formData = new FormData(form);
            $.ajax({
                headers: {
                    'X-OCTOBER-REQUEST-HANDLER': 'feedback::onSend'
                },
                type: 'post',
                cache: false,
                contentType: false, // important
                processData: false,  // important
                data: formData,
                success: function (data) {
                    if (typeof successCallback == 'function') {
                        successCallback(data);
                    }
                },
                error: function (data) {
                    if (typeof errorCallback == 'function') {
                        errorCallback(data);
                    }
                }
            });
        }
    }

    if($('.grohman_feedback-forms').length) {
        $('.grohman_feedback-forms').on('submit', function () {
            sendFeedbackForm(this, function (data) {
                alert(data.result);
            }, function (data) {
                alert(data.responseJSON.result);
            });
            return false;
        });
    }
});