<?php $ct = count($orders)-1;
foreach ($orders as $order) { ?>                    
    <tr>
        <td><a href="https://<?= $_SESSION['shop'] ?>/admin/orders/<?= $order['id'] ?>" target="_blank">#<?php echo $order['order_no']; ?></a></td>
        <td><?= date("M d, h:sa", strtotime($order['created_at'])) ?></td>
        <td>
            <address>
                <strong>
                    <?php echo $order['s_first_name'] . ' ' . $order['s_last_name']; ?>
                    <!--<a class="red-text" target="_blank" href="https://www.knowthycustomer.com/f/search/person?age=&city=&fn=<?= $order['first_name'] ?>&ln=<?= $order['last_name'] ?>&mn=&state=&address=&phone=&email=&ip="><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></a>-->
                </strong>
                <br><span class="font-normal">
                    <?php echo $order['s_address1'] . ', ' . $order['s_city'] . '<br>' . $order['s_province'] . ', ' . $order['s_country'] . ' - ' . $order['s_zip']; ?></span>
            </address>
        </td>
    <!--                                        <td>
            <span>
        <?php if (isset($order['shipping_address'])) { ?>
                    <button class="tip contact-btn" data-hover="<?= $order['shipping_address']['phone'] ?>" onclick="opennewtab('https://www.knowthycustomer.com/f/search/phone?phone=<?= $order['shipping_address']['phone'] ?>')"><i class="fa fa-phone-square fa-1x" aria-hidden="true"></i></button>
        <?php } ?>
                <button class="tip contact-btn" data-hover="<?= $order['contact_email'] ?>" onclick="opennewtab('https://www.knowthycustomer.com/f/search/email?email=<?= $order['contact_email'] ?>')"><i class="fa fa-envelope fa-1x" aria-hidden="true"></i></button>
        </td>-->
        <td>
            <?php
            $color = '';
            $payment = $order['payment_status'];
            if ($payment == 'paid') {
                $color = 'btn-success';
            }
            else if ($payment == 'partially_refunded') {
                $color = 'btn-alert';
            }
            else if ($payment == 'partially_paid') {
                $color = 'btn-info';
            }
            else if ($payment == 'pending') {
                $color = 'btn-warning';
            }
            else if ($payment == 'refunded') {
                $color = 'btn-primary';
            }
            else if ($payment == 'unpaid') {
                $color = 'btn-danger';
            }
            else if ($payment == 'voided') {
                $color = 'btn-dark';
            }
            ?>
            <span class="tag <?= $color ?>"><i class="fa fa-clock-o" aria-hidden="true"></i> <?= $payment ?></span>
        <td>
            <?php
            $fullfill = is_null($order['fulfillment_status']) ? 'unfulfilled' : $order['fulfillment_status'];
            if ($fullfill == 'unshipped') {
                $color = 'btn-danger';
            }
            else if ($fullfill == 'partial') {
                $color = 'btn-alert';
            }
            else if ($fullfill == 'unfulfilled') {
                $color = 'btn-warning';
            }
            ?>
            <span class="tag <?= $color ?>"><?= $fullfill ?></span>
        </td>
        <td><strong><?= $order['total_price'] ?></strong></td>
        <td>
    <?php if (isset($order['shipping_address'])) { ?>
                <button class="btn-Blue" onclick="opennewtab('https://www.knowthycustomer.com/f/search/property?address=<?= $order['s_address1'] ?>&city=<?= $order['s_city'] ?>&state=&zipcode=<?= $order['s_zip'] ?>')">Run Fraud Check <i class="fa fa-share-square-o" aria-hidden="true"></i></button>
    <?php } ?>
        </td>
    </tr>
<?php } ?>
    <tr class="display-none" id="pagecount"><td><?= $count ?></td></tr>