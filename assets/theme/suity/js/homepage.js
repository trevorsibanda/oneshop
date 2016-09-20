$(document).ready(function(c) {
	$('.alert-close').on('click', function(c){
		$('.message').fadeOut('slow', function(c){
	  		$('.message').remove();
		});
	});	  
});

$(document).ready(function(c) {
	$('.alert-close1').on('click', function(c){
		$('.message1').fadeOut('slow', function(c){
	  		$('.message1').remove();
		});
	});	  
});

$(document).ready(function(c) {
	$('.alert-close2').on('click', function(c){
		$('.message2').fadeOut('slow', function(c){
	  		$('.message2').remove();
		});
	});	  
});

//init accordion
$(function() {
    var menu1_ul = $('.menu1> li > ul'),
           menu1_a  = $('.menu1 > li > a');
    menu1_ul.hide();
    menu1_a.click(function(e) {
        e.preventDefault();
        if(!$(this).hasClass('active')) {
            menu1_a.removeClass('active');
            menu1_ul.filter(':visible').slideUp('normal');
            $(this).addClass('active').next().stop(true,true).slideDown('normal');
        } else {
            $(this).removeClass('active');
            $(this).next().stop(true,true).slideUp('normal');
        }
    });

});

var $ = jQuery.noConflict();
$(function() {
	$('#activator').click(function(){
			$('#box').animate({'left':'0px'},500);
	});
	$('#boxclose').click(function(){
			$('#box').animate({'left':'-2300px'},500);
	});
});
$(document).ready(function(){

//Hide (Collapse) the toggle containers on load
$(".toggle_container").hide(); 

//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
$(".trigger").click(function(){
	$(this).toggleClass("active").next().slideToggle("slow");
	return false; //Prevent the browser jump to the link anchor
});

});


