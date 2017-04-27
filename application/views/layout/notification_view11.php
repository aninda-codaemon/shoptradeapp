<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <?php //foreach ($userInfo as $val) { print_r($val); } exit; ?>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Admin</title>

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">

        <!-- Custom CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7+1/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    </head>

    <body> <?php //echo count($errors); exit; ?>
        <?php if (isset($errors) && count($errors) > 0) { ?>
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    <?php foreach ($errors->all() as $error) { ?>
                        <li>{{ $error }}</li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <div id="wrapper">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Push Notification</h3>
                    </div>
                    <div class="panel-body">

                        <form name="" id="" action="sendNotification" method="post" enctype="multipart/form-data" >
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Offer's</label>
                                    <select id="lstOffers" name="lstOffers">
                                        <option value="1">Coupon Code</option>
                                        <option value="2">Promotional Offer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Store</label>
                                    <select id="lstStores" name="lstStores">
                                        <option value="">All Store</option>
                                        <option value="7894561230">Yummy Tummy Store</option>
                                        <option value="5458254512">Beau Monde Store</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6" id="selectStore">
                                    <label>Users</label>
                                    <select id="lstUsers" name="lstUsers" multiple="multiple">
                                        <?php
                                        $i = 0;
                                        foreach ($userInfo as $val) { //print_r($val); 
                                            ?>
                                            <option value="<?php echo $i; ?>" id="<?= $val['id'] ?>"><?= $val['userName'] ?></option>
    <?php $i++;
} ?>
                                    </select>
                                    <input type="hidden" name="userids" id="userids" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Message</label>
                                    <input type="text" name="message" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Description</label>
                                    <textarea name="description" value=""></textarea>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Coupon Code</label>
                                    <select id="lstCouponCode" name="lstCouponCode">
                                    <?php 
                                    $coupon_details = DB::table('coupon_codes')->where('Status','Active')->get();
                                    foreach($coupon_details as $keys => $vals){ ?>
                                        <option value="<?php echo $vals->CouponCode; ?>"><?php echo $vals->CouponCode; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-6">
                                    <label>Image</label>
                                    <input type="file" name="image" value="">
                                </div>
                            </div>
                            <input type="submit"value="submit" id="btnSelected">
                        </form>
                    </div>
                </div>
                <!-- /.panel-default -->

            </div>
            <!-- /.col-lg-12 -->

        </div>
        <!-- /#wrapper -->

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css"
              rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
              rel="stylesheet" type="text/css" />
        <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
        type="text/javascript"></script>
        <script type="text/javascript">
    $(function () {
        $('#lstUsers').multiselect({
            includeSelectAllOption: true
        });
    });
        </script>
        <script type="text/javascript">
            $("#lstStores").change(function () {
                if ($(this).val() == '7894561230') {
                    var token = '7894561230';

                } else if ($(this).val() == '5458254512')
                 {
                     var token = '5458254512';
                 }
                 else if ($(this).val() == '')
                 {
                     var token = ''; 
                 }
                 
                 $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: '/wrapper-tool/admin/getUserByTokenID',
                        data: {'token': token},
                        success: function (response) { // What to do if we succeed
                            
                            $('#selectStore').find('.multiselect-native-select').remove();
                            newselect = '<select class="form-control" id="lstUsers" name="lstUsers" multiple="multiple"></select>';
                            $('#selectStore').append(newselect);
                            $.each(response, function (index, value) { //alert(value);

                                //console.log(value.id);
                                $('#lstUsers').append($('<option>').text(value.userName).val(value.id).attr('id',value.id));
                            });
                            $('#lstUsers').multiselect({
                                includeSelectAllOption: true
                            });
                         },
                    })

            });

            $('#btnSelected').click(function () {
                var selected = $("#lstUsers option:selected");
                var message = "";
                selected.each(function () {
                    message += $(this).attr('id') + ",";
                });
                //alert(message);
                $('#userids').val(message);
            });
        </script>

    </body>

</html>