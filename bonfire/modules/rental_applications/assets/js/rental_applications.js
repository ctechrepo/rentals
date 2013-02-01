/**
 * Author: Shawn Rhoney
 * Date: 1/29/13
 */

ajaxController = "//localhost:8888/rentals/rental_applications/ajax/";
baseUrl = "//localhost:8888/rentals/rental_applications/";


(function($)
{
   csrf_token = $.cookie('ci_csrf_token');

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

  $(".btn-next").click(function(){
      gotoPage(page+1);
  });

  $(".btn-prev").click(function(){
      gotoPage(page-1);
  });

 function gotoPage(page)
 {
   //var params = $("#pageData").serialize();
   var params = {ci_csrf_token : csrf_token};
   console.log(params);
   $.ajax({
       type:"POST",
       url: ajaxController+'page',
       data: params,
       success: function(data){
           window.location = baseUrl+resource+'/page/'+page;
       },
       dataType: 'json'
   })
     .fail(function(){
          console.log("Failed to transfer data to next page.");
     })
   ;
 }

})(jQuery);
