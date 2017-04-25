<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link href="http://seaff.microapps.com/css/seaff.css" rel="stylesheet" type="text/css">

        <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
        <script type="text/javascript">

        ShopifyApp.init({
            apiKey: '<?=$api_key?>',
            shopOrigin: 'https://<?=$shop?>',
            debug: true,
        });

        </script>
        <script type="text/javascript">

            ShopifyApp.ready(function(){
                alert('here');
                ShopifyApp.Bar.initialize({
                    icon: "http://me.com/app.png",
                    buttons: {
                        primary: {
                            label: 'Settings',
                            href: "/settings",
                            target: "app"
                        }
                    },
                    title: "Settings"
                });
            });
        </script>
    </head>
    <body>
        
    </body>    
</html>