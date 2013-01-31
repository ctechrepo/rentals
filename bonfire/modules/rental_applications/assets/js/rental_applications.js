/**
 * Author: Shawn Rhoney
 * Date: 1/29/13
 */

var ajaxController = "//localhost:8888/rentals/rental_applications/ajax/";

(function($)
{
  $("select[name='school_filter']").change(function(){

      params = {school:$(this).val()};

      $.get(ajaxController+'accessories',params,function(data){
          console.log(data);
      },'json')
          .fail(function(){
              console.log("Failed to get data from 'accessories' ");
          });
  });
}

)(jQuery);
