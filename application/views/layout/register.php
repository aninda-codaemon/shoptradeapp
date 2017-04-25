<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>User Registration</title>
        <?php $this->load->view('common/front_css'); ?>
    </head>
    <body>
        <header class="custom-head">
            <article>
                <div class="columns four">
                    <div class="logo-image align-left">
                        <img src="<?php echo base_url();?>img/img_logo_with_text.png" alt="" width="300em">
                    </div>
                </div>
                <div class="columns eight align-right">
                    <p class="text-contact text-Navy">Need assistance? Call <span class="text-Teal">1-844-823-2869</span> or email <a href="mailto:support@knowthycustomer.com" class="text-Teal">support@knowthycustomer.com</a>
                    </p>
                </div>
            </article>
        </header>
        <section class="full-width">
            <article>
                <div class="card blue-bg">
                    <div class="columns four text-white">
                        <div class="card align-center">
                            <img src="<?php echo base_url();?>img/logo.png" alt="" width="150em">
                            <h1 class="text-logo align-center text-Navy"><span class="text-Teal">KnowThy</span>Customer</h1>
                            <h2 class="text-tag-line align-right text-Navy">Fraud Check</h2>
                            <p class="text-info align-center text-Navy">Set up your <span class="">FREE</span> account today to view fraud indicators and detailed reports for your Shopify orders</p>
                        </div>
                    </div>
                    <div class="columns eight">
                        <form class="form-horizontal" id="signup_user" action="signup_user" method="post">
                            <h2 class="text-white">Set up your free account</h2>
                            <article class="margin-top-1">
                                <div class="columns six text-white">
                                    <div class="row">
                                        <label class="text-white">First Name</label>
                                        <input id="fn" name="fn" type="text" value="<?= $user['first_name'] ?>" class="form-control input-md" data-bvalidator="alpha,required" data-bvalidator-msg="First name is required and only alphabets allowed.">
                                        <input id="userid" name="userid" type="text" value="<?= $user['user_id'] ?>" class="display-none form-control input-md" >
                                    </div>
                                    <div class="row">
                                        <label class="text-white">Email Address</label>
                                        <input id="email" name="email" type="text" value='<?= $user['email'] ?>' class="form-control input-md" data-bvalidator="email,required" data-bvalidator-msg="Email is required.">
                                    </div>
                                    <div class="row">
                                        <label class="text-white">Company</label>
                                        <input id="cmpny" name="cmpny" type="text" placeholder="Company name" class="form-control input-md" data-bvalidator="required" data-bvalidator-msg="Enter you company name.">
                                    </div>
                                </div>

                                <div class="columns six text-white">
                                    <div class="row">
                                        <label class="text-white">Last Name</label>
                                        <input id="ln" name="ln" type="text" value='<?= $user['last_name'] ?>' class="form-control input-md" data-bvalidator="alpha,required" data-bvalidator-msg="Last name is required and only alphabets allowed.">
                                    </div>
                                    <div class="row">
                                        <label class="text-white">Phone Number</label>
                                        <input id="phone" name="phone" type="text" placeholder="123-456-7899" class="form-control input-md" data-bvalidator="number,required" data-bvalidator-msg="Phone number is required.">
                                    </div>
                                    <div class="row">
                                        <label class="text-white">Job Title</label>
                                        <input id="jobt" name="jobt" type="text" placeholder="Job title" class="form-control input-md" data-bvalidator="required" data-bvalidator-msg="Enter you job title.">
                                    </div>
                                </div>
                            </article>
                            <article class="margin-top-1">
                                <div class="columns one text-white">
                                    <div class="row">
                                        <label class="text-white">
                                            <input id="tos" type="checkbox" name="tos" value="1" data-bvalidator="required,required"  data-bvalidator-msg="Please check this box."> 
                                        </label>
                                    </div>
                                </div>
                                <div class="columns eleven text-white">
                                    <div class="row">
                                        <p class="text-white text-terms">By clicking the checkbox you represent that you are over 18 years of age and agree to accept our <span class="text-Teal">Terms of Service</span> and <span class="text-Teal">Privacy Policy</span>. You agree that you will not use any information about an individual obtained from KnowThyCustomer as a factor in determining an individual's eligibility for employment; tenancy; educational admission or benefits; personal credit, loans, or insurance; or for any other purpose prohibited by our <span class="text-Teal">Terms of Service</span>. For more information about whether a use of information falls under one of these categories, please contact us at <a href="mailto:support@knowthycustomer.com" class="text-Teal">support@knowthycustomer.com</a></p>
                                    </div>
                                </div>
                            </article>
                            <article class="margin-top-1">
                                <div class="columns six text-white">
                                    <div class="row">
                                        <label class="text-white">Create Password</label>
                                        <input id="pwd" name="pwd" type="password" placeholder="Password" class="form-control input-md" data-bvalidator="minlength[8],required" data-bvalidator-msg="Enter your new password. Minimum length should be 8 charactets.">
                                    </div>
                                </div>
                                <div class="columns six text-white">
                                    <div class="row">
                                        <label class="text-white">Confirm Password</label>
                                        <input id="rpwd" name="rpwd" type="password" placeholder="Re-enter password" class="form-control input-md" data-bvalidator="equalto[pwd],required" data-bvalidator-msg="This password should match previous password.">
                                    </div>
                                </div>
                            </article>
                            <article class="align-right margin-top-2">
                                <div class="columns">
                                    <button class="btn-Teal" id="form_submit_btn">Create Your Account</button>
                                </div>
                            </article>
                        </form>
                    </div>
                </div>
            </article>
        </section>
        <?php $this->load->view('common/footer_js'); ?>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>-->
        <script>
            $(document).ready(function () {
                var optionsBootstrap = {
                    position: {x:'left', y:'top'},
                    offset:   {x:15, y:-10},
                };
                $('#signup_user').bValidator(optionsBootstrap);
            });
        </script>
    </body>    
</html>