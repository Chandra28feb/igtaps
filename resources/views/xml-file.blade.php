<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Xml File Upload</title>
    @include('layout.header')
<style>
        #loader {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        text-align: center;
        z-index: 9999;
    }
    
    #loader img {
        margin-top: 20%;
    }
    </style>
</head>
<body>
    <div class="container text-center">
        @include('layout.navbar')
        <div id="responseMessage" class="alert alert-success d-none" style="margin-top: 100px;"></div>
        <ul class="errors" style="margin-top: 80px;">
            
        </ul>

        <form  id="xml_file_upload_form" enctype="multipart/form-data">
            <div class="col-md-4 offset-4 p-5">
             <div class="form-group p-2">
                <label for="file">Select Xml</label>
                <input type="file" class="form-control" id="xml" name="xml">
              </div>
        <button type="submit"  class="btn btn-success mt-5">Save</button>
    </div>
    </form>
    <div id="loader">
        <img src="{{ asset('loader/1488.gif') }}" alt="Loading..." />
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
            </tr>
        </thead>
        <tbody id="user-table-body">
           
        </tbody>
    </table>
    </div>
<script>
$(document).on('submit','#xml_file_upload_form',function(event){
    event.preventDefault();
    var formData = new FormData(this);
    showLoader();
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"/api/xml/file/upload",
            type:"POST",
            processData: false,
            contentType: false,
            cache: false,
            data:formData,
            success:function(response){
                hideLoader();
                $('#responseMessage').removeClass('d-none');
                $('#responseMessage').text(response.message);
                $("input").val(null);
                fetchData();
                setTimeout(() => {
                    $('#responseMessage').addClass('d-none');
                }, 5000);
            },
            error: function(xhr, status, error) {
                hideLoader();
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

function fetchData(){
    $.ajax({
        url: "/api/user/lists",
        type: "GET",
        dataType: "json",
        success: function(data) {
            var tableBody = $('#user-table-body');
            tableBody.empty();
            $.each(data, function(index, user) {
                var row = '<tr>' +
                            '<td>' + user.name + '</td>' +
                            '<td>' + user.email + '</td>' +
                            '<td>' + user.mobile + '</td>' +
                            '</tr>';
                tableBody.append(row);
            });
        }
    });
}
(function() {
    fetchData();
})();
function showLoader() {
        $("#loader").fadeIn();
    }
    
    function hideLoader() {
        $("#loader").fadeOut();
    }
</script>
</body>
</html>