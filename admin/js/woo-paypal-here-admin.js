(function ($) {
    'use strict';
    $(function () {
        $('#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_push_button').click(function () {
            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'angelleye_woo_paypal_here_update_api_key',
                    security: woocommerce_admin_api_keys.update_api_nonce,
                    key_id: '',
                    description: 'PayPal Here',
                    user: woocommerce_admin_api_keys.user,
                    permissions: 'read_write',
                    enabled: $('#woocommerce_angelleye_woo_paypal_here_enabled').is(':checked'),
                    paypal_here_endpoint_url: $('#woocommerce_angelleye_woo_paypal_here_paypal_here_endpoint_url').val(),
                    paypal_here_endpoint_title: $('#woocommerce_angelleye_woo_paypal_here_paypal_here_endpoint_title').val()
                },
                success: function (response) {
                    var data = response.data;
                    if (response.success) {
                        if (0 < data.consumer_key.length && 0 < data.consumer_secret.length) {
                            $('#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_key_value').val('...' + data.truncated_key);
                        }
                        $('.angelleye_paypal_here_notice').hide();
                        $('h1').append('<div class="wc-api-message updated"><p>' + 'API Key generated successfully.' + '</p></div>');
                        $('#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_push_button').closest('tr').hide();
                        $('#woocommerce_angelleye_woo_paypal_here_generate_woocommerce_rest_api_key_value').closest('tr').show();
                    } else {
                        $('h1').append('<div class="wc-api-message error"><p>' + response.data.message + '</p></div>');
                    }
                }
            });
        });
        $('#rest_api_key_value_description').click(function (evt) {
            evt.preventDefault();
            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: ajaxurl,
                data: {
                    action: 'angelleye_woo_paypal_here_revoke_key',
                    security: woocommerce_admin_api_keys.update_api_nonce,
                },
                success: function (response) {
                    var data = response.data;
                    if (response.success) {
                        window.location.reload();
                        return;
                    }
                }
            });
        });
        var url = 'https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' + woocommerce_admin_api_keys.paypal_here_url + '&chld=H|O';
        url = url.toString();
        $('.paypal_here_endpoint_url_qrcode').attr('src', url);
    });
})(jQuery);

jQuery(function () {
    jQuery('[id^=angelleye_notification]').each(function (i) {
        jQuery('[id="' + this.id + '"]').slice(1).remove();
    });
    var el_notice = jQuery(".angelleye-notice");
    el_notice.fadeIn(750);
    jQuery(".angelleye-notice-dismiss").click(function(e){
        e.preventDefault();
        jQuery( this ).parent().parent(".angelleye-notice").fadeOut(600, function () {
            jQuery( this ).parent().parent(".angelleye-notice").remove();
        });
        notify_wordpress(jQuery( this ).data("msg"));
    });
    function notify_wordpress(message) {
        var param = {
            action: 'angelleye_paypal_here_adismiss_notice',
            data: message
        };
        jQuery.post(ajaxurl, param);
    }
    jQuery(document).on('click', '#angelleye-updater-notice .notice-dismiss', function( event ) {
        var r = confirm("If you do not install the Updater plugin you will not receive automated updates for Angell EYE products going forward!");
        if (r == true) {
            data = {
                action : 'angelleye_updater_dismissible_admin_notice'
            };
            jQuery.post(ajaxurl, data, function (response) {
                var $el = jQuery( '#angelleye-updater-notice' );
                event.preventDefault();
                $el.fadeTo( 100, 0, function() {
                        $el.slideUp( 100, function() {
                                $el.remove();
                        });
                });
            });
        } 
    });
});
