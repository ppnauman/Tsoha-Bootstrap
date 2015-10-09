$(document).ready(function(){
    
  $("select[name='trap_type']").change(function(){
      showTrapModels($(this).val());
  });
  
});

function showTrapModels(trapType){
    $("#trapmodelGroup").removeClass("hidden");
    $("select[name='trap_model']").val('0');
    $("option.trapmodel").hide();
    $("option."+trapType+"").show();   
};


