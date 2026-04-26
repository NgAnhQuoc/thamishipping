jQuery(document).ready(function($) {
    const $wrapper = $('#cs-thamibutton-wrapper');
    const $inquiryBtn = $('#cs-thamibutton-btn-inquiry');

    $inquiryBtn.on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $wrapper.toggleClass('is-open');
    });

    // Close when clicking outside
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#cs-thamibutton-wrapper').length) {
            $wrapper.removeClass('is-open');
        }
    });
});
