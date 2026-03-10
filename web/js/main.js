jQuery(document).ready(function(){

    $('.alert').animate({ "top": "80px"}, 1000).delay(5000).fadeOut(500);

    $('.alert .close').click(function () {
        $('.alert').animate({ "top": "-200px"}, 1000);
    });

	// Все картинки
	$('.js_allimg').click(function(){
		var id = $(this).attr('oid');
		$.ajax({
			type: 'POST',
			url: 'https://igoevent.com/site/allimg?id='+id,
			success: function(data){
				$('.allimg').html(data);
			}
		});
	});

	// Все картинки
	$('.js_allimgband').click(function(){
		var id = $(this).attr('oid');
		$.ajax({
			type: 'POST',
			url: 'https://igoevent.com/site/allimgband?id='+id,
			success: function(data){
				$('.allimgband').html(data);
			}
		});
	});

	// Все картинки
	$('.js_allimglanding').click(function(){
		var id = $(this).attr('oid');
		$.ajax({
			type: 'POST',
			url: 'https://igoevent.com/site/allimglanding?id='+id,
			success: function(data){
				$('.allimglanding').html(data);
			}
		});
	});
	
	// Все картинки для выбора картинок лендинга
	$('.js_allimglandingimg').click(function(){
		var id = $(this).attr('oid');
		var imageid = $(this).attr('imageid');
		$.ajax({
			type: 'POST',
			url: 'https://igoevent.com/site/allimglandingimg?id='+id+'&imageid='+imageid,
			success: function(data){
				$('.allimglandingimg').html(data);
			}
		});
	});

	// Popups
	$('.js_popup').click(function(){
        var myscrollpop = ($(window).scrollTop()) + 100;
        var tag = $(this).attr('tag');
        if (tag) {
            $('.popup.'+tag).animate({top: myscrollpop, 'opacity': 1}, { duration: 500, queue: false }).fadeIn(500);
        }else{
            $('.popup').animate({top: myscrollpop, 'opacity': 1}, { duration: 500, queue: false }).fadeIn(500);
        };
        $('.overlay').fadeIn(500);
    });

	
	// Popups
	$('.js_buy').click(function(){
		var myscrollpop = $(window).scrollTop();
		$('.popup.buy').animate({'top': myscrollpop + 100, 'opacity': 1}, 500);
		$('.overlay').fadeIn();
	});

	$('.js_end').click(function(){
		var myscrollpop = $(window).scrollTop();
		$('.popup.end').animate({'top': myscrollpop + 100, 'opacity': 1}, 500);
		$('.overlay').fadeIn();
	});

	$('.overlay, .clo').click(function(){
		$('.popup').animate({'top': - 10000, 'opacity': 0}, 500);
		$('.overlay').fadeOut();
		$('.search_result').fadeOut(); // прячем результат поиска
		$(".form_lite").css({'zIndex': 1000});
	});


	// Popups Вопрос
	$('.nav .quest').click(function(e){
		var myscrollpop = $(window).scrollTop();
		$('#quest_page').val(window.location.href);
		if (window.location.href.indexOf('/tasks/update?id') != -1){
			$('#quest_task').val(window.location.href.replace(/[^-0-9]/gim,''));
		}
		$('.popup.pop_quest').animate({'top': myscrollpop + 100, 'opacity': 1}, 500);
		$('.overlay').fadeIn();
		e.preventDefault();
	});

	// Отправляем форму
	$('#quest_form').on('click', '.submit', function(e) {
		var forma = $('#quest_form').serialize(); // пoдгoтaвливaeм дaнныe
		$.ajax({
			type: 'POST',
			url: 'https://igoevent.com/site/feedback',
			data: forma,
			success: function(data){
				$('#quest_form').fadeOut(500);
				$('.overlay').fadeOut(500);
				$('.pop_quest').html(data).delay(3000).fadeOut(500);
			}
		});
		e.preventDefault();
	});




	// кнопка вверх
	$(window).scroll(function() {
		if ($(this).scrollTop() > 100) {
			if ($('.up_btn').is(':hidden')) {
				$('.up_btn').css({opacity : 1}).fadeIn('slow');
			}
		} else { $('.up_btn').stop(true, false).fadeOut('fast'); }
	});
	$('.up_btn').click(function() {
		$('html, body').stop().animate({scrollTop : 0}, 300);
	});

	$('.left_toggle').click(function(){
		$('.left_blo').slideToggle();
	});


	$(function(){
		$('.blocks').css({'minHeight':($('.blocks').width()+30)}); 

		$(window).resize(function(){
			$('.blocks').css({'minHeight':($('.blocks').width()+30)}); 
		});
	});


	$('.stacktable').stacktable();

	$(function(){
		$('.left_blo').css({minHeight: $('.wrap').height() - 170});
		$(window).resize(function(){
			$('.left_blo').css({minHeight: $('.wrap').height() - 170});
		});
	});

	$(function(){
		$('.main_right').css({minHeight: $('.wrap').height() - 50});
		$(window).resize(function(){
			$('.main_right').css({minHeight: $('.wrap').height() - 50});
		});
	});





    // изменить настройку
    $(document).on("input", '.jMark', function() {
        var tik_id = $(this).attr('tik_id');
        var text = this.value;
        $.ajax({
            type: 'POST',
            url: "/api/mark",
            timeout: 3000,
            data: {'tik_id': tik_id, 'text': text},
            response: 'text',
            success: function(data) {
            	// alert(data.msg);
            }
        })
    });







});