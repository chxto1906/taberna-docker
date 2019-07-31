/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

$(document).ready(function(){
    $('input.star').rating();
    $('.auto-submit-star').rating();

    $('.open-comment-form').click(function(){
        if ($('#criterions_list').length) {
            $('#comment-modal').modal('show');
        } else {
            if ($('#comment-modal .modal-header .disable-form-comment').length) {
                $('#comment-modal').modal('show');
            } else {
                $('#submitNewMessage').remove();
                $('#comment-modal .modal-header .modal-title').remove();
                $('#comment-modal .modal-body').remove();
                $('#comment-modal .modal-header').append('<h2 class="disable-form-comment">'+disable_comment+'</h2>');
                $('#comment-modal').modal('show');
            }

        }
        return false;
    });

    $(document).on('click', 'button.usefulness_btn', function(e){
        var id_product_comment = $(this).data('id-product-comment');
        var is_usefull = $(this).data('is-usefull');
        var parent = $(this).parent();

        $.ajax({
            url: productcomments_controller_url,
            data: {
                id_product_comment: id_product_comment,
                action: 'comment_is_usefull',
                value: is_usefull
            },
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            success: function(result){
                parent.fadeOut('slow', function() {
                    parent.remove();
                });
            }
        });
    });

    $(document).on('click', 'span.report_btn', function(e){
        var idProductComment = $(this).data('id-product-comment');
        var parent = $(this).parent();

        $.ajax({
            url: productcomments_controller_url,
            data: {
                id_product_comment: idProductComment,
                action: 'report_abuse'
            },
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            success: function(result){
                parent.fadeOut('slow', function() {
                    parent.remove();
                });
            }
        });
    });

    $(document).on('click', '#submitNewMessage', function(e){
        /* Kill default behaviour */
        e.preventDefault();

        /* Form element */

        url_options = '?';
        if (productcomments_url_rewrite==0) {
            url_options = '&';
        }

        $.ajax({
            url: productcomments_controller_url + url_options + 'action=add_comment',
            data: $('#id_new_comment_form').serialize(),
            type: 'POST',
            headers: { "cache-control": "no-cache" },
            dataType: "json",
            success: function(data){
                if (data.result) {
                    $('#submitNewMessage').fadeOut('slow', function(){
                        $(this).remove();
                    });

                    $('#comment-modal .modal-body').fadeOut('slow', function(){
                        $(this).remove();
                        $('#comment-modal .modal-header .modal-title').remove();
                        if (moderation_active) {
                            $('#comment-modal .modal-header').append('<h2>'+productcomment_added_moderation+'</h2>');
                        } else {
                            $('#comment-modal .modal-header').append('<h2>'+productcomment_added+'</h2>');
                        }
                    });
                } else {
                    $('#new_comment_form_error ul').html('');
                    $.each(data.errors, function(index, value) {
                        $('#new_comment_form_error ul').append('<li>'+value+'</li>');
                    });
                    $('#new_comment_form_error').slideDown('slow');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("TECHNICAL ERROR, Please Try Again Later");
                window.location.reload();
            }
        });
        $('#comment-modal').on('hidden.bs.modal', function () {
            if (!$('#submitNewMessage').length && !$('#comment-modal .modal-body .disable-form-comment').length) {
                window.location.reload();
            }
        });
    });

    $(document).on('click', '.comments_advices .reviews', function(e){
        if ($('.commenttab').length) {
            $('.commenttab').trigger('click');
            $('html, body').animate({
                scrollTop: $('.commenttab').offset().top
            }, 500);
        }
        return false;
    });
});

