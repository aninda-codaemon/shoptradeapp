<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Order List</title>
        <?php $this->load->view('common/front_css'); ?>
    </head>
    <body>
        <header class="custom-head">
            <article>
                <div class="columns four">
                    <div class="logo-image align-left">
                        <img src="<?php echo base_url();?>public/img/logo_156x70_old.png" alt="" width="200em">
                    </div>
                </div>
                <div class="columns eight align-right">
                    <div class="btn-visit">
                        <!--<button class="btn-Teal" onclick="opennewtab('https://www.knowthycustomer.com/')">Visit KnowThyCustomer <i class="fa fa-share-square-o" aria-hidden="true"></i></button>-->
                    </div>
                </div>
            </article>
        </header>
        <section class="full-width">
            <article class="">
                <!--<p class="T-margin-2">Click the "Run Fraud Check" button to view fraud indicators for any of your orders below. Your KnowThyCustomer Fraud Check will open in a new browser tab.</p>-->

            </article>
        </section>
        <section class="full-width" id="mainframe">
            <article>
                <div class="columns has-sections card">
                    <ul class="tabs">
                        <li class="active"><a href="#">All Orders</a></li>
                    </ul>
                    <div class="card-section">
                        <table class="table-customer" id="order_list">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <!--<th>Contact</th>-->
                                    <th>Payment Status</th>
                                    <th>Fulfillment Status</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $lastid = 0;

                                    if($lastid == 0) {
                                        foreach ($orders as $order) { ?>                    
                                    <tr>
                                        <td><a href="#" target="_blank">#<?php echo $order['name']; ?></a></td>
                                        <td><?= date("M d, h:sa", strtotime($order['created_at'])) ?></td>
                                        <td>
                                            <address>
                                                <strong>
                                                    <?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?>
                                                    <!--<a class="red-text" target="_blank" href="https://www.knowthycustomer.com/f/search/person?age=&city=&fn=<?= $order['customer']['first_name'] ?>&ln=<?= $order['customer']['last_name'] ?>&mn=&state=&address=&phone=&email=&ip="><?php echo $order['customer']['first_name'] . ' ' . $order['customer']['last_name']; ?></a>-->
                                                </strong>
                                                <br><span class="font-normal">
                                                <?php
                                                    //echo $order['s_address1'] . ', ' . $order['s_city'] . '<br>' . $order['s_province'] . ', ' . $order['s_country'] . ' - ' . $order['s_zip'];
                                                ?></span>
                                            </address>
                                        </td>
<!--                                        <td>
                                            <span>
                                                <?php if (isset($order['shipping_address'])) { ?>
                                                <button class="tip contact-btn" data-hover="<?= $order['shipping_address']['phone'] ?>" onclick="opennewtab('https://www.knowthycustomer.com/f/search/phone?phone=<?= $order['s_phone'] ?>')"><i class="fa fa-phone-square fa-1x" aria-hidden="true"></i></button>
                                                <?php } ?>
                                                <button class="tip contact-btn" data-hover="<?= $order['contact_email'] ?>" onclick="opennewtab('https://www.knowthycustomer.com/f/search/email?email=<?= $order['contact_email'] ?>')"><i class="fa fa-envelope fa-1x" aria-hidden="true"></i></button>
                                        </td>-->
                                        <td>
                                            <?php $color = '';
                                                $payment = $order['financial_status'];
                                                if($payment == 'paid') {
                                                    $color = 'green';
                                                } else if($payment == 'partially_refunded') {
                                                    $color = 'yellow';
                                                } else if($payment == 'partially_paid') {
                                                    $color = 'grey';
                                                } else if($payment == 'pending') {
                                                    $color = 'orange';
                                                } else if($payment == 'refunded') {
                                                    $color = 'blue';
                                                } else if($payment == 'unpaid') {
                                                    $color = 'red';
                                                } else if($payment == 'voided') {
                                                    $color = 'lightblue';
                                                }
                                            ?>
                                            <span class="tag <?=$color?>"><i class="fa fa-clock-o" aria-hidden="true"></i> <?=$payment?></span>
                                        <td>
                                            <?php 
                                                $fullfill = is_null($order['fulfillment_status']) ? 'unfulfilled' : $order['fulfillment_status'];
                                                if($fullfill == 'unshipped') {
                                                    $color = 'red';
                                                } else if($fullfill == 'partial') {
                                                    $color = 'grey';
                                                } else if($fullfill == 'unfulfilled') {
                                                    $color = 'yellow';
                                                }
                                            ?>
                                            <span class="tag <?=$color?>"><?=$fullfill?></span>
                                        </td>
                                        <td><?= $order['total_price'] ?></td>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>
                                <?php } ?>
                                    <tr class="display-none" id="lastid"><td><?php $lastordernew = $order['name'];?></td></tr>
                                    <tr class="display-none" id="pagecount"><td><?php $count ?></td></tr>
                                <?php } else {
                                    $this->load->view('layout/order_listing_table',$orders);
                                }?>
                            </tbody>
                        </table>
                        <div id="navdiv">
                            <p class="display-none" id="ocount"><?php //echo $ocount;?></p>
                            <button style="float: left;" class="display-none btn-Blue" id="prevpage">Prev</button>
                            <button style="text-align:right;" class="btn-Blue" id="nextpage">Next</button>
                        </div>
                    </div>
                </div>
            </article>
        </section>
        <?php $this->load->view('common/footer_js'); ?>
        <script>
            $(document).ready(function () {
                $('#signup_user').bValidator();
                
                $('#nextpage').on('click', function(){
//                    lastid = $('#lastid').find('td').html();
                    count = $('#pagecount').find('td').html();
                    console.log(count);
                    $.ajax({
                        url: "<?php echo site_url('shopify/goto_order_list');?>/next/"+count,
                        type: "GET",
                        success: function(data) {
//                            alert(data);
                            $('#order_list').find('tbody').empty().append(data);
                            count = parseInt($('#pagecount').find('td').html())+50;
                            if((count) > 0){
                                $('#prevpage').removeClass('display-none');
                            }
                            ocount = $('#ocount').html();
                            if(count >= ocount){
                                $('#nextpage').addClass('display-none');
                            }
                            $("html, body").animate({scrollTop:0}, 'fast');
                        }
                    });
                });
                $('#prevpage').on('click', function(){
//                    previd = $('#previd').find('td').html();
                    count = $('#pagecount').find('td').html();
                    console.log(count);
                    $.ajax({
                        url: "<?php echo site_url('shopify/goto_order_list');?>/prev/"+count,
                        type: "GET",
                        success: function(data) {
//                            alert(data);
                            $('#order_list').find('tbody').empty().append(data);
                            newlastid = $('#lastid').find('td').html();
                            ocount = $('#ocount').html();
                            firstorder = $('#firstorder').find('td').html();
                            count = $('#pagecount').find('td').html();
                            if(count <= 0) {
                                $('#prevpage').addClass('display-none');
                            }
                            if((parseInt(count)+50) < ocount){
                                $('#nextpage').removeClass('display-none');
                            }
                            $("html, body").animate({scrollTop:0}, 'fast');
                            $('#mainframe').scrollTop();
//                            $("body,html", window.parent).animate({scrollTop:0}, 'slow');
                        }
                    });
                });
            });
            function opennewtab(url){
                var win = window.open(url, '_blank');
            }
        </script>
    </body>
</html>