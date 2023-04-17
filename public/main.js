function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// require('./bootstrap');
// require('datatables.net-bs4');
// require('datatables.net-buttons-bs4');
// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// Vue.component('example-component', require('./components/ExampleComponent.vue'));
// const app = new Vue({
//     el: '#app'
// });

/**
 * Init Section
 */
$(document).ready(function () {
  _ajax.init();

  tables.init();
  form.init();
  grafik.init();
  ui.slide.init();
  validation.addMethods(); // if ($('#main-wrapper').length) {
  //     other.checkSession.init();
  // }
  // $("#modalResponBulk").modal('show')

  if ($("#bodyCandidate").length) {
    if ($('.btn-home-color').length) {
      _ajax.getData('/get-color', 'post', null, function (result) {
        $(".btn-home-color").addClass(result.value);
      });
    }
  }

  $(document).ajaxError(function (event, jqxhr, settings, exception) {
    console.log('exception = ' + exception);
  });

  moveOnMax = function moveOnMax(field, nextFieldID) {
    if (field.value.length == 1) {
      document.getElementById(nextFieldID).focus();
    }
  };

  if ($('#notif').length) {
    var status = $('#notif').data('status');
    var message = $('#notif').data('message');
    var title = $('#notif').data('title');
    var button = $('#notif').data('button');
    console.log(status, message, title, button);
    ui.popup.show(status, title, message, button);
  }

  if ($('#notifModal').length) {
    var _status = $('#notifModal').data('status');

    var _message = $('#notifModal').data('message');

    var url = $('#notifModal').data('url');

    if (_status == 'success') {
      $('#titleSuccessNotif').html(_message);
      $('#modalNotifForSuccess').modal({
        backdrop: 'static',
        show: true
      });
    } else {
      $('#titleErrorNotif').html(_message);
      $('#modalNotifForError').modal({
        backdrop: 'static',
        show: true
      });
    }
  }

  if ($('#mustLogin').length) {
    $('.modal').modal('hide');
    ui.popup.hideLoader();
    $('#modalNotifForLogin').modal({
      backdrop: 'static',
      show: true
    });
  }

  if ($('#profileSaved').length) {
    $('.modal').modal('hide');
    ui.popup.hideLoader();
    $('#modalNotifProfileSaved').modal({
      backdrop: 'static',
      show: true
    });
  }

  if ($('#addTest').length) {
    ui.popup.hideLoader();
    $('.modal').modal('hide');

    var _url = $('#addTest').data('url');

    $("#urlTest").attr('href', _url);
    $('#modalSuccessAddTest').modal({
      backdrop: 'static',
      show: true
    });
  }

  $('#btnDrawer').click(function () {
    document.getElementById("navbarSupportedContent").style.width = "335px";
  });
  $('#btnDrawerClose').click(function () {
    document.getElementById("navbarSupportedContent").style.width = "0";
  });
  $('#btn-mobile').click(function () {
    console.log('click');
    document.getElementById("sidebarUtama").style.width = "290px";
  });
  $('#btn-mobile-close').click(function () {
    console.log('click');
    document.getElementById("sidebarUtama").style.width = "0";
  });
}); // $('.modal').on('hidden.bs.modal', function (e) {
//     $(this).find('form')[0].reset();
//     $('.select').val('').trigger('change');
// })
//FAQ

if ($("#divFaq").length) {
  var openContent = function openContent(event, id) {
    var content = document.getElementsByClassName("content");

    for (var i = 0; i < content.length; i++) {
      content[i].style.display = "none";
    }

    var tabLinks = document.getElementsByClassName("tab-links");

    for (var _i = 0; _i < tabLinks.length; _i++) {
      tabLinks[_i].className = tabLinks[_i].className.replace(" active", "");
    }

    document.getElementById(id).style.display = "block";
    event.currentTarget.className += " active";
  };

  document.getElementById("defaultOpen").click();
}

var baseUrl = $('meta[name=base]').attr('content') + '/';
var baseImage = $('meta[name=baseImage]').attr('content') + '/';
var cdn = $('meta[name=cdn]').attr('content');
var other = {
  encrypt: function encrypt(formdata, callback) {
    $.ajax({
      url: baseUrl + 's',
      type: 'post',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(data) {
        var pass = data;

        if (pass.status !== "error" && pass.status !== "reload") {
          var password = pass.password;
          var salt = CryptoJS.lib.WordArray.random(128 / 8);
          var key256Bits500Iterations = CryptoJS.PBKDF2("Secret Passphrase", salt, {
            keySize: 256 / 32,
            iterations: 500
          });
          var iv = CryptoJS.enc.Hex.parse(password[2]);

          if (formdata.indexOf("&captcha=")) {
            var form = formdata.split("&captcha=");
            var captcha = form[1];
            formdata = form[0];
          }

          var encrypted = CryptoJS.AES.encrypt(formdata + '&safer=', key256Bits500Iterations, {
            iv: iv
          });
          var data_base64 = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
          var iv_base64 = encrypted.iv.toString(CryptoJS.enc.Base64);
          var key_base64 = encrypted.key.toString(CryptoJS.enc.Base64);
          var encData = data_base64 + password[0] + iv_base64 + password[1] + key_base64 + password[2];
          var data = {
            data: encData
          };

          if (captcha != 'undefined') {
            data["captcha"] = captcha;
          }

          callback(null, data);
        } else {
          swal({
            title: pass.messages.title,
            text: pass.messages.message,
            type: "error",
            html: true,
            showCancelButton: true,
            confirmButtonColor: "green",
            confirmButtonText: "Refresh"
          }, function () {
            location.reload();
          });
        }
      }
    });
  },
  // js untuk fitur notifikasi backoffice
  notification: {
    init: function init() {
      if ($('#buttonNotif').length) {
        $.ajax({
          url: baseUrl + "notif/check",
          type: "POST",
          cache: false,
          beforeSend: function beforeSend(jxqhr) {},
          success: function success(result) {
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
              $.each(result.notif, function (index, data) {
                var li_element = null;

                if (data.status_notif == '1') {
                  li_element = $('<li>');
                } else {
                  li_element = $('<li>').addClass("unread");
                }

                li_element.append('<a href="' + baseUrl + 'notif/get/' + data.id_notif + '" class="aNotif">' + '<b class="font-notif">' + data.message_notif + '</b> </br>' + '<span class="font-notif">' + data.created_at + '</span>' + '</a>');
                div_element.append(li_element);
              });
            } else {
              li_element.append('<li class="dropdown-item-notif">' + '<span>Belum ada notifikasi</span>' + '</li>');
              div_element.append(li_element);
            }

            if (result.countNotif > 0) {
              $("#total-notif").show();
              $("#totalNotif").html(result.countNotif);
            } else {
              $("#total-notif").hide();
            }
          }
        });
      }
    }
  },
  checkSession: {
    stat: false,
    init: function init() {
      var time = 905;

      function timerCheck() {
        if (time == 0) {
          other.checkSession.action();
        } else {
          time--;
        }
      }

      function reset() {
        time = 905;
      }

      $(document).on('mousemove keypress', function () {
        reset();
      });
      setInterval(function () {
        timerCheck();
      }, 1000);
    },
    action: function action() {
      if (!other.checkSession.stat) {
        other.checkSession.stat = true;
        $.ajax({
          url: baseUrl + 'checkSession',
          global: false,
          type: 'get',
          beforeSend: function beforeSend(jxqhr) {},
          success: function success(data) {
            if (data == '1') {
              other.checkSession.idler = 0;
              other.checkSession.stat = false;
            } else {
              ui.popup.show('warning', 'Anda sudah tidak aktif dalam waktu 15 menit', '/logout');
            }
          }
        });
      }
    }
  }
};

function reload() {
  location.reload();
}

var _ajax = {
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
          ui.popup.show('warning', 'Sesi Anda telah habis, harap login kembali', 'Session Expired');

          if ($('.toast-warning').length == 2) {
            $('.toast-warning')[1].remove();
          }

          setInterval(function () {
            window.location = '/logout';
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
  submitData: function submitData(url, data, form_id) {
    var files = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    other.encrypt(data, function (err, encData) {
      if (err) {
        callback(err);
      } else {
        var formData = new FormData();
        formData.append('data', encData.data);

        if (files != null) {
          if (files.length > 1) {
            var totalFiles = files.length;

            for (var i = 0; i < totalFiles; i++) {
              formData.append("file[]", files[i]);
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
            $(".modal").modal('hide');
            ui.popup.hideLoader();
            ui.popup.show(status, 'Terjadi kesalahan', _error2, 'Oke, Mengerti');
          },
          success: function success(result, status) {
            $(".modal").modal('hide');

            if (result == null) {
              ui.popup.show(result.status, 'Terjadi kesalahan', 'Internal Server Error', 'Oke, Mengerti');
              ui.popup.hideLoader();
            } else if (result == 401) {
              ui.popup.show('warning', 'Sesi anda habis, mohon login kembali', 'Session Expired');
              ui.popup.hideLoader();
              setInterval(function () {
                window.location = '/logout';
              }, 3000);
            } else {
              if (result.status == 'success') {
                ui.popup.hideLoader();

                if (result.callback == 'redirect') {
                  // $('.modal').modal('hide');
                  ui.popup.show(result.status, result.title, result.message, result.button, result.url);
                } else if (result.callback == 'reloadTable') {
                  ui.popup.showLoader();
                  $('#' + result.tableId).DataTable().ajax.reload();
                } else if (result.callback == 'login') {
                  // ui.toast.show();
                  setInterval(function () {
                    window.location = result.url;
                  }, 2000);
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
                  var column = [{
                    'data': 'project_id'
                  }, {
                    'data': 'project_name'
                  }];
                  columnDefs = [{
                    "targets": 2,
                    "data": "project_id",
                    "render": function render(data, type, full, meta) {
                      var data = '<input type="checkbox" value="' + full.project_id + '@' + full.project_name + '" name="projectList">';
                      return data;
                    }
                  }];
                  tables.setAndPopulate('tableProject', column, result.data.project, columnDefs);
                  $("#btnAddCust").click(function (e) {
                    form.validate('formAddCust', 1);
                  });
                  $("#clsNumber").mask('00000000000000000000000');
                  $("#bcNumber").mask('00000000000000000000000');
                }
              } else if (result.status == 'info') {
                ui.popup.hideLoader(); // bisa menggunakan if seperti diatas
              } else if (result.status == 'warning') {
                $('.modal').modal('hide');
                ui.popup.hideLoader();

                if (result.callback == 'redirect') {
                  ui.popup.show(result.status, result.message, result.url);
                } else if (result.callback == 'mustLogin') {
                  $('#modalNotifForLogin').modal({
                    backdrop: 'static',
                    show: true
                  });
                } else {
                  $('#titleErrorNotif').html(result.message);
                  $('#modalNotifForError').modal({
                    backdrop: 'static',
                    show: true
                  });
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
  submitDataImagesMultiple: function submitData(url, data, form_id) {
    var files = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : [];
    other.encrypt(data, function (err, encData) {
      if (err) {
        callback(err);
      } else {
        var formData = new FormData();
        formData.append('data', encData.data);
        var totalFiles = files.length;

        for (var i = 0; i < totalFiles; i++) {
          formData.append("file[]", files[i]);
        }

        $.ajax({
          url: url,
          type: 'post',
          data: formData,
          processData: false,
          contentType: false,
          error: function error(jxqhr, status, _error2) {
            $(".modal").modal('hide');
            ui.popup.hideLoader();
            ui.popup.show(status, 'Terjadi kesalahan', _error2, 'Oke, Mengerti');
          },
          success: function success(result, status) {
            $(".modal").modal('hide');

            if (result == null) {
              ui.popup.show(result.status, 'Terjadi kesalahan', 'Internal Server Error', 'Oke, Mengerti');
              ui.popup.hideLoader();
            } else if (result == 401) {
              ui.popup.show('warning', 'Sesi anda habis, mohon login kembali', 'Session Expired');
              ui.popup.hideLoader();
              setInterval(function () {
                window.location = '/logout';
              }, 3000);
            } else {
              if (result.status == 'success') {
                ui.popup.hideLoader();

                if (result.callback == 'redirect') {
                  // $('.modal').modal('hide');
                  ui.popup.show(result.status, result.title, result.message, result.button, result.url);
                } else if (result.callback == 'login') {
                  // ui.toast.show();
                  setInterval(function () {
                    window.location = result.url;
                  }, 2000);
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
                  var column = [{
                    'data': 'project_id'
                  }, {
                    'data': 'project_name'
                  }];
                  columnDefs = [{
                    "targets": 2,
                    "data": "project_id",
                    "render": function render(data, type, full, meta) {
                      var data = '<input type="checkbox" value="' + full.project_id + '@' + full.project_name + '" name="projectList">';
                      return data;
                    }
                  }];
                  tables.setAndPopulate('tableProject', column, result.data.project, columnDefs);
                  $("#btnAddCust").click(function (e) {
                    form.validate('formAddCust', 1);
                  });
                  $("#clsNumber").mask('00000000000000000000000');
                  $("#bcNumber").mask('00000000000000000000000');
                }
              } else if (result.status == 'info') {
                ui.popup.hideLoader(); // bisa menggunakan if seperti diatas
              } else if (result.status == 'warning') {
                $('.modal').modal('hide');
                ui.popup.hideLoader();

                if (result.callback == 'redirect') {
                  ui.popup.show(result.status, result.message, result.url);
                } else if (result.callback == 'mustLogin') {
                  $('#modalNotifForLogin').modal({
                    backdrop: 'static',
                    show: true
                  });
                } else {
                  $('#titleErrorNotif').html(result.message);
                  $('#modalNotifForError').modal({
                    backdrop: 'static',
                    show: true
                  });
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
  }
};
var form = {
  init: function init() {
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
    });
    $('textarea').change(function () {
      if (this.value != "") {
        $("#" + this.id + "-error").remove();
      }
    });
    $("select").change(function () {
      if (this.value != "") {
        $("#" + this.id + "-error").remove();
      }
    });
    $('input').blur(function () {
      var inputValue = $(this).val();

      if (inputValue == "") {
        $(this).removeClass('filled');
        $(this).parents('.form-group').removeClass('focused');
      } else {
        $(this).addClass('filled');
      }
    });
    $('textarea').blur(function () {
      var inputValue = $(this).val();

      if (inputValue == "") {
        $(this).removeClass('filled');
        $(this).parents('.form-group').removeClass('focused');
      } else {
        $(this).addClass('filled');
      }
    });
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
      $('#modalLoginCandidate').modal('show');
    });
    $('.goToRegister').click(function () {
      $('.modal').modal('hide');
      $('#modalSignUpCandidate').modal('show');
    });
    $('.goToForget').click(function () {
      $('.modal').modal('hide');
      $('#modalForgetPassword').modal('show');
    });
  },
  validate: function validate(form_id) {
    var konfirm = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;
    var formVal = $('#' + form_id);
    var message = formVal.attr('message');
    var agreement = formVal.attr('agreement');
    var defaultOptions = {
      errorPlacement: function errorPlacement(error, element) {
        if (element.parent().hasClass('input-group')) {
          error.appendTo(element.parent().parent());
        } else {
          var help = element.parents('.form-group').find('.help-block');

          if (help.length) {
            error.appendTo(help);
          } else {
            error.appendTo(element.parents('.form-group'));
          }
        }
      },
      highlight: function highlight(element, errorClass, validClass) {
        $(element).parents('.form-group').addClass('has-error');
        $(element).parents('.form-group').addClass('form-error');
      },
      unhighlight: function unhighlight(element, errorClass, validClass) {
        $(element).parents('.form-group').removeClass('has-error');
        $(element).parents('.form-group').removeClass('form-error');
      }
    };
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
      } else if (agreement != null && agreement != '') {
        message = $("#" + agreement).html();
        ui.popup.agreement('Persetujuan Agen Baru', message, 'form.submit("' + form_id + '")');
      } else {
        if (konfirm == 1) {
          if (form_id == 'formAddCust') {
            $("#modalAddCustomer").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditCust') {
            $("#modalEditCustomer").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddTicket') {
            $("#modalAddTicket").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddRole') {
            $("#modalAddRole").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddCategory') {
            $("#modalAddCategory").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddProduct') {
            $("#modalAddProduct").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddVoucher') {
            $("#modalAddVoucher").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditProduct') {
            $("#modalEditProduct").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddBanner') {
            $("#modalAddBanner").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddInventory') {
            $("#modalAddInventory").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditInventory') {
            $("#modalEditInventory").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditVoucher') {
            $("#modalEditVoucher").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditCategory') {
            $("#modalEditCategory").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditBanner') {
            $("#modalEditBanner").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditRole') {
            $("#modalEditRole").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddUser') {
            $("#modalAddUser").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditUser') {
            $("#modalEditUser").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formAddBroadcast') {
            $("#modalAddBroadcast").modal({
              backdrop: 'static'
            });
          } else if (form_id == 'formEditUrl') {
            $("#modalEditURL").modal({
              backdrop: 'static'
            });
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
  submit: function submit(form_id) {
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
          _ajax.submitDataImagesMultiple(url, data, form_id, files);
        } else {
          if (ismultipleFiles) {
            _ajax.submitDataImagesMultiple(url, data, form_id, files);
          } else {
            _ajax.submitData(url, data, form_id, files);
          }
        }
      } else {
        _ajax.submitData(url, data, form_id);
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
          form.find('select,input:not("input[type=file],input[type=hidden][name=_token],input[name=captcha]")').attr('disabled', 'true').end().append(encryptedElement).unbind('submit').submit();
        }
      });
    }
  },
  resetForm: function resetForm(form_id) {
    $('#' + form_id).trigger("reset");
  }
};
$('.thisIconEye').click(function () {
  var item = $(this).parent().find('.form-control');
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
  var item = $(this).parent().find('.form-control');
  var attr = item.attr('type');

  if (attr == 'password') {
    item.attr('type', 'text');
    $('.thisIconEyeConf').html('Hide Password');
  } else {
    item.attr('type', 'password');
    $('.thisIconEyeConf').html('Show Password');
  }
}); // Fungsi Format rupiah untuk form

function formatRupiahRp(angka) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi); // tambahkan titik jika yang di input sudah menjadi angka ribuan

  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + split[1] : rupiah; // return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";

  return 'Rp ' + rupiah;
} ////////


function formatRupiahKoma(angka) {
  var number_string = angka.replace(/[^.\d]/g, "").toString(),
      split = number_string.split("."),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi); // tambahkan titik jika yang di input sudah menjadi angka ribuan

  if (ribuan) {
    separator = sisa ? "," : "";
    rupiah += separator + ribuan.join(",");
  }

  rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
  return rupiah;
}

function formatRupiahh(angka) {
  var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi); // tambahkan titik jika yang di input sudah menjadi angka ribuan

  if (ribuan) {
    separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] != undefined ? rupiah + split[1] : rupiah;
  return rupiah;
}

$("#reloadCaptcha").click(function () {
  _ajax.getData('reload-captcha', 'post', null, function (data) {
    $(".captcha span").html(data.captcha);
  });
});

if ($("#formLogin").length) {
  $("#username").keyup(function () {
    if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginBtn").attr("disabled", false);
      $("#loginBtn").removeClass("disabled");
    } else {
      $("#loginBtn").attr("disabled", true);
      $("#loginBtn").addClass("disabled");
    }
  });
  $("#password").keyup(function () {
    if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginBtn").attr("disabled", false);
      $("#loginBtn").removeClass("disabled");
    } else {
      $("#loginBtn").attr("disabled", true);
      $("#loginBtn").addClass("disabled");
    }
  });
  $("#captchaLogin").keyup(function () {
    if ($("#username").val() != "" && $("#password").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginBtn").attr("disabled", false);
      $("#loginBtn").removeClass("disabled");
    } else {
      $("#loginBtn").attr("disabled", true);
      $("#loginBtn").addClass("disabled");
    }
  });
}

if ($("#formForgetPassword").length) {
  $("#email").keyup(function () {
    if ($("#email").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginForget").attr("disabled", false);
      $("#loginForget").removeClass("disabled");
    } else {
      $("#loginForget").attr("disabled", true);
      $("#loginForget").addClass("disabled");
    }
  });
  $("#captchaLogin").keyup(function () {
    if ($("#email").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginForget").attr("disabled", false);
      $("#loginForget").removeClass("disabled");
    } else {
      $("#loginForget").attr("disabled", true);
      $("#loginForget").addClass("disabled");
    }
  });
}

if ($("#formResetPassword").length) {
  $("#newPassword").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginReset").attr("disabled", false);
      $("#loginReset").removeClass("disabled");
    } else {
      $("#loginReset").attr("disabled", true);
      $("#loginReset").addClass("disabled");
    }
  });
  $("#konfirmasiPassword").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginReset").attr("disabled", false);
      $("#loginReset").removeClass("disabled");
    } else {
      $("#loginReset").attr("disabled", true);
      $("#loginReset").addClass("disabled");
    }
  });
  $("#captchaLogin").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginReset").attr("disabled", false);
      $("#loginReset").removeClass("disabled");
    } else {
      $("#loginReset").attr("disabled", true);
      $("#loginReset").addClass("disabled");
    }
  });
}

if ($("#formNewPassword").length) {
  $("#newPassword").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginNew").attr("disabled", false);
      $("#loginNew").removeClass("disabled");
    } else {
      $("#loginNew").attr("disabled", true);
      $("#loginNew").addClass("disabled");
    }
  });
  $("#konfirmasiPassword").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginNew").attr("disabled", false);
      $("#loginNew").removeClass("disabled");
    } else {
      $("#loginNew").attr("disabled", true);
      $("#loginNew").addClass("disabled");
    }
  });
  $("#captchaLogin").keyup(function () {
    if ($("#newPassword").val() != "" && $("#konfirmasiPassword").val() != "" && $("#captchaLogin").val() != "") {
      $("#loginNew").attr("disabled", false);
      $("#loginNew").removeClass("disabled");
    } else {
      $("#loginNew").attr("disabled", true);
      $("#loginNew").addClass("disabled");
    }
  });
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
      $("#flagStatus").addClass('status-active');
      $("#flagStatus").removeClass('status-suspend');
    } else {
      $("#flagStatus").removeClass('status-active');
      $("#flagStatus").addClass('status-suspend');
    }
  });
  $("#btnChangeStatus").click(function () {
    var status = $("#flagStatus").val();

    if (status == '1') {
      $(".statusText").html('Active');
    } else {
      $(".statusText").html('Block');
    } // else if (status == '2') {
    // 	$(".statusText").html('Suspend');
    // }else if (status == '3') {
    // 	$(".statusText").html('Remove');
    // }


    $("#statusChange").val(status);
    $("#modalChangeStatus").modal('show');
  });
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
    format: 'DD-MM-YYYY'
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
    $("#rowService").empty();
    $("#rowService").append('<div class="form-group d-flex flex-column">' + '<select name="byService" id="byService" class="form-control select2">' + '<option value="">Choose Service</option>' + '</select>' + '</div>');

    _ajax.getData('get-service', 'post', {
      idMerchant: this.value
    }, function (data) {
      var dataService = [];

      for (var i = 0; i < data.length; i++) {
        var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>';
        dataService.push(option);
      }

      $("#byService").append(dataService);
      $("#byService").select2({
        placeholder: 'Choose Service'
      });
    });
  });
  var value = $('#filterDashboard').serialize();

  _ajax.getData('dashboard-top', 'post', value, function (data) {
    if (data.code == '01') {
      ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
    } else {
      $("#valueTransactionSuccess").html(data.transactionSuccess + '<span class="span-persen" id="spanPersen"></span>');
      $("#spanPersen").html(data.persenTransactionSuccess + ' %');
      $("#valueTransactionVolume").html(data.allTransaction);
      var options = {
        series: [{
          name: "Add MSISDN",
          data: data.add_msisdn
        }, {
          name: "Cek",
          data: data.cek
        }, {
          name: "Check Result",
          data: data.check_result
        }],
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
          enabled: true
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
            colors: ['#f3f3f3', 'transparent'],
            // takes an array which will be repeated on columns
            opacity: 0.5
          }
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: data.day // title: {
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

  _ajax.getData('dashboard-middle', 'post', value, function (data) {
    if (data.code == '01') {// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
    } else {
      $("#rowAverage").empty();

      for (var i = 0; i < data.average.length; i++) {
        $("#rowAverage").append('<div class="col-lg-6 col-md-12 pl-4 pt-2 mb-2">' + '<p class="title-step">' + data.average[i].step + ' - ' + data.average[i].name + '</p>' + '</div>' + '<div class="col-lg-5 col-md-12 pt-2">' + '<p class="value-step">' + data.average[i].mili + ' m/s</p>' + '</div>');
      }

      var color = ['red', 'blue', 'orange', 'green'];
      $("#rowCLS").empty();

      for (var _i2 = 0; _i2 < data.cls.length; _i2++) {
        var limit_usage = '';

        if (data.cls[_i2].limit_usage == null) {
          limit_usage = '0';
        } else {
          limit_usage = data.cls[_i2].limit_usage;
        }

        $("#rowCLS").append('<div class="col-lg-6 col-md-12">' + '<div class="row">' + '<div class="col-lg-6 col-md-6 pl-2 pt-2">' + '<p class="title-step">' + data.cls[_i2].cust_name + '</p>' + '</div>' + '<div class="col-lg-4 col-md-4 pt-2">' + '<p class="value-step">' + data.cls[_i2].project_name + '</p>' + '</div>' + '</div>' + '<div class="row">' + '<div class="col-lg-10 col-md-10 pl-2">' + '<progress id="progressA" class="progress-custom progress-' + color[_i2] + '" value="' + limit_usage + '" max="100"></progress>' + '</div>' + '</div>' + '<div class="row">' + '<div class="col-lg-6 col-md-6 pl-2">' + '<p class="title-kuota">Kuota ' + limit_usage + '</p>' + '</div>' + '<div class="col-lg-4 col-md-4">' + '<p class="value-kuota">' + data.cls[_i2].persentase + '%</p>' + '</div>' + '</div>' + '</div>');
      }
    }
  });

  _ajax.getData('dashboard-bottom', 'post', value, function (data) {
    if (data.code == '01') {// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
    } else {
      var column = [{
        'data': 'operator_name'
      }, {
        'data': 'volume'
      }];
      var columnDefs = [];
      tables.setAndPopulate('tableTotalTransaction', column, data.operator, columnDefs);
      var columnMerchant = [{
        'data': 'cust_name'
      }, {
        'data': 'msisdn'
      }, {
        'data': 'endpoint'
      }];
      var columnDefsMerchant = [{
        "targets": 3,
        "data": "status",
        "className": "p-2",
        "render": function render(data, type, full, meta) {
          var data = '';

          if (full.status == 200) {
            data = "<div class='span-status-dashboard status-done'>Done</div>";
          } else {
            data = "<div class='span-status-dashboard status-terminate'>Failed</div>";
          }

          return data;
        }
      }];
      tables.setAndPopulate('tableMerchantTransaction', columnMerchant, data.merchant, columnDefsMerchant);
    }
  });
}

if ($("#formAddTicket").length) {
  $('#responseTicket').summernote({
    height: 100,
    direction: 'ltr',
    codemirror: {
      // codemirror options
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
    } // if (htmleditor == '<p><br></p>' || htmleditor == '') {
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
  });
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
  $("#deleteButtonCategory").click(function (e) {
    form.submit('formDeleteCategory');
  });
}

if ($("#formDeleteInventory").length) {
  $("#deleteButtonInventory").click(function (e) {
    form.submit('formDeleteInventory');
  });
}

if ($("#formDeleteUser").length) {
  $("#deleteButtonUser").click(function (e) {
    form.submit('formDeleteUser');
  });
}

if ($("#formDeleteRole").length) {
  $("#deleteButtonRole").click(function (e) {
    form.submit('formDeleteRole');
  });
}

if ($("#formDeleteProduk").length) {
  $("#deleteButtonProduk").click(function (e) {
    form.submit('formDeleteProduk');
  });
}

if ($("#formDeleteBanner").length) {
  $("#deleteButtonBanner").click(function (e) {
    form.submit('formDeleteBanner');
  });
}

if ($("#formChangeStatusCategory").length) {
  $("#buttonStatusCategory").click(function (e) {
    form.submit('formChangeStatusCategory');
  });
}

if ($("#formChangeStatusTransaction").length) {
  $("#buttonStatusTransaction").click(function (e) {
    form.submit('formChangeStatusTransaction');
  });
}

if ($("#formKonfirmasi").length) {
  $("#submitKonfirmasi").click(function (e) {
    form.submit('formKonfirmasi');
  });
  $(".cancel-transaction").click(function (e) {
    $("#modalStatusBatalkan").modal('show');
  });
}

if ($("#formPesananDiproses").length) {
  $("#submitProses").click(function (e) {
    form.submit('formPesananDiproses');
  });
  $(".cancel-transaction").click(function (e) {
    $("#modalStatusBatalkan").modal('show');
  });
}

if ($("#formBatalkan").length) {
  $(".submit").click(function (e) {
    form.submit('formBatalkan');
  });
}

if ($("#formPesananDikirim").length) {
  $("#kirimPesanan").click(function (e) {
    form.validate('formPesananDikirim', 0);
  });
}

if ($("#formPesananSelesai").length) {
  $("#pesananSelesai").click(function (e) {
    form.validate('formPesananSelesai', 0);
  });
}

if ($("#formChangeStatusUser").length) {
  $("#buttonStatusUser").click(function (e) {
    form.submit('formChangeStatusUser');
  });
}

if ($("#formChangeStatusProduk").length) {
  $("#buttonStatusProduk").click(function (e) {
    form.submit('formChangeStatusProduk');
  });
}

if ($("#formChangeStatusPromoProduk").length) {
  $("#buttonStatusPromoProduk").click(function (e) {
    form.submit('formChangeStatusPromoProduk');
  });
}

if ($("#formChangeStatusBanner").length) {
  $("#buttonStatusBanner").click(function (e) {
    form.submit('formChangeStatusBanner');
  });
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
  $("#promoStatus").change(function () {
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
  $("#categoryId").change(function () {
    var code = $("#categoryId option:selected").data('code');
    $("#categoryCode").text(code + '-');
  });
  $("#valuePriceProduct").keyup(function () {
    var price = $("#valuePriceProduct").val();
    $("#valuePriceProduct").val(formatRupiahRp(price));
    var splitedRp = price.split('Rp.');
    var splittedPrice = splitedRp[1].split('.');
    document.getElementById('priceValueProduct').value = splittedPrice.join('');
  });
  $("#promoType").change(function () {
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
  $("#promoValueInput").keyup(function () {
    if ($("#valuePriceProduct").val() == '') {
      document.getElementById('promoValue').value = '';
      document.getElementById('promoValueInput').value = '';
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
      console.log(document.getElementById('promoValue').value);
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
  $("#promoStatus").change(function () {
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
  $("#promoType").change(function () {
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
  $("#promoValueInput").keyup(function () {
    if ($("#valuePriceProduct").val() == '') {
      document.getElementById('promoValue').value = '';
      document.getElementById('promoValueInput').value = '';
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
  $("#valuePriceProduct").keyup(function () {
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
      $("#flagStatus").addClass('status-active');
      $("#flagStatus").removeClass('status-remove');
    } else {
      $("#flagStatus").removeClass('status-active');
      $("#flagStatus").addClass('status-remove');
    }
  });
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

    $("#statusChange").val(status);
    $("#modalChangeStatus").modal('show');
  });
}

if ($("#formAddUser").length) {
  $("#role").change(function () {
    var role = this.value;
    $("#divPermission").removeClass('hidden');
    $("#listPermission").empty();

    _ajax.getData('user/search-permission', 'post', {
      role: role
    }, function (data) {
      var permission = '';

      for (var i = 0; i < data.length; i++) {
        permission = permission + '<div class="col-md-5 pl-0"><label for="" class="checkbox pl-0 mb-2">' + '<div class="row">' + '<div class="col-md-1 pr-0">' + '<center>' + '<input class="fitur-role" type="checkbox" checked>' + '<span class="cbver2"></span>' + '</center>' + '</div>' + '<div class="col-md-6 pl-0">' + '<p class="text-fitur">' + data[i].title + '</p>' + '</div>' + '</div>' + '</label></div>';
      }

      $("#listPermission").append(permission);
    });

    if ($("#role").val() != '') {
      $('#role').parents('.form-group').removeClass('has-error');
      $('#role').parents('.form-group').removeClass('form-error');
    }
  });
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
    $("#divPermission").removeClass('hidden');
    $("#listPermission").empty();

    _ajax.getData('user/search-permission', 'post', {
      role: role
    }, function (data) {
      var permission = '';

      for (var i = 0; i < data.length; i++) {
        permission = permission + '<div class="col-md-5 pl-0"><label for="" class="checkbox pl-0 mb-2">' + '<div class="row">' + '<div class="col-md-1 pr-0">' + '<center>' + '<input class="fitur-role" type="checkbox" checked>' + '<span class="cbver2"></span>' + '</center>' + '</div>' + '<div class="col-md-6 pl-0">' + '<p class="text-fitur">' + data[i].title + '</p>' + '</div>' + '</div>' + '</label></div>';
      }

      $("#listPermission").append(permission);
    });

    if ($("#role").val() != '') {
      $('#role').parents('.form-group').removeClass('has-error');
      $('#role').parents('.form-group').removeClass('form-error');
    }
  });
  $("#flagStatus").change(function (e) {
    var status = this.value;

    if (status == '1') {
      $("#flagStatus").addClass('status-active');
      $("#flagStatus").removeClass('status-remove');
    } else {
      $("#flagStatus").removeClass('status-active');
      $("#flagStatus").addClass('status-remove');
    }
  });
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

    $("#statusChange").val(status);
    $("#modalChangeStatus").modal('show');
  });
  $("#btnEditUser").click(function (e) {
    form.validate('formEditUser', 1);
  });
}

if ($("#formAddBroadcast").length) {
  var readFile = function readFile(input) {
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
        var htmlPreview = '<img src="' + e.target.result + '" class="cover-fit" style="width:100%;height:150px;margin-top:0px;" />'; // console.log(htmlPreview.width())

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
  };

  var reset = function reset(e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
  };

  $('#contentBroadcast').summernote({
    height: 100,
    direction: 'ltr',
    codemirror: {
      // codemirror options
      theme: 'monokai'
    },
    toolbar: [// [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']], ['font', ['fontname', 'fontsizeunit']], ['color', ['color', 'forecolor', 'backcolor']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['table']]]
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
    } // if (htmleditor == '<p><br></p>' || htmleditor == '') {
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
    $("#subjectModal").html($("#subjectBroadcast").val());
    form.validate('formAddBroadcast', 1);
  });
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
      $("#flagStatus").addClass('status-active');
      $("#flagStatus").removeClass('status-remove');
    } else {
      $("#flagStatus").removeClass('status-active');
      $("#flagStatus").addClass('status-remove');
    }

    $("#status").val(status);
  });
  $("#btnCancel").click(function () {
    if (valueStatus == '1') {
      $("#flagStatus").addClass('status-active');
      $("#flagStatus").removeClass('status-remove');
    } else {
      $("#flagStatus").removeClass('status-active');
      $("#flagStatus").addClass('status-remove');
    }

    $("#flagStatus").val(valueStatus);
    $("#status").val(valueStatus);
    $("#modalEditURL").modal('hide');
  });
  $("#btnRevoke").click(function () {
    $("#modalRevokeApi").modal({
      backdrop: 'static'
    });
  });
  $("#btnEditUrl").click(function (e) {
    form.validate('formEditUrl', 1);
  });
  $("#btnCopyApi").click(function () {
    var copyText = document.getElementById("apikey");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
  });
}

if ($("#filterTransaction")) {
  var today = new Date();
  $('#startDate').datetimepicker({
    format: 'DD-MM-YYYY'
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
    $("#rowService").empty();
    $("#rowService").append('<div class="form-group d-flex flex-column">' + '<select name="byService" id="byService" class="form-control select2">' + '<option value="">Choose Service</option>' + '</select>' + '</div>');

    _ajax.getData('transaction/get-project', 'post', {
      idMerchant: this.value
    }, function (data) {
      var dataService = [];

      for (var i = 0; i < data.length; i++) {
        var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>';
        dataService.push(option);
      }

      $("#byService").append(dataService);
      $("#byService").select2({
        placeholder: 'Choose Service'
      });
    });
  });
}

if ($("#filterRecon")) {
  var today = new Date();
  $('#startDate').datetimepicker({
    format: 'DD-MM-YYYY'
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
    $("#rowService").empty();
    $("#rowService").append('<label for="">Merchant Project</label>' + '<div class="form-group d-flex flex-column">' + '<select name="service" id="service" class="form-control select2">' + '<option value="">Pilih Merchant Project</option>' + '</select>' + '</div>');

    _ajax.getData('recon/get-project', 'post', {
      idMerchant: this.value
    }, function (data) {
      var dataService = [];

      for (var i = 0; i < data.length; i++) {
        var option = '<option value="' + data[i].id + '">' + data[i].project_name + '</option>';
        dataService.push(option);
      }

      $("#service").append(dataService);
      $("#service").select2({
        placeholder: 'Pilih Merchant Project'
      });
    });
  });
}

if ($("#filterIntegration")) {
  var today = new Date();
  $('#startDate').datetimepicker({
    format: 'DD-MM-YYYY'
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
  var data = JSON.parse($("#request").val());
  document.getElementById("requestDetail").innerHTML = '<b>' + JSON.stringify(data, null, 4) + '<b>';
}

if ($("#responseDetail").length) {
  var data = JSON.parse($("#response").val());
  document.getElementById("responseDetail").innerHTML = '<b>' + JSON.stringify(data, null, 4) + '<b>';
}
/** FOR FILES SUBMIT MODAL */
// $("#submitCategory").click(function (e) {
// });


formatRupiah = function formatRupiah(money) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(money);
};

var tables = {
  init: function init() {
    if ($('#tableCategory').length) {
      var datatable = $('#tableCategory').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'category/list-category',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 6]
          }
        }],
        columns: [{
          data: 'icon',
          name: 'icon',
          orderable: false,
          searchable: false
        }, {
          data: 'creator',
          name: 'creator'
        }, {
          data: 'category_name',
          name: 'category_name'
        }, {
          data: 'code_category',
          name: 'code_category'
        }, {
          data: 'status_category',
          name: 'status_category'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }]
      });
      $(".cancel-status").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableRole').length) {
      var datatable = $('#tableRole').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'role/list-role',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2]
          }
        }],
        columns: [{
          data: 'title_name',
          name: 'title_name'
        }, {
          data: 'creator',
          name: 'creator'
        }, {
          data: 'updated_at',
          name: 'updated_at'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }]
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableBanner').length) {
      var datatable = $('#tableBanner').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'banner/list-banner',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 4]
          }
        }],
        columns: [{
          data: 'title',
          name: 'title'
        }, {
          data: 'creator',
          name: 'creator'
        }, {
          data: 'status_banner',
          name: 'status_banner'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }]
      });
      $(".cancel-status").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableVoucher').length) {
      var datatable = $('#tableVoucher').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'voucher/list-voucher',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 7]
          }
        }],
        columns: [{
          data: 'title_voucher',
          name: 'title_voucher'
        }, {
          data: 'code_voucher',
          name: 'code_voucher'
        }, {
          data: 'type',
          name: 'type'
        }, {
          data: 'rest_voucher',
          name: 'rest_voucher'
        }, {
          data: 'used_voucher',
          name: 'used_voucher'
        }, {
          data: 'status_voucher',
          name: 'status_voucher'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }]
      });
      $(".cancel-status").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableTransaction').length) {
      var datatable = $('#tableTransaction').DataTable({
        processing: true,
        // serverSide: true,
        method: 'POST',
        ajax: 'transaction/list-transaction',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5, 6]
          }
        }],
        columns: [{
          data: 'transaction_id',
          name: 'transaction_id'
        }, {
          data: 'invoice',
          name: 'invoice'
        }, {
          data: 'customer_name',
          name: 'customer_name'
        }, {
          data: 'whatsapp',
          name: 'whatsapp'
        }, {
          data: 'transaction_date',
          name: 'transaction_date'
        }, {
          data: 'total',
          name: 'total'
        }, {
          data: 'status',
          name: 'status'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }]
      });
      $(".btn-notif-cancel").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableCustomer').length) {
      var datatable = $('#tableCustomer').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'customer/list-customer',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2]
          }
        }],
        columns: [{
          data: 'id',
          name: 'id'
        }, {
          data: 'whatsapp_number',
          name: 'whatsapp_number'
        }, {
          data: 'fullname',
          name: 'fullname'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }]
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableProductInventory').length) {
      var params = new URL(document.location).searchParams;
      var productId = params.get("product");
      var datatable = $('#tableProductInventory').DataTable({
        processing: true,
        // serverSide: true,
        ajax: '/product/inventory-detail-product?product=' + productId,
        columns: [{
          data: 'agen',
          name: 'agen'
        }, {
          data: 'total_inventory',
          name: 'total_inventory'
        }]
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableCustomerTransaksi').length) {
      var _params = new URL(document.location).searchParams;

      var customerId = _params.get("customer");

      var datatable = $('#tableCustomerTransaksi').DataTable({
        processing: true,
        // serverSide: true,
        ajax: '/customer/detail-customer/transaction?customer=' + customerId,
        columns: [{
          data: 'transaction_id',
          name: 'transaction_id'
        }, {
          data: 'invoice',
          name: 'invoice'
        }, {
          data: 'customer_name',
          name: 'customer_name'
        }, {
          data: 'whatsapp',
          name: 'whatsapp'
        }, {
          data: 'transaction_date',
          name: 'transaction_date'
        }, {
          data: 'total',
          name: 'total'
        }, {
          data: 'status',
          name: 'status'
        }]
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableProduct').length) {
      var _formatRupiahRp = function _formatRupiahRp(angka) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi); // tambahkan titik jika yang di input sudah menjadi angka ribuan

        if (ribuan) {
          separator = sisa ? "." : "";
          rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + split[1] : rupiah; // return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";

        return 'Rp. ' + rupiah;
      };

      var _formatPercent = function _formatPercent(angka) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            percent = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi); // tambahkan titik jika yang di input sudah menjadi angka ribuan

        if (ribuan) {
          separator = sisa ? "." : "";
          percent += separator + ribuan.join(".");
        }

        percent = split[1] != undefined ? percent + split[1] : percent; // return prefix == undefined ? percent : percent ? "Rp. " + percent : "";

        return percent + '%';
      };

      var datatable = $('#tableProduct').DataTable({
        processing: true,
        ajax: 'product/list-product',
        dom: 'lBfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 10, 5, 11, 7, 8]
          }
        }],
        columns: [{
          data: 'code',
          name: 'code'
        }, {
          data: 'name',
          name: 'name'
        }, {
          data: 'category_name',
          name: 'category_name'
        }, {
          data: 'price_product',
          name: 'price_product'
        }, {
          data: 'status_promo',
          name: 'status_promo'
        }, {
          data: 'promo_total_price',
          name: 'promo_total_price'
        }, {
          data: 'product_status',
          name: 'product_status'
        }, {
          data: 'creator',
          name: 'creator'
        }, {
          data: 'updated_at',
          name: 'updated_at'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }, {
          data: 'string_promo_status',
          name: 'string_promo_status',
          visible: false
        }]
      });
      $(".cancel_status").click(function () {
        datatable.ajax.reload(null, false);
      });
      $(".cancel_status_promo").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
      $("#promoStatus").change(function () {
        var status = $("#promoStatus option:selected").val();

        if (status == '1') {
          $("#formPromoType").removeClass('d-none');
          $("#formPromoValue").removeClass('d-none');
          $("#formTotalPrice").removeClass('d-none');
        } else {
          $("#formPromoType").addClass('d-none');
          $("#formPromoValue").addClass('d-none');
          $("#formTotalPrice").addClass('d-none');
        }
      });
      $("#valuePriceProduct").keyup(function () {
        var price = $("#valuePriceProduct").val();
        $("#valuePriceProduct").val(_formatRupiahRp(price));
        var splitedRp = price.split('Rp.');
        var splittedPrice = splitedRp[1].split('.');
        document.getElementById('priceValueProduct').value = splittedPrice.join('');
      });
      $("#promoType").change(function () {
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
      $("#promoValueInput").keyup(function () {
        if ($("#valuePriceProduct").val() == '') {
          document.getElementById('promoValue').value = '';
          document.getElementById('promoValueInput').value = '';
        }

        var typePromo = $("#promoType option:selected").val();
        var valuePromo = $('#promoValueInput');
        var priceProduct = $('#priceValueProduct').val();

        if (typePromo == 'fixed') {
          var price = _formatRupiahRp(valuePromo.val());

          $("#promoValueInput").val(price);
          var splitedRp = price.split('Rp.');
          var splittedPrice = splitedRp[1].split('.');
          document.getElementById('promoValue').value = splittedPrice.join('');
          var calculated = parseInt(priceProduct) - parseInt(splittedPrice.join(''));
          console.log(calculated);

          if (calculated < 0) {
            document.getElementById('priceProduct').value = _formatRupiahRp('0');
          } else {
            document.getElementById('priceProduct').value = _formatRupiahRp(calculated.toString());
          }
        } else {
          var realValue = valuePromo.val();
          document.getElementById('promoValueInput').value = _formatPercent(realValue);
          var splitedDiscount = realValue.split('%');
          var splittedPrice = splitedDiscount.join('');
          var splitValueDiscount = splittedPrice.split('.');
          var realDiscount = splitValueDiscount.join('');
          document.getElementById('promoValue').value = realDiscount;
          var discount = realDiscount / 100;
          var totalDeducted = parseInt(priceProduct) * discount;
          var calculatePromo = parseInt(priceProduct) - parseInt(totalDeducted);

          if (calculatePromo < 0) {
            document.getElementById('priceProduct').value = _formatRupiahRp('0');
          } else {
            document.getElementById('priceProduct').value = _formatRupiahRp(calculatePromo.toString());
          }
        }
      });
    }

    if ($('#tableInventory').length) {
      var datatable = $('#tableInventory').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'inventory/list-inventory',
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 11, 5, 12, 7, 8, 9],
            modifier: {
              page: 'all',
              search: 'none'
            }
          }
        }],
        columns: [{
          data: 'code_product',
          name: 'code_product'
        }, {
          data: 'product_name',
          name: 'product_name'
        }, {
          data: 'category',
          name: 'category'
        }, {
          data: 'product_price',
          name: 'product_price'
        }, {
          data: 'status_promo',
          name: 'status_promo'
        }, {
          data: 'promo_total_price',
          name: 'promo_total_price'
        }, {
          data: 'product_status',
          name: 'product_status'
        }, {
          data: 'total_inventory',
          name: 'total_inventory'
        }, {
          data: 'total_sold',
          name: 'total_sold'
        }, {
          data: 'current_qty',
          name: 'current_qty'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }, {
          data: 'string_promo_status',
          name: 'string_promo_status',
          visible: false
        }]
      });
      $(".btn-notif-cancel").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }

    if ($('#tableUser').length) {
      var datatable = $('#tableUser').DataTable({
        processing: true,
        // serverSide: true,
        ajax: 'user/list-user',
        language: {
          search: "",
          searchPlaceholder: "Search records"
        },
        dom: 'Bfrtip',
        buttons: [{
          extend: 'excelHtml5',
          className: 'btn btn-save btn-status text-white',
          text: 'Export',
          exportOptions: {
            columns: [0, 1, 2, 3, 4, 9, 6, 7]
          }
        }],
        columns: [{
          data: 'id',
          name: 'id'
        }, {
          data: 'fullname',
          name: 'fullname'
        }, {
          data: 'email_user',
          name: 'email_user'
        }, {
          data: 'phone',
          name: 'phone'
        }, {
          data: 'role',
          name: 'role'
        }, {
          data: 'status_user',
          name: 'status_user'
        }, {
          data: 'creator',
          name: 'creator'
        }, {
          data: 'updated_at',
          name: 'updated_at'
        }, {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }, {
          data: 'string_status',
          name: 'string_status',
          visible: false
        }]
      });
      $(".cancel-status").click(function () {
        datatable.ajax.reload(null, false);
      });
      datatable.buttons().container().appendTo('#tableTransaction_filter');
    }
  },
  filter: function filter(id, value) {
    if (id == 'filterDashboard') {
      _ajax.getData('dashboard-top', 'post', value, function (data) {
        if (data.code == '01') {
          ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
        } else {
          $("#chartMerchantTransaction").empty();
          $("#valueTransactionSuccess").html(data.transactionSuccess + '<span class="span-persen" id="spanPersen"></span>');
          $("#spanPersen").html(data.persenTransactionSuccess + ' %');
          $("#valueTransactionVolume").html(data.allTransaction);
          var options = {
            series: [{
              name: "Add MSISDN",
              data: data.add_msisdn
            }, {
              name: "Cek",
              data: data.cek
            }, {
              name: "Check Result",
              data: data.check_result
            }],
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
              enabled: true
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
                colors: ['#f3f3f3', 'transparent'],
                // takes an array which will be repeated on columns
                opacity: 0.5
              }
            },
            markers: {
              size: 1
            },
            xaxis: {
              categories: data.day // title: {
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

      _ajax.getData('dashboard-middle', 'post', value, function (data) {
        if (data.code == '01') {// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
        } else {
          $("#rowAverage").empty();

          for (var i = 0; i < data.average.length; i++) {
            $("#rowAverage").append('<div class="col-lg-6 col-md-12 pl-4 pt-2 mb-2">' + '<p class="title-step">' + data.average[i].endpoint + '</p>' + '</div>' + '<div class="col-lg-5 col-md-12 pt-2">' + '<p class="value-step">' + data.average[i].mili + ' m/s</p>' + '</div>');
          }

          var color = ['red', 'blue', 'orange', 'green'];
          $("#rowCLS").empty();

          for (var _i3 = 0; _i3 < data.cls.length; _i3++) {
            var limit_usage = '';

            if (data.cls[_i3].limit_usage == null) {
              limit_usage = '0';
            } else {
              limit_usage = data.cls[_i3].limit_usage;
            }

            $("#rowCLS").append('<div class="col-lg-6 col-md-12">' + '<div class="row">' + '<div class="col-lg-6 col-md-6 pl-2 pt-2">' + '<p class="title-step">' + data.cls[_i3].cust_name + '</p>' + '</div>' + '<div class="col-lg-4 col-md-4 pt-2">' + '<p class="value-step">' + data.cls[_i3].project_name + '</p>' + '</div>' + '</div>' + '<div class="row">' + '<div class="col-lg-10 col-md-10 pl-2">' + '<progress id="progressA" class="progress-custom progress-' + color[_i3] + '" value="' + limit_usage + '" max="100"></progress>' + '</div>' + '</div>' + '<div class="row">' + '<div class="col-lg-6 col-md-6 pl-2">' + '<p class="title-kuota">Kuota ' + limit_usage + '</p>' + '</div>' + '<div class="col-lg-4 col-md-4">' + '<p class="value-kuota">' + data.cls[_i3].persentase + '%</p>' + '</div>' + '</div>' + '</div>');
          }
        }
      });

      _ajax.getData('dashboard-bottom', 'post', value, function (data) {
        if (data.code == '01') {// ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
        } else {
          var column = [{
            'data': 'operator_name'
          }, {
            'data': 'volume'
          }];
          var columnDefs = [];
          tables.setAndPopulate('tableTotalTransaction', column, data.operator, columnDefs);
          var columnMerchant = [{
            'data': 'cust_name'
          }, {
            'data': 'msisdn'
          }, {
            'data': 'endpoint'
          }];
          var columnDefsMerchant = [{
            "targets": 3,
            "data": "status",
            "className": "p-2",
            "render": function render(data, type, full, meta) {
              var data = '';

              if (full.status == 200) {
                data = "<div class='span-status-dashboard status-done'>Done</div>";
              } else {
                data = "<div class='span-status-dashboard status-terminate'>Failed</div>";
              }

              return data;
            }
          }];
          tables.setAndPopulate('tableMerchantTransaction', columnMerchant, data.merchant, columnDefsMerchant);
        }
      });
    }

    if (id == 'filterTransaction') {
      $("#downloadLog").attr('href', '/transaction/export/' + value);
      var column = [{
        'data': 'unique_id'
      }, {
        'data': 'msisdn'
      }, {
        'data': 'request'
      }, {
        'data': 'operator_name'
      }, {
        'data': 'updated_at'
      }, {
        'data': 'status'
      }, {
        'data': 'endpoint'
      }, {
        'data': 'response'
      }, {
        'data': 'cust_name'
      }, {
        'data': 'project_name'
      }];
      columnDefs = [{
        "targets": 6,
        "data": "endpoint",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var value = '';

          if (full.endpoint != null) {
            var data1 = full.endpoint.substring(0, 30);
            var data2 = full.endpoint.substring(31);
            value = '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>';
          } else {
            value = full.endpoint;
          } // console.log()


          return value;
        }
      }, {
        "targets": 2,
        "data": "request",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var data1 = full.request.substring(0, 100);
          var data2 = full.request.substring(101, 200);
          var data3 = full.request.substring(201); // console.log()

          return '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>' + '<span class="spanReq3">' + data3 + '</span>';
        }
      }, {
        "targets": 7,
        "data": "response",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var data1 = full.response.substring(0, 100);
          var data2 = full.response.substring(101, 200);
          var data3 = full.response.substring(201); // console.log()

          return '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>' + '<span class="spanReq3">' + data3 + '</span>';
        }
      }, {
        "targets": 5,
        "data": "status",
        "render": function render(data, type, full, meta) {
          var data = '';

          if (full.status == "200" || full.status == "302") {
            data = "<div class='span-status status-berhasil'>" + full.status + "</div>";
          } else {
            data = "<div class='span-status status-suspend'>" + full.status + "</div>";
          }

          return '<center>' + data + '</center>';
        }
      }, {
        "targets": 10,
        "data": "id",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var data = '<center><a class="detail-table" href="/transaction/detail-transaction/' + full.id + '">Detail</a></center>';
          return data;
        }
      }];
      tables.serverSide('table-transaction-error', column, 'transaction/list-transaction-error', value, columnDefs, "simple_numbers", "Search by MSISDN");
    }

    if (id == 'filterRecon') {}

    if (id == 'filterIntegration') {
      $("#downloadIntegration").attr('href', '/integration/export/' + value);
      var column = [{
        'data': 'transaction_id'
      }, {
        'data': 'request'
      }, {
        'data': 'response'
      }, {
        'data': 'endpoint'
      }, {
        'data': 'created_at'
      }, {
        'data': 'updated_at'
      }, {
        'data': 'status'
      }];
      columnDefs = [{
        "targets": 1,
        "data": "request",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var regex = /(<([^>]+)>)/ig; // console.log()

          return data.replace(regex, "").substring(1, 100);
        }
      }, {
        "targets": 2,
        "data": "response",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var regex = /(<([^>]+)>)/ig; // console.log()

          return data.replace(regex, "").substring(1, 100);
        }
      }, {
        "targets": 6,
        "data": "status",
        "render": function render(data, type, full, meta) {
          var data = '';

          if (full.status == "200") {
            data = "<div class='span-status status-berhasil'>Success</div>";
          } else {
            data = "<div class='span-status status-suspend'>Failed</div>";
          }

          return '<center>' + data + '</center>';
        }
      }, {
        "targets": 7,
        "data": "id",
        "className": "action-poster-news",
        "render": function render(data, type, full, meta) {
          var data = '<center><a class="detail-table" href="/integration/detail-integration/' + full.id + '">Detail</a></center>';
          return data;
        }
      }];
      tables.serverSide('table-integration', column, 'integration/list-integration', value, columnDefs, "simple_numbers", "Search by Transaction ID");
    }
  },
  getData: function getData(url, params, callback) {
    $.ajax({
      url: url,
      type: 'post',
      data: params,
      success: function success(result) {
        if (!result.error) {
          callback(null, result.data);
        } else {
          callback(data);
        }
      }
    });
  },
  clear: function clear(id) {
    var tbody = $('#' + id).find('tbody');
    tbody.html('');
  },
  serverSide: function serverSide(id, columns, url) {
    var custParam = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var columnDefs = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;
    var pagingType = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : "simple_numbers";
    var placeholder = arguments.length > 6 && arguments[6] !== undefined ? arguments[6] : "";
    var urutan = [0, 'desc'];

    if (id == 'table-transaction-error') {
      urutan = [4, 'desc'];
    }

    var ordering = true;
    var search = true;

    if (id == 'table-invoice') {
      search = false;
    }

    var svrTable = $("#" + id).DataTable({
      pagingType: pagingType,
      // "drawCallback": function( settings ) {
      // 	if (id == "tableVacancy") {
      // 		$('.dataTables_scrollHead').remove()
      // 		$('.dataTables_scrollBody table thead').hide()
      // 	}
      // },
      // processing:true,
      // scrollY: "325px",
      scrollCollapse: true,
      // serverSide: true,
      columnDefs: columnDefs,
      columns: columns,
      responsive: false,
      scrollX: true,
      // scrollY: true,
      ajax: function ajax(data, callback, settings) {
        data.param = custParam;

        _ajax.getData(url, 'post', data, function (result) {
          if (result.status == 'reload') {
            ui.popup.show('confirm', result.messages.title, result.messages.message, 'refresh');
          } else if (result.status == 'logout') {
            ui.popup.alert(result.messages.title, result.messages.message, 'logout');
          } else {
            callback(result);
          }
        });
      },
      bDestroy: true,
      searching: search,
      order: urutan,
      ordering: ordering,
      language: {
        searchPlaceholder: placeholder
      }
    });
    $('div.dataTables_filter input').unbind();
    $('div.dataTables_filter input').bind('keyup', function (e) {
      if (e.keyCode == 13) {
        svrTable.search(this.value).draw();
      }
    });
  },
  setAndPopulate: function setAndPopulate(id, columns, data, columnDefs, ops, order) {
    var _option;

    var orderby = order ? order : [0, "asc"];
    var search = true;
    var paging = true;
    var ordering = true;
    var info = true;

    if (id == 'tableProject' || id == 'tableMerchantTransaction' || id == 'tableTotalTransaction') {
      search = false;
      paging = false;
      info = false;
    }

    var option = (_option = {
      "data": data,
      "drawCallback": function drawCallback(settings) {},
      "searching": search,
      "paging": paging,
      "ordering": ordering,
      "info": info,
      "columns": columns,
      "pageLength": 10,
      "order": [orderby],
      "bDestroy": true,
      "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
      "aoColumnDefs": columnDefs,
      "scrollX": true,
      "scrollY": true
    }, _defineProperty(_option, "lengthMenu", [[10, 25, 50, -1], [10, 25, 50, "All"]]), _defineProperty(_option, "rowCallback", function rowCallback(row, data) {
      if (id == "tbl_notification") {
        if (data.read == "1") {
          $(row).css('background-color', '#D4D4D4');
        }
      }

      if (id == "tbl_mitra" || id == "tbl_user" || id == "tbl_agent_approved") {
        if (data.status == "0") {
          $(row).css('background-color', '#FF7A7A');
        }
      }
    }), _option);

    if (ops != null) {
      $.extend(option, ops);
    }

    var tbody = $('#' + id).find('tbody');
    var t = $('#' + id).DataTable(option);
    t.on('order.dt search.dt', function () {
      if (id == 'tableProject' || id == 'tableMerchantTransaction' || id == 'tableTotalTransaction') {} else {
        t.column(0, {
          search: 'applied',
          order: 'applied'
        }).nodes().each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
      }
    }).draw();
  }
};
$('#tableProject tbody').on('click', 'button.edit-price', function (e) {
  var table = $('#tableProject').DataTable();
  var dataRow = table.row($(this).closest('tr')).data();
  var onnet = document.getElementById('onnet');
  onnet.addEventListener("keyup", function (e) {
    // tambahkan 'Rp.' pada saat form di ketik
    // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
    onnet.value = formatRupiahRp(this.value);
  });
  var offnet = document.getElementById('offnet');
  offnet.addEventListener("keyup", function (e) {
    // tambahkan 'Rp.' pada saat form di ketik
    // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
    offnet.value = formatRupiahRp(this.value);
  });
  var valueOffnet = dataRow.price_offnet;
  var rupiahOffnet = '';
  var angkaOffnet = valueOffnet.toString().split('').reverse().join('');

  for (var i = 0; i < angkaOffnet.length; i++) {
    if (i % 3 == 0) rupiahOffnet += angkaOffnet.substr(i, 3) + '.';
  }

  var nominalOffnet = rupiahOffnet.split('', rupiahOffnet.length - 1).reverse().join('');
  $("#offnet").val('Rp ' + nominalOffnet);
  var valueOnnet = dataRow.price_onnet;
  var rupiahOnnet = '';
  var angkaOnnet = valueOnnet.toString().split('').reverse().join('');

  for (var i = 0; i < angkaOnnet.length; i++) {
    if (i % 3 == 0) rupiahOnnet += angkaOnnet.substr(i, 3) + '.';
  }

  var nominalOnnet = rupiahOnnet.split('', rupiahOnnet.length - 1).reverse().join('');
  $("#onnet").val('Rp ' + nominalOnnet);
  $("#idProject").val(dataRow.id);
  $("#modalChangePrice").modal({
    backdrop: 'static'
  });
});
var ui = {
  popup: {
    show: function show(type, title, message, button, url) {
      var cancelBtn = arguments.length > 5 && arguments[5] !== undefined ? arguments[5] : false;

      if (type == 'error') {
        $("#iconError").removeClass('hidden');
        $("#iconSuccess").addClass('hidden');
        $("#titleNotif").html(title);
        $("#contentNotif").html(message);
        $("#btnNotif").html('<button onclick="window.location.reload();" type="button" data-dismiss="modal" aria-label="Close" class="btn btn-save">' + button + '</button>');
        $('#modalNotif').modal({
          backdrop: 'static',
          show: true
        });
      } else if (type == 'success') {
        // $("#iconError").addClass('hidden')
        // $("#iconSuccess").removeClass('hidden')
        if (url == 'close') {
          // $("#titleNotif").html(title);
          $("#titleNotif").html(message); // $("#titleNotif").html(message);
          // $("#btnNotif").html('<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif">'+button+'</button>')

          $("#btnNotif").html("<button onClick=\"window.location.href = '".concat(window.location.origin).concat(url, "'\" class=\"btn btn-save btn-status btn-notif\" data-dismiss=\"modal\" aria-label=\"Close\" type=\"button\" id=\"buttonStatusUser\" class=\"btn-notif\">Lanjutkan</button>"));
          $('#modalNotif').modal({
            backdrop: 'static',
            show: true
          });
        } else {
          var cancelButton = '';

          if (cancelBtn) {
            cancelButton = '<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif-cancel" id="">Batalkan</button>';
          } // $("#titleNotif").html(title);


          $("#titleNotif").html(message); // $("#titleNotif").html(message);
          // $("#btnNotif").html('<button type="button" data-dismiss="modal" aria-label="Close" class="btn-notif">'+button+'</button>')

          $("#btnNotif").html("<button class=\"btn btn-save btn-status\" onClick=\"window.location.href = '".concat(window.location.origin).concat(url, "'\"  data-dismiss=\"modal\" aria-label=\"Close\" type=\"button\" id=\"buttonStatusUser\" class=\"btn-notif\">Lanjutkan</button>"));
          $('#modalNotif').modal({
            backdrop: 'static',
            show: true
          });
          $("#notifRedirect").click(function () {
            window.location = url;
          });
        }
      } else {
        $("#iconError").removeClass('hidden');
        $("#iconSuccess").addClass('hidden');
        Swal.fire({
          title: message,
          type: type,
          confirmButtonText: 'Mengerti',
          allowOutsideClick: false
        });
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
  slide: {
    init: function init() {
      $('.carousel-control').on('click', function (e) {
        e.preventDefault();
        var control = $(this);
        var item = control.parent();

        if (control.hasClass('right')) {
          ui.slide.next(item);
        } else {
          ui.slide.prev(item);
        }
      });
      $('.slideBtn').on('click', function (e) {
        e.preventDefault();
        var control = $(this);
        var item = $("#" + control.attr('for'));

        if (item[0].id === 'page-1') {
          $('.tracking-line div').removeClass();
          $('.education-information img').attr('src', '/image/icon/homepage/track-toga-red.svg');
          $('.personal-information').removeClass('active');
          $('.education-information').addClass('active');
          $('.tracking-line div:first-child').addClass('red-line');
          $('.tracking-line div:last-child').addClass('gray-line');
        } else if (item[0].id === 'page-2') {
          $('.tracking-line div').removeClass();
          $('.other-information img').attr('src', '/image/icon/homepage/track-pin-red.svg');
          $('.education-information').removeClass('active');
          $('.other-information').addClass('active');
          $('.tracking-line div:first-child').addClass('red-line');
          $('.tracking-line div:last-child').addClass('red-line');
        } else {
          $('.tracking-line div').removeClass();
          $('.tracking-line div:first-child').addClass('red-line');
          $('.tracking-line div:last-child').addClass('red-line');
        }

        if (control.hasClass('btn-next')) {
          ui.slide.next(item);
        } else {
          ui.slide.prev(item);
        }
      });
    },
    next: function next(item) {
      var nextItem = item.next();
      item.toggle({
        'slide': {
          direction: 'left'
        }
      });
      nextItem.toggle({
        'slide': {
          direction: 'right'
        }
      });
    },
    prev: function prev(item) {
      var prevItem = item.prev();
      item.toggle({
        'slide': {
          direction: 'right'
        }
      });
      prevItem.toggle({
        'slide': {
          direction: 'left'
        }
      });
    }
  }
};
var formrules = {
  // contoh validasi id form
  'formLogin': {
    ignore: null,
    rules: {
      'username': {
        STD_VAL_WEB_5: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'form-add-respone': {
    ignore: null,
    rules: {
      'ticket_respone': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      ticket_respone: {
        required: 'Silahkan masukan Minimal 1 Karakter'
      }
    }
  },
  'formForgetPassword': {
    ignore: null,
    rules: {
      'email': {
        STD_VAL_WEB_5: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formResetPassword': {
    ignore: null,
    rules: {
      'newPassword': {
        passcheck: true
      },
      'konfirmasiPassword': {
        equalTo: '#newPassword'
      }
    },
    submitHandler: false,
    messages: {
      newPassword: {
        passcheck: 'Mohon isi password dengan kombinasi angka, huruf besar, dan huruf kecil dengan minimal 8 karakter dan maksimal 12 karakter'
      },
      konfirmasiPassword: {
        equalTo: 'Konfirmasi password tidak sesuai'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formNewPassword': {
    ignore: null,
    rules: {
      'newPassword': {
        passcheck: true
      },
      'konfirmasiPassword': {
        equalTo: '#newPassword'
      }
    },
    submitHandler: false,
    messages: {
      newPassword: {
        passcheck: 'Mohon isi password dengan kombinasi angka, huruf besar, dan huruf kecil dengan minimal 8 karakter dan maksimal 12 karakter'
      },
      konfirmasiPassword: {
        equalTo: 'Konfirmasi password tidak sesuai'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formInqCust': {
    ignore: null,
    rules: {
      'msisdnInq': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      msisdnInq: {
        required: 'Mohon isi nomor MSISDN'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddCust': {
    ignore: null,
    rules: {
      'clsNumber': {
        required: true
      },
      'bcNumber': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      clsNumber: {
        required: 'Mohon isi nomor CLS'
      },
      bcNumber: {
        required: 'Mohon isi nomor BC'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditCust': {
    ignore: null,
    rules: {
      'email': {
        required: true,
        STD_VAL_WEB_5: true
      }
    },
    submitHandler: false,
    messages: {
      email: {
        required: 'Mohon isi Email'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formChangePrice': {
    ignore: null,
    rules: {
      'offnet': {
        required: true
      },
      'onnet': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      offnet: {
        required: 'Mohon isi Offnet'
      },
      onnet: {
        required: 'Mohon isi Onnet'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddTicket': {
    ignore: null,
    rules: {
      'subjectTicket': {
        required: true
      },
      'responseTicket': {
        required: true
      },
      'typeTicket': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      subjectTicket: {
        required: 'Mohon isi Subject Ticket'
      },
      responseTicket: {
        required: 'Silahkan masukan Minimal 1 Karakter'
      },
      typeTicket: {
        required: 'Silahkan Pilih Type Ticket'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      if (element.is("#responseTicket")) {
        error.appendTo(element.parents('#responseTicketDiv'));
      } else if (element.is("#typeTicket")) {
        error.appendTo(element.parents('#typeTicketDiv'));
      } else {
        error.insertAfter(element);
      }

      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddRole': {
    ignore: null,
    rules: {
      'titleRole': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      titleRole: {
        required: 'Mohon isi title role'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddCategory': {
    ignore: null,
    rules: {
      'categoryName': {
        required: true
      },
      'codeCategory': {
        required: true
      },
      'file': {
        required: true,
        extension: "jpg,jpeg,png"
      }
    },
    submitHandler: false,
    messages: {
      categoryName: {
        required: 'Mohon isi nama kategori'
      },
      codeCategory: {
        required: 'Mohon isi kode kategori'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditCategory': {
    ignore: null,
    rules: {
      'categoryName': {
        required: true
      },
      'codeCategory': {
        required: true
      },
      'file': {
        extension: "jpg,jpeg,png"
      }
    },
    submitHandler: false,
    messages: {
      categoryName: {
        required: 'Mohon isi nama kategori'
      },
      codeCategory: {
        required: 'Mohon isi kode kategori'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddBanner': {
    ignore: null,
    rules: {
      'titleBanner': {
        required: true
      },
      'file': {
        required: true,
        extension: "jpg,jpeg,png"
      }
    },
    submitHandler: false,
    messages: {
      titleBanner: {
        required: 'Mohon isi title banner'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditBanner': {
    ignore: null,
    rules: {
      'titleBanner': {
        required: true
      },
      'file': {
        extension: "jpg,jpeg,png"
      }
    },
    submitHandler: false,
    messages: {
      titleBanner: {
        required: 'Mohon isi title banner'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddInventory': {
    ignore: [],
    rules: {
      'productId[]': {
        required: true
      },
      'totalInventory[]': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditInventory': {
    ignore: null,
    rules: {
      'totalInventory': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditRole': {
    ignore: null,
    rules: {
      'titleRole': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      titleRole: {
        required: 'Mohon isi title role'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddVoucher': {
    ignore: null,
    rules: {
      'judulVoucher': {
        required: true
      },
      'minimumShopingInput': {
        required: true
      },
      'promoType': {
        required: true
      },
      'promoValueInput': {
        required: true
      },
      'maximalDiskonInput': {
        required: function required(e) {
          return $("#promoType").val() == 'discount';
        }
      },
      'codeVoucher': {
        required: true
      },
      'kuota': {
        required: true
      },
      'startVoucher': {
        required: true
      },
      'endVoucher': {
        required: true
      },
      'typeVoucher': {
        required: true
      },
      'description': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditVoucher': {
    ignore: null,
    rules: {
      'judulVoucher': {
        required: true
      },
      'minimumShopingInput': {
        required: true
      },
      'promoType': {
        required: true
      },
      'promoValueInput': {
        required: true
      },
      'maximalDiskonInput': {
        required: function required(e) {
          return $("#promoType").val() == 'discount';
        }
      },
      'codeVoucher': {
        required: true
      },
      'kuota': {
        required: true
      },
      'startVoucher': {
        required: true
      },
      'endVoucher': {
        required: true
      },
      'typeVoucher': {
        required: true
      },
      'description': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddUser': {
    ignore: null,
    rules: {
      'name': {
        required: true
      },
      'email': {
        required: true,
        STD_VAL_WEB_5: true
      },
      'role': {
        required: true
      },
      'phoneNumber': {
        required: true
      },
      'address': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      name: {
        required: 'Mohon isi nama user'
      },
      email: {
        required: 'Mohon isi email user'
      },
      role: {
        required: 'Mohon pilih role user'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      if (element.is("#role")) {
        error.appendTo(element.parents('#roleDiv'));
      } else {
        error.insertAfter(element);
      }

      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditUser': {
    ignore: null,
    rules: {
      'name': {
        required: true
      },
      'email': {
        required: true,
        STD_VAL_WEB_5: true
      },
      'role': {
        required: true
      },
      'phoneNumber': {
        required: true
      },
      'address': {
        required: true
      }
    },
    submitHandler: false,
    messages: {
      name: {
        required: 'Mohon isi nama user'
      },
      email: {
        required: 'Mohon isi email user'
      },
      role: {
        required: 'Mohon pilih role user'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      if (element.is("#role")) {
        error.appendTo(element.parents('#roleDiv'));
      } else {
        error.insertAfter(element);
      }

      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formPesananDikirim': {
    ignore: null,
    rules: {
      'deliveredType': {
        required: true
      },
      'postman_name': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formPesananSelesai': {
    ignore: null,
    rules: {
      'receiverName': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditUrl': {
    ignore: null,
    rules: {
      'callbackURL': {
        STD_VAL_WEB_20: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formChangeStatusTransaction': {
    ignore: null,
    rules: {
      'statusChange': {
        required: true
      },
      'deliveredType': {
        required: true
      },
      'postmanName': {
        required: true
      },
      'receiverName': {
        required: true
      }
    },
    submitHandler: false,
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formAddProduct': {
    ignore: null,
    rules: {
      'productName': {
        required: true
      },
      'categoryId': {
        required: true
      },
      'description': {
        required: true
      },
      'valuePriceProduct': {
        required: true
      },
      'promoType': {
        required: function required(e) {
          return $("#promoStatus").val() == '1';
        }
      },
      'promoValueInput': {
        required: function required(e) {
          return $("#promoStatus").val() == '1';
        }
      },
      'priceProduct': {
        required: function required(e) {
          return $("#promoStatus").val() == '1';
        }
      },
      'file[]': {
        required: true,
        extension: "jpg,jpeg,png",
        filesizecount: 5
      }
    },
    submitHandler: false,
    messages: {
      categoryName: {
        required: 'Mohon isi nama kategori'
      },
      codeCategory: {
        required: 'Mohon isi kode kategori'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  },
  'formEditProduct': {
    ignore: null,
    rules: {
      'productName': {
        required: true
      },
      'categoryId': {
        required: true
      },
      'description': {
        required: true
      },
      'valuePriceProduct': {
        required: true
      },
      'file[]': {
        required: true,
        extension: "jpg,jpeg,png",
        filesizecount: 5
      }
    },
    submitHandler: false,
    messages: {
      categoryName: {
        required: 'Mohon isi nama kategori'
      },
      file: {
        required: 'Mohon upload icon kategori'
      }
    },
    errorPlacement: function errorPlacement(error, element) {
      error.insertAfter(element);
      $(element).parents('.form-group').addClass('form-error');
    },
    success: function success(error, element) {
      error.remove();
      $(element).parents('.form-group').removeClass('form-error');
    }
  }
};
var validation = {
  messages: {
    required: function required() {
      return '<i class="fa fa-exclamation-circle"></i> Mohon isi kolom ini';
    },
    minlength: function minlength(length) {
      return '<i class="fa fa-exclamation-circle"></i> Isi dengan minimum ' + length;
    },
    maxlength: function maxlength(length) {
      return '<i class="fa fa-exclamation-circle"></i> Isi dengan maximum ' + length;
    },
    max: function max(message, length) {
      return '<i class="fa fa-exclamation-circle"></i> ' + message + length;
    },
    email: function email() {
      return '<i class="fa fa-exclamation-circle"></i> Email Anda salah. Email harus terdiri dari @ dan domain';
    },
    digits: function digits() {
      return '<i class="fa fa-exclamation-circle"></i> Mohon isi hanya dengan nomor';
    },
    numbers2: function numbers2() {
      return '<i class="fa fa-exclamation-circle"></i> Mohon isi hanya dengan nomor';
    },
    nameCheck: function nameCheck() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung A-Z dan \'';
    },
    numericsSlash: function numericsSlash() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung 0-9 dan /';
    },
    alphaNumeric: function alphaNumeric() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung 0-9, A-Z dan spasi';
    },
    alphaNumericNS: function alphaNumericNS() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung 0-9 dan A-Z';
    },
    alpha: function alpha() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung A-Z dan spasi';
    },
    alphaNS: function alphaNS() {
      return '<i class="fa fa-exclamation-circle"></i> Nama hanya boleh mengandung A-Z';
    },
    equalTo: function equalTo() {
      return '<i class="fa fa-exclamation-circle"></i> Mohon mengisi dengan isian yang sama';
    },
    addresscheck: function addresscheck() {
      return '<i class="fa fa-exclamation-circle"></i> Input harus mengandung satu nomor, satu huruf kecil dan satu huruf besar';
    },
    pwcheck: function pwcheck() {
      return '<i class="fa fa-exclamation-circle"></i> Input minimum 8 dan mengandung satu nomor, satu huruf kecil dan satu huruf besar';
    },
    pwcheck_alfanum: function pwcheck_alfanum() {
      return '<i class="fa fa-exclamation-circle"></i> Input antara 8-14 karakter dan harus merupakan kombinasi antara angka dan huruf';
    },
    pwcheck2: function pwcheck2() {
      return '<i class="fa fa-exclamation-circle"></i> Input antara 8-14 karakter dan harus mengandung nomor, huruf kecil, huruf besar dan simbol kecuali ("#<>\/\\=\')';
    },
    notEqual: function notEqual(message) {
      return '<i class="fa fa-exclamation-circle"></i> ' + message;
    },
    checkDate: function checkDate() {
      return '<i class="fa fa-exclamation-circle"></i> Format tanggal salah';
    },
    checkTime: function checkTime() {
      return '<i class="fa fa-exclamation-circle"></i> Format time (HH:mm) salah';
    },
    formatSeparator: function formatSeparator() {
      return '<i class="fa fa-exclamation-circle"></i> Contoh format: Ibu rumah tangga, pedagang, tukang jahit';
    },
    acceptImage: function acceptImage() {
      return '<i class="fa fa-exclamation-circle"></i> Mohon upload hanya gambar';
    },
    filesize: function filesize(size) {
      return '<i class="fa fa-exclamation-circle"></i> Max file size: ' + size;
    },
    extension: function extension(format) {
      return '<i class="fa fa-exclamation-circle"></i> Format yang Anda pilih tidak sesuai';
    },
    minValue: function minValue(_minValue) {
      return '<i class="fa fa-exclamation-circle"></i> Minimal Amount: ' + _minValue;
    },
    ageCheck: function ageCheck(age) {
      return '<i class="fa fa-exclamation-circle"></i> Minimal Age ' + age;
    },
    checkDateyyyymmdd: function checkDateyyyymmdd() {
      return '<i class="fa fa-exclamation-circle></i> Format tanggal YYYY-MM-DD, contoh: 2016-01-30';
    },
    checkDateddmmyyyy: function checkDateddmmyyyy() {
      return '<i class="fa fa-exclamation-circle></i> Format tanggal DD/MM/YYYY, contoh: 17/08/1945';
    }
  },
  addMethods: function addMethods() {
    // alert('method')
    // jQuery.validator.addMethod("maxDateRange",
    jQuery.extend(jQuery.validator.messages, {
      required: "Mohon isi kolom ini.",
      remote: "Please fix this field.",
      email: "Email Anda salah. Email harus terdiri dari @ dan domain.",
      url: "Please enter a valid URL.",
      date: "Please enter a valid date.",
      dateISO: "Please enter a valid date (ISO).",
      number: "Please enter a valid number.",
      digits: "Mohon isi hanya dengan angka.",
      creditcard: "Please enter a valid credit card number.",
      equalTo: "Mohon isi dengan value yang sama.",
      accept: "Format yang Anda pilih tidak sesuai.",
      maxlength: jQuery.validator.format("Mohon isi dengan tidak melebihi {0} karakter."),
      minlength: jQuery.validator.format("Mohon isi dengan minimal {0} karakter."),
      rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
      range: jQuery.validator.format("Please enter a value between {0} and {1}."),
      max: jQuery.validator.format("Mohon isi tidak melebihi {0}."),
      min: jQuery.validator.format("Mohon isi minimal {0}."),
      extension: "Format yang Anda pilih tidak sesuai.",
      alphaNumeric: "Hanya boleh mengandung 0-9, A-Z dan spasi" // addresscheck:"Input harus mengandung satu nomor, satu huruf kecil dan satu huruf besar"

    });
    $.validator.addMethod("maxDateRange", function (value, element, params) {
      var end = new Date(value);
      var start = new Date($(params[0]).val());
      var range = (end - start) / 86400000;

      if (!/Invalid|NaN/.test(new Date(value))) {
        return range <= params[1];
      }

      return isNaN(value) && isNaN($(params[0]).val()) || range <= params[1];
    }, 'Melebihi maksimal range {1} hari.');
    jQuery.validator.addMethod("greaterThan", function (value, element, params) {
      if (!/Invalid|NaN/.test(new Date(value))) {
        return new Date(value) > new Date($(params).val());
      }

      return isNaN(value) && isNaN($(params).val()) || Number(value) > Number($(params).val());
    }, 'Must be greater than {0}.');
    $.validator.addMethod("ageCheck", function (value, element, param) {
      var now = moment(); //return now;

      function parseNewDate(date) {
        var split = date.split('-');
        var b = moment([split[2], split[1] - 1, split[0]]);
        return b;
      }

      var difference = now.diff(parseNewDate(value), 'years');
      return difference >= param;
    }, "Check Umur");
    jQuery.validator.addMethod("numbers2", function (value, element) {
      return this.optional(element) || /^-?(?!0)(?:\d+|\d{1,3}(?:\.\d{3})+)$/.test(value);
    }, "Mohon isi hanya dengan nomor");
    jQuery.validator.addMethod("nameCheck", function (value, element) {
      return this.optional(element) || /^([a-zA-Z' ]+)$/.test(value);
    }, "Nama hanya boleh mengandung A-Z dan '");
    jQuery.validator.addMethod("numericsSlash", function (value, element) {
      return this.optional(element) || /^([0-9/]+)$/.test(value);
    }, "Nama hanya boleh mengandung 0-9 dan /");
    jQuery.validator.addMethod('filesizecount', function (value, element, param) {
      return this.optional(element) || element.files.length <= param;
    }, 'File tidak boleh melebihi {0}');
    jQuery.validator.addMethod("numericDot", function (value, element) {
      return this.optional(element) || /^([0-9.]+)$/.test(value);
    }, "Nama hanya boleh mengandung 0-9 dan .");
    jQuery.validator.addMethod("numericKoma", function (value, element) {
      return this.optional(element) || /^([0-9,]+)$/.test(value);
    }, "Nama hanya boleh mengandung 0-9 dan ,");
    jQuery.validator.addMethod("alphaNumeric", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9. ]*$/.test(value);
    }, "Hanya boleh mengandung 0-9, A-Z, Titik dan spasi");
    jQuery.validator.addMethod("alphaNumericNS", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
    }, "Nama hanya boleh mengandung 0-9 dan A-Z");
    jQuery.validator.addMethod("alamatFormat", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9 .,-/]*$/.test(value);
    }, "Nama hanya boleh mengandung A-Z, 0-9, titik, koma, dan strip");
    jQuery.validator.addMethod("defaultText", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9 ',-.:/?!&%()+=_\n]*$/.test(value);
    }, "Inputan hanya boleh mengandung A-Z, 0-9, spasi dan simbol .,:'/?!&%()-+=_");
    jQuery.validator.addMethod("defaultName", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9 .']*$/.test(value);
    }, "Inputan hanya boleh mengandung A-Z, 0-9, spasi dan simbol .'");
    jQuery.validator.addMethod("arabic", function (value, element) {
      return this.optional(element) || /^[\u0600-\u06FF\u0750-\u077F ]*$/.test(value);
    }, "Inputan hanya boleh bahasa Arab.");
    jQuery.validator.addMethod("defaultPhone", function (value, element) {
      return this.optional(element) || /^[0-9-/']*$/.test(value);
    }, "Inputan hanya boleh mengandung 0-9, spasi, dan simbol-/'");
    jQuery.validator.addMethod("alpha", function (value, element) {
      return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    }, "Nama hanya boleh mengandung A-Z dan spasi");
    jQuery.validator.addMethod("alphaNS", function (value, element) {
      return this.optional(element) || /^[a-zA-Z]*$/.test(value);
    }, "Nama hanya boleh mengandung A-Z");
    jQuery.validator.addMethod("passcheck", function (value, element) {
      // 3x salah blokir
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,12}$/.test(value);
    }, "Password kombinasi huruf kapital, huruf kecil, angka, dan karakter non-alphabetic");
    jQuery.validator.addMethod("addresscheck", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\s).{8,}$/.test(value);
    }, "Input harus mengandung satu nomor, satu huruf kecil dan satu huruf besar");
    jQuery.validator.addMethod("pwcheck", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$/.test(value);
    }, "Input harus mengandung satu nomor, satu huruf kecil dan satu huruf besar");
    jQuery.validator.addMethod("pwcheck_alfanum", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*\D)(?!.*\s).{8,14}$/.test(value);
    }, "Input harus merupakan kombinasi antara angka dan huruf");
    jQuery.validator.addMethod("pwcheck2", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w])(?!.*[#<>\/\\=”’"'])(?!.*\s).{8,14}$/.test(value);
    }, "Input harus mengandung satu nomor, satu huruf kecil, satu huruf besar, simbol kecuali \"#<>\/\\=\"'");
    jQuery.validator.addMethod("pwcheck3", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w])(?!.*\s).{8,12}$/.test(value);
    }, "Input harus mengandung satu nomor, satu huruf kecil, satu huruf besar, simbol");
    jQuery.validator.addMethod("max", function (value, element, param) {
      var val = parseFloat(value.replace(/\./g, ""));
      return this.optional(element) || val <= param;
    }, jQuery.validator.format("Maksimal {0}"));
    jQuery.validator.addMethod("maxDec", function (value, element, param) {
      var data = value.replace(',', '.');
      return this.optional(element) || data <= param;
    }, jQuery.validator.format("Maksimal {0}"));
    jQuery.validator.addMethod("maxDecMargin", function (value, element, param) {
      var data = value.replace(',', '.');
      return this.optional(element) || data <= param;
    }, jQuery.validator.format("Margin tidak valid"));
    jQuery.validator.addMethod("notEqual", function (value, element, param) {
      return this.optional(element) || value != $(param).val();
    }, "This has to be different...");
    jQuery.validator.addMethod("notZero", function (value, element, param) {
      var val = parseFloat(value.replace(/\./g, ""));
      var nol = value.substr(0, 1);
      return this.optional(element) || val != param;
    }, jQuery.validator.format("Value Tidak Boleh 0"));
    jQuery.validator.addMethod("zeroValid", function (value, element, param) {
      var nol = value.substr(0, 1);
      var val = parseFloat(value.replace(/\./g, ""));

      if (value.length == 1) {
        return this.optional(element) || val == nol;
      } else {
        return this.optional(element) || nol != param;
      }
    }, jQuery.validator.format("Angka pertama tidak boleh 0"));
    jQuery.validator.addMethod("minValue", function (value, element, param) {
      return value >= param;
    }, "Min Value needed");
    jQuery.validator.addMethod("checkDate", function (value, element) {
      return this.optional(element) || /^(((0[1-9]|[12]\d|3[01])\-(0[13578]|1[02])\-((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\-(0[13456789]|1[012])\-((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\-02\-((19|[2-9]\d)\d{2}))|(29\-02\-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/.test(value);
    }, "Format tanggal salah");
    jQuery.validator.addMethod("checkTime", function (value, element) {
      return this.optional(element) || /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(value);
    }, "Format time (HH:mm) salah");
    jQuery.validator.addMethod("formatSeparator", function (value, element) {
      return this.optional(element) || /^[A-Za-z ]+(,[A-Za-z ]+){0,2}$/.test(value);
    }, "Contoh format: Ibu rumah tangga,pedagang,tukang jahit");
    jQuery.validator.addMethod("checkDateyyyymmdd", function (value, element) {
      return this.optional(element) || /^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$/.test(value);
    }, "Format tanggal YYYY-MM-DD, contoh: 2016-01-30");
    jQuery.validator.addMethod("checkDateddmmyyyy", function (value, element) {
      return this.optional(element) || /^\d{2}\/\d{2}\/\d{4}$/.test(value);
    }, "Format tanggal Bulan/Tanggal/Tahun, contoh: 06/08/1945");
    jQuery.validator.addMethod("emailType", function (value, element) {
      value = value.toLowerCase();
      return this.optional(element) || /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
    }, "Email Anda salah. Email harus terdiri dari @ dan domain");
    jQuery.validator.addMethod("symbol", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9!@#$%^&()]*$/.test(value);
    }, "Password hanya boleh mengandung A-Z, a-z, 0-9 dan simbol dari 0-9");
    jQuery.validator.addMethod('filesize', function (value, element, param) {
      return this.optional(element) || element.files[0].size <= param;
    }, "Ukuran Maksimal Gambar 1 MB");
    jQuery.validator.addMethod("STD_VAL_WEB_1", function (value, element) {
      return this.optional(element) || /^(?=.*\d)([a-zA-Z0-9]+)(?!.*[ #<>\/\\=”’"'!@#$%^&()]).{6,10}$/.test(value);
    }, "Username yang Anda masukkan harus terdiri dari 6-10 karakter alfanumerik tanpa spasi");
    jQuery.validator.addMethod("STD_VAL_WEB_2", function (value, element) {
      // 3x salah blokir
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\w])(?!.*\s).{8,12}$/.test(value);
    }, "Password kombinasi huruf kapital, huruf kecil, angka, dan karakter non-alphabetic");
    jQuery.validator.addMethod("STD_VAL_WEB_3", function (value, element) {
      return this.optional(element) || /^[a-zA-Z.' ]*$/.test(value);
    }, "Nama harus terdiri dari alfabet, titik (.) dan single quote (')"); // STD_VAL_WEB_4 Jenis Kelamin (kemungkinan select option)

    jQuery.validator.addMethod("STD_VAL_WEB_5", function (value, element) {
      value = value.toLowerCase();
      return this.optional(element) || /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value);
    }, "Email Anda salah. Email harus terdiri dari @ dan domain");
    jQuery.validator.addMethod("STD_VAL_WEB_6", function (value, element) {
      return this.optional(element) || /^\d{16}$/.test(value);
    }, "Nomor KTP yang Anda masukkan salah. Harus terdiri dari 16 karakter");
    jQuery.validator.addMethod("STD_VAL_WEB_7", function (value, element) {
      return this.optional(element) || /^\d{15}$/.test(value);
    }, "NPWP yang Anda masukkan salah. Harus terdiri dari 15 karakter tanpa spasi dan symbol");
    jQuery.validator.addMethod("STD_VAL_WEB_8", function (value, element) {
      return this.optional(element) || /^\d{11,13}$/.test(value);
    }, "Nomor HP yang Anda masukkan salah");
    jQuery.validator.addMethod("STD_VAL_WEB_9", function (value, element) {
      // 3x salah blokir
      return this.optional(element) || /^\d{6}$/.test(value);
    }, "Pin yang anda masukkan salah. Jika salah hingga 3x akan otomatis terblokir");
    jQuery.validator.addMethod("STD_VAL_WEB_10", function (value, element) {
      return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\s).{6,255}$/.test(value);
    }, "Alamat yang anda masukkan salah");
    jQuery.validator.addMethod("STD_VAL_WEB_11", function (value, element) {
      return this.optional(element) || /^(((0[1-9]|[12]\d|3[01])\-(0[13578]|1[02])\-((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\-(0[13456789]|1[012])\-((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\-02\-((19|[2-9]\d)\d{2}))|(29\-02\-((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/.test(value);
    }, "Masukkan format tanggal yang sesuai");
    jQuery.validator.addMethod("STD_VAL_WEB_12", function (value, element) {
      return this.optional(element) || /^([1-9]|([012][0-9])|(3[01]))-([0]{0,1}[1-9]|1[012])-\d\d\d\d [012]{0,1}[0-9]:[0-6][0-9]:[0-6][0-9]$/.test(value);
    }, "Masukkan format tanggal yang sesuai");
    jQuery.validator.addMethod("STD_VAL_WEB_13", function (value, element) {
      // 3x salah blokir, expired 3 menit, 1 menit untuk retry
      return this.optional(element) || /^\d{6}$/.test(value);
    }, "OTP yang Anda masukkan salah");
    jQuery.validator.addMethod("STD_VAL_WEB_14", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9]{8,12}$/.test(value);
    }, "MPIN yang Anda masukkan salah");
    jQuery.validator.addMethod("STD_VAL_WEB_15", function (value, element) {
      // setelah 4 input angka otomatis spasi (tambahkan pada masking)
      return this.optional(element) || /^[0-9 ]{19}$/.test(value);
    }, "Nomor kartu yang Anda masukkan tidak valid/salah");
    jQuery.validator.addMethod("STD_VAL_WEB_16", function (value, element) {
      // Saat input otomatis masking
      return this.optional(element) || /^\d{3}$/.test(value);
    }, "CVV yang Anda masukkan tidak valid/salah");
    jQuery.validator.addMethod("STD_VAL_WEB_17", function (value, element) {
      // Maxlength sesuai kebutuhan
      return this.optional(element) || /^[0-9]*$/.test(value);
    }, "Virtual Account Number yang anda masukkan tidak valid");
    jQuery.validator.addMethod('STD_VAL_WEB_18', function (value, element, param) {
      param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpeg|png";
      return this.optional(element) || element.files[0].size <= 1000000 && value.match(new RegExp("\\.(" + param + ")$", "i"));
    }, "Upload gambar maksimal 1MB");
    jQuery.validator.addMethod('STD_VAL_WEB_19', function (value, element, param) {
      param = typeof param === "string" ? param.replace(/,/g, "|") : "doc|docx|xls|xlsx|csv";
      return this.optional(element) || element.files[0].size <= 5000000 && value.match(new RegExp("\\.(" + param + ")$", "i"));
    }, "Upload file maksimal 5MB");
    jQuery.validator.addMethod("STD_VAL_WEB_20", function (value, element) {
      return this.optional(element) || /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/.test(value);
    }, "URL yang Anda masukkan tidak valid"); // STD_VAL_WEB_21 Accepted (kemungkinan checkbox)
    // STD_VAL_WEB_22 Active_url

    jQuery.validator.addMethod("STD_VAL_WEB_23", function (value, element) {
      return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    }, "Input harus a-z A-Z");
    jQuery.validator.addMethod("STD_VAL_WEB_24", function (value, element) {
      return this.optional(element) || /^([0-9]+)$/.test(value);
    }, "Input harus 0-9");
    jQuery.validator.addMethod("STD_VAL_WEB_25", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9 ]*$/.test(value);
    }, "Input harus 0-9, a-z, A-Z");
    jQuery.validator.addMethod("STD_VAL_WEB_26", function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9-_ ]*$/.test(value);
    }, "Input harus 0-9, a-z, A-Z, -, _"); // STD_VAL_WEB_27 array
    // STD_VAL_WEB_28 boolean (radio button)

    jQuery.validator.addMethod("STD_VAL_WEB_29", function (value, element, param) {
      return this.optional(element) || value == $(param).val();
    }, "Input tidak cocok");
    jQuery.validator.addMethod("STD_VAL_WEB_30", function (value, element) {
      return this.optional(element) || /^\d+$/.test(value);
    }, "Input harus digit");
    jQuery.validator.addMethod("STD_VAL_WEB_31", function (value, element, param) {
      return this.optional(element) || value >= param[0] && value <= param[1];
    }, "Input harus berdasarkan range"); // STD_VAL_WEB_32
    // STD_VAL_WEB_33

    jQuery.validator.addMethod("STD_VAL_WEB_34", function (value, element) {
      return this.optional(element) || /^-?\d+$/.test(value);
    }, "Input harus bilangan bulat");
    jQuery.validator.addMethod("STD_VAL_WEB_35", function (value, element) {
      return this.optional(element) || /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(value);
    }, "Input harus valid IP");
    jQuery.validator.addMethod("STD_VAL_WEB_36", function (value, element) {
      return this.optional(element) || /^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(value);
    }, "Input harus ipv4");
    jQuery.validator.addMethod("STD_VAL_WEB_37", function (value, element) {
      return this.optional(element) || /^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(value);
    }, "Input harus ipv6");
    jQuery.validator.addMethod("STD_VAL_WEB_38", function (value, element, param) {
      return this.optional(element) || /^[\],:{}\s]*$/.test(text.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, '')); // function isValidJSON(param) {
      //     try {
      //         JSON.parse(param);
      //     } catch (e) {
      //         return false;
      //     }
      //     return true;
      // }
    }, "Input harus string json");
  },
  validateMe: function validateMe(id, valRules, valMessages) {
    validation.addMethods();
    $("#" + id).validate({
      rules: valRules,
      messages: valMessages,
      errorPlacement: function errorPlacement(error, element) {
        alert('test');
        var ele = element.parents('.input');
        element.parents('.inputGroup').children('.alert.error').remove();
        error.insertAfter(ele);
        error.addClass('alert error');
      },
      success: function success(error) {
        error.parents('span.alert.error').remove();
      },
      wrapper: 'span'
    });
  },

  /* CR17682 OTP START */
  validateMultiple: function validateMultiple(id, valRules, valMessages) {
    validation.addMethods();
    $("#" + id).removeData("validator");
    $("#" + id).removeData("check");
    $("#" + id).removeData("confirm");
    $("#" + id).find('input').removeClass('error');
    var validator = $("#" + id).validate({
      rules: valRules,
      messages: valMessages,
      errorPlacement: function errorPlacement(error, element) {
        var ele = element.parents('.input');
        element.parents('.inputGroup').children('.alert.error').remove();
        error.insertAfter(ele);
        error.addClass('alert error');
      },
      success: function success(error) {
        error.parents('span.alert.error').remove();
      },
      wrapper: 'span'
    });
    validator.resetForm();
  },

  /* CR17682 OTP END*/
  submitTry: function submitTry(id) {
    if ($('.nio_select').length) {
      $('.nio_select').show();
    }

    if ($('.added_photo').length && !$('.imageAttachmentWrap.noApi').length) {
      $('.added_photo').show();
    }

    if ($('.tinymce').length) {
      $('.tinymce').show();
    }

    if ($('.stepForm').length) {
      var curr = $('.stepForm.active').index() + 1;
      $('.stepForm').addClass('active');
    } //after valid (have to make fn if not working)


    if ($('#' + id).valid()) {
      $('.nio_select').hide();
      $('.tinymce').hide();

      if (validation.FileApiSupported()) {
        $('.added_photo').hide();
      }

      return 'vPassed';
    } else {
      $('.nio_select').hide();
      $('.tinymce').hide();

      if (validation.FileApiSupported()) {
        $('.added_photo').hide();
      }

      return 'vError';
    }
  },
  FileApiSupported: function FileApiSupported() {
    return !!(window.File && window.FileReader && window.FileList && window.Blob);
  }
};
var grafik = {
  init: function init() {// js grafik
    // if ($("#chartMerchantTransaction").length) {
    //     var options = {
    //         series: [
    //             {
    //                 name: "High - 2013",
    //                 data: [28, 29, 33, 36, 32, 32, 33]
    //             },
    //             {
    //                 name: "Low - 2013",
    //                 data: [12, 11, 14, 18, 17, 13, 13]
    //             }
    //         ],
    //             chart: {
    //             height: 350,
    //             type: 'line',
    //             dropShadow: {
    //                 enabled: true,
    //                 color: '#000',
    //                 top: 18,
    //                 left: 7,
    //                 blur: 10,
    //                 opacity: 0.2
    //             },
    //             toolbar: {
    //                 show: false
    //             }
    //         },
    //         colors: ['#00AC57', '#F36E23'],
    //         dataLabels: {
    //             enabled: true,
    //         },
    //         stroke: {
    //             curve: 'smooth'
    //         },
    //         // title: {
    //         //     text: 'Merchant Transaction Timeline',
    //         //     align: 'left'
    //         // },
    //         grid: {
    //             borderColor: '#e7e7e7',
    //             row: {
    //             colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
    //             opacity: 0.5
    //             },
    //         },
    //         markers: {
    //             size: 1
    //         },
    //         xaxis: {
    //             categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    //             // title: {
    //             //     text: 'Day'
    //             // }
    //         },
    //         legend: {
    //             position: 'top',
    //             horizontalAlign: 'right',
    //             floating: true,
    //             offsetY: -25,
    //             offsetX: -5
    //         }
    //       };
    //       var chart = new ApexCharts(document.querySelector("#chartMerchantTransaction"), options);
    //       chart.render();
    // }
  }
};

if ($("#form-add-respone").length) {
  $('#htmleditor').summernote({
    height: 100,
    direction: 'ltr',
    codemirror: {
      // codemirror options
      theme: 'monokai'
    }
  });
  $("div.note-editing-area div.note-editable").keypress(function (evt) {
    var kc = evt.keyCode;
    var htmleditor = $('#htmleditor').summernote('code');

    if (kc === 32 && (htmleditor.length == 0 || htmleditor == '<p><br></p>')) {
      $('#htmleditor').val('');
      evt.preventDefault();
    }
  });
}

$('.confirm-status').click(function (e) {
  e.preventDefault();
  var newstatus = $(this).data('status');
  var currentStatus = $(this).data('current-status');
  var title = 'Status dirubah menjadi "' + newstatus + '" ?';
  var text = "Kamu akan merubah status dari '" + currentStatus + "' menjadi " + newstatus + ". Apakah kamu yakin ?";
  var url = $(this).attr("href");
  ui.popup.show("success", title, text, 'Lanjutkan', url, true);
});
$('.confirm-download').click(function (e) {
  e.preventDefault();
  var document = $(this).data('document');
  var title = 'Download Data "' + document + '" ?';
  var text = "Kamu akan mendownload data dari <br>" + document + ", apakah kamu yakin ?";
  var url = $(this).attr("href");
  ui.popup.show("success", title, text, 'Lanjutkan', url, true);
});
