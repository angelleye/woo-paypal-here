(function ($) {
    'use strict';
    $(document).ready(function () {
        $('.discount_field').hide();
        $('.shipping_field').hide();
        $('#paypal_here_coupon_code').show();
        $('#paypal_here_shipping_postal_code').show();
        $(".discount-img").click(function () {
            $(".discount-img").removeClass('checked');
            $(this).addClass("checked");

        });
        $(".shipping-img").click(function () {
            $(".shipping-img").removeClass('checked');
            $(this).addClass("checked");

        });
        if (typeof AutoNumeric != 'undefined') {
            new AutoNumeric('#paypal_here_percentage', 'percentageUS2dec');
            new AutoNumeric('#paypal_here_dollar', 'dollarPos');
        }
        $('input[type=radio][name=discount_amount]').change(function () {
            $('.discount_field').hide();
            var discount_amount = $('[name=discount_amount]:checked').val();
            if (discount_amount == 'coupon') {
                $('#paypal_here_coupon_code').show();
            } else if (discount_amount == 'percentage') {
                $('#paypal_here_percentage').show();
            } else if (discount_amount == 'amount') {
                $('#paypal_here_dollar').show();
            }
        });
        $('input[type=radio][name=shipping_amount]').change(function () {
            $('.shipping_field').hide();
            var shipping_amount = $('[name=shipping_amount]:checked').val();
            if (shipping_amount == 'postal_code') {
                $('#paypal_here_shipping_postal_code').show();
            } else if (shipping_amount == 'percentage') {
                $('#paypal_here_shipping_percentage').show();
            } else if (shipping_amount == 'amount') {
                $('#paypal_here_shipping_dollar').show();
            }
        });
        $(".paypal_here_add_to_cart_button").click(function () {
            $('.modal-content').block({message: null, overlayCSS: {background: '#fff', opacity: 0.4}});
            var get_attributes = function () {
                var select = $('.variations_form').find('.variations input[type=radio]:checked'),
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
                'product_id': $("input[name=add-to-cart]").val(),
                'variation_id': $("input[name=variation_id]").val(),
                'is_create_new_order': paypal_here_ajax_param.is_create_new_order
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    $('#paypal_here_modal').modal('hide');
                    if ('success' === result.result) {
                        if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = decodeURI(result.redirect);
                        }
                    }
                },
                error: function (e) {
                    if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                        window.location.href = result.redirect;
                    } else {
                        window.location.href = decodeURI(result.redirect);
                    }
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
                        $('.modal-body').html('');
                        $('.modal-body').html(data.html);
                        $('#paypal_here_modal').modal({show: true});
                        $('.paypal_here_number_input').bootstrapNumber();
                    } else {

                    }
                }
            });
        });

        $('.send_to_paypal_here').click(function () {
            $('.send_to_paypal_here').block({message: null, overlayCSS: {background: '#fff', opacity: 0.4}});
            var data = {
                action: 'send_to_paypal_here_action',
                'security': paypal_here_ajax_param.paypal_here_nonce,
                'order_id': $("input[name=order_id]").val()
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    $('#paypal_here_modal').modal('hide');
                    if ('success' === result.result) {
                        if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = decodeURI(result.redirect);
                        }
                    }
                },
                error: function (e) {
                    $('.send_to_paypal_here').unblock();
                    $('#paypal_here_modal').modal('hide');
                    location.reload();
                }
            });

        });

        $(".paypal_here_apply_coupon").click(function () {
            $('.paypal_here_apply_coupon').block({message: null, overlayCSS: {background: '#fff', opacity: 0.4}});
            var discount_amount = $('[name=discount_amount]:checked').val();
            if (discount_amount == 'coupon') {
                var data = {
                    action: 'paypal_here_apply_coupon',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'coupon_code': $("input[name=coupon_code]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else if (discount_amount == 'percentage') {
                var data = {
                    action: 'paypal_here_apply_coupon',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_percentage': $("input[name=paypal_here_percentage]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else if (discount_amount == 'amount') {
                var data = {
                    action: 'paypal_here_apply_coupon',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_amount': $("input[name=paypal_here_amount]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else {
                var data = {
                    action: 'paypal_here_apply_coupon',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'coupon_code': $("input[name=coupon_code]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            }
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    $('#paypal_here_modal_discount').modal('hide');
                    if ('success' === result.result) {
                        if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = decodeURI(result.redirect);
                        }
                    }
                },
                error: function (e) {

                }
            });
        });

        $(".paypal_here_apply_tax").click(function () {
            $('.paypal_here_apply_tax').block({message: null, overlayCSS: {background: '#fff', opacity: 0.4}});
            var data = {
                action: 'paypal_here_apply_tax',
                rate_id: $("input[name=add_order_tax]").val(),
                order_id: $("input[name=order_id]").val(),
                security: paypal_here_ajax_param.paypal_here_nonce
            };

            $.ajax({
                url: paypal_here_ajax_param.ajax_url,
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function (result) {
                    $('#paypal_here_modal_tax').modal('hide');
                    location.reload();
                }
            });
        });

        $(".paypal_here_apply_shipping").click(function () {
            $('.paypal_here_apply_shipping').block({message: null, overlayCSS: {background: '#fff', opacity: 0.4}});
            var shipping_amount = $('[name=shipping_amount]:checked').val();
            if (shipping_amount == 'coupon') {
                var data = {
                    action: 'paypal_here_apply_shipping',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_shipping_postal_code': $("input[name=paypal_here_shipping_postal_code]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else if (shipping_amount == 'percentage') {
                var data = {
                    action: 'paypal_here_apply_shipping',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_shipping_percentage': $("input[name=paypal_here_shipping_percentage]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else if (shipping_amount == 'amount') {
                var data = {
                    action: 'paypal_here_apply_shipping',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_shipping_dollar': $("input[name=paypal_here_shipping_dollar]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            } else {
                var data = {
                    action: 'paypal_here_apply_shipping',
                    'security': paypal_here_ajax_param.paypal_here_nonce,
                    'paypal_here_shipping_postal_code': $("input[name=paypal_here_shipping_postal_code]").val(),
                    'order_id': $("input[name=order_id]").val()
                };
            }
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    $('#paypal_here_modal_shipping').modal('hide');
                    if ('success' === result.result) {
                        if (-1 === result.redirect.indexOf('https://') || -1 === result.redirect.indexOf('http://')) {
                            window.location.href = result.redirect;
                        } else {
                            window.location.href = decodeURI(result.redirect);
                        }
                    }
                },
                error: function (e) {

                }
            });
        });


        var searchRequest;
        $("#paypal_here_coupon_code").autocomplete({
            minLength: 3,
            source: function (term, suggest) {
                try {
                    searchRequest.abort();
                } catch (e) {
                }
                if ($('#paypal_here_coupon_code').attr("placeholder") == 'Coupon code') {
                    searchRequest = $.post(paypal_here_ajax_param.ajax_url, {search: term, action: 'paypal_here_get_copon_code'}, function (res) {
                        suggest(res.data);
                    });
                }
            }
        });
        $('.angelleye_delete_button_paypal_here').click(function () {
            var data = {
                action: 'paypal_here_delete_order_item',
                'security': paypal_here_ajax_param.paypal_here_nonce,
                'item_id': $(this).data("item_id")
            };
            $('.table-responsive').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    if ('success' === result.result) {
                        document.location.reload(true);
                    }
                },
                error: function (e) {
                    $('.table-responsive').unblock();
                    alert("Error in ajax post:" + e.statusText);
                }
            });
        });
        $('#angelleye_delete_pending_order_paypal_here').click(function () {
            var data = {
                action: 'paypal_here_delete_order',
                'security': paypal_here_ajax_param.paypal_here_nonce,
                'order_id': $(this).data("order_id")
            };
            $('.table-responsive').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            $.ajax({
                type: 'POST',
                data: data,
                url: paypal_here_ajax_param.ajax_url,
                dataType: 'json',
                success: function (result) {
                    if ('success' === result.result) {
                        document.location.reload(true);
                    }
                },
                error: function (e) {
                    $('.table-responsive').unblock();
                    //alert("Error in ajax post:" + e.statusText);
                    console.log(e);
                }
            });
        });
        $(".paypal_here_discount").click(function () {
            $('#paypal_here_modal_discount').modal({show: true});
        });
        $(".paypal_here_shipping").click(function () {
            $('#paypal_here_modal_shipping').modal({show: true});
        });
        $(".paypal_here_tax").click(function () {
            $('#paypal_here_modal_tax').modal({show: true});
        });
        $(".paypal_here_clickable_row").click(function (e) {
            $('.table-responsive').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
            if (e.target.id === 'angelleye_delete_pending_order_paypal_here') {
                e.preventDefault();
            } else {
                window.document.location = $(this).data("href");
            }
        });
    });
})(jQuery);