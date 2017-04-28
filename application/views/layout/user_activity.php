<?php $this->load->view('common/header') ?>

<body>
    <div class="container">
		<div class="row centered-form">
    <div>
        <div class="col-lg-6"><h3 class="margin-0 product-title">User History For Last 7 Day</h3></div>
        <form id="search_acitvity_frm" class="form-inline" method="POST" action="/shopify/search" >
            <div class="form-group col-lg-6 pull-right">
                <div class="col-lg-5">
                    <input value="<?php echo $from_date; ?>" type="text" id="from_date" name="from_date" class="search form-control" placeholder="Start Date" >
                </div>
                <div class="col-lg-5">
                    <input value="<?php echo $to_date; ?>" type="text" id="to_date" name="to_date" class="search form-control" placeholder="End Date" >
                </div>
                <button class="btn btn-custom pull-right" type="submit">Submit</button>
            </div>
        </form>
    </div>
<span class="counter pull-right"></span>
<table class="table table-hover table-bordered results">
  <thead>
    <tr>
        <th class="col-md-1 col-xs-5">First Name</th>
        <th class="col-md-1 col-xs-5">Last Name</th>
        <th class="col-md-4 col-xs-4">Email</th>
        <th class="col-md-3 col-xs-3">Page</th>
        <th class="col-md-1 col-xs-3">Date</th>
        <th class="col-md-2 col-xs-3">Action</th>
    </tr>    
  </thead>
  <tbody>
    <?php
        if (empty($user_activity)){
    ?>
    <tr >
      <td colspan="6"><i class="fa fa-warning"></i>No records for user activity found</td>
    </tr>
    <?php } ?>

    <?php
        if (!empty($user_activity)){
            foreach ($user_activity as $ua) {                
    ?>
    <tr>
        <td><?php echo $ua['first_name']; ?></td>
        <td><?php echo $ua['last_name']; ?></td>
        <td><?php echo $ua['email']; ?></td>
        <td>
            <?php
                if ($ua['history_type'] == 'browse_category'){
                    echo 'Browsed a product category';
                }elseif ($ua['history_type'] == 'product_details'){
                    echo 'Saw a product details';
                }elseif ($ua['history_type'] == 'product_in_cart'){
                    echo 'Added products in cart';
                }elseif ($ua['history_type'] == 'payment_page'){
                    echo 'Bounced from the payment page';
                }elseif ($ua['history_type'] == 'product_in_wishlist'){
                    echo 'Added products in wishlist';
                }
            ?>
        </td>
        <td><?php echo date('d-m-Y', strtotime($ua['create_date'])); ?></td>
        <td>
            <button class="btn btn-info" type="submit">Details</button>
            <button class="btn btn-custom" type="submit">Push</button>
        </td>
    </tr>
    <?php
            }
        }
    ?>
    
  </tbody>
</table>

  
		</div>
    
</div>
<script type="">      
    $( function() {
        $( "#to_date" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#from_date" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );

    $(document).on('submit', '#search_acitvity_frm', function(e){
        e.preventDefault();

        var from_date = $('#from_date');
        var to_date = $('#to_date');

        if (from_date.val() == ''){
            alert('Please insert start date');    
            return false;
        }

        if (to_date.val() == ''){
            alert('Please insert end date');    
            return false;
        }

        return true;
    });
</script>
<?php $this->load->view('common/footer') ?>