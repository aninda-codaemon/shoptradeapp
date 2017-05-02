<?php $this->load->view('common/header') ?>

<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
              rel="stylesheet" type="text/css" />
       
    <body>
        
        <div class="container">
            <div class="row centered-form">
                <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-bell" aria-hidden="true"></i>Send Push Notification</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">Offer's</label>
                                            <select class="form-control" id="lstOffers" name="lstOffers">
                                                <option value="1">Coupon Code</option>
                                                <option value="2">Promotional Offer</option>
                                            </select>
                                            <?php echo form_error('lstOffers'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="sel1">Store</label>
                                            <select class="form-control" id="lstStores" name="lstStores">
                                                <option value="<?= $store_details['store_id'] ?>"><?= $store_details['store_name'] ?></option>
				            </select>
                                            <?php echo form_error('lstStores'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group" id="selectStore">
                                            <label>Users</label><br/>
                                            <select id="lstUsers" name="lstUsers" class="form-control" multiple="multiple" style="width:100%;">
                                                 <?php
                                                    $i = 0;
                                                    foreach ($userInfo as $val) { ?>
                                                    <option value="<?php echo $i; ?>" id="<?= $val['id'] ?>"><?= $val['userName'] ?></option>
                                                    <?php $i++; } ?>
                                            </select>
                                            <input type="hidden" name="userids" id="userids" value="">
                                            <?php echo form_error('lstUsers'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <input type="text" name="message" value="" class="form-control input-sm" placeholder="Message">
                                            <?php echo form_error('message'); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" rows="2" id="comment" name="description" value=""></textarea>
                                    <?php echo form_error('description'); ?>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Coupon Code</label>
                                            <select class="form-control" id="lstCouponCode" name="lstCouponCode">
                                                <?php foreach($coupon_details as $keys => $vals){ ?>
                                                    <option value="<?php echo $vals['CouponCode']; ?>"><?php echo $vals['CouponCode']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('lstCouponCode'); ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label>Upload Image</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <span class="btn btn-default btn-file">
                                                        Browse… <input type="file" id="imgInp" name="image" value="">
                                                    </span>
                                                </span>
                                                <input type="text" class="form-control" readonly>
                                                <!--<input type="file" name="image" value="">-->
                                            </div>
                                            <img id='img-upload'/>
                                            <?php echo form_error('image'); ?>
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" value="Submit" id="btnSelected" class="btn btn-custom btn-block">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

        <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
        type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#lstUsers').multiselect({
                    includeSelectAllOption: true

                });
                $(document).on('change', '.btn-file :file', function () {
                    var input = $(this),
                            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                    input.trigger('fileselect', [label]);
                });

                $('.btn-file :file').on('fileselect', function (event, label) {

                    var input = $(this).parents('.input-group').find(':text'),
                            log = label;

                    if (input.length) {
                        input.val(log);
                    } else {
                        if (log)
                            alert(log);
                    }

                });
                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#img-upload').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#imgInp").change(function () {
                    readURL(this);
                });

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
        
        <style>.error{color:red;}</style>
    </body>
</html>
