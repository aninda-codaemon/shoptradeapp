<?php $this->load->view('common/header') ?>

<body>
    <div class="container">
		<div class="row centered-form">
    <!-- Search Functionality & Header -->
    
    <div>
        <div class="col-lg-6 col-md-6 padding-0">
            <h3 class="margin-0 product-title">
                <?php
                    if (!isset($app_title)){
                ?>
                User Activity For Last 30 Day
                <?php
                    }else{
                        echo $app_title;
                    }
                ?>
            </h3>
        </div>

        <div class="col-md-12 col-lg-12 padding-0">
            <form class="form-inline pull-right" id="search_acitvity_frm" method="POST" action="<?php echo base_url(); ?>shopify/search" >
                <div class="form-group">               
                    <input class="form-control" id="from_date" name="from_date" placeholder="Start Date" type="text" value="<?php echo (isset($from_date)?$from_date:''); ?>" >
                </div>
                <div class="form-group">
                
                    <input class="form-control" id="to_date" name="to_date" placeholder="End Date" type="text" value="<?php echo (isset($to_date)?$to_date:''); ?>" >
                </div>

               <button type="button" class="btn btn-custom" id="search_btn" >Submit</button>
            </form>

        </div>
    </div>
    <!-- Search Functionality & Header -->

<span class="counter pull-right"></span>

<div id="activity_data_container" >
    <table class="table table-hover table-bordered results">
      <thead>
        <tr>
            <th class="col-md-1 col-xs-5">First Name</th>
            <th class="col-md-1 col-xs-5">Last Name</th>
            <th class="col-md-3 col-xs-4">Email</th>
            <th class="col-md-3 col-xs-3">Page</th>
            <th class="col-md-2 col-xs-3">Date</th>
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
                <a class="btn btn-info custom-btn" href="<?php echo base_url().'shopify/activity_details/'.$ua['id']; ?>" >Details</a>
                <!--<button class="btn btn-info" type="button" onclick="" >Details</button>-->
                <a href="<?php echo base_url().'notification/user_push_notification/'.$ua['user_id']; ?>" class="btn btn-custom custom-btn" >Push</a>
            </td>
        </tr>
        <?php
                }            
            }
        ?>
        
      </tbody>
    </table>

    <div class="text-center col-md-12" style="border:#ccc 1px solid;">
        <input type="hidden" name="last_h_wk" value="<?php echo $last_wk; ?>" />
        <input type="hidden" name="from_h_date" value="<?php echo $from_date; ?>" />
        <input type="hidden" name="to_h_date" value="<?php echo $to_date; ?>" />

        <nav aria-label="Page navigation example">
            <?php
                //echo 'total_page: '.$total_page.'  current_page: '.$current_page.'  next_page: '.$next_page;
            ?>

            <?php
                if ($current_page > 1){
            ?>
            <ul class="pagination margin-B-10 margin-T-10 pull-left">
                <li class="page-item"><a data-href="<?php echo ($current_page - 1); ?>" class="page-link page-bg" href="javascript: void(0);">Previous</a></li>
            </ul>
            <?php } ?>

            <?php
                if ($next_page < $total_page){
            ?>
            <ul class="pagination margin-B-10 margin-T-10 pull-right">
                <li class="page-item"><a data-href="<?php echo ($next_page); ?>" class="page-link page-bg" href="javascript: void(0);">Next</a></li>
            </ul>
            <?php } ?>
        </nav>
    </div>

</div>

</div>
    
</div>
<script type="">      
    $( function() {
        $( "#to_date" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#from_date" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );

    $(document).on('click', '#search_btn', function(e){
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

        $('#search_acitvity_frm').submit();
    });

    $(document).on('click', '.page-link', function(e){
        e.stopImmediatePropagation();
        console.log($(this).attr('data-href'));
        var page = $(this).attr('data-href');
        var from_date = $.trim($('input:hidden[name="from_h_date"]').val());
        var to_date = $.trim($('input:hidden[name="to_h_date"]').val());
        var last_wk = $.trim($('input:hidden[name="last_h_wk"]').val());
        
        var response = JSON.parse($.ajax({
                            url: '<?php echo base_url(); ?>shopify/ajax_get_activity_page',
                            type: 'POST',
                            data: {'page': page, 'from_date': from_date, 'to_date': to_date, 'last_wk': last_wk},
                            dataType: 'json',
                            async: false
                        }).responseText);

        console.log(response);
        $('#activity_data_container').html(response.page_data);
    });
</script>
<?php $this->load->view('common/footer') ?>
