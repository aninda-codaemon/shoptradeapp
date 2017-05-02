<?php $this->load->view('common/header') ?>
<body>
    <div class="container">
        <div class="row centered-form">
            <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-share-square" aria-hidden="true"></i>
                            Send Push Notification to <?= $user_details['first_name'] ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="" method="POST">
                            <input type="hidden" name="user_id" value="<?= $user_details['id'] ?>" >
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label>Message Title</label>
                                        <input type="text" class="form-control input-sm" name="message" placeholder="Message">
                                        <?php echo form_error('message'); ?>
                                    </div>

                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="sel1">Select Coupon Code</label>
                                       <select class="form-control" id="lstCouponCode" name="lstCouponCode">
                                            <?php foreach($coupon_codes as $keys => $vals){ ?>
                                                <option value="<?php echo $vals['CouponCode']; ?>"><?php echo $vals['CouponCode']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php echo form_error('lstCouponCode'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message Content</label>
                                <textarea class="form-control" rows="2" id="comment" name="description"></textarea>
                                <?php echo form_error('description'); ?>
                            </div>
                            <input type="submit" value="Submit" class="btn btn-custom btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<style>.error{color:red;}</style>
</body>
</html>
