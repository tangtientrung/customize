require(['jquery','domReady!'], function ($){
    var waitForEl = function(selector, callback) {
    if (jQuery(selector).length) {
        callback();
    } else {
        setTimeout(function() {
            waitForEl(selector, callback);
        }, 100);
        }
    };
    var selector = '[name="customer[company_type]"]';
    waitForEl(selector, function() {
        // work the magic
        if ($('[name="customer[company_type]"]').val() == 4)
            $('[data-index="organization_name"]').show();
        else $('[data-index="organization_name"]').hide();
            $('[name="customer[company_type]"]').change(function() {
            var data = $(this).val();
            if(data == 4){
                $('[data-index="organization_name"]').show();
            } else {
                $('[data-index="organization_name"]').hide();
                $('[name="customer[organization_name]"]').val('');
            }
        });
    });
});