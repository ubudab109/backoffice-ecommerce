const baseUrl  = $('meta[name=base]').attr('content')+'/';
const baseImage  = $('meta[name=baseImage]').attr('content')+'/';
const cdn  = $('meta[name=cdn]').attr('content');

var other = {
	
	encrypt:function(formdata,callback){
		$.ajax({
			url:baseUrl+'s',
			type:'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success:function(data){
				var pass = data;
				if(pass.status!=="error" && pass.status!=="reload"){
					var password = pass.password;
					var salt = CryptoJS.lib.WordArray.random(128/8); 
			        var key256Bits500Iterations = CryptoJS.PBKDF2("Secret Passphrase", salt, { keySize: 256/32, iterations: 500 });
			        var iv  = CryptoJS.enc.Hex.parse(password[2]); 
			        if(formdata.indexOf("&captcha=")){
			        	var form = formdata.split("&captcha=");
			        	var captcha = form[1];
			        	formdata = form[0];
			        }
					var encrypted = CryptoJS.AES.encrypt(formdata+'&safer=',key256Bits500Iterations,{ iv: iv });

					var data_base64 = encrypted.ciphertext.toString(CryptoJS.enc.Base64); 
			        var iv_base64   = encrypted.iv.toString(CryptoJS.enc.Base64);       
			        var key_base64  = encrypted.key.toString(CryptoJS.enc.Base64);
					
					var encData = data_base64+password[0]+iv_base64+password[1]+key_base64+password[2];
					var data = {data:encData};
					if(captcha!='undefined'){
						data["captcha"] = captcha;
					}
					callback(null,data);
				}else{
					swal({
						title:pass.messages.title,
						text:pass.messages.message,
						type:"error",
						html:true,
						showCancelButton: true,
						confirmButtonColor: "green",
						confirmButtonText:"Refresh"
					},function(){
						location.reload();
					})
				}
			}
		});
	},

	// js untuk fitur notifikasi backoffice
	notification: {
		init:function() {
			if($('#buttonNotif').length){
				$.ajax({
					url: baseUrl+"notif/check",
					type: "POST",
					cache: false,
					beforeSend: function(jxqhr) {},
					success: function(result) {
						var resultCount = 0;
						var i;
						for (i in result) {
						    if (result.hasOwnProperty(i)) {
						        resultCount++;
						    }
						}

						if (resultCount > 0) {
							var link = '';
							var div_element = $('.drop-content-notif');
							div_element.empty();
							$.each(result.notif, function(index, data) {
								var li_element = null;

								if(data.status_notif == '1') {
									li_element = $('<li>');
								}
								else {
									li_element = $('<li>').addClass("unread");
								}
								li_element.append('<a href="'+baseUrl+'notif/get/'+data.id_notif+'" class="aNotif">'+
											'<b class="font-notif">'+data.message_notif+'</b> </br>'+
											'<span class="font-notif">'+data.created_at+'</span>'+
										'</a>');
								div_element.append(li_element);

							});
							
						} else {
							li_element.append('<li class="dropdown-item-notif">'+
									'<span>Belum ada notifikasi</span>'+
								'</li>');
							div_element.append(li_element);

						}
						if (result.countNotif > 0) {
							$("#total-notif").show();
							$("#totalNotif").html(result.countNotif);
						}else{
							$("#total-notif").hide();
						}
					}
				});
			}
		}
	},

	checkSession: {
		stat:false,
		init:function() {
			var time = 905;
			function timerCheck() {
				if (time == 0) {
			      other.checkSession.action();                 
			    } else {
			        time--;
			    }
			}

			function reset(){
				time = 905
			}

			$(document).on('mousemove keypress', function() {
				reset();
			});

			setInterval(function(){timerCheck()}, 1000);
		},
		action:function() {
			if (!other.checkSession.stat) {
				other.checkSession.stat = true;
				$.ajax({
					url:baseUrl + 'checkSession',
					global: false,
					type:'get',
					beforeSend:function(jxqhr){
			    	},
					success:function(data) {
						if (data == '1') {
							other.checkSession.idler = 0;
							other.checkSession.stat = false;
						}
						else {
							ui.popup.show('warning', 'Anda sudah tidak aktif dalam waktu 15 menit', '/logout');
						}
					}
				});
			}

		}
	},
}
function reload(){
	location.reload();
}
