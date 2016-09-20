$(function(){

	$('#change-form')
	   .jqTransform()
	   .validate({
         submitHandler: function(form) {
           $(form).ajaxSubmit({
                success: function() {
                    $('#change-form').hide();
                    $('#page-wrap').append("<p class='thanks'>Thanks! Your request has been sent.</p>")
                }
           });
         }
        }); 
        
    $("#addURLSArea").hide();
        

    $('.jqTransformCheckbox').click(function(){
        if ($('#multCheck:checked').val() == 'on') {
            $("#addURLSArea").slideDown();
        } else {
            $("#addURLSArea").slideUp();
        }
    });  
    
    $(".jqTransformRadio").click(function() {
        if ($(".jqTransformRadio").eq(1).attr("class") == "jqTransformRadio jqTransformChecked") {
            $("#curTextArea").slideUp();
        } else {
            $("#curTextArea").slideDown();
        }
    });
	
});