<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Upload</title>
    @include('layout.header')
</head>
<body>
    <div class="container text-center">
        @include('layout.navbar')
        <div id="responseMessage" class="alert alert-success d-none" style="margin-top: 150px;"></div>
        <ul class="errors" style="margin-top: 80px;">
            
        </ul>

        <form  id="file_upload_form" enctype="multipart/form-data">
            <div class="col-md-4 offset-4 p-5">
             <div class="form-group p-2">
                <label for="file">Select Image</label>
                <input type="file" class="form-control" id="photo" name="photo">
              </div>
        <button type="submit"  class="btn btn-success mt-5">Upload</button>
    </div>
    </form>
    </div>
<script>

$(document).on('submit','#file_upload_form',function(event){
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"/api/file/upload",
            type:"POST",
            processData: false,
            contentType: false,
            cache: false,
            data:formData,
            success:function(response){
                $('#responseMessage').removeClass('d-none');
                $('#responseMessage').text(response.message);
                $("input").val(null);
                setTimeout(() => {
                    $('#responseMessage').addClass('d-none');
                }, 5000);
            },
            error: function(xhr, status, error) {
                    var errors = xhr.responseJSON.error;
                    $.each(errors, function(i, item) {
                        $(".errors").append("<li class='alert alert-danger'>"+item+"</li>")
                    });
                    setTimeout(() => {
                        $('.errors').empty();
                    }, 5000);
        }
    });
});
</script>
</body>
</html>