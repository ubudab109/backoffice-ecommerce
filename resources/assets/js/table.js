formatRupiah = (money) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(money);
}
var tables = {
    init: function () {

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
                }, ],
                columns: [{
                        data: 'icon',
                        name: 'icon',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'creator',
                        name: 'creator'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'code_category',
                        name: 'code_category'
                    },
                    {
                        data: 'status_category',
                        name: 'status_category'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                ]
            });
            $(".cancel-status").click(() => {
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
                }, ],
                columns: [{
                        data: 'title_name',
                        name: 'title_name'
                    },
                    {
                        data: 'creator',
                        name: 'creator'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
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
                }, ],
                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'creator',
                        name: 'creator'
                    },
                    {
                        data: 'status_banner',
                        name: 'status_banner'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                ]
            });
            $(".cancel-status").click(() => {
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
                }, ],
                columns: [{
                        data: 'title_voucher',
                        name: 'title_voucher'
                    },
                    {
                        data: 'code_voucher',
                        name: 'code_voucher'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'rest_voucher',
                        name: 'rest_voucher'
                    },
                    {
                        data: 'used_voucher',
                        name: 'used_voucher'
                    },
                    {
                        data: 'status_voucher',
                        name: 'status_voucher'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                ]
            });
            $(".cancel-status").click(() => {
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
                }, ],
                columns: [{
                        data: 'transaction_id',
                        name: 'transaction_id'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'whatsapp',
                        name: 'whatsapp'
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $(".btn-notif-cancel").click(() => {
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
                }, ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'whatsapp_number',
                        name: 'whatsapp_number'
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            datatable.buttons().container().appendTo('#tableTransaction_filter');
        }

        if ($('#tableProductInventory').length) {
            let params = (new URL(document.location)).searchParams;
            let productId = params.get("product");
            var datatable = $('#tableProductInventory').DataTable({
                processing: true,
                // serverSide: true,
                ajax: '/product/inventory-detail-product?product=' + productId,
                columns: [{
                        data: 'agen',
                        name: 'agen'
                    },
                    {
                        data: 'total_inventory',
                        name: 'total_inventory'
                    },
                ]
            });
            datatable.buttons().container().appendTo('#tableTransaction_filter');
        }

        if ($('#tableCustomerTransaksi').length) {
            let params = (new URL(document.location)).searchParams;
            let customerId = params.get("customer");
            var datatable = $('#tableCustomerTransaksi').DataTable({
                processing: true,
                // serverSide: true,
                ajax: '/customer/detail-customer/transaction?customer=' + customerId,
                columns: [{
                        data: 'transaction_id',
                        name: 'transaction_id'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name'
                    },
                    {
                        data: 'whatsapp',
                        name: 'whatsapp'
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                ]
            });
            datatable.buttons().container().appendTo('#tableTransaction_filter');
        }

        if ($('#tableProduct').length) {
            var datatable = $('#tableProduct').DataTable({
                processing: true,
                ajax: 'product/list-product',
                dom: 'lBfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btn btn-save btn-status text-white',
                    text: 'Export',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 10, 5, 11, 7, 8],
                    }
                }, ],
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'price_product',
                        name: 'price_product'
                    },
                    {
                        data: 'status_promo',
                        name: 'status_promo'
                    },
                    {
                        data: 'promo_total_price',
                        name: 'promo_total_price'
                    },
                    {
                        data: 'product_status',
                        name: 'product_status'
                    },
                    {
                        data: 'creator',
                        name: 'creator'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                    {
                        data: 'string_promo_status',
                        name: 'string_promo_status',
                        visible: false
                    },
                ]
            });
            $(".cancel_status").click(() => {
                datatable.ajax.reload(null, false);
            });
            $(".cancel_status_promo").click(() => {
                datatable.ajax.reload(null, false);
            });
            datatable.buttons().container().appendTo('#tableTransaction_filter');

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
                return 'Rp. ' + rupiah
            }

            function formatPercent(angka) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    percent = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? "." : "";
                    percent += separator + ribuan.join(".");
                }

                percent = split[1] != undefined ? percent + split[1] : percent;
                // return prefix == undefined ? percent : percent ? "Rp. " + percent : "";
                return percent + '%'
            }
            $("#promoStatus").change(() => {
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
                    var calculated = parseInt(priceProduct) - parseInt(splittedPrice.join(''));
                    console.log(calculated);
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
                    if (calculatePromo < 0) {
                        document.getElementById('priceProduct').value = formatRupiahRp('0');
                    } else {
                        document.getElementById('priceProduct').value = formatRupiahRp(calculatePromo.toString());
                    }
                }

            })
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
                    },

                }, ],
                columns: [
                    {
                        data: 'code_product',
                        name: 'code_product'
                    },
                    {
                        data: 'product_name',
                        name: 'product_name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'product_price',
                        name: 'product_price'
                    },
                    {
                        data: 'status_promo',
                        name: 'status_promo'
                    },
                    {
                        data: 'promo_total_price',
                        name: 'promo_total_price'
                    },
                    {
                        data: 'product_status',
                        name: 'product_status'
                    },
                    {
                        data: 'total_inventory',
                        name: 'total_inventory'
                    },
                    {
                        data: 'total_sold',
                        name: 'total_sold'
                    },
                    {
                        data: 'current_qty',
                        name: 'current_qty'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                    {
                        data: 'string_promo_status',
                        name: 'string_promo_status',
                        visible: false
                    },
                ]
            });
            $(".btn-notif-cancel").click(() => {
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
                }, ],
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'fullname',
                        name: 'fullname'
                    },
                    {
                        data: 'email_user',
                        name: 'email_user'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'status_user',
                        name: 'status_user'
                    },
                    {
                        data: 'creator',
                        name: 'creator'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'string_status',
                        name: 'string_status',
                        visible: false
                    },
                ]
            });

            $(".cancel-status").click(() => {
                datatable.ajax.reload(null, false);
            });
            datatable.buttons().container().appendTo('#tableTransaction_filter');
        }

    },
    filter: function (id, value) {
        if (id == 'filterDashboard') {
            ajax.getData('dashboard-top', 'post', value, function (data) {
                if (data.code == '01') {
                    ui.popup.show('error', 'Dashboard', data.message, 'Oke, Mengerti', 'close');
                } else {
                    $("#chartMerchantTransaction").empty()
                    $("#valueTransactionSuccess").html(data.transactionSuccess + '<span class="span-persen" id="spanPersen"></span>')
                    $("#spanPersen").html(data.persenTransactionSuccess + ' %')
                    $("#valueTransactionVolume").html(data.allTransaction)
                    var options = {
                        series: [{
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
                            '<p class="title-step">' + data.average[i].endpoint + '</p>' +
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
                    var column = [{
                            'data': 'operator_name'
                        },
                        {
                            'data': 'volume'
                        },
                    ];

                    var columnDefs = [];

                    tables.setAndPopulate('tableTotalTransaction', column, data.operator, columnDefs);

                    var columnMerchant = [{
                            'data': 'cust_name'
                        },
                        {
                            'data': 'msisdn'
                        },
                        {
                            'data': 'endpoint'
                        }
                    ];

                    var columnDefsMerchant = [{
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
                    }, ];

                    tables.setAndPopulate('tableMerchantTransaction', columnMerchant, data.merchant, columnDefsMerchant);
                }
            });
        }

        if (id == 'filterTransaction') {
            $("#downloadLog").attr('href', '/transaction/export/' + value)
            var column = [{
                    'data': 'unique_id'
                },
                {
                    'data': 'msisdn'
                },
                {
                    'data': 'request'
                },
                {
                    'data': 'operator_name'
                },
                {
                    'data': 'updated_at'
                },
                {
                    'data': 'status'
                },
                {
                    'data': 'endpoint'
                },
                {
                    'data': 'response'
                },
                {
                    'data': 'cust_name'
                },
                {
                    'data': 'project_name'
                },
            ];

            columnDefs = [{
                    "targets": 6,
                    "data": "endpoint",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {

                        var value = ''

                        if (full.endpoint != null) {
                            var data1 = full.endpoint.substring(0, 30);
                            var data2 = full.endpoint.substring(31);

                            value = '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>'
                        } else {
                            value = full.endpoint
                        }
                        // console.log()

                        return value;
                    }
                },
                {
                    "targets": 2,
                    "data": "request",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var data1 = full.request.substring(0, 100);
                        var data2 = full.request.substring(101, 200);
                        var data3 = full.request.substring(201);
                        // console.log()
                        return '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>' + '<span class="spanReq3">' + data3 + '</span>'
                    }
                },
                {
                    "targets": 7,
                    "data": "response",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var data1 = full.response.substring(0, 100);
                        var data2 = full.response.substring(101, 200);
                        var data3 = full.response.substring(201);
                        // console.log()
                        return '<span class="spanReq1">' + data1 + '</span>' + '<span class="spanReq2">' + data2 + '</span>' + '<span class="spanReq3">' + data3 + '</span>'
                    }
                },
                {
                    "targets": 5,
                    "data": "status",
                    "render": function (data, type, full, meta) {
                        var data = '';
                        if (full.status == "200" || full.status == "302") {
                            data = "<div class='span-status status-berhasil'>" + full.status + "</div>";
                        } else {
                            data = "<div class='span-status status-suspend'>" + full.status + "</div>";
                        }
                        return '<center>' + data + '</center>';
                    }
                },
                {
                    "targets": 10,
                    "data": "id",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var data = '<center><a class="detail-table" href="/transaction/detail-transaction/' + full.id + '">Detail</a></center>';
                        return data;
                    }
                }
            ];

            tables.serverSide('table-transaction-error', column, 'transaction/list-transaction-error', value, columnDefs, "simple_numbers", "Search by MSISDN")
        }

        if (id == 'filterRecon') {

        }

        if (id == 'filterIntegration') {
            $("#downloadIntegration").attr('href', '/integration/export/' + value)
            var column = [{
                    'data': 'transaction_id'
                },
                {
                    'data': 'request'
                },
                {
                    'data': 'response'
                },
                {
                    'data': 'endpoint'
                },
                {
                    'data': 'created_at'
                },
                {
                    'data': 'updated_at'
                },
                {
                    'data': 'status'
                },
            ];

            columnDefs = [

                {
                    "targets": 1,
                    "data": "request",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var regex = /(<([^>]+)>)/ig

                        // console.log()
                        return data.replace(regex, "").substring(1, 100);
                    }
                },
                {
                    "targets": 2,
                    "data": "response",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var regex = /(<([^>]+)>)/ig
                        // console.log()
                        return data.replace(regex, "").substring(1, 100);
                    }
                },
                {
                    "targets": 6,
                    "data": "status",
                    "render": function (data, type, full, meta) {
                        var data = '';
                        if (full.status == "200") {
                            data = "<div class='span-status status-berhasil'>Success</div>";
                        } else {
                            data = "<div class='span-status status-suspend'>Failed</div>";
                        }
                        return '<center>' + data + '</center>';
                    }
                },
                {
                    "targets": 7,
                    "data": "id",
                    "className": "action-poster-news",
                    "render": function (data, type, full, meta) {
                        var data = '<center><a class="detail-table" href="/integration/detail-integration/' + full.id + '">Detail</a></center>';
                        return data;
                    }
                }
            ];

            tables.serverSide('table-integration', column, 'integration/list-integration', value, columnDefs, "simple_numbers", "Search by Transaction ID")
        }
    },
    getData: function (url, params, callback) {
        $.ajax({
            url: url,
            type: 'post',
            data: params,
            success: function (result) {
                if (!result.error) {
                    callback(null, result.data);
                } else {
                    callback(data);
                }
            }
        })
    },
    clear: function (id) {
        var tbody = $('#' + id).find('tbody');
        tbody.html('');
    },
    serverSide: function (id, columns, url, custParam = null, columnDefs = null, pagingType = "simple_numbers", placeholder = "") {
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
            ajax: function (data, callback, settings) {
                data.param = custParam
                ajax.getData(url, 'post', data, function (result) {
                    if (result.status == 'reload') {
                        ui.popup.show('confirm', result.messages.title, result.messages.message, 'refresh');
                    } else if (result.status == 'logout') {
                        ui.popup.alert(result.messages.title, result.messages.message, 'logout');
                    } else {
                        callback(result);
                    }
                })
            },
            bDestroy: true,
            searching: search,
            order: urutan,
            ordering: ordering,
            language: {
                searchPlaceholder: placeholder
            }
        })

        $('div.dataTables_filter input').unbind();
        $('div.dataTables_filter input').bind('keyup', function (e) {
            if (e.keyCode == 13) {
                svrTable.search(this.value).draw();
            }
        });
    },
    setAndPopulate: function (id, columns, data, columnDefs, ops, order) {

        var orderby = order ? order : [0, "asc"];
        var search = true;
        var paging = true
        var ordering = true
        var info = true

        if (id == 'tableProject' || id == 'tableMerchantTransaction' || id == 'tableTotalTransaction') {
            search = false
            paging = false
            info = false
        }
        var option = {
            "data": data,
            "drawCallback": function (settings) {

            },
            "searching": search,
            "paging": paging,
            "ordering": ordering,
            "info": info,
            "columns": columns,
            "pageLength": 10,
            "order": [orderby],
            "bDestroy": true,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "aoColumnDefs": columnDefs,
            "scrollX": true,
            "scrollY": true,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "rowCallback": function (row, data) {
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
            }
        };
        if (ops != null) {
            $.extend(option, ops);
        }
        var tbody = $('#' + id).find('tbody');

        var t = $('#' + id).DataTable(option);
        t.on('order.dt search.dt', function () {
                if (id == 'tableProject' || id == 'tableMerchantTransaction' || id == 'tableTotalTransaction') {

                } else {
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            })
            .draw();
    }
}

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


    var valueOffnet = dataRow.price_offnet
    var rupiahOffnet = '';
    var angkaOffnet = valueOffnet.toString().split('').reverse().join('');
    for (var i = 0; i < angkaOffnet.length; i++)
        if (i % 3 == 0) rupiahOffnet += angkaOffnet.substr(i, 3) + '.';
    var nominalOffnet = rupiahOffnet.split('', rupiahOffnet.length - 1).reverse().join('')
    $("#offnet").val('Rp ' + nominalOffnet)

    var valueOnnet = dataRow.price_onnet
    var rupiahOnnet = '';
    var angkaOnnet = valueOnnet.toString().split('').reverse().join('');
    for (var i = 0; i < angkaOnnet.length; i++)
        if (i % 3 == 0) rupiahOnnet += angkaOnnet.substr(i, 3) + '.';
    var nominalOnnet = rupiahOnnet.split('', rupiahOnnet.length - 1).reverse().join('')


    $("#onnet").val('Rp ' + nominalOnnet)

    $("#idProject").val(dataRow.id)

    $("#modalChangePrice").modal({
        backdrop: 'static'
    })
});
