document.addEventListener("DOMContentLoaded", function() {
	var mybtn = document.getElementsByClassName('igo_btn');
	[].forEach.call(mybtn, function(el){
		el.addEventListener("click", function(e){
			e.preventDefault();
			var id = el.id;
			var list = el.getAttribute('list');
			// console.log(list);
			id = encodeURIComponent(id);
			list = encodeURIComponent(list);
			var request = new XMLHttpRequest();
			// console.log('создали первый запрос request');
			// console.log(request);
			request.open('GET','https://igoevent.com/api/dateselect?id='+id+'&list_id='+list,true);
			request.addEventListener('readystatechange', function() {
				if ((request.readyState==4) && (request.status==200)) {

					var body = document.body;
					var popup = document.getElementById('igo_popup');
					popup.innerHTML = JSON.parse(request.responseText);
					popup.style.display = 'block';
					body.appendChild(popup);
					// var overlay = document.createElement('div');
					// overlay.className = "igo_overlay";
					var overlay = document.querySelector('.igo_overlay');
					overlay.style.display = 'block';
					body.appendChild(overlay);
					// var loading = document.createElement('div');
					// loading.className = "loading";
					// loading.innerHTML = "<div class='loadico'></div>";
					var loading = document.querySelector('.loading');
					body.appendChild(loading);



					popup.querySelector('.igo_close').addEventListener('click', function(e){
						overlay.style.display = 'none';
						popup.style.display = 'none';
					});
					document.querySelector('.igo_overlay').addEventListener('click', function(e){
						this.style.display = 'none';
						popup.style.display = 'none';
					});

					// $('input[name="from_url"]').val(window.location.href);
					// getCookies();

					// Выбираем дату из селекта
					var popup = document.getElementById('igo_popup');
					var event = popup.querySelector('#event_id');

					// console.log('изменили селект');
					// console.log(event);

					event.addEventListener("change", function(){
						var elem = (typeof this.selectedIndex === "undefined" ? window.event.srcElement : this);
						var date_id = elem.value || elem.options[elem.selectedIndex].value;

						// console.log('получили id даты');
						// console.log(date_id);

								// Получаем билеты в дате
								var rseat = new XMLHttpRequest();
								// console.log('создали второй запрос rseat');
								// console.log(rseat);
								rseat.open('GET','https://igoevent.com/api/formselect?id='+date_id,true);
								rseat.addEventListener('readystatechange', function() {
									if ((rseat.readyState==4) && (rseat.status==200)) {
										var ajaxseats = popup.querySelector('.ajaxseats');
										// console.log('ajaxseats');
										// console.log(ajaxseats);
										ajaxseats.innerHTML = JSON.parse(rseat.responseText);

										// Проверим mail гостя
										var igo_mail = popup.querySelector('#igo_mail').addEventListener('change', igoMail);
										var count = document.getElementsByClassName('igo_count');
										[].forEach.call(count, function(count){
											count.addEventListener("change", function(){
												if(count.value > 0){
													var rcount = new XMLHttpRequest();
													var userticket = count.value;
													var id = count.getAttribute('id');
													console.log('создали запрос rcount');
													console.log(rcount);
													console.log(userticket);
													console.log(id);
													rcount.open('GET','https://igoevent.com/api/countseat?userticket='+userticket+'&seat='+id,true);
													rcount.addEventListener('readystatechange', function() {
														if ((rcount.readyState==4) && (rcount.status==200)) {
															count.innerHTML = JSON.parse(rcount.responseText);
															count.value = rcount.responseText;
														};
													});
													rcount.send(userticket, id);
												}
											});
										});
										// console.log('получили igo_mail');
										// console.log(igo_mail);

										var submit = popup.querySelector('#submit').addEventListener('click', submitForm);

									};
								});
								rseat.send(date_id);
							});

							// Проверим mail гостя
							var popup = document.getElementById('igo_popup');
							var igo_mail = popup.querySelector('#igo_mail').addEventListener('change', igoMail);
							var count = document.getElementsByClassName('igo_count');
							[].forEach.call(count, function(count){
								count.addEventListener("change", function(){
									if(count.value > 0){
										var rcount = new XMLHttpRequest();
										var userticket = count.value;
										var id = count.getAttribute('id');
										console.log('создали запрос rcount');
										console.log(rcount);
										console.log(userticket);
										console.log(id);
										rcount.open('GET','https://igoevent.com/api/countseat?userticket='+userticket+'&seat='+id,true);
										rcount.addEventListener('readystatechange', function() {
											if ((rcount.readyState==4) && (rcount.status==200)) {
												count.innerHTML = JSON.parse(rcount.responseText);
												count.value = rcount.responseText;
											};
										});
										rcount.send(userticket, id);
									}
								});
							});

							// console.log('получили igo_mail');
							// console.log(igo_mail);

							var submit = popup.querySelector('#submit').addEventListener('click', submitForm);

						}
					});
request.send(id);
});
});
});

// Проверим mail гостя
function igoMail(){
	var mail = this.value;
	var rmail = new XMLHttpRequest();
	// console.log('создали третий запрос rmail');
	// console.log(mail);
	// console.log(rmail);
	rmail.open('GET','https://igoevent.com/api/jsuseresset?mail='+mail,true);
	rmail.addEventListener('readystatechange', function() {
		if ((rmail.readyState==4) && (rmail.status==200)) {
			var user_id = document.getElementById('id').value = rmail.responseText;
		};
	});
	rmail.send(mail);
}


function submitForm(){
	event.preventDefault();
	var err = 0;
	var required = document.getElementsByClassName('required');
	[].forEach.call(required, function(required){
		if(!required.value){
			required.style.border = "#f00 solid thin";
			err = 1;
			// console.log(err);
		}else{
			required.style.border = "#ccc solid thin";
			err = 0;
			// console.log(err);
		};
	});

	var counttik = 0;
	var count = document.getElementsByClassName('igo_count');

	[].forEach.call(count, function(count){
		if(count.value > 0){
			counttik = counttik + 1;
		}
		// console.log('counttik');
		// console.log(counttik);
		var countspan = document.querySelector('.counttik');
		countspan.innerHTML = counttik;
	});
	if(counttik > 0){
		if(err == 0){
			var form = document.querySelector('#igo_form');
			var data = new FormData(form);
			// console.log('форма');
			// console.log(data);

			var sendform = new XMLHttpRequest();
			sendform.open('POST', 'https://igoevent.com/api/ticketbuy');
			sendform.addEventListener('readystatechange', function() {
				if ((sendform.readyState==4) && (sendform.status==200)) {
					var popup = document.getElementById('igo_popup');
					popup.innerHTML = JSON.parse(sendform.responseText);
				};
			});
			sendform.send(data);
		}
	}else {
		alert('Выберите хотя бы одно место!')
	};
};