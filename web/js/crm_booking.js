$(function(){

	// $('.f_title').val($('h1').text());
	// $('.from_url').val(document.location.href);

	var url = window.location.href;
	// console.log(url);
	$('.from_url').val(url);


	// Устанавливаем куки в браузер
	function setCookie(key) {
		var date = new Date(new Date().getTime() + 60 * 60 * 24 * 365 * 1000);
		name = key;
		var s = window.location.search;
		s = s.match(new RegExp(key + '=([^&=]+)'));
		if (s) {
			var value =s[1];
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
			// console.log (cookie[0]);
		}
		return;
	}

	getCookies();


	// Отправляем форму
	$('#book_form').on('click', '.submit', function() {
		var data = $('#book_form').serialize(); // пoдгoтaвливaeм дaнныe
		$.ajax({
			type: 'POST',
			url: 'https://crm.goodrepublic.ru/site/bookingapi',
			dataType: 'json',
			data: data,
			success: function(data){
				$('#book_form').fadeOut(500);
				$('.book_form .book_messageok').fadeIn(500);
			}
		});
		console.log(data);
		return false;
	});
	

});