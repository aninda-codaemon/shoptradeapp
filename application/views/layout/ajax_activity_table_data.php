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
                <a class="btn btn-info" href="<?php echo base_url().'shopify/activity_details/'.$ua['id']; ?>" >Details</a>
                <!--<button class="btn btn-info" type="button" onclick="" >Details</button>-->
                <a href="#" class="btn btn-custom" >Push</a>
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
                echo 'total_page: '.$total_page.'  current_page: '.$current_page.'  next_page: '.$next_page;
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