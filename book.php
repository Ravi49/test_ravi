<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/toastr.min.css">
</head>
<body>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h4>
                    Book Details 
                    <button type="button" id="addMoreButton" class="btn btn-primary float-end">Add More +</button>
                </h4>
            </div>
            <div class="card-body">
                <form class="submit_form" method="POST" autocomplete="off">

                    <div class="add-more-mainForm">

                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label>Book Name</label>
                                <input type="text" name="book_name[]" class="form-control book_name">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Book Title</label>
                                <input type="text" name="book_title[]"  class="form-control book_title">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Book Author</label>
                                <input type="text" name="book_author[]" class="form-control book_author">
                            </div>
                             <div class="col-md-4 mb-3">
                                <label>Book Contents</label>
                                <textarea name="book_contents[]" class="form-control book_contents"></textarea>
                            </div>
                            <div class="col-md-2">
                                <div class="addRemoveButton">
                                </div>
                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary float-end">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>

    <script>
        $(document).ready(function () {
            $(document).on('click', '#addMoreButton', function () {
                var htmlForm = $(".add-more-mainForm").first().clone();
                $(htmlForm).find(".addRemoveButton").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>Remove</a>");
                $(htmlForm).find("input[type=text], textarea").val("");
                $(".add-more-mainForm").last().after(htmlForm);

            });
            $(document).on('click', '.remove', function (e) {
                e.preventDefault();
                $(this).parents(".add-more-mainForm").remove();
            });
        });
        $(document).on('submit', '.submit_form', function(e){
            e.preventDefault();
            var isValid = true;
            // Iterate over each form field and validate
            $(this).find('.add-more-mainForm').each(function() {
                var book_name = $(this).find('.book_name').val();
                var book_title = $(this).find('.book_title').val();
                var book_author = $(this).find('.book_author').val();
                var book_contents = $(this).find('.book_contents').val(); 
                // Check if any field is empty
                if(book_name == '' || book_title == '' || book_author == '' || book_contents == '') {
                    toastr.error('All fields are required.', 'Error', {
                        'closeButton': true,
                        'timeOut': '6000'
                    });
                    isValid = false;
                    return false;// Exit loop if any field is empty
                }    
            });
            // If all fields are valid, submit the form
            if(isValid) {
                var form_data = $(this).serialize();
                //alert(form_data);return;
                $.ajax({
                    type:'POST',
                    url:'<?php echo base_url(); ?>book/submit',
                    data:form_data,
                    success:function(result){
                        var data = $.parseJSON(result);
                        if(data.success){
                            toastr.success(data.msg, "success", {"closeButton":true, "timeOut":"3000"});
                            $('.submit_form')[0].reset();
                        }else{
                            toastr.error(data.msg, "error", {"closeButton":true, "timeOut":"3000"});
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>