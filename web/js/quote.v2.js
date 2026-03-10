$(document).ready(function(){

	$('#text_preview').css('font-size','28px');
	$('#text_preview').css('text-align','center');
	$('#text_preview').css('color','#000');
	$('#color').val('#000');
	$('#img_preview').attr('src', '/web/images/harplivemusic_6270-2-1kx1k-b70.jpg');


	$('.color').change(function(){
		$('#text_preview').css('color',$(this).val());
		$('#color').val($(this).val());
	});

	$('.fon').change(function(){
		$('#img_preview').attr('src', '/web/images/'+$(this).val()+'.jpg');
	});

	$('#color').bind('input propertychange, change', function(){
		$('#text_preview').css('color',$(this).val());
	});

	$('#text').bind('input propertychange', function(){
		$('#text_preview').html($(this).val());
	});

	$('.centr').change(function(){
		$('#text_preview').css('text-align',$(this).val());
	});

	$('.size').change(function(){
		$('#text_preview').css('font-size',$(this).val()+'px');
	});



});
