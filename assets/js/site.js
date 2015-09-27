$(document).ready(function(){
    
  $("select[name='traptype']").change(function(){
      showTrapModels($(this).val());
  });
  
});

function showTrapModels(trapType){
    $("#trapmodelGroup").removeClass("hidden");
    $("select[name='trapmodel']").val('default');
    $("option.trapmodel").hide();
    $("option."+trapType+"").show();   
};


