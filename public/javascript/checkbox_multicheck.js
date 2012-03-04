// JavaScript Document
(function(){
   $(document).bind('ready',function(){
       $("#multi_check").click(function(){            
            if(this.checked){                
                 $(":checkbox:gt(0)").attr('checked',true);
            }else{
                 $(":checkbox:gt(0)").attr('checked',false);
            }
       });
   })
}())