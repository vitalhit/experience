$(function(){
	// Подключим другие файлы API
	// var x = document.createElement('script');
	// x.src = 'https://igoevent.com/js/igoeventadd.js';
	// x.async = false;
	// document.getElementsByTagName("head")[0].appendChild(x);


	// Получаем даты в этом событии
	$.each($('.igoevent_event'), function() { 
		var event = $(this);
		var event_id = $(this).attr('id');
		var list_id = $(this).attr('tag');
		$.ajax({
			url: 'https://igoevent.com/api/dates?id='+event_id+'&list_id='+list_id,
			dataType : "json",
			success: function (dates) {
				$(event).html(dates);
			}
		});
	});

	$('.igo_popup').remove();
	$('.igo_overlay').remove();
	$('.loading').remove();
	$("body").append('<div class="igo_popup"></div><div class="igo_overlay"></div><div class="loading"><div class="loadico"></div></div>');


	// Получаем билеты в этой дате
	$('.igoevent_event').on('click', ".igo_btn", function(e){
		e.preventDefault();
		$("html, body").animate({scrollTop: '50px' },"slow");
		var date_id = this.id;
		$.ajax({
			url: 'https://igoevent.com/api/form?id='+date_id,
			dataType : "json",
			success: function (form) {				
				$(".igo_popup").html(form);
				$('input[name="from_url"]').val(window.location.href);
				getCookies();
				$(".igo_popup").fadeIn();
				$(".igo_overlay").fadeIn();
				//console.log(form);
			}
		});
	});

	// Оплата конкретного билета
	$('.igoevent_seat').on('click', ".igo_btn", function(e){
		e.preventDefault();
		$("html, body").animate({scrollTop: '50px' },"slow");
		var date_id = this.id;
		var seat_id = $(this).attr('tag');
		$.ajax({
			url: 'https://igoevent.com/api/seat?id='+date_id+'&seatid='+seat_id,
			dataType : "json",
			success: function (form) {				
				$(".igo_popup").html(form);
				$('input[name="from_url"]').val(window.location.href);
				getCookies();
				$(".igo_popup").fadeIn();
				$(".igo_overlay").fadeIn();
				//console.log(form);
			}
		});
	});



	// Видже2 с селектом даты
	$('.igoevent_event2').on('click', ".igo_btn", function(e){
		e.preventDefault();
		$("html, body").animate({scrollTop: '50px' },"slow");
		var event_id = $(this).attr('id');
		var list_id = $(this).attr('tag');
		// console.log(event_id);
		$.ajax({
			url: 'https://igoevent.com/api/dateselect?id='+event_id+'&list_id='+list_id,
			dataType : "json",
			success: function (form) {				
				$(".igo_popup").html(form);
				$('input[name="from_url"]').val(window.location.href);
				getCookies();
				$(".igo_popup").fadeIn();
				$(".igo_overlay").fadeIn();
			}
		})
	});



	// Получаем BOOKFORM для - оставьте заявку - если в событии нет дат
	$(".igoevent_event").on('click', ".igo_book_btn", function(){
		$("html, body").animate({scrollTop: '50px' },"slow");
		var id = $(this).closest('.igoevent_event').attr('id');
		var list_id = $(this).closest('.igoevent_event').attr('tag');
		var template = $(this).closest('.igoevent_event').attr('template');
		// console.log(list_id);
		$.ajax({
			url: 'https://igoevent.com/api/bookform?id='+id+'&list_id='+list_id+'&template='+template,
			dataType : "json",
			success: function (form) {
				$(".igo_popup").addClass('igo_fix');
				$(".igo_popup").html(form);
				$('input[name="from_url"]').val(window.location.href);
				getCookies();
				$( ".igo_popup.igo_fix" ).fadeIn(0).animate({top: '15%', opacity: '1'}, 1000);
				$(".igo_overlay").fadeIn();
			}
		});
	});

	
    // Изменить кол-во билетов Без места
    $(document).on('click', ".btn-num", function(e){
    	e.preventDefault();
    	fieldName = $(this).attr('data-field');
    	type      = $(this).attr('data-type');
    	var input = $("#"+fieldName);
    	seatid      = $(input).attr('seatid');
    	var currentVal = parseInt(input.html());

    	if(type == 'minus') {
    		if(currentVal > input.attr('min')) {
    			input.html(currentVal - 1);
    		} 
    		if(parseInt(input.html()) == input.attr('min')) {
    			$(this).attr('disabled', true);
    		} else {
    			$('.btn-plus').attr('disabled', false);
    		}
    	} else if(type == 'plus') {
    		if(currentVal < input.attr('max')) {
    			input.html(currentVal + 1);
    		}
    		if(parseInt(input.html()) == input.attr('max')) {
    			$(this).attr('disabled', true);
    		} else {
    			$('.btn-minus').attr('disabled', false);
    		}
    	}
    	var userticket = parseInt(input.html());

    	$.ajax({
    		type: 'get',
    		url: "https://igoevent.com/api/countseat",
    		data: {'userticket': userticket, 'seat': seatid},
    		response: 'text',
    		success: function(data){
    			$(input).val(data);
    			input.html(data);
    			console.log('кол-во(igoeventapi-2023): ' + data);
    			console.log('seatid: ' + seatid);

        // console.log(currentVal);
        // console.log(seatid);

		        // Пересчет суммы
		        var all_summa = 0;
		        var counttik = 0;
		        $('.check').each(function() {
		        	all_summa = all_summa + parseInt($(this).attr('seatsum'));
		        });
		        $('.tick_num').each(function() {
		        	all_summa = all_summa + parseInt($(this).attr('ticksum') * $(this).html());
		        	counttik = counttik + parseInt($(this).html());
		        });
		        $('.all_summa').html(all_summa);
		        $('.counttik').html(counttik);
		    }
		})

    });
    

	// Проверяем есть ли столько билетов в наличии
	$(document).on('change', '.igo_count', function() {
		var userticket = this.value;
		var input = $(this);
		// console.log(userticket);
		// console.log(input);
		$(".igo_popup .igo_tick .counttik").html(userticket);
		$.ajax({
			type: 'get',
			url: "https://igoevent.com/api/countseat",
			data: {'userticket': userticket, 'seat': this.id},
			response: 'text',
			success: function(data){
				$(input).val(data);
				var price = $(input).prev().prev().val();
				$(".igo_popup .igo_tick .summa").html(data);
				// console.log(data);
			}
		})
	});



	//Наличие такого юзера в системе по mail
	$(document).on('change', '.igo_mail', function() {
		var mail = this.value;
		var company_id = $('.igo_form .company_id').val();
		$.ajax({
			url: 'https://igoevent.com/api/useresset?mail='+mail+'&company_id='+company_id,
			dataType: 'json',
			success: function(data){
				if (data){
					$('.id').val(data.id);
				}else{
					$('.id').val("");
				}
			}
		})
	});


	// Отправляем форму
	$(document).on('click', '.submit', function() {
		$("html, body").animate({scrollTop: '0px' },"slow");

		var err = 0;
		$(document).find("input.required").each(function() {// проверяем каждое поле в форме
			if(!$(this).val()){// если поле пустое
				$(this).css('border', 'red 1px solid');
				err = 1;
			}else{
				$(this).css('border', '#ccc 1px solid');
				err = 0;
			}
		});

		var counttik = 0;
		$(document).find("input.igo_count").each(function() {// проверяем кол-во билетов
			if($(this).val() > 0){// если поле НЕ пустое
				counttik = counttik + 1;       	
			}
			$('.counttik').html(counttik);
		});

		console.log(counttik);
		// console.log(err);

		var data = $('#igo_form').serialize(); // пoдгoтaвливaeм дaнныe
		if ($('.counttik').html() > 0) {
			if(err == 0) {
				$('.loading').fadeIn(500);
				$.ajax({
					type: 'POST',
					url: 'https://igoevent.com/api/ticketbuy',
					dataType: 'json',
					data: data,
					success: function(data){
						$('.loading').fadeOut(500);
						$('.igo_popup').html('<div class="igo_close"></div>' + data);
					}
				})
			}
		} else {
			alert('Выберите хотя бы одно место!')
		};
		// console.log(data);
		return false;
	});



	// Отправляем заявку BOOKFORM
	$(document).on('click', '.book_submit', function() {
		$("html, body").animate({scrollTop: '0px' },"slow");

		var err = 0;
		$(document).find("input.required").each(function() {// проверяем каждое поле в форме
			if(!$(this).val()){// если поле пустое
				$(this).css('border', 'red 1px solid');
				err = 1;
			}else{
				$(this).css('border', '#ccc 1px solid');
				err = 0;
			}
		});

		var data = $('#igo_book_form').serialize(); // пoдгoтaвливaeм дaнныe
		if(err == 0) {
			$('.loading').fadeIn(500);
			$.ajax({
				type: 'POST',
				url: 'https://igoevent.com/api/booking',
				dataType: 'json',
				data: data,
				success: function(data){
					$('.loading').fadeOut(500);
					$('.igo_popup').html('<div class="igo_close"></div>' + data);
				}
			})
		}
		// console.log(data);
		return false;
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

	// $("body").on('click', ".igo_popup_close", function(){
	// 	$(".endreg").fadeOut();			
	// 	$(".igo_form").remove();
	// 	$(".igo_popup").fadeOut();
	// 	$(".igo_overlay").fadeOut();
	// });

	$("body").on('click', ".igo_popup_close", function(){ location.reload(); });


	// $("body").on('click', ".igo_fix .igo_popup_close, .igo_fix .igo_close", function(){
	// 	$(".igo_fix").animate({top: '-1000px', opacity: '0'}, 1000);
	// 	$(".igo_form").remove();
	// 	$(".overlay").fadeOut();
	// });

	$("body").on('click', ".igo_fix .igo_popup_close, .igo_fix .igo_close", function(){ location.reload(); });


	// $("body").on('click', ".igo_close", function(){
	// 	$(".igo_popup").fadeOut();
	// 	$(".igo_overlay").fadeOut();
	// });
	
	$("body").on('click', ".igo_close", function(){ location.reload(); });


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