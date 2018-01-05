(function ($) {
    'use strict';
    $(document).ready(function () {
        $(".paypal_here_add_to_cart_button").click(function () {
            var get_attributes = function () {
                var select = $('.variations_form').find('.variations select'),
                        data = {},
                        count = 0,
                        chosen = 0;
                select.each(function () {
                    var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
                    var value = $(this).val() || '';
                    if (value.length > 0) {
                        chosen++;
                    }
                    count++;
                    data[ attribute_name ] = value;
                });
                return {
                    'count': count,
                    'chosenCount': chosen,
                    'data': data
                };
            };
            var data = {
                 action: 'paypal_here_add_to_cart',
                'security': paypal_here_ajax_param.paypal_here_nonce,
                'qty': $("input[name=quantity]").val(),
                'attributes': $('.variations_form').length ? get_attributes().data : [],
                'product_id': $("input[name=add-to-cart]").val()
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                success: function (data) {
                    $('#paypal_here_modal').modal('hide');
                },
                error: function (e) {
                    alert("Error in ajax post:" + e.statusText);


                }
            });



            
            
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