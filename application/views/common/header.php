<!DOCTYPE html>
<html >
<head>
  	<meta charset="UTF-8">
  	<title>Bootstrap Table Search</title>
  	
  	<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css'>
    <link rel="stylesheet" href="<?php echo base_url(); ?>public/css/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <style style="text/css">
        .padding-0{
            padding: 0;
        }
        .margin-0{
            margin: 0;
        }
        .margin-T-10{
            margin-top:10px;
        }
        .margin-B-10{
            margin-bottom:10px;
        }
        .page-bg{
            color: #fff!important;
            background: #337ab7!important;
            border: none!important;
        }        
    </style>
</head>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Shoptradeonline</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="<?php echo base_url().'shopify/user_activity'; ?>">User Activity</a></li>
                <li><a href="#">Push Notification</a></li>          
            </ul>
        </div>
    </nav>