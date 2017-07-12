<?php $this->load->view('common/header') ?>
<body>    
    <style>
        .panel *{margin:0;}
        .border-bottom{border-bottom: 3px solid;border-radius: 0 0 5px 5px;
    margin: 40px 10px;
    text-align: right;
    padding: 10px 0px;}
        .border-paleturquoise{border-color:paleturquoise;}
        .border-red{border-color:red;}
        .border-peru{border-color:peru;}
        .border-plum{border-color:plum;}
        .border-orange{border-color:orange;}
        .panel-custom>.panel-heading{background-color:#42bcb7;border-color:#42bcb7;}
        .panel-custom{border-color:#42bcb7;}
    </style>
    <div class="container">
		<div class="row centered-form">
        <div class="col-md-12">
            <div>
                <h3 class="product-title col-md-10">Dashboard</h3>
                <!--<h2>These Analysis are from mobile app store.</h2>-->
            </div>
        </div>
        <div class="clearfix"></div>
        
        </div>
        <div class="panel panel-primary panel-custom">
                <div class="panel-heading">
                  <h3 class="panel-title">Activity</h3>              
                </div>
                <div class="panel-body">
                    <div class="row">
                        
                        <div class="col-md-4">
                            
                            <div class="row"><div class="border-bottom border-paleturquoise">
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-cart-plus fa-2x" aria-hidden="true"></i>

                                </div>
                                <div class="col-md-8">
                                    <article>
                                        <h2><?php echo $activity_count['total_cart'];?></h2>
                                        <p><small>Product in cart</small></p>
                                    </article>
                                </div>
                             <div class="clearfix"></div>
                            </div>
                        </div>
                        
                        </div>
                        
                        <div class="col-md-4">
                            <div class="row"><div class="border-bottom border-red">
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-heart fa-2x" aria-hidden="true"></i>

                                </div>
                                <div class="col-md-8">
                                    <article>
                                        <h2><?php echo $activity_count['total_wishtlist'];?></h2>
                                        <p><small>Product in wishlist</small></p>
                                    </article>
                                </div>
                            <div class="clearfix"></div>
                            </div>
                                </div>
                            
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="border-bottom border-peru">
                                <div class="col-md-4 text-center">
                                    <i class="fa fa-eye fa-2x" aria-hidden="true"></i>

                                </div>
                                <div class="col-md-8">
                                    <article>
                                        <h2><?php echo $activity_count['total_product_views'];?></h2>
                                        <p><small>Product viewed</small></p>
                                    </article>
                                </div>
                            <div class="clearfix"></div>
                            </div>
                        </div>
                             
                    </div>
                     <div class="row">
                        <div class="col-md-4">
                            <div class="row"><div class="border-bottom border-orange">
                                <div class="col-md-4 text-center"><i class="fa fa-user-times fa-2x" aria-hidden="true"></i>
</div>
                                <div class="col-md-8">
                                    <article>
                                        <h2><?php echo $activity_count['total_bounced'];?></h2>
                                        <p><small>Bounced Customer</small></p>
                                    </article>
                                </div>
                            <div class="clearfix"></div>
                            </div></div>
                             
                        </div>
                        <div class="col-md-4">
                            <div class="row"><div class="border-bottom border-plum">
                                <div class="col-md-4 text-center"><i class="fa fa-download fa-2x" aria-hidden="true"></i>
</div>
                                <div class="col-md-8">
                                    <article>
                                        <h2><?php echo $activity_count['total_app_download'];?></h2>
                                        <p><small>Total App downloaded</small></p>
                                    </article>
                                </div>
                            <div class="clearfix"></div>
                            </div></div>
                             
                        </div>
                        
                    </div>
                </div>
           </div>

</div>
<!--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>-->

<?php $this->load->view('common/footer') ?>