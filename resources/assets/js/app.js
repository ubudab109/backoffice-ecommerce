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
    ajax.init();
    tables.init();
    form.init();
    grafik.init();
    ui.slide.init();
    validation.addMethods();

    // if ($('#main-wrapper').length) {
    //     other.checkSession.init();
    // }
    // $("#modalResponBulk").modal('show')
    if ($("#bodyCandidate").length) {
        if ($('.btn-home-color').length) {
            ajax.getData('/get-color', 'post', null, function (result) {
                $(".btn-home-color").addClass(result.value);
            })
        }
    }

    $(document).ajaxError(function (event, jqxhr, settings, exception) {
        console.log('exception = ' + exception)
    });

    moveOnMax = function (field, nextFieldID) {
        if (field.value.length == 1) {
            document.getElementById(nextFieldID).focus();
        }
    }

    if ($('#notif').length) {
        const status = $('#notif').data('status')
        const message = $('#notif').data('message')
        const title = $('#notif').data('title')
        const button = $('#notif').data('button')
        console.log(status, message, title, button)
        ui.popup.show(status, title, message, button)

    }
    if ($('#notifModal').length) {
        const status = $('#notifModal').data('status')
        const message = $('#notifModal').data('message')
        const url = $('#notifModal').data('url')

        if (status == 'success') {
            $('#titleSuccessNotif').html(message)
            $('#modalNotifForSuccess').modal({
                backdrop: 'static',
                show: true
            })
        } else {
            $('#titleErrorNotif').html(message)
            $('#modalNotifForError').modal({
                backdrop: 'static',
                show: true
            })
        }
    }
    if ($('#mustLogin').length) {
        $('.modal').modal('hide');
        ui.popup.hideLoader();

        $('#modalNotifForLogin').modal({
            backdrop: 'static',
            show: true
        })
    }
    if ($('#profileSaved').length) {
        $('.modal').modal('hide');
        ui.popup.hideLoader();

        $('#modalNotifProfileSaved').modal({
            backdrop: 'static',
            show: true
        })
    }

    if ($('#addTest').length) {
        ui.popup.hideLoader();
        $('.modal').modal('hide');
        const url = $('#addTest').data('url')
        $("#urlTest").attr('href', url);
        $('#modalSuccessAddTest').modal({
            backdrop: 'static',
            show: true
        })
    }

    $('#btnDrawer').click(function () {
        document.getElementById("navbarSupportedContent").style.width = "335px";
    });
    $('#btnDrawerClose').click(function () {
        document.getElementById("navbarSupportedContent").style.width = "0";
    });

    $('#btn-mobile').click(function () {
        console.log('click')
        document.getElementById("sidebarUtama").style.width = "290px";
    });
    $('#btn-mobile-close').click(function () {
        console.log('click')
        document.getElementById("sidebarUtama").style.width = "0";
    });
})

// $('.modal').on('hidden.bs.modal', function (e) {
//     $(this).find('form')[0].reset();
//     $('.select').val('').trigger('change');

// })

//FAQ
if ($("#divFaq").length) {
    function openContent(event, id) {
        let content = document.getElementsByClassName("content");
        for (let i = 0; i < content.length; i++) {
            content[i].style.display = "none";

        }

        let tabLinks = document.getElementsByClassName("tab-links");
        for (let i = 0; i < tabLinks.length; i++) {
            tabLinks[i].className = tabLinks[i].className.replace(" active", "");

        }

        document.getElementById(id).style.display = "block";
        event.currentTarget.className += " active";

    }

    document.getElementById("defaultOpen").click();

}
