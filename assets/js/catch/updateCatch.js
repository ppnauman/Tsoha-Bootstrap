//modify content of trap_type and trap_model <SELECT>s 
$(document).ready(function(){
  //hide trapmodels of unselected type (when updating catch) 
  $("option.trapmodel").each(function () {
    if(!$(this).hasClass($("select[name='trap_type']").val())) {
        $(this).hide();
    }
  });
  //view traps when traptype selection is changed (when creating / updating catch)
  $("select[name='trap_type']").change(function(){
      showTrapModels($(this).val());
  });
  
});

function showTrapModels(trapType){
    $("select[name='trap_model']").val('default');
    $("option.trapmodel").hide();
    $("option."+trapType+"").show();   
};

