/**
*  @author    RV Templates
*  @copyright 2015-2017 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

$(document).ready(function () {
    $('#id_customer, #id_wishlist').change( function () {
        $('#module_form').submit();
    });
});