$(document).ready(function(){
	
	
	$('.navbar').fadeOut(0);
	
	setTimeout(function () {
		$('.navbar').fadeIn(300);
	}, 300);
	
	
	$('form').submit(function (e) {
		var prevent = false;
		$(this).find('input.required,textarea.required').each(function () {
			var elem = $(this);
			if (elem.val() === '') {
				prevent = true;
				elem.addClass('inp-error');
			} else {
				elem.removeClass('inp-error');
			}
		});
		if (prevent) {
			e.preventDefault();
			return false;
		}
	});
	
	setTimeout(function () {
		$('.success-plash-wrap').fadeOut(300);
	}, 3000);
	
	$(".closes").click(function() {
		$('#callback-modal').modal('hide');
	});
	
	$(".closes").click(function() {
		$('#manager-modal').modal('hide');
	});
	
	$(".closes").click(function() {
		$('#sale-modal').modal('hide');
	});
	
	$(".geo-this").click(function() {
		if ($(this).hasClass('open-geo')) {
			$(this).removeClass('open-geo');
			$('.geo-row').removeClass('active');
		} else {
			$(this).addClass('open-geo');
			$('.geo-row').addClass('active'); 
		}
	});
	$(".geo-in-this").click(function() {
		if ($(this).hasClass('open-geo')) {
			$(this).removeClass('open-geo');
			$('.geo-in-row').removeClass('active');
		} else {
			$(this).addClass('open-geo');
			$('.geo-in-row').addClass('active'); 
		}
	});
	
	
    $(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $(".geolocation"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			$('.geo-this').removeClass('open-geo');
		$('.geo-row').removeClass('active');
	}
		var geoin = $(".title-geo"); // тут указываем ID элемента
		if (!geoin.is(e.target) // если клик был не по нашему блоку
		    && geoin.has(e.target).length === 0) { // и не по его дочерним элементам
			$('.geo-in-this').removeClass('open-geo');
		$('.geo-in-row').removeClass('active');
	}
});


    // показать форму входа
    $(".jslogin").click(function() {
    	$(".site-login").addClass('open');
    });

    $(".close-login").click(function() {
    	$(".site-login").removeClass('open');
    });

    
    $(window).scroll(function() {
    	if ($(window).scrollTop() > 80) {
    		$('#nav').addClass('fixet');
    	} else {
    		$('#nav').removeClass('fixet');
    	}
    });
    
    // $("#nav-tgl").click(function() {
	   // $('#nav-tgl').removeClass('active');
    //     $('#nav').addClass('active');
    // });
    // $("#nav").on("click","a", function (event) {
    //     event.preventDefault();
    //     var id  = $(this).attr('href'),
    //     top = $(id).offset().top;
    //     $('body,html').animate({scrollTop: top}, 1500);
    // });
    
    $("#nav-tgl").click(function() {
    	if($(this).hasClass('active-tgl')){
    		$('#nav-tgl').removeClass('active-tgl');
    		$('#nav').removeClass('active');
    		$('.search-btn').removeClass('active-tgl').removeClass('active');
    		$('.sisea-search-form').removeClass('active');
    	}else{
    		$('#nav-tgl').addClass('active-tgl');
    		$('#nav').addClass('active');
    		$('.search-btn').addClass('active-tgl').removeClass('active');
    		$('.sisea-search-form').removeClass('active');
    	}
    });
    
    
});