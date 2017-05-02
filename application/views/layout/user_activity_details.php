<?php $this->load->view('common/header') ?>

<body>    
    <div class="container">
		<div class="row centered-form">
        <div class="col-md-12">
            <div>
                <h3 class="product-title col-md-10">User History Product Details</h3>
                <!--<button class="btn btn-custom pull-right" type="submit">Push</button>-->
                <a href="<?php echo base_url().'notification/user_push_notification/'.$activity_details['user_id']; ?>" class="btn btn-custom pull-right" >Push</a>
            </div>
        </div>
        <div class="clearfix"></div>


		<?php

			if (!empty($product_info)){
				foreach($product_info as $pd){
		?> 
		<div class="card">
			<div class="container-fliud">
				<div class="wrapper row">
					<div class="preview col-md-4">						
						<div class="preview-pic tab-content">
						  <div class="tab-pane active" id="pic-1"><img src="<?php echo $pd['images'][0]['src']; ?>" /></div>
						  <div class="tab-pane" id="pic-2"><img src="http://placekitten.com/400/252" /></div>
						  <div class="tab-pane" id="pic-3"><img src="http://placekitten.com/400/252" /></div>
						  <div class="tab-pane" id="pic-4"><img src="http://placekitten.com/400/252" /></div>
						  <div class="tab-pane" id="pic-5"><img src="http://placekitten.com/400/252" /></div>
						</div>
				    </div>
					
                    <div class="details col-md-8">
                        <h4 class="product-title"><?php echo $pd['title']; ?></h4>
                        <h5 class="price">current price: <span>$<?php echo $pd['variants'][0]['price']; ?></span></h5>
                        <h5 class="price">Category: <span><?php echo $pd['product_type']; ?></span></h5>

                        <article class="user-detail">                        
                            <p class="vote"><strong>Name :</strong> <?php echo $activity_details['first_name']; ?> <?php echo $activity_details['last_name']; ?> </p>
                            <p class="vote"><strong>Email :</strong> <?php echo $activity_details['email']; ?></p>
                            <p class="vote"><strong>Activity :</strong> 
	                        	<?php
					                if ($activity_details['history_type'] == 'browse_category'){
					                    echo 'Browsed a product category';
					                }elseif ($activity_details['history_type'] == 'product_details'){
					                    echo 'Saw a product details';
					                }elseif ($activity_details['history_type'] == 'product_in_cart'){
					                    echo 'Added products in cart';
					                }elseif ($activity_details['history_type'] == 'payment_page'){
					                    echo 'Bounced from the payment page';
					                }elseif ($activity_details['history_type'] == 'product_in_wishlist'){
					                    echo 'Added products in wishlist';
					                }
					            ?>
                            </p>                            
                        </article>
                            
				    </div>
				</div>
			</div>
		</div>
		<?php
				}
			}
		?>
        

	</div>
</div>
<!--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>-->

<?php $this->load->view('common/footer') ?>