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
      gotoPage(page,page+1);
  });

  $(".btn-prev").click(function(){
      gotoPage(page,page-1);
  });

 function gotoPage(from,to)
 {
   var params = $(".pageData").serialize();
   params += '&ci_csrf_token='+csrf_token;
   params += '&resource='+resource;
   params += '&pageFrom='+from;
   params += '&pageTo='+to;

   $.ajax({
       type:"POST",
       url: ajaxController+'page',
       data: params,
       success: function(data){
           console.log(data);

           //remove previous validation
           $('.control-group').removeClass('error');
           $('.help-inline').html('');
           $('.message').html('');
           //---------------------------

           switch (data.error)
           {
               case 'none':
                   window.location = baseUrl+resource+'/page/'+to;
                   break;

               case 'Failed Validation':
                   console.log('validation check.');
                   $('.message').html(
                       '<div class="alert">' +
                           '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                           '<strong>Please Correct.</strong> The information you submitted contains errors.'+
                      '</div>'
                   );
                   for (index in data.formErrors)
                   {
                       //add error and message to Twitter Bootstrap control group.
                       $("#"+index+"Group").find('.control-group').toggleClass('error');
                        console.log("#"+index+"Error");
                       $("#"+index+"Group").find('.help-inline').html(data.formErrors[index]);
                   }
                   break;
           }

       },
       dataType: 'json'
   })
     .fail(function(){
          console.log("Failed to transfer data to next page.");
     })
   ;
 }

 if (sendReceipts == 'yes'){
     receipts();
 }

  // receipt driver
 function receipts()
 {
    console.log("creating receipts");
    createReceipt('unsecure');
    createReceipt('secure');

 }

 function receiptCallBack(level){

     switch(level)
     {
         case 'unsecure':
             return function(data){
                 sendEmail('receipt');
             };

         case 'secure':
             return function(data){
                 sendEmail('notice');
             };

     }
 }

 function sendEmail(type)
 {
     var params = {
        method: type,
        resource: resource,
        ci_csrf_token: csrf_token
     };

     var url = ajaxController+'notify';
     $.post(url,params);
 }

 function createReceipt(level){
     //var params = 'ci_csrf_token='+csrf_token;
     //params += '&resource='+resource;
     //params += '&security='+level;
     //params += '&getReceipt='+'yes';

     var params = {ci_csrf_token: csrf_token,
               resource: resource,
               security: level,
               getReceipt: 'yes'
     };

     $.ajax({
         type:"POST",
         url: ajaxController+'receipt',
         data: params,
         success: receiptCallBack(level),
         dataType: 'json'
     })
      .fail(function(){
           console.log("Failed to create receipt.")
         })
     ;

 }

})(jQuery);
