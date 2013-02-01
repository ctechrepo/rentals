/**
 * Author: Shawn Rhoney
 * Date: 1/29/13
 */

ajaxController = "//localhost:8888/rentals/rental_applications/ajax/";

(function($)
{
  $("select[name='school']").change(function(){

      var params = $("#recommendations").serialize();

      $.get(ajaxController+'accessories',params,function(data){

         var objectArray = eval(data.list);
         $("input[type='checkbox']").attr('checked',false);

         for (var i = 0; i < objectArray.length; i++)
         {
             var id = objectArray[i].accessory_id;
             $("input[type='checkbox'][value='"+id+"']").attr('checked',true);
         }

         console.log(objectArray);
         console.log(objectArray[0].accessory_id);

      },'json')
          .fail(function(){
              console.log("Failed to get data from 'accessories' ");
          })
      ;
  });
}

)(jQuery);
