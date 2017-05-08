<?php $this->load->view('common/header') ?>
<body>    
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
<div class="col-md-3">
          <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Product in cart</h3>              
                </div>
                <div class="panel-body" style="text-align:center;">
                    <h1><?php echo $activity_count['total_cart'];?>
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </h1>
                    <div class="bs-component">
                        <div class="progress" style="height: 2px;">
                            <div class="progress-bar" style="width: 60%; line-height: 2px;"></div>
                        </div>
                    </div>
                </div>
           </div>
      
          <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Product in wishlist</h3>
                </div>
                <div class="panel-body">
                    <h1><?php echo $activity_count['total_wishtlist'];?>
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </h1>
                    <div class="bs-component">
                        <div class="progress" style="height: 2px;">
                            <div class="progress-bar" style="width: 60%; line-height: 2px;"></div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-warning">
                <div class="panel-heading">
                  <h3 class="panel-title">Product viewed</h3>              
                </div>
                <div class="panel-body" style="text-align:center;">
                    <h1><?php echo $activity_count['total_product_views'];?>
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </h1>
                    <div class="bs-component">
                        <div class="progress" style="height: 2px;">
                            <div class="progress-bar" style="width: 60%; line-height: 2px;"></div>
                        </div>
                    </div>
                </div>
           </div>
      
          <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">Bounced Customer</h3>
                </div>
                <div class="panel-body">
                    <h1><?php echo $activity_count['total_bounced'];?>
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </h1>
                    <div class="bs-component">
                        <div class="progress" style="height: 2px;">
                            <div class="progress-bar" style="width: 60%; line-height: 2px;"></div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title">Total App downloaded</h3>              
                </div>
                <div class="panel-body" style="text-align:center;">
                    <h1><?php echo $activity_count['total_app_download'];?>
                    <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
                    </h1>
                    <div class="bs-component">
                        <div class="progress" style="height: 2px;">
                            <div class="progress-bar" style="width: 60%; line-height: 2px;"></div>
                        </div>
                    </div>
                </div>
           </div>
        </div>        
</div>
<!--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>-->

<?php $this->load->view('common/footer') ?>