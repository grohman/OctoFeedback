$(function () {
    function sendFeedbackForm(form, successCallback, errorCallback) {
        if (typeof FormData === 'undefined') {
            // damn...
            var $ifr = $('<iframe>', {'name': 'feedbackFrame'}).css('display', 'none').appendTo('body');
            if ($(form).hasClass('falledback') == false) {
                $(form).attr({
                    action: '/feedback/frame_fallback',
                    target: 'feedbackFrame',
                    method: 'POST'
                }).addClass('falledback');
            }
            $(form).submit();
            $ifr.on('load', function () {
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

    if ($('.idesigning_feedback-forms').length) {
        $('.idesigning_feedback-forms').on('submit', function () {
            var _this = this;
            sendFeedbackForm(this, function (data) {
                if ($(_this).data('feedbackSuccess') !== undefined && window[$(_this).data('feedbackSuccess')] !== undefined) {
                    return window[$(_this).data('feedbackSuccess')](_this, data);
                } else {
                    alert(data.result);
                }
            }, function (data) {
                if ($(_this).data('feedbackError') !== undefined && window[$(_this).data('feedbackError')] !== undefined) {
                    return window[$(_this).data('feedbackError')](_this, data);
                } else {
                    alert(data.responseJSON.result);
                }
            });
            return false;
        });
    }
});