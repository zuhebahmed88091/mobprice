let editor;
let richTextAreaObj = $('.rich-textarea');
let updateRichTextEditor = function () {
    if (richTextAreaObj.val().length == 0) {
        $(editor.currentView.editableArea).addClass('has-error');
        richTextAreaObj.parent().addClass('has-error');
        richTextAreaObj.siblings('span.help-block').show();
    } else {
        $(editor.currentView.editableArea).removeClass('has-error');
        richTextAreaObj.parent().removeClass('has-error');
        richTextAreaObj.siblings('span.help-block').hide();
    }
};

$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    richTextAreaObj.wysihtml5({
        toolbar: {
            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": true, //Button which allows you to edit the generated HTML. Default false
            "link": true, //Button to insert a link. Default true
            "image": true, //Button to insert an image. Default true,
            "color": false, //Button to change color of font
            "fa": false, // font awesome icons
            "blockquote": true //Blockquote
        },
        events: {
            "load": function() {
                editor = this;
                $(editor.composer.element).bind('keyup', function(){
                    updateRichTextEditor();
                });
            },
            "blur": function() {
                editor = this;
                updateRichTextEditor();
            }
        }
    });
});
