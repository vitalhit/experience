$(function(){
	// Получаем даты в этом событии
	var event_id = $('.crm_tikets').attr('id');
	$.ajax({
		url: 'https://igoevent.com/site/dateapi?id='+event_id,
		dataType : "json",
		success: function (data) {
			$.each(data, function (index, item) { 
				Data = new Date(item.date.replace(/\s/, 'T'));
				Year = Data.getFullYear();
				Month = Data.getMonth();
				Day = Data.getDate();
				Hour = Data.getHours();
				Minutes = Data.getMinutes();

				if (Minutes == 0) { Minutes = '00'};

				switch (Month)
				{
					case 0: fMonth="января"; break;
					case 1: fMonth="февраля"; break;
					case 2: fMonth="марта"; break;
					case 3: fMonth="апреля"; break;
					case 4: fMonth="мая"; break;
					case 5: fMonth="июня"; break;
					case 6: fMonth="июля"; break;
					case 7: fMonth="августа"; break;
					case 8: fMonth="сентября"; break;
					case 9: fMonth="октября"; break;
					case 10: fMonth="ноября"; break;
					case 11: fMonth="декабря"; break;
				}

				var mydate = (Day+" "+fMonth+" "+Year+" "+Hour+":"+Minutes);

				$(".crm_tikets").append('<p class="tac"><a id="'+ item.id +'" class="my_button">'+ item.button +'<span>'+ mydate+'</span></a>'+ item.place +'<br>'+ item.address +'</p>');  
			});
		}               
	});	



	// Получаем билеты (места) в этой дате
	$(".crm_tikets").on('click', ".my_button", function(){
		$("html, body").animate({scrollTop: '50px' },"slow");
		var date_id = this.id;
		$.ajax({
			url: 'https://igoevent.com/site/formnewapi?id='+date_id,
			dataType : "json",
			success: function (tickets) {
				$(".mypopup").html(tickets);
				//console.log(tickets);
			}               
		});
		$('input[name="from_url"]').val(window.location.href);
		$(".mypopup").fadeIn();
		$(".my_overlay").fadeIn();
	});



	// Проверяем есть ли столько билетов в наличии
	$('.mypopup').on('change', '.count', function() {
		var userticket = this.value;
		var input = $(this);
		$(".mypopup .my_tick .counttik").html(userticket);
		$.ajax({
			type: 'get',
			url: "/site/countseatapi",
			data: {'userticket': userticket, 'seat': this.id, 'eventid': event_id},
			response: 'text',
			success: function(data){					
				$(input).val(data);
				var price = $(input).prev().prev().val();
				$(".mypopup .my_tick .summa").html(data);	
			}
		})
	});

	//Наличие такого юзера в системе по mail
	$('.mypopup').on('change', '.my_mail', function() {
		var mail = this.value;
		$.ajax({
			url: 'https://igoevent.com/site/userisapi?mail='+mail,
			dataType: 'json',
			success: function(data){
				$('.my_user').append(data);
				if (data){
/*					$('.secondname').val(data.second_name);
					$('.name').val(data.name);
					$('.phone').val(data.phone);
*/					$('.id').val(data.id);
				}
				// console.log(data);
			}
		})
		// console.log(mail);
	});




	// Отправляем форму
	$('.mypopup').on('click', '.submit', function() {
		$("html, body").animate({scrollTop: '0px' },"slow");
		var data = $('#event_form').serialize(); // пoдгoтaвливaeм дaнныe
			if ($('.counttik').html() > 0) {
				$('.loading').fadeIn(500);
				$.ajax({
					type: 'POST',
					url: 'https://igoevent.com/site/ticketbuyapi',
					dataType: 'json',
					data: data,
					success: function(data){
						$('.loading').fadeOut(500);
						$('.mypopup').html('<div class="pop_close"></div>' + data);
					}
				})
			} else {
				alert('Выберите хотя бы одно место!')
			};
		console.log(data);
		return false;
	});





	$("body").on('click', ".my_overlay, .btn_close", function(){
		$("html, body").animate({scrollTop: '0px' },"slow");
		$(".endreg").fadeIn();
		$(".my_form").fadeOut();			
	});

	$("body").on('click', ".mypopup_goon", function(){
		$(".endreg").fadeOut();
		$(".my_form").fadeIn();
	});

	$("body").on('click', ".mypopup_close", function(){
		$(".endreg").fadeOut();			
		$(".my_form").fadeIn();
		$(".mypopup").fadeOut();
		$(".my_overlay").fadeOut();
	});

	$("body").on('click', ".pop_close", function(){
		$(".mypopup").fadeOut();
		$(".my_overlay").fadeOut();
	});

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

});