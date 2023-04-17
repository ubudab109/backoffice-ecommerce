var form = {
	init: function () {
		$('form').attr('autocomplete', 'off');
		if ($('.select2').length) {
			$('.select2').select2();
		}
		if ($('.select2-custom').length) {
			$('.select2-custom').select2({
				tags: true,
				placeholder: 'Pilih atau Input'
			});
		}
		$('input').focus(function () {
			$(this).parents('.form-group').addClass('focused');
		});

		$('textarea').focus(function () {
			$(this).parents('.form-group').addClass('focused');
		});

		$('input').change(function () {
			if (this.value != "") {
				$("#" + this.id + "-error").remove();
			}
		})

		$('textarea').change(function () {
			if (this.value != "") {
				$("#" + this.id + "-error").remove();
			}
		})

		$("select").change(function () {
			if (this.value != "") {
				$("#" + this.id + "-error").remove();
			}
		})

		$('input').blur(function () {
			var inputValue = $(this).val();
			if (inputValue == "") {
				$(this).removeClass('filled');
				$(this).parents('.form-group').removeClass('focused');
			} else {
				$(this).addClass('filled');
			}
		})
		$('textarea').blur(function () {
			var inputValue = $(this).val();
			if (inputValue == "") {
				$(this).removeClass('filled');
				$(this).parents('.form-group').removeClass('focused');
			} else {
				$(this).addClass('filled');
			}
		})
		$.validator.addMethod("lettersonly", function (value, element) {
			return this.optional(element) || /^[a-z]+$/i.test(value);
		}, "Letters only please");

		$.validator.addMethod("regexp", function (value, element, regexpr) {
			return regexpr.test(value);
		}, "");
		$.each($('form'), function (key, val) {
			$(this).validate(formrules[$(this).attr('id')]);
		});
		$('form').submit(function (e) {
			e.preventDefault();
			var form_id = $(this).attr('id');
			form.validate(form_id);
		});

		$('.goToLogin').click(function () {
			$('.modal').modal('hide');
			$('#modalLoginCandidate').modal('show')
		});

		$('.goToRegister').click(function () {
			$('.modal').modal('hide');
			$('#modalSignUpCandidate').modal('show')
		});

		$('.goToForget').click(function () {
			$('.modal').modal('hide');
			$('#modalForgetPassword').modal('show')
		});
	},
	validate: function (form_id, konfirm = 0) {

		var formVal = $('#' + form_id);
		var message = formVal.attr('message');
		var agreement = formVal.attr('agreement');
		var defaultOptions = {
			errorPlacement: function (error, element) {
				if (element.parent().hasClass('input-group')) {
					error.appendTo(element.parent().parent());
				} else {
					var help = element.parents('.form-group').find('.help-block');
					if (help.length) {
						error.appendTo(help);
					} else {
						error.appendTo(element.parents('.form-group'))
					}
				}
			},
			highlight: function (element, errorClass, validClass) {
				$(element).parents('.form-group').addClass('has-error');
				$(element).parents('.form-group').addClass('form-error');
			},
			unhighlight: function (element, errorClass, validClass) {

				$(element).parents('.form-group').removeClass('has-error');
				$(element).parents('.form-group').removeClass('form-error');
			},
		}
		var ops = Object.assign(defaultOptions, formrules[form_id]);

		var myform = formVal.validate(ops);
		$('button[type=reset]').click(function () {
			myform.resetForm();
		});
		if (formVal.valid()) {

			if (message != null && message != '') {
				if (message.indexOf('|') > -1) {
					var m_data = message.split('|');
					var m_text = m_data[0];
					var m_val = m_data[1];


					var t_data = m_val.split(';');
					var table = '<table class="table">';
					$.each(t_data, function (key, val) {
						var c1 = val.split(':')[0];
						var c2 = form.find('input[name=' + val.split(':')[1] + '],select[name=' + val.split(':')[1] + ']').val();
						table += '<tr><td>' + c1 + '</td><td>' + c2 + '</td></tr>';
					});
					table += '</table>';


					message = m_text + table;
				}
				ui.popup.confirm('Konfirmasi', message, 'form.submit("' + form_id + '")');
			}
			else if (agreement != null && agreement != '') {
				message = $("#" + agreement).html();
				ui.popup.agreement('Persetujuan Agen Baru', message, 'form.submit("' + form_id + '")');
			}
			else {
				if (konfirm == 1) {
					if (form_id == 'formAddCust') {
						$("#modalAddCustomer").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditCust') {
						$("#modalEditCustomer").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddTicket') {
						$("#modalAddTicket").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddRole') {
						$("#modalAddRole").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddCategory') {
						$("#modalAddCategory").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddProduct') {
						$("#modalAddProduct").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddVoucher') {
						$("#modalAddVoucher").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditProduct') {
						$("#modalEditProduct").modal({
							backdrop: 'static'
						})
					}
					else if (form_id == 'formAddBanner') {
						$("#modalAddBanner").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddInventory') {
						$("#modalAddInventory").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditInventory') {
						$("#modalEditInventory").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditVoucher') {
						$("#modalEditVoucher").modal({
							backdrop: 'static'
						})
					}
					else if (form_id == 'formEditCategory') {
						$("#modalEditCategory").modal({
							backdrop: 'static'
						})
					}
					else if (form_id == 'formEditBanner') {
						$("#modalEditBanner").modal({
							backdrop: 'static'
						})
					}
					else if (form_id == 'formEditRole') {
						$("#modalEditRole").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddUser') {
						$("#modalAddUser").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditUser') {
						$("#modalEditUser").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formAddBroadcast') {
						$("#modalAddBroadcast").modal({
							backdrop: 'static'
						})
					} else if (form_id == 'formEditUrl') {
						$("#modalEditURL").modal({
							backdrop: 'static'
						})
					}
				} else {
					// if (formVal.attr('files') == 'true') {
					// 	form.submit(form_id, $(".file-upload"))
					// } else {
					// }
					form.submit(form_id);
				}
			}
		}
	},
	submit: function (form_id) {
		var form = $('#' + form_id);
		var url = form.attr('action');
		var ops = formrules[form_id];
		if (ops == null) {
			ops = {};
		}
		var i = 1;
		var input = $('.form-control');
		var data = form.serialize();
		var isajax = form.attr('ajax');
		var isFilter = form.attr('filter');
		var isFile = form.attr('files');
		var ismultipleFiles = form.attr('multipleFiles');
		if (isajax == 'true') {
			if (isFile == 'true') {
				var files = $('#file_upload')[0].files;
				if (files.length > 1) {
					ajax.submitDataImagesMultiple(url, data, form_id, files);
				} else {
					if (ismultipleFiles) {
						ajax.submitDataImagesMultiple(url, data, form_id, files);
					} else {
						ajax.submitData(url, data, form_id, files);
					}
				}
			} else {
				ajax.submitData(url, data, form_id);
			}
		} else if (isFilter == 'true') {
			if (form_id == 'filterSearchList' || form_id == 'filterJobList') {
				var filterSearch = $('#filterSearchList').serialize();
				var filterJob = $('#filterJobList').serialize();
				data = filterSearch + '&' + filterJob;
			}
			tables.filter(form_id, data);
		} else {
			other.encrypt(data, function (err, encData) {
				if (err) {
					callback(err);
				} else {
					var encryptedElement = $('<input type="hidden" name="data" />');
					$(encryptedElement).val(encData['data']);
					form.find('select,input:not("input[type=file],input[type=hidden][name=_token],input[name=captcha]")')
						.attr('disabled', 'true')
						.end()
						.append(encryptedElement)
						.unbind('submit')
						.submit();
				}
			});
		}
	},
	resetForm: function (form_id) {
		$('#' + form_id).trigger("reset");
	}
}


$('.thisIconEye').click(function () {
	var item = $(this).parent().find('.form-control')
	var attr = item.attr('type');
	if (attr == 'password') {
		item.attr('type', 'text');
		$('.thisIconEye').html('Hide Password');
	} else {
		item.attr('type', 'password');
		$('.thisIconEye').html('Show Password');
	}
});

$('.thisIconEyeConf').click(function () {
	var item = $(this).parent().find('.form-control')
	var attr = item.attr('type');
	if (attr == 'password') {
		item.attr('type', 'text');
		$('.thisIconEyeConf').html('Hide Password');
	} else {
		item.attr('type', 'password');
		$('.thisIconEyeConf').html('Show Password');
	}
});



// Fungsi Format rupiah untuk form
function formatRupiahRp(angka) {
	var number_string = angka.replace(/[^,\d]/g, "").toString(),
		split = number_string.split(","),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? "." : "";
		rupiah += separator + ribuan.join(".");
	}

	rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
	// return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
	return 'Rp ' + rupiah
}

////////

function formatRupiahKoma(angka) {
	var number_string = angka.replace(/[^.\d]/g, "").toString(),
		split = number_string.split("."),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? "," : "";
		rupiah += separator + ribuan.join(",");
	}

	rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
	return rupiah
}

function formatRupiahh(angka) {
	var number_string = angka.replace(/[^,\d]/g, "").toString(),
		split = number_string.split(","),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi);

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? "." : "";
		rupiah += separator + ribuan.join(".");
	}

	rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
	return rupiah
}

$("#reloadCaptcha").click(function () {
	ajax.getData('reload-captcha', 'post', null, function (data) {
		$(".captcha span").html(data.captcha);
	})
})

if ($("#formLogin").length) {
	$("#username").keyup(function () {
		if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginBtn").attr("disabled", false)
			$("#loginBtn").removeClass("disabled")
		} else {
			$("#loginBtn").attr("disabled", true)
			$("#loginBtn").addClass("disabled")
		}
	})

	$("#password").keyup(function () {
		if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginBtn").attr("disabled", false)
			$("#loginBtn").removeClass("disabled")
		} else {
			$("#loginBtn").attr("disabled", true)
			$("#loginBtn").addClass("disabled")
		}
	})

	$("#captchaLogin").keyup(function () {
		if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginBtn").attr("disabled", false)
			$("#loginBtn").removeClass("disabled")
		} else {
			$("#loginBtn").attr("disabled", true)
			$("#loginBtn").addClass("disabled")
		}
	})
}
if ($("#formForgetPassword").length) {
	$("#email").keyup(function () {
		if ($("#email").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginForget").attr("disabled", false)
			$("#loginForget").removeClass("disabled")
		} else {
			$("#loginForget").attr("disabled", true)
			$("#loginForget").addClass("disabled")
		}
	})

	$("#captchaLogin").keyup(function () {
		if ($("#email").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginForget").attr("disabled", false)
			$("#loginForget").removeClass("disabled")
		} else {
			$("#loginForget").attr("disabled", true)
			$("#loginForget").addClass("disabled")
		}
	})
}

if ($("#formResetPassword").length) {
	$("#newPassword").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginReset").attr("disabled", false)
			$("#loginReset").removeClass("disabled")
		} else {
			$("#loginReset").attr("disabled", true)
			$("#loginReset").addClass("disabled")
		}
	})

	$("#konfirmasiPassword").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginReset").attr("disabled", false)
			$("#loginReset").removeClass("disabled")
		} else {
			$("#loginReset").attr("disabled", true)
			$("#loginReset").addClass("disabled")
		}
	})

	$("#captchaLogin").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginReset").attr("disabled", false)
			$("#loginReset").removeClass("disabled")
		} else {
			$("#loginReset").attr("disabled", true)
			$("#loginReset").addClass("disabled")
		}
	})
}

if ($("#formNewPassword").length) {
	$("#newPassword").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginNew").attr("disabled", false)
			$("#loginNew").removeClass("disabled")
		} else {
			$("#loginNew").attr("disabled", true)
			$("#loginNew").addClass("disabled")
		}
	})

	$("#konfirmasiPassword").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginNew").attr("disabled", false)
			$("#loginNew").removeClass("disabled")
		} else {
			$("#loginNew").attr("disabled", true)
			$("#loginNew").addClass("disabled")
		}
	})

	$("#captchaLogin").keyup(function () {
		if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
			$("#loginNew").attr("disabled", false)
			$("#loginNew").removeClass("disabled")
		} else {
			$("#loginNew").attr("disabled", true)
			$("#loginNew").addClass("disabled")
		}
	})
}

if ($("#formInqCust").length) {
	$("#msisdnInq").mask('62000000000000');
}

if ($("#formEditCategory").length) {
	$("#btnEditCategory").click(function (e) {
		form.validate('formEditCategory', 1);
	});
	$("#btnResetEditCategory").click(function (e) {
		form.resetForm('formEditCategory');
	});

}

if ($("#formEditBanner").length) {
	$("#btnEditBanner").click(function (e) {
		form.validate('formEditBanner', 1);
	});
	$("#btnResetEditBanner").click(function (e) {
		$(".custom-file-label").html('');
		form.resetForm('formEditBanner');
	});

}

if ($("#formEditCust").length) {
	$("#flagStatus").change(function (e) {
		var status = this.value;
		if (status == '1') {
			$("#flagStatus").addClass('status-active')
			$("#flagStatus").removeClass('status-suspend')
		} else {
			$("#flagStatus").removeClass('status-active')
			$("#flagStatus").addClass('status-suspend')
		}
	})

	$("#btnChangeStatus").click(function () {
		var status = $("#flagStatus").val();
		if (status == '1') {
			$(".statusText").html('Active');
		} else {
			$(".statusText").html('Block');
		}

		// else if (status == '2') {
		// 	$(".statusText").html('Suspend');
		// }else if (status == '3') {
		// 	$(".statusText").html('Remove');
		// }

		$("#statusChange").val(status)
		$("#modalChangeStatus").modal('show')
	})

	$("#msisdn").mask('0000000000000');

	$("#btnEditCust").click(function (e) {
		form.validate('formEditCust', 1);
	});

	$("#clsNumber").mask('00000000000000000000000');
	$("#bcNumber").mask('00000000000000000000000');



}

if ($("#filterDashboard").length) {
	var today = new Date();
	$('#startDate').datetimepicker({
		format: 'DD-MM-YYYY',
	});

	$("#startDate").on("dp.change", function (e) {
		$('#endDate').data("DateTimePicker").minDate(e.date);
	});

	$("#endDate").on("dp.change", function (e) {
		$('#startDate').data("DateTimePicker").maxDate(e.date);
	});

	$('#endDate').datetimepicker({
		format: 'DD-MM-YYYY'
	});

	$("#byMerchant").change(function (e) {
		$("#rowService").empty()
		$("#rowService").append(
			'<div class="form-group d-flex flex-column">' +
			'<select name="byService" id="byService" class="form-control select2">' +
			'<option value="">Choose Service</option>' +
			'</select>' +
			'</div>'
		);

		ajax.getData('get-service', 'post', { idMerchant: this.value }, function (data) {
			var dataService = [];
			for (var i = 0; i < data.length; i++) {
				var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>'
				dataService.push(option);
			}

			$("#byService").append(dataService);

			$("#byService").select2({
				placeholder: 'Choose Service'
			});
		})
	})

	var value = $('#filterDashboard').serialize();
	ajax.getData('dashboard-top', 'post', value, function (data) {
		if (data.code == '01') {
			ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
		} else {
			$("#valueTransactionSuccess").html(data.transactionSuccess + '<span class="span-persen" id="spanPersen"></span>')
			$("#spanPersen").html(data.persenTransactionSuccess + ' %')
			$("#valueTransactionVolume").html(data.allTransaction)
			var options = {
				series: [
					{
						name: "Add MSISDN",
						data: data.add_msisdn
					},
					{
						name: "Cek",
						data: data.cek
					},
					{
						name: "Check Result",
						data: data.check_result
					}
				],
				chart: {
					height: 350,
					type: 'line',
					dropShadow: {
						enabled: true,
						color: '#000',
						top: 18,
						left: 7,
						blur: 10,
						opacity: 0.2
					},
					toolbar: {
						show: false
					}
				},
				colors: ['#00AC57', '#F36E23', '#07A0C3'],
				dataLabels: {
					enabled: true,
				},
				stroke: {
					curve: 'smooth'
				},
				// title: {
				//     text: 'Merchant Transaction Timeline',
				//     align: 'left'
				// },
				grid: {
					borderColor: '#e7e7e7',
					row: {
						colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
						opacity: 0.5
					},
				},
				markers: {
					size: 1
				},
				xaxis: {
					categories: data.day,
					// title: {
					//     text: 'Day'
					// }
				},
				legend: {
					position: 'top',
					horizontalAlign: 'right',
					floating: true,
					offsetY: -25,
					offsetX: -5
				}
			};

			var chart = new ApexCharts(document.querySelector("#chartMerchantTransaction"), options);
			chart.render();
		}
	});

	ajax.getData('dashboard-middle', 'post', value, function (data) {
		if (data.code == '01') {
			// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
		} else {
			$("#rowAverage").empty()
			for (let i = 0; i < data.average.length; i++) {
				$("#rowAverage").append(
					'<div class="col-lg-6 col-md-12 pl-4 pt-2 mb-2">' +
					'<p class="title-step">' + data.average[i].step + ' - ' + data.average[i].name + '</p>' +
					'</div>' +
					'<div class="col-lg-5 col-md-12 pt-2">' +
					'<p class="value-step">' + data.average[i].mili + ' m/s</p>' +
					'</div>'
				);
			}

			var color = ['red', 'blue', 'orange', 'green'];
			$("#rowCLS").empty()
			for (let i = 0; i < data.cls.length; i++) {
				var limit_usage = ''
				if (data.cls[i].limit_usage == null) {
					limit_usage = '0'
				} else {
					limit_usage = data.cls[i].limit_usage
				}
				$("#rowCLS").append(
					'<div class="col-lg-6 col-md-12">' +
					'<div class="row">' +
					'<div class="col-lg-6 col-md-6 pl-2 pt-2">' +
					'<p class="title-step">' + data.cls[i].cust_name + '</p>' +
					'</div>' +
					'<div class="col-lg-4 col-md-4 pt-2">' +
					'<p class="value-step">' + data.cls[i].project_name + '</p>' +
					'</div>' +
					'</div>' +
					'<div class="row">' +
					'<div class="col-lg-10 col-md-10 pl-2">' +
					'<progress id="progressA" class="progress-custom progress-' + color[i] + '" value="' + limit_usage + '" max="100"></progress>' +
					'</div>' +
					'</div>' +
					'<div class="row">' +
					'<div class="col-lg-6 col-md-6 pl-2">' +
					'<p class="title-kuota">Kuota ' + limit_usage + '</p>' +
					'</div>' +
					'<div class="col-lg-4 col-md-4">' +
					'<p class="value-kuota">' + data.cls[i].persentase + '%</p>' +
					'</div>' +
					'</div>' +
					'</div>'
				)
			}
		}
	});

	ajax.getData('dashboard-bottom', 'post', value, function (data) {
		if (data.code == '01') {
			// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
		} else {
			var column = [
				{ 'data': 'operator_name' },
				{ 'data': 'volume' },
			];

			var columnDefs = [];

			tables.setAndPopulate('tableTotalTransaction', column, data.operator, columnDefs);

			var columnMerchant = [
				{ 'data': 'cust_name' },
				{ 'data': 'msisdn' },
				{ 'data': 'endpoint' }
			];

			var columnDefsMerchant = [
				{
					"targets": 3,
					"data": "status",
					"className": "p-2",
					"render": function (data, type, full, meta) {
						var data = ''
						if (full.status == 200) {
							data = "<div class='span-status-dashboard status-done'>Done</div>"
						} else {
							data = "<div class='span-status-dashboard status-terminate'>Failed</div>";
						}

						return data
					}
				},
			];

			tables.setAndPopulate('tableMerchantTransaction', columnMerchant, data.merchant, columnDefsMerchant);
		}
	});

}

if ($("#formAddTicket").length) {

	$('#responseTicket').summernote({
		height: 100,
		direction: 'ltr',
		codemirror: { // codemirror options
			theme: 'monokai'
		}
	});

	$('#responseTicket').each(function () {
		var summernote = $(this);
		$('form').on('submit', function () {
			if (summernote.summernote('isEmpty')) {
				summernote.val('');
			} else if (summernote.val() == '<br>') {
				summernote.val('');
			}
		});
	});

	$("div.note-editing-area div.note-editable").keypress(function (evt) {
		var kc = evt.keyCode;
		var htmleditor = $('#responseTicket').summernote('code');

		if (kc === 32 && (htmleditor.length == 0 || htmleditor == '<p><br></p>')) {
			$('#responseTicket').val('');
			evt.preventDefault();
		}

		// if (htmleditor == '<p><br></p>' || htmleditor == '') {
		// 	$("#responseTicket-error").appendTo($('#responseTicket').parents('#responseTicketDiv'))
		// 	$('#responseTicket').parents('.form-group').addClass('has-error');
		// 	$('#responseTicket').parents('.form-group').addClass('form-error');
		// 	$("#responseTicket-error").removeClass('hidden')
		// }else{
		// 	$("#responseTicket-error").addClass('hidden')
		// 	$('#responseTicket').parents('.form-group').removeClass('has-error');
		// 	$('#responseTicket').parents('.form-group').removeClass('form-error');
		// }
	});

	$("#typeTicket").change(function () {
		if ($("#typeTicket").val() != '') {
			$('#typeTicket').parents('.form-group').removeClass('has-error');
			$('#typeTicket').parents('.form-group').removeClass('form-error');
		}
	})

	$("#btnAddTicket").click(function (e) {
		form.validate('formAddTicket', 1);
	});
}

if ($("#formAddRole").length) {
	$("#btnAddRole").click(function (e) {
		form.validate('formAddRole', 1);
	});
	$("#btnResetAddRole").click(function (e) {
		form.resetForm('formAddRole');
	});
}

if ($("#formDeleteCategory").length) {
	$("#deleteButtonCategory").click((e) => {
		form.submit('formDeleteCategory')
	})
}

if ($("#formDeleteInventory").length) {
	$("#deleteButtonInventory").click((e) => {
		form.submit('formDeleteInventory')
	})
}

if ($("#formDeleteUser").length) {
	$("#deleteButtonUser").click((e) => {
		form.submit('formDeleteUser')
	})
}

if ($("#formDeleteRole").length) {
	$("#deleteButtonRole").click((e) => {
		form.submit('formDeleteRole')
	})
}

if ($("#formDeleteProduk").length) {
	$("#deleteButtonProduk").click((e) => {
		form.submit('formDeleteProduk')
	})
}

if ($("#formDeleteBanner").length) {
	$("#deleteButtonBanner").click((e) => {
		form.submit('formDeleteBanner')
	})
}

if ($("#formChangeStatusCategory").length) {
	$("#buttonStatusCategory").click((e) => {
		form.submit('formChangeStatusCategory')
	})
}

if ($("#formChangeStatusTransaction").length) {
	$("#buttonStatusTransaction").click((e) => {
		form.submit('formChangeStatusTransaction')
	})
}

if ($("#formKonfirmasi").length) {
	$("#submitKonfirmasi").click((e) => {
		form.submit('formKonfirmasi');
	});
	$(".cancel-transaction").click((e) => {
		$("#modalStatusBatalkan").modal('show')

	})
}

if ($("#formPesananDiproses").length) {
	$("#submitProses").click((e) => {
		form.submit('formPesananDiproses');
	});
	$(".cancel-transaction").click((e) => {
		$("#modalStatusBatalkan").modal('show')

	})
}

if ($("#formBatalkan").length) {
	$(".submit").click((e) => {
		form.submit('formBatalkan');
	});
}

if ($("#formPesananDikirim").length) {
	$("#kirimPesanan").click((e) => {
		form.validate('formPesananDikirim', 0);
	});
}

if ($("#formPesananSelesai").length) {
	$("#pesananSelesai").click((e) => {
		form.validate('formPesananSelesai', 0);
	});
}




if ($("#formChangeStatusUser").length) {
	$("#buttonStatusUser").click((e) => {
		form.submit('formChangeStatusUser')
	})
}

if ($("#formChangeStatusProduk").length) {
	$("#buttonStatusProduk").click((e) => {
		form.submit('formChangeStatusProduk')
	})
}

if ($("#formChangeStatusPromoProduk").length) {
	$("#buttonStatusPromoProduk").click((e) => {
		form.submit('formChangeStatusPromoProduk')
	})
}

if ($("#formChangeStatusBanner").length) {
	$("#buttonStatusBanner").click((e) => {
		form.submit('formChangeStatusBanner')
	})
}

if ($("#formAddCategory").length) {
	$("#btnAddCategory").click(function (e) {
		form.validate('formAddCategory', 1);
	});
	$("#btnResetAddCategory").click(function (e) {
		form.resetForm('formAddCategory');
	});
}

if ($("#formAddVoucher").length) {
	$("#btnAddVoucher").click(function (e) {
		form.validate('formAddVoucher', 1);
	});
	$("#btnResetAddVoucher").click(function (e) {
		form.resetForm('formAddVoucher');
	});
}

if ($("#formEditVoucher").length) {
	$("#btnEditVoucher").click(function (e) {
		form.validate('formEditVoucher', 1);
	});
	$("#btnResetEditVoucher").click(function (e) {
		form.resetForm('formEditVoucher');
	});
}

if ($("#formDeleteVoucher").length) {
	$("#deleteButtonVoucher").click(function (e) {
		form.submit('formDeleteVoucher');
	});
}

if ($("#formChangeStatusVoucher").length) {
	$("#buttonStatusVoucher").click(function (e) {
		form.submit('formChangeStatusVoucher');
	});
}

if ($("#formAddProduct").length) {

	$("#promoStatus").change(() => {
		$("#promoValue").val('');
		$("#promoValue").val('');
		$("#promoValueInput").val('');
		$('#priceProduct').val('');
		var status = $("#promoStatus").is(':checked');
		if (status == true) {
			$("#formPromoType").removeClass('d-none');
			$("#formPromoValue").removeClass('d-none');
			$("#formTotalPrice").removeClass('d-none');
		} else {
			$("#formPromoType").addClass('d-none');
			$("#formPromoValue").addClass('d-none');
			$("#formTotalPrice").addClass('d-none');
		}
	});
	$("#categoryId").change(() => {
		var code = $("#categoryId option:selected").data('code');
		$("#categoryCode").text(code + '-')
	});

	$("#valuePriceProduct").keyup(() => {
		var price = $("#valuePriceProduct").val();
		$("#valuePriceProduct").val(formatRupiahRp(price));
		var splitedRp = price.split('Rp.');
		var splittedPrice = splitedRp[1].split('.');
		document.getElementById('priceValueProduct').value = splittedPrice.join('');
	});

	$("#promoType").change(() => {
		$("#promoValue").val('');
		$("#promoValueInput").val('');
		$('#priceProduct').val('');
		var typePromo = $("#promoType option:selected").val();
		if (typePromo !== '') {
			$("#promoValueInput").removeAttr('disabled');
		} else {
			$("#promoValue").val('');
			$("#promoValueInput").val('');
			$('#priceProduct').val('');
			$("#promoValueInput").attr('disabled', true);
		}
	});

	$("#promoValueInput").keyup(() => {
		if ($("#valuePriceProduct").val() == '') {
			document.getElementById('promoValue').value = ''
			document.getElementById('promoValueInput').value = ''
		}
		var typePromo = $("#promoType option:selected").val();
		var valuePromo = $('#promoValueInput');
		var priceProduct = $('#priceValueProduct').val();
		if (typePromo == 'fixed') {
			var price = formatRupiahRp(valuePromo.val());
			$("#promoValueInput").val(price);
			var splitedRp = price.split('Rp.');
			var splittedPrice = splitedRp[1].split('.');
			document.getElementById('promoValue').value = splittedPrice.join('');
			console.log(document.getElementById('promoValue').value)
			var calculated = parseInt(priceProduct) - parseInt(splittedPrice.join(''));
			if (calculated < 0) {
				document.getElementById('priceProducts').value = formatRupiahRp('0');
			} else {
				document.getElementById('priceProducts').value = formatRupiahRp(calculated.toString());
			}
		} else {
			var realValue = valuePromo.val();
			document.getElementById('promoValueInput').value = formatPercent(realValue);
			var splitedDiscount = realValue.split('%');
			var splittedPrice = splitedDiscount.join('');
			var splitValueDiscount = splittedPrice.split('.');
			var realDiscount = splitValueDiscount.join('');
			document.getElementById('promoValue').value = realDiscount;
			var discount = realDiscount / 100;
			var totalDeducted = parseInt(priceProduct) * discount;
			var calculatePromo = parseInt(priceProduct) - parseInt(totalDeducted);
			document.getElementById('priceProducts').value = formatRupiahRp(calculatePromo.toString());
		}

	});
	$("#btnAddProduct").click(function (e) {
		form.validate('formAddProduct', 1);
	});
	$("#btnResetAddProduct").click(function (e) {
		form.resetForm('formAddProduct');
		$("div#image").html('');
	});
}

if ($("#formEditProduct").length) {

	$("#promoStatus").change(() => {
		// $("#promoValue").val('');
		// $("#promoValue").val('');
		// $("#promoValueInput").val('');
		// $('#priceProduct').val('');
		var status = $("#promoStatus").is(':checked');
		if (status == true) {
			$("#formPromoType").removeClass('d-none');
			$("#formPromoValue").removeClass('d-none');
			$("#formTotalPrice").removeClass('d-none');
		} else {
			$("#formPromoType").addClass('d-none');
			$("#formPromoValue").addClass('d-none');
			$("#formTotalPrice").addClass('d-none');
		}
	});

	$("#promoType").change(() => {
		$("#promoValue").val('');
		$("#promoValueInput").val('');
		$('#priceProduct').val('');
		var typePromo = $("#promoType option:selected").val();
		if (typePromo !== '') {
			$("#promoValueInput").removeAttr('disabled');
		} else {
			$("#promoValue").val('');
			$("#promoValueInput").val('');
			$('#priceProduct').val('');
			$("#promoValueInput").attr('disabled', true);
		}
	});

	$("#promoValueInput").keyup(() => {
		if ($("#valuePriceProduct").val() == '') {
			document.getElementById('promoValue').value = ''
			document.getElementById('promoValueInput').value = ''
		}
		var typePromo = $("#promoType option:selected").val();
		var valuePromo = $('#promoValueInput');
		var priceProduct = $('#priceValueProduct').val();
		if (typePromo == 'fixed') {
			var price = formatRupiahRp(valuePromo.val());
			$("#promoValueInput").val(price);
			var splitedRp = price.split('Rp.');
			var splittedPrice = splitedRp[1].split('.');
			document.getElementById('promoValue').value = splittedPrice.join('');
			var calculated = parseInt(priceProduct) - parseInt(splittedPrice.join(''));
			if (calculated < 0) {
				document.getElementById('priceProduct').value = formatRupiahRp('0');
			} else {
				document.getElementById('priceProduct').value = formatRupiahRp(calculated.toString());
			}
		} else {
			var realValue = valuePromo.val();
			document.getElementById('promoValueInput').value = formatPercent(realValue);
			var splitedDiscount = realValue.split('%');
			var splittedPrice = splitedDiscount.join('');
			var splitValueDiscount = splittedPrice.split('.');
			var realDiscount = splitValueDiscount.join('');
			document.getElementById('promoValue').value = realDiscount;
			var discount = realDiscount / 100;
			var totalDeducted = parseInt(priceProduct) * discount;
			var calculatePromo = parseInt(priceProduct) - parseInt(totalDeducted);
			document.getElementById('priceProduct').value = formatRupiahRp(calculatePromo.toString());
		}
	});

	$("#valuePriceProduct").keyup(() => {
		var price = $("#valuePriceProduct").val();
		$("#valuePriceProduct").val(formatRupiahRp(price));
		var splitedRp = price.split('Rp.');
		var splittedPrice = splitedRp[1].split('.');
		document.getElementById('priceValueProduct').value = splittedPrice.join('');
	});

	$("#btnEditProduct").click(function (e) {
		form.validate('formEditProduct', 1);
	});
	$("#btnResetEditProduct").click(function (e) {
		form.resetForm('formEditProduct');
		$("div#image").html('');
	});
}

if ($("#formAddBanner").length) {
	$("#btnAddBanner").click(function (e) {
		form.validate('formAddBanner', 1);
	});
	$("#btnResetAddBanner").click(function (e) {
		$(".custom-file-label").html('');
		form.resetForm('formAddBanner');
	});
}

if ($("#formAddInventory").length) {
	$("#btnAddInventory").click(function (e) {
		form.validate('formAddInventory', 1);
	});
	$("#btnResetAddInventory").click(function (e) {
		form.resetForm('formAddInventory');
	});
}

if ($("#formEditInventory").length) {
	$("#btnEditInventory").click(function (e) {
		form.validate('formEditInventory', 1);
	});
	$("#btnResetEditInventory").click(function (e) {
		form.resetForm('formEditInventory');
	});
}



if ($("#formEditRole").length) {
	$("#btnEditRole").click(function (e) {
		form.validate('formEditRole', 1);
	});

	$("#flagStatus").change(function (e) {
		var status = this.value;
		if (status == '1') {
			$("#flagStatus").addClass('status-active')
			$("#flagStatus").removeClass('status-remove')
		} else {
			$("#flagStatus").removeClass('status-active')
			$("#flagStatus").addClass('status-remove')
		}
	})

	$("#btnResetEditRole").click(function (e) {
		form.resetForm('formEditRole');
	});

	$("#btnChangeStatus").click(function () {
		var status = $("#flagStatus").val();
		if (status == '1') {
			$(".statusText").html('Active');
		} else {
			$(".statusText").html('Inactive');
		}

		$("#statusChange").val(status)
		$("#modalChangeStatus").modal('show')
	})
}

if ($("#formAddUser").length) {

	$("#role").change(function () {
		var role = this.value;
		$("#divPermission").removeClass('hidden')
		$("#listPermission").empty()
		ajax.getData('user/search-permission', 'post', { role: role }, function (data) {
			var permission = ''
			for (var i = 0; i < data.length; i++) {
				permission = permission + '<div class="col-md-5 pl-0"><label for="" class="checkbox pl-0 mb-2">' +
					'<div class="row">' +
					'<div class="col-md-1 pr-0">' +
					'<center>' +
					'<input class="fitur-role" type="checkbox" checked>' +
					'<span class="cbver2"></span>' +
					'</center>' +
					'</div>' +
					'<div class="col-md-6 pl-0">' +
					'<p class="text-fitur">' + data[i].title + '</p>' +
					'</div>' +
					'</div>' +
					'</label></div>'
			}

			$("#listPermission").append(permission)
		})

		if ($("#role").val() != '') {
			$('#role').parents('.form-group').removeClass('has-error');
			$('#role').parents('.form-group').removeClass('form-error');
		}

	})

	$("#btnAddUser").click(function (e) {
		form.validate('formAddUser', 1);
	});

	$("#btnResetAddUser").click(function (e) {
		form.resetForm('formAddUser');
	});
}

if ($("#formEditUser").length) {

	$("#role").change(function () {
		var role = this.value;
		$("#divPermission").removeClass('hidden')
		$("#listPermission").empty()
		ajax.getData('user/search-permission', 'post', { role: role }, function (data) {
			var permission = ''
			for (var i = 0; i < data.length; i++) {
				permission = permission + '<div class="col-md-5 pl-0"><label for="" class="checkbox pl-0 mb-2">' +
					'<div class="row">' +
					'<div class="col-md-1 pr-0">' +
					'<center>' +
					'<input class="fitur-role" type="checkbox" checked>' +
					'<span class="cbver2"></span>' +
					'</center>' +
					'</div>' +
					'<div class="col-md-6 pl-0">' +
					'<p class="text-fitur">' + data[i].title + '</p>' +
					'</div>' +
					'</div>' +
					'</label></div>'
			}

			$("#listPermission").append(permission)
		})

		if ($("#role").val() != '') {
			$('#role').parents('.form-group').removeClass('has-error');
			$('#role').parents('.form-group').removeClass('form-error');
		}

	})

	$("#flagStatus").change(function (e) {
		var status = this.value;
		if (status == '1') {
			$("#flagStatus").addClass('status-active')
			$("#flagStatus").removeClass('status-remove')
		} else {
			$("#flagStatus").removeClass('status-active')
			$("#flagStatus").addClass('status-remove')
		}
	})

	$("#btnResetEditUser").click(function (e) {
		form.resetForm('formEditUser');
	});
	$("#btnChangeStatus").click(function () {
		var status = $("#flagStatus").val();
		if (status == '1') {
			$(".statusText").html('Active');
		} else {
			$(".statusText").html('Block');
		}

		$("#statusChange").val(status)
		$("#modalChangeStatus").modal('show')
	})

	$("#btnEditUser").click(function (e) {
		form.validate('formEditUser', 1);
	});
}

if ($("#formAddBroadcast").length) {

	$('#contentBroadcast').summernote({
		height: 100,
		direction: 'ltr',
		codemirror: { // codemirror options
			theme: 'monokai'
		},
		toolbar: [
			// [groupName, [list of button]]
			['style', ['bold', 'italic', 'underline', 'clear']],
			['font', ['fontname', 'fontsizeunit']],
			['color', ['color', 'forecolor', 'backcolor']],
			['para', ['ul', 'ol', 'paragraph']],
			['insert', ['table']]
		]
	});

	$('#contentBroadcast').each(function () {
		var summernote = $(this);
		$('form').on('submit', function () {
			if (summernote.summernote('isEmpty')) {
				summernote.val('');
			} else if (summernote.val() == '<br>') {
				summernote.val('');
			}
		});
	});

	$("div.note-editing-area div.note-editable").keypress(function (evt) {
		var kc = evt.keyCode;
		var htmleditor = $('#contentBroadcast').summernote('code');

		if (kc === 32 && (htmleditor.length == 0 || htmleditor == '<p><br></p>')) {
			$('#contentBroadcast').val('');
			evt.preventDefault();
		}

		// if (htmleditor == '<p><br></p>' || htmleditor == '') {
		// 	$("#contentBroadcast-error").appendTo($('#contentBroadcast').parents('#contentBroadcastDiv'))
		// 	$('#contentBroadcast').parents('.form-group').addClass('has-error');
		// 	$('#contentBroadcast').parents('.form-group').addClass('form-error');
		// 	$("#contentBroadcast-error").removeClass('hidden')
		// }else{
		// 	$("#contentBroadcast-error").addClass('hidden')
		// 	$('#contentBroadcast').parents('.form-group').removeClass('has-error');
		// 	$('#contentBroadcast').parents('.form-group').removeClass('form-error');
		// }
	});

	$("#btnAddBroadcast").click(function (e) {
		$("#subjectModal").html($("#subjectBroadcast").val())
		form.validate('formAddBroadcast', 1);
	});

	function readFile(input) {
		// console.log(input.files, input.files[0])
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				// var image = new Image();
				// image.src = e.target.result;

				// image.onload = function() {
				// 	// access image size here
				// 	$("#widthImg").val(this.width)
				// 	$("#heigthImg").val(this.height)
				// 	console.log(this.width, this.height);
				// };
				var htmlPreview = '<img src="' + e.target.result + '" class="cover-fit" style="width:100%;height:150px;margin-top:0px;" />';
				// console.log(htmlPreview.width())
				var wrapperZone = $(input).parent();
				var previewZone = $(input).parent().parent().find('.preview-zone');
				var boxZone = $(input).parent().find('.dropzone-desc');
				var top = Math.floor(150 / 2);

				wrapperZone.removeClass('dragover');
				previewZone.removeClass('hidden');
				boxZone.empty();
				boxZone.css('top', '0');
				boxZone.append(htmlPreview);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}
	function reset(e) {
		e.wrap('<form>').closest('form').get(0).reset();
		e.unwrap();
	}

	$(".dropzone").change(function () {
		readFile(this);
	});
	$('.dropzone-wrapper').on('dragover', function (e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).addClass('dragover');
	});
	$('.dropzone-wrapper').on('dragleave', function (e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).removeClass('dragover');
	});
	$('.remove-preview').on('click', function () {
		var boxZone = $(this).parents('.preview-zone').find('.box-body');
		var previewZone = $(this).parents('.preview-zone');
		var dropzone = $(this).parents('.form-group').find('.dropzone');
		boxZone.empty();
		previewZone.addClass('hidden');
		reset(dropzone);
	});
}

if ($("#formEditUrl").length) {
	var valueStatus = $("#flagStatus").val();
	$("#flagStatus").change(function (e) {
		var status = this.value;
		if (status == '1') {
			$("#flagStatus").addClass('status-active')
			$("#flagStatus").removeClass('status-remove')
		} else {
			$("#flagStatus").removeClass('status-active')
			$("#flagStatus").addClass('status-remove')
		}
		$("#status").val(status)
	});

	$("#btnCancel").click(function () {
		if (valueStatus == '1') {
			$("#flagStatus").addClass('status-active')
			$("#flagStatus").removeClass('status-remove')
		} else {
			$("#flagStatus").removeClass('status-active')
			$("#flagStatus").addClass('status-remove')
		}
		$("#flagStatus").val(valueStatus);
		$("#status").val(valueStatus)
		$("#modalEditURL").modal('hide')
	})

	$("#btnRevoke").click(function () {
		$("#modalRevokeApi").modal({
			backdrop: 'static'
		})
	})

	$("#btnEditUrl").click(function (e) {
		form.validate('formEditUrl', 1);
	});

	$("#btnCopyApi").click(function () {
		var copyText = document.getElementById("apikey");
		copyText.select();
		copyText.setSelectionRange(0, 99999)
		document.execCommand("copy");

	})
}

if ($("#filterTransaction")) {
	var today = new Date();
	$('#startDate').datetimepicker({
		format: 'DD-MM-YYYY',
	});

	$("#startDate").on("dp.change", function (e) {
		$('#endDate').data("DateTimePicker").minDate(e.date);
	});

	$("#endDate").on("dp.change", function (e) {
		$('#startDate').data("DateTimePicker").maxDate(e.date);
	});

	$('#endDate').datetimepicker({
		format: 'DD-MM-YYYY'
	});

	$("#byMerchant").change(function (e) {
		$("#rowService").empty()
		$("#rowService").append(
			'<div class="form-group d-flex flex-column">' +
			'<select name="byService" id="byService" class="form-control select2">' +
			'<option value="">Choose Service</option>' +
			'</select>' +
			'</div>'
		);

		ajax.getData('transaction/get-project', 'post', { idMerchant: this.value }, function (data) {
			var dataService = [];
			for (var i = 0; i < data.length; i++) {
				var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>'
				dataService.push(option);
			}

			$("#byService").append(dataService);

			$("#byService").select2({
				placeholder: 'Choose Service'
			});
		})
	})
}

if ($("#filterRecon")) {
	var today = new Date();
	$('#startDate').datetimepicker({
		format: 'DD-MM-YYYY',
	});

	$("#startDate").on("dp.change", function (e) {
		$('#endDate').data("DateTimePicker").minDate(e.date);
	});

	$("#endDate").on("dp.change", function (e) {
		$('#startDate').data("DateTimePicker").maxDate(e.date);
	});

	$('#endDate').datetimepicker({
		format: 'DD-MM-YYYY'
	});

	$("#merchant").change(function (e) {
		$("#rowService").empty()
		$("#rowService").append(
			'<label for="">Merchant Project</label>' +
			'<div class="form-group d-flex flex-column">' +
			'<select name="service" id="service" class="form-control select2">' +
			'<option value="">Pilih Merchant Project</option>' +
			'</select>' +
			'</div>'
		);

		ajax.getData('recon/get-project', 'post', { idMerchant: this.value }, function (data) {
			var dataService = [];
			for (var i = 0; i < data.length; i++) {
				var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>'
				dataService.push(option);
			}

			$("#service").append(dataService);

			$("#service").select2({
				placeholder: 'Pilih Merchant Project'
			});
		})
	})
}

if ($("#filterIntegration")) {
	var today = new Date();
	$('#startDate').datetimepicker({
		format: 'DD-MM-YYYY',
	});

	$("#startDate").on("dp.change", function (e) {
		$('#endDate').data("DateTimePicker").minDate(e.date);
	});

	$("#endDate").on("dp.change", function (e) {
		$('#startDate').data("DateTimePicker").maxDate(e.date);
	});

	$('#endDate').datetimepicker({
		format: 'DD-MM-YYYY'
	});

}

if ($("#requestDetail").length) {
	var data = JSON.parse($("#request").val())
	document.getElementById("requestDetail").innerHTML = '<b>' + JSON.stringify(data, null, 4) + '<b>';
}

if ($("#responseDetail").length) {
	var data = JSON.parse($("#response").val())
	document.getElementById("responseDetail").innerHTML = '<b>' + JSON.stringify(data, null, 4) + '<b>';
}

/** FOR FILES SUBMIT MODAL */

// $("#submitCategory").click(function (e) {
// });