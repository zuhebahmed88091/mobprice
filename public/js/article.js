$(document).ready(function () {

    let status = 'All';
    let categoryBox = $('#categoryBox');
    let departmentIdObj = $('#department_id');
    let tagsObj = $('#tags');

    let getCheckedCategories = function() {
        return  $('.checkbox.icheck input[type=checkbox]:checked').map(function(){
            return $(this).val();
        }).toArray();
    };

    $('#savArticle').click(function () {
        if ($('.checkbox.icheck input[type=checkbox]:checked').length) {
            $('#formArticle').submit();
        } else {
            $('#categoryBox').css('border', '1px solid #dd4b39');
            alert('You must select at least a category')
        }
    });

    // icheck for nice checkbox
    $('.category-validate .checkbox.icheck input').on('ifChanged', function () {
        if ($('.checkbox.icheck input[type=checkbox]:checked').length) {
            categoryBox.css('border', '0');
        } else {
            categoryBox.css('border', '1px solid #dd4b39');
        }
    });

    $(".saveCategory").click(function () {
        let modalCategoryObj = $('#modal-category');
        let formCategoryObj = $("#formCategory");

        formCategoryObj.validate();
        if (formCategoryObj.valid()) {
            $.ajax({
                type: "POST",
                url: routes.categories.store,
                data: formCategoryObj.serialize() + '&' + $.param({'categories': getCheckedCategories()}),
                dataType: "html",
                success: function (jsonString) {
                    let jsonObject = JSON.parse(jsonString);
                    if (jsonObject.status === 'OK') {
                        $('#categoryBox').html(jsonObject.html);

                        // icheck for nice checkbox
                        $('.checkbox.icheck input').iCheck({
                            checkboxClass: 'icheckbox_square-blue',
                            radioClass: 'iradio_square-blue',
                        });

                        modalCategoryObj.modal('hide');
                        alertify.success('New category added and marked!');

                        // reset form
                        formCategoryObj.trigger('reset');
                        $('#formCategory #product_id').val('').trigger('change');
                        $('#formCategory #parent_id').val('').trigger('change');
                        formCategoryObj.validate().resetForm();
                    }
                },
                error: function (xhr) {
                    alertify.error(xhr.statusText);
                }
            });
        }
    });

    $(".saveTag").click(function () {
        let modalTagObj = $('#modal-tag');
        let formTagObj = $("#formTag");
        let checkedTags = $('#tags').val();

        formTagObj.validate();
        if (formTagObj.valid()) {
            $.ajax({
                type: "POST",
                url: routes.tags.store,
                data: formTagObj.serialize() + '&' + $.param({'tags': checkedTags}),
                dataType: "html",
                success: function (jsonString) {
                    let jsonObject = JSON.parse(jsonString);
                    if (jsonObject.status === 'OK') {
                        $('#tags').html(jsonObject.html);

                        modalTagObj.modal('hide');
                        alertify.success('New tag added and selected!');

                        // reset form
                        formTagObj.trigger('reset');
                    }
                },
                error: function (xhr) {
                    alertify.error(xhr.statusText);
                }
            });
        }
    });

    $('.special :input').change(function() {
        status = $(this).val();
        getArticles(1);
    });

    $('body').on('click', '.pagination a', function (e) {
        e.preventDefault();

        $('.data-list li').removeClass('active');
        $(this).parent('li').addClass('active');
        let page_no = $(this).attr('href').split('page=')[1];
        getArticles(page_no);
    });

    departmentIdObj.change(function () {
        getArticles(1);
    });

    // icheck for nice checkbox
    $('.category-search .checkbox.icheck input').on('ifChanged', function () {
        getArticles(1);
    });

    tagsObj.change(function () {
        getArticles(1);
    });

    function getArticles(page) {
        $.ajax({
            type: "GET",
            url: routes.articles.withProduct,
            data: {
                'page': page,
                'departmentId': departmentIdObj.val(),
                'tags': tagsObj.val(),
                'categories': getCheckedCategories(),
                'status': status
            },
            dataType: "html",
            beforeSend: function () {
                if (loaderImageHtml) {
                    $('.data-list').html(loaderImageHtml).fadeIn(50);
                }
            },
            success: function (data) {
                $('.data-list').html(data);
            },
            error: function (xhr) {
                alertify.error(xhr.statusText);
            }
        });
    }

    $('#btnExportXLSX').click(function () {
        location.href = routes.articles.exportXLSX +
            '?departmentId=' + departmentIdObj.val() +
            '&tags=' + tagsObj.val() +
            '&categories=' + getCheckedCategories() +
            '&status=' + status;
    });
});
