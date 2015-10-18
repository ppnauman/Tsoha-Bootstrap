

$(document).ready(function() {
    $("#search-box").keyup(function() {
       viewRows($(this).val());
   });
});


function viewRows(str){
    $("tr").hide();
    $("tr:contains('"+str+"')").show();
};

