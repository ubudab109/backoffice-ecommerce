$('.confirm-status').click(function(e){
    e.preventDefault();
    var newstatus = $(this).data('status');
    let currentStatus = $(this).data('current-status');
    let title = 'Status dirubah menjadi "'+newstatus+'" ?';
    let text = "Kamu akan merubah status dari '"+currentStatus+"' menjadi "+newstatus+". Apakah kamu yakin ?";
    let url = $(this).attr("href")
    ui.popup.show("success", title, text, 'Lanjutkan', url, true);
});

$('.confirm-download').click(function(e){
    e.preventDefault();
    var document = $(this).data('document');
    let title = 'Download Data "'+document+'" ?';
    let text = "Kamu akan mendownload data dari <br>"+document+", apakah kamu yakin ?";
    let url = $(this).attr("href")
    ui.popup.show("success", title, text, 'Lanjutkan', url, true);
});