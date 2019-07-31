/**
*  @author    RV Templates
*  @copyright 2015-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

$(function(){
    var content_result = "<div id='rvsearch'><div id='rvsearchresult' class='rvsearch_content'></div></div>";
    $(content_result).insertAfter("#category_search #searchbox");

    $('#search_query_nav').click(function(){
        $("#category_search").addClass('show');
    });

    $('body').click(function(){
        $('#rvsearch').slideUp(300);
        $("#category_search").removeClass('show');
    });

    $('#searchbox input.search_query').keyup(function(){ 
        $('.ac_results').remove();
        $('#rvsearch').slideDown(400);
        if(this.value.length < 3) {
            $('#rvsearchresult').html("<div class='char_limit'>" + limitCharacter + "</div>");				
        } else {
            var id_cat = $('#all_category').val();
            searchRvProducts(this.value, id_cat);
        }
    });

    $( "#all_category" ).change(function() {
        if($('#searchbox input.search_query').val().length < 3) {
            $('#rvsearchresult').html("<div class='char_limit'>" + limitCharacter + "</div>");
        } else {
            var id_cat = $('#all_category').val();
            searchRvProducts($('#searchbox input.search_query').val(), id_cat);
        }
    });
});

function searchRvProducts(inputString, id_cat) {
    $.post($('#rvajax_search input.ajaxUrl').val(), {queryString: inputString, id_Cat: id_cat}, function(data){ 
        $('#rvsearchresult').html(data);
    });
}
