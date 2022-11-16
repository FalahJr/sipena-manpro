(function($) {
   'use strict';

   //  Switchery Starts

    var elem = document.querySelector('.js-switch.switch-success.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#46c35f',
        secondaryColor: '#46c35f',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-success.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#46c35f',
        secondaryColor: '#46c35f',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-success.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#46c35f',
        secondaryColor: '#46c35f',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    var elem = document.querySelector('.js-switch.switch-primary.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#41478a',
        secondaryColor: '#41478a',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-primary.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#41478a',
        secondaryColor: '#41478a',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-primary.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#41478a',
        secondaryColor: '#41478a',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    var elem = document.querySelector('.js-switch.switch-info.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#57c7d4',
        secondaryColor: '#57c7d4',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-info.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#57c7d4',
        secondaryColor: '#57c7d4',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-info.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#57c7d4',
        secondaryColor: '#57c7d4',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    var elem = document.querySelector('.js-switch.switch-warning.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#f2a654',
        secondaryColor: '#f2a654',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-warning.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#f2a654',
        secondaryColor: '#f2a654',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-warning.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#f2a654',
        secondaryColor: '#f2a654',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    var elem = document.querySelector('.js-switch.switch-danger.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#f96868',
        secondaryColor: '#f96868',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-danger.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#f96868',
        secondaryColor: '#f96868',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-danger.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#f96868',
        secondaryColor: '#f96868',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    var elem = document.querySelector('.js-switch.switch-secondary.switch-normal');
    var switchery = new Switchery(elem, {
        color: '#e4eaec',
        secondaryColor: '#e4eaec',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-secondary.switch-small');
    var switchery = new Switchery(elem, {
        size: 'small',
        color: '#e4eaec',
        secondaryColor: '#e4eaec',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });
    var elem = document.querySelector('.js-switch.switch-secondary.switch-lg');
    var switchery = new Switchery(elem, {
        size: 'large',
        color: '#e4eaec',
        secondaryColor: '#e4eaec',
        jackColor: '#ffffff',
        jackSecondaryColor: '#ffffff'
    });

    // Switchery Ends

    // Jquery Tag Input Starts

    $('#tags').tagsInput({
        'width': '100%',
        'height': '75%',
        'interactive': true,
        'defaultText': 'Add More',
        'removeWithBackspace': true,
        'minChars': 0,
        'maxChars': 20, // if not provided there is no limit
        'placeholderColor': '#666666'
    });

    // Jquery Tag Input Ends
    // Jquery Knob Starts Here

    $(function() {
        $(".dial").knob();
    });

    // Jquery Knob Ends Here
    // Jquery Bar Rating Starts

    $(function() {
        function ratingEnable() {
            $('#example-1to10').barrating('show', {
                theme: 'bars-1to10'
            });

            $('#example-movie').barrating('show', {
                theme: 'bars-movie'
            });

            $('#example-movie').barrating('set', 'Mediocre');

            $('#example-square').barrating('show', {
                theme: 'bars-square',
                showValues: true,
                showSelectedRating: false
            });

            $('#example-pill').barrating('show', {
                theme: 'bars-pill',
                initialRating: 'A',
                showValues: true,
                showSelectedRating: false,
                allowEmpty: true,
                emptyValue: '-- no rating selected --',
                onSelect: function(value, text) {
                    alert('Selected rating: ' + value);
                }
            });

            $('#example-reversed').barrating('show', {
                theme: 'bars-reversed',
                showSelectedRating: true,
                reverse: true
            });

            $('#example-horizontal').barrating('show', {
                theme: 'bars-horizontal',
                reverse: true,
                hoverState: false
            });

            $('#example-fontawesome').barrating({
                theme: 'fontawesome-stars',
                showSelectedRating: false
            });

            $('#example-css').barrating({
                theme: 'css-stars',
                showSelectedRating: false
            });

            $('#example-bootstrap').barrating({
                theme: 'bootstrap-stars',
                showSelectedRating: false
            });

            var currentRating = $('#example-fontawesome-o').data('current-rating');

            $('.stars-example-fontawesome-o .current-rating')
                .find('span')
                .html(currentRating);

            $('.stars-example-fontawesome-o .clear-rating').on('click', function(event) {
                event.preventDefault();

                $('#example-fontawesome-o')
                    .barrating('clear');
            });

            $('#example-fontawesome-o').barrating({
                theme: 'fontawesome-stars-o',
                showSelectedRating: false,
                initialRating: currentRating,
                onSelect: function(value, text) {
                    if (!value) {
                        $('#example-fontawesome-o')
                            .barrating('clear');
                    } else {
                        $('.stars-example-fontawesome-o .current-rating')
                            .addClass('hidden');

                        $('.stars-example-fontawesome-o .your-rating')
                            .removeClass('hidden')
                            .find('span')
                            .html(value);
                    }
                },
                onClear: function(value, text) {
                    $('.stars-example-fontawesome-o')
                        .find('.current-rating')
                        .removeClass('hidden')
                        .end()
                        .find('.your-rating')
                        .addClass('hidden');
                }
            });
        }

        function ratingDisable() {
            $('select').barrating('destroy');
        }

        $('.rating-enable').click(function(event) {
            event.preventDefault();

            ratingEnable();

            $(this).addClass('deactivated');
            $('.rating-disable').removeClass('deactivated');
        });

        $('.rating-disable').click(function(event) {
            event.preventDefault();

            ratingDisable();

            $(this).addClass('deactivated');
            $('.rating-enable').removeClass('deactivated');
        });

        ratingEnable();
    });


    // Jquery Bar Rating Ends

})(jQuery);
