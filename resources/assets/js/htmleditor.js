if ($("#form-add-respone").length) {
  $('#htmleditor').summernote({
    height: 100,
    direction: 'ltr',
    codemirror: { // codemirror options
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