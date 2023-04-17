var ajax = {
	init: function init() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			beforeSend: function beforeSend(jxqhr) {
				ui.popup.showLoader();
			},
			timeout: 30000,
			error: function error(event, jxqhr, status, _error) {
				ui.popup.show('error', 'Sedang Gangguan Jaringan', 'Error');
				ui.popup.hideLoader();
			},
			complete: function complete() {
				ui.popup.hideLoader();

			},
			global: true
		});
	},
	getData: function getData(url, method, params, callback) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		if (params == null) {
			params = {};
		}
		$.ajax({
			type: method,
			url: baseUrl + url,
			data: params,
			success: function success(result) {
				ui.popup.hideLoader();
				if (result.status == 'success') {
					ui.popup.hideLoader();
					if (result.callback == 'redirect') {
						ui.popup.show(result.status, result.message, result.url);
					} else if (result.callback == 'download') {
						location.reload();
					}
				}
				if (result.status == 'error') {
					ui.popup.show('error', result.messages.message, result.messages.title);
				} else if (result.status == 'reload') {
					ui.popup.alert(result.messages.title, result.messages.message, 'refresh');
				} else if (result.status == 'logout') {
					ui.popup.alert(result.messages.title, result.messages.message, 'logout');
				} else if (result == 401) {
					ui.popup.show('warning', 'Sesi Anda telah habis, harap login kembali', 'Session Expired')
					if ($('.toast-warning').length == 2) {
						$('.toast-warning')[1].remove();
					}
					setInterval(function () {
						window.location = '/logout'
					}, 3000);
				} else {
					if (result instanceof Array || result instanceof Object) {
						callback(result);
					} else {
						callback(JSON.parse(result));
					}
				}
			}
		});
	},
	submitData: function submitData(url, data, form_id, files = null) {
		other.encrypt(data, function (err, encData) {
			if (err) {
				callback(err);
			} else {
				var formData = new FormData();
				formData.append('data', encData.data);
				if (files != null) {
					if (files.length > 1) {
						var totalFiles = files.length;
						for (let i = 0; i < totalFiles; i++) {
							formData.append(`file[]`, files[i]);
						}
					} else {
						formData.append('file', files[0]);
					}
				}
				$.ajax({
					url: url,
					type: 'post',
					data: formData,
					processData: false,
					contentType: false,
					error: function error(jxqhr, status, _error2) {
						$(".modal").modal('hide')
						ui.popup.hideLoader();
						ui.popup.show(status, 'Terjadi kesalahan', _error2, 'Oke, Mengerti');
					},
					success: function success(result, status) {

						$(".modal").modal('hide')
						if (result == null) {
							ui.popup.show(result.status, 'Terjadi kesalahan', 'Internal Server Error', 'Oke, Mengerti');
							ui.popup.hideLoader();
						} else if (result == 401) {
							ui.popup.show('warning', 'Sesi anda habis, mohon login kembali', 'Session Expired')
							ui.popup.hideLoader();
							setInterval(function () {
								window.location = '/logout'
							}, 3000);
						} else {
							if (result.status == 'success') {
								ui.popup.hideLoader();

								if (result.callback == 'redirect') {
									// $('.modal').modal('hide');
									ui.popup.show(result.status, result.title, result.message, result.button, result.url);
								} else if (result.callback == 'reloadTable') {
									ui.popup.showLoader()
									$('#'+result.tableId).DataTable().ajax.reload()
								} else if (result.callback == 'login') {
									// ui.toast.show();
									setInterval(function () { window.location = result.url; }, 2000);
								} else if (result.callback == 'inqCustomer') {
									ui.popup.show(result.status, result.title, result.message, result.button, result.url);

									$("#rowInquiry").addClass('hidden');
									$("#rowAddCust").removeClass('hidden');

									$("#customerId").val(result.data.customer_account_id);
									$("#customerName").val(result.data.customer_account_name);
									$("#msisdn").val(result.data.msisdn);
									$("#email").val(result.data.email);
									$("#corporateID").val(result.data.id_number);
									$("#listEmail").val(result.data.listEmail);
									var column = [
										{ 'data': 'project_id' },
										{ 'data': 'project_name' },
									];

									columnDefs = [
										{
											"targets": 2,
											"data": "project_id",
											"render": function (data, type, full, meta) {
												var data = '<input type="checkbox" value="' + full.project_id + '@' + full.project_name + '" name="projectList">'

												return data
											}
										},
									];

									tables.setAndPopulate('tableProject', column, result.data.project, columnDefs);

									$("#btnAddCust").click(function (e) {
										form.validate('formAddCust', 1);
									});
									$("#clsNumber").mask('00000000000000000000000');
									$("#bcNumber").mask('00000000000000000000000');
								}
							} else if (result.status == 'info') {
								ui.popup.hideLoader();
								// bisa menggunakan if seperti diatas
							} else if (result.status == 'warning') {
								$('.modal').modal('hide');
								ui.popup.hideLoader();
								if (result.callback == 'redirect') {
									ui.popup.show(result.status, result.message, result.url);
								} else if (result.callback == 'mustLogin') {
									$('#modalNotifForLogin').modal({
										backdrop: 'static',
										show: true
									})
								} else {
									$('#titleErrorNotif').html(result.message)
									$('#modalNotifForError').modal({
										backdrop: 'static',
										show: true
									})
								}
							} else {
								if (result.messages == '<p>Error: Validation</p>') {
									ui.popup.hideLoader();
									$("#" + form_id).validate().showErrors(result.errors);
									ui.popup.show(result.status, "Harap cek isian");
								} else {
									if (result.captcha != "undefined") {
										$(".captcha span").html(result.captcha);
										$("#captchaLogin").val("");
										ui.popup.show(result.status, result.title, result.message, result.button);
										ui.popup.hideLoader();
									} else {
										ui.popup.show(result.status, result.title, result.message, result.button);
										ui.popup.hideLoader();
									}
								}
							}
						}

					}
				});
			}
		});
	},
	submitDataImagesMultiple: function submitData(url, data, form_id, files = []) {
		other.encrypt(data, function (err, encData) {
			if (err) {
				callback(err);
			} else {
				var formData = new FormData();
				formData.append('data', encData.data);
				var totalFiles = files.length;
				for (let i = 0; i < totalFiles; i++) {
					formData.append(`file[]`, files[i]);
				}
				$.ajax({
					url: url,
					type: 'post',
					data: formData,
					processData: false,
					contentType: false,
					error: function error(jxqhr, status, _error2) {
						$(".modal").modal('hide')
						ui.popup.hideLoader();
						ui.popup.show(status, 'Terjadi kesalahan', _error2, 'Oke, Mengerti');
					},
					success: function success(result, status) {

						$(".modal").modal('hide')
						if (result == null) {
							ui.popup.show(result.status, 'Terjadi kesalahan', 'Internal Server Error', 'Oke, Mengerti');
							ui.popup.hideLoader();
						} else if (result == 401) {
							ui.popup.show('warning', 'Sesi anda habis, mohon login kembali', 'Session Expired')
							ui.popup.hideLoader();
							setInterval(function () {
								window.location = '/logout'
							}, 3000);
						} else {
							if (result.status == 'success') {
								ui.popup.hideLoader();

								if (result.callback == 'redirect') {
									// $('.modal').modal('hide');
									ui.popup.show(result.status, result.title, result.message, result.button, result.url);
								} else if (result.callback == 'login') {
									// ui.toast.show();
									setInterval(function () { window.location = result.url; }, 2000);
								} else if (result.callback == 'inqCustomer') {
									ui.popup.show(result.status, result.title, result.message, result.button, result.url);

									$("#rowInquiry").addClass('hidden');
									$("#rowAddCust").removeClass('hidden');

									$("#customerId").val(result.data.customer_account_id);
									$("#customerName").val(result.data.customer_account_name);
									$("#msisdn").val(result.data.msisdn);
									$("#email").val(result.data.email);
									$("#corporateID").val(result.data.id_number);
									$("#listEmail").val(result.data.listEmail);
									var column = [
										{ 'data': 'project_id' },
										{ 'data': 'project_name' },
									];

									columnDefs = [
										{
											"targets": 2,
											"data": "project_id",
											"render": function (data, type, full, meta) {
												var data = '<input type="checkbox" value="' + full.project_id + '@' + full.project_name + '" name="projectList">'

												return data
											}
										},
									];

									tables.setAndPopulate('tableProject', column, result.data.project, columnDefs);

									$("#btnAddCust").click(function (e) {
										form.validate('formAddCust', 1);
									});
									$("#clsNumber").mask('00000000000000000000000');
									$("#bcNumber").mask('00000000000000000000000');
								}
							} else if (result.status == 'info') {
								ui.popup.hideLoader();
								// bisa menggunakan if seperti diatas
							} else if (result.status == 'warning') {
								$('.modal').modal('hide');
								ui.popup.hideLoader();
								if (result.callback == 'redirect') {
									ui.popup.show(result.status, result.message, result.url);
								} else if (result.callback == 'mustLogin') {
									$('#modalNotifForLogin').modal(
										{
											backdrop: 'static',
											show: true
										}
									)
								} else {
									$('#titleErrorNotif').html(result.message)
									$('#modalNotifForError').modal(
										{
											backdrop: 'static',
											show: true
										}
									)
								}
							} else {
								if (result.messages == '<p>Error: Validation</p>') {
									ui.popup.hideLoader();
									$("#" + form_id).validate().showErrors(result.errors);
									ui.popup.show(result.status, "Harap cek isian");
								} else {
									if (result.captcha != "undefined") {
										$(".captcha span").html(result.captcha);
										$("#captchaLogin").val("");
										ui.popup.show(result.status, result.title, result.message, result.button);
										ui.popup.hideLoader();
									} else {
										ui.popup.show(result.status, result.title, result.message, result.button);
										ui.popup.hideLoader();
									}
								}
							}
						}
					}
				});
			}
		});
	},
}
