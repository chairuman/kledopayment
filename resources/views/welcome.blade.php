<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
 
    <title>{{ config('app.name') }}</title>
</head>
<body style="padding: 10rem 0rem;">
    <div class="container">
        <h1 class="text-center">{{config('app.name')}}</h1>
          <div class="col-12">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Tambah
          </button>
        <div class="row">
            <table id="payment" class="table display select" cellspacing="0" width="100%">
                <thead>
                   <tr>
                      <th>Payment ID</th>
                      <th>Payment Name</th>
                      <th>Delete</th>
                   </tr>
                </thead>
                <tbody></tbody>
             </table>
          </div>
            <div id="button"></div>
        </div>
        <div id="message" class="alert alert-primary mt-3" role="alert">
            
        </div>
      </div>
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Tambah Payment</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_name" class="form-label">Name</label>
                        <input type="text" name="payment_name" class="form-control" id="payment_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button id="simpan" type="button" class="btn btn-success">Simpan</button>
                </div>
            </form>
          </div>
        </div>
      </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        $(document).ready(function (){

            var pusher = new Pusher('e16ddae55d0b46632282', {
                cluster: 'ap1'
            });
            var channel = pusher.subscribe('delete-progress');
            channel.bind('delete-channel', function(data){
                $("#message").text(data['data']);
            });

            getData('/api/payments');
            $(document).on('click', '#next', function(){
                var url = $("#next").data('url');
                getData(url);
            });
            $(document).on('click', '#prev', function(){
                var url = $("#prev").data('url');
                getData(url);
            });
            $(document).on('click', '#delete', function(){
                var id = [];
                $('input[type="checkbox"]:checked').each(function(){
                    id.push($(this).val());
                })
                deleteData(id);
            });
            $(document).on('click', '#simpan', function(){
                var payment_name = $("#payment_name").val();
                if(payment_name != ''){
                    addData(payment_name);
                }else{
                    alert('Name is required');
                }
            })

            function addData(payment_name) {
                $.ajax({
                    url: '/api/payments',
                    type: 'POST',
                    data: {payment_name: payment_name},
                }).done(function(response){
                    window.location.reload();
                });
            }

            function deleteData(id) {
                $("#delete").attr("disabled", true);
                $.ajax({
                    url: '/api/payments?payment_id=' + id,
                    type: 'DELETE'
                }).done(function(response){
                    window.location.reload();
                });
            }

            function getData(url){
                $("#next").removeAttr('disabled data-url');
                $("#prev").removeAttr('disabled data-url');
                $("tbody").empty();
                $.ajax({
                    url: url,
                }).done(function(response){
                    var html;
                    $.each(response['data'], function(index, data){
                        html += '<tr><td>' + data['id'] + '</td>';
                        html += '<td>' + data['payment_name'] + '</td>';
                        html += '<td><input type="checkbox" name="id[]" value="'+ data['id'] +'"></td><tr>';
                    });
                    $("tbody").html(html);

                    btn = '<button id="prev" type="button" class="btn btn-primary me-3">Prev</button>';
                    btn += '<button id="next" type="button" class="btn btn-primary">Next</button>';
                    btn += '<button id="delete" class="btn btn-danger flex float-end">Delete</button>';
                    $("#button").html(btn);

                    (response['links']['prev'] != null) ? $("#prev").attr('data-url', response['links']['prev']) : $("#prev").attr('disabled', true);
                    (response['links']['next'] != null) ? $("#next").attr('data-url', response['links']['next']) : $("#next").attr('disabled', true);
                });
            }
        });
    </script>
</body>
</html>