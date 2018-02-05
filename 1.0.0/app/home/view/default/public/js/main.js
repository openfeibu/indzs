$(function(){

	$('.toRegister').click(function(){
		$(".registerDiv").show();
		$(".loginDiv").hide();
	});
	$('.toLogin').click(function(){
		$(".loginDiv").show();
		$(".registerDiv").hide();
	});


})
//ajax分页
function ajax_page(page) {
	$.ajax({
		type:"POST",
		data:{page:page},
		success: function(data,status){
			$("#news_list").html(data);
		}
	});
}
