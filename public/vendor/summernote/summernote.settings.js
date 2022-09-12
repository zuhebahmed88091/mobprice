$(document).ready(function () {
    // Code segment for rich text editor
    let summerNoteObj = $('.rich-textarea');
    let imageDir = summerNoteObj.data('image-dir');
    let summerNoteParams = {
        height: 300,
        maximumImageFileSize: maxUploadSizeInByte,
        callbacks: {
            onChange: function (contents) {
                summerNoteObj.val(contents);
                summerNoteObj.valid();
            },
            onBlurCodeview: function(contents) {
                summerNoteObj.val(contents);
                summerNoteObj.valid();
            },
            onImageUpload: function(image) {
                if (image[0].size > maxUploadSizeInByte) {
                    alertify.error('Too large image size! Max allowed upload size is ' + maxUploadSize + 'MB');
                    return false;
                } else {
                    uploadImage(image[0], imageDir);
                }
            }
        }
    };
    summerNoteObj.summernote(summerNoteParams);

    function uploadImage(image, imageDir) {
        let data = new FormData();
        data.append("image", image);
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));
        if (typeof imageDir !== 'undefined') {
            data.append("imageDir", imageDir);
        }

        $.ajax ({
            type: "POST",
            url: mediaUploadRoute,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(url) {
                summerNoteObj.summernote("insertImage", url);
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
});
