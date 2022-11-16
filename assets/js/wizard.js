(function ($) {
    'use strict';
    var form = $("#example-form");
    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onFinished: function (event, currentIndex) {
            alert("Submitted!");
        }
    });
    var verticalForm = $("#example-vertical-wizard");
    verticalForm.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        onFinished: function (event, currentIndex) {
            alert("Submitted!");
        }
    });
    var validationForm = $("#example-validation-form");
    validationForm.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
            if (currentIndex > newIndex)
            {
                return true;
            }
            validationForm.valid({
                ignore: [":disabled",":hidden"]
            });
            return validationForm.valid();
        },
        onFinishing: function (event, currentIndex) {
            validationForm.valid({
                ignore: [':disabled']
            })
            return validationForm.valid();
        },
        onFinished: function (event, currentIndex) {
            alert("Submitted!");
        }
    });
})(jQuery);
