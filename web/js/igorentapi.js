$(function(){
	// Получаем зал
	$(".igoevent_rent").on('click', ".igo_rent_btn", function(){
		var room_id = $(this).attr('tag');
		$("html, body").animate({scrollTop: '0px' },"slow");
		$.ajax({
			url: 'https://igoevent.com/api/bron?id='+room_id,
			dataType : "json",
			success: function (date) {
				$('.igo_popup').remove();
				$('.igo_overlay').remove();
				$('.loading').remove();
				$("body").append('<div class="igo_popup"></div><div class="igo_overlay"></div><div class="loading"><div class="loadico"></div></div>');
				$(".igo_popup").html(date);
				$('.userform').fadeOut();
				$('input[name="from_url"]').val(window.location.href);
				getCookies();
				$(".igo_popup").fadeIn(500);
				$(".igo_overlay").fadeIn(500);
			}
		});
	});



	$("body").on('click', ".igo_overlay, .btn_close", function(){
		$("html, body").animate({scrollTop: '0px' },"slow");
		$(".endreg").fadeIn();
		$(".igo_form").fadeOut();
	});

	$("body").on('click', ".igo_popup_goon", function(){
		$(".endreg").fadeOut();
		$(".igo_form").fadeIn();
	});

	$("body").on('click', ".igo_popup_close", function(){
		$(".endreg").fadeOut();			
		$(".igo_form").fadeIn();
		$(".igo_popup").fadeOut();
		$(".igo_overlay").fadeOut();
	});

	$("body").on('click', ".igo_fix .igo_popup_close, .igo_fix .igo_close", function(){
		$( ".igo_fix" ).animate({top: '-1000px', opacity: '0'}, 1000);
		$( ".overlay" ).fadeOut();
	});


	$("body").on('click', ".igo_close", function(){
		$(".igo_popup").fadeOut();
		$(".igo_overlay").fadeOut();
	});


		// Устанавливаем куки в браузер
	function setCookie(key) {
		var date = new Date(new Date().getTime() + 60 * 60 * 24 * 365 * 1000);
		var name = key;
		var s = window.location.search;
		s = s.match(new RegExp(key + '=([^&=]+)'));
		if (s) {
			var value = s[1];
			document.cookie = name + "=" + value + "; expires=" + date.toUTCString();
		};
	}
	setCookie('utm_source');setCookie('utm_medium');setCookie('utm_campaign');setCookie('utm_content');setCookie('utm_term'); 

	
	// Получаем куки и записываем их в форму
	function getCookies(){
		var cookies = document.cookie.split("; ");
		for (var i=0; i<cookies.length; i++){
			var cookie = cookies[i].split("=");
			$('input[name="'+cookie[0]+'"]').val(cookie[1]);
			// console.log (cookie[1]);
		}
		return;
	}


});