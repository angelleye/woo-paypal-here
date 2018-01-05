(function ($) {
    'use strict';
    $(document).ready(function () {
        $(".paypal_here_add_to_cart_button").click(function () {
           
            $('#paypal_here_modal').modal('hide');
            alert(jQuery('.paypal_here_number_input').val());
        });
       
        $(".open-modal").click(function () {
            $.ajax({
                method: 'POST',
                dataType: 'json',
                url: paypal_here_ajax_param.ajax_url,
                data: {
                    action: 'paypal_here_get_modal_body',
                    security: paypal_here_ajax_param.paypal_here_nonce,
                    product_id: $(this).attr("id")
                },
                success: function (response) {
                    var data = response.data;
                    if (response.success) {
                        $('.modal-body').html(data.html);
                        $('#paypal_here_modal').modal({show: true});
                        $('.paypal_here_number_input').bootstrapNumber();
                    } else {

                    }
                }
            });
        });
    });
})(jQuery);