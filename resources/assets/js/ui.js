var ui = {
	popup:{
		show:function(type, title, message, button, url, cancelBtn = false) {
			if (type == 'error') {
				$("#iconError").removeClass('hidden')
				$("#iconSuccess").addClass('hidden')
				$("#titleNotif").html(title);
				$("#contentNotif").html(message);
				$("#btnNotif").html('<button onclick="window.location.reload();" type="button" data-dismiss="modal" aria-label="Close" class="btn btn-save">'+button+'</button>')
				$('#modalNotif').modal({
					backdrop: 'static',
					show: true,
				});
			} else if(type == 'success'){
				// $("#iconError").addClass('hidden')
				// $("#iconSuccess").removeClass('hidden')
				if (url == 'close') {
					
					// $("#titleNotif").html(title);
					$("#titleNotif").html(message);
					// $("#titleNotif").html(message);
					// $("#btnNotif").html('<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif">'+button+'</button>')
					$("#btnNotif").html(`<button onClick="window.location.href = '${window.location.origin}${url}'" class="btn btn-save btn-status btn-notif" data-dismiss="modal" aria-label="Close" type="button" id="buttonStatusUser" class="btn-notif">Lanjutkan</button>`)
					$('#modalNotif').modal({
						backdrop: 'static',
						show: true,
					});
				} else {
					let cancelButton = ''
					if(cancelBtn ){
						cancelButton = '<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel" id="">Batalkan</button>';
					}
					// $("#titleNotif").html(title);
					$("#titleNotif").html(message);
					// $("#titleNotif").html(message);
					
					// $("#btnNotif").html('<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif">'+button+'</button>')
					$("#btnNotif").html(`<button class="btn btn-save btn-status" onClick="window.location.href = '${window.location.origin}${url}'"  data-dismiss="modal" aria-label="Close" type="button" id="buttonStatusUser" class="btn-notif">Lanjutkan</button>`)
					$('#modalNotif').modal({
						backdrop: 'static',
						show: true,
					});
					$("#notifRedirect").click(function(){
						window.location = url
					})
				}
			}  else {
				$("#iconError").removeClass('hidden')
				$("#iconSuccess").addClass('hidden')
				Swal.fire({
					title: message,
				  	type: type,
					confirmButtonText: 'Mengerti',
					allowOutsideClick: false
				})
			}
		},
		showLoader: function showLoader() {
			$("#loading-overlay").addClass("active");
			$("body").addClass("modal-open");
		},
		hideLoader: function hideLoader() {
			$("#loading-overlay").removeClass("active");
			$("body").removeClass("modal-open");
		},
		hide: function hide(id) {
			$('.' + id).toggleClass('submitted');
		}

	},
	slide:{
		init:function(){
			$('.carousel-control').on('click',function(e){
				e.preventDefault();
				var control = $(this);

				var item = control.parent();

				if(control.hasClass('right')){
					ui.slide.next(item);
				}else{
					ui.slide.prev(item);
				}

			});
			$('.slideBtn').on('click',function(e){
				e.preventDefault();
				var control = $(this);
				var item = $("#"+control.attr('for'));
				
				if (item[0].id === 'page-1') {
					$('.tracking-line div').removeClass();
					$('.education-information img').attr('src', '/image/icon/homepage/track-toga-red.svg')
					$('.personal-information').removeClass('active')
					$('.education-information').addClass('active')
					$('.tracking-line div:first-child').addClass('red-line');
					$('.tracking-line div:last-child').addClass('gray-line');
				} else if (item[0].id === 'page-2') {
					$('.tracking-line div').removeClass();
					$('.other-information img').attr('src', '/image/icon/homepage/track-pin-red.svg')
					$('.education-information').removeClass('active')
					$('.other-information').addClass('active')
					$('.tracking-line div:first-child').addClass('red-line');
					$('.tracking-line div:last-child').addClass('red-line');
				} else {
					$('.tracking-line div').removeClass();
					$('.tracking-line div:first-child').addClass('red-line');
					$('.tracking-line div:last-child').addClass('red-line');
				}

				if(control.hasClass('btn-next')){
					ui.slide.next(item);
				}else{
					ui.slide.prev(item);
				}
			})
		},
		next:function(item){
			var nextItem = item.next();
			item.toggle({'slide':{
				direction:'left'
			}})
			nextItem.toggle({'slide':{
				direction:'right'
			}})
		},
		prev:function(item){
			var prevItem = item.prev();
			item.toggle({'slide':{
				direction:'right'
			}});
			prevItem.toggle({'slide':{
				direction:'left'
			}});
		}
	}
}

