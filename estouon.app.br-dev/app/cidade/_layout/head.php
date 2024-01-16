<?php
global $app;
global $seo_subtitle;
global $seo_description;
global $seo_keywords;
global $seo_image;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    
    <title><?php echo seo_app( $seo_subtitle ); ?></title>
    <meta name="description" content="<?php echo $seo_description; ?>">
    <meta name="keywords" content="<?php echo $seo_keywords; ?>">

    <meta property="og:title" content="<?php echo seo_app( $seo_subtitle ); ?>">
    <meta property="og:description" content="<?php echo $seo_description; ?>">
    <meta property="og:image" content="<?php echo $app['avatar']; ?>">
    <link rel="shortcut icon" href="<?php echo $app['avatar']; ?>"/>
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/class.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/forms.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/typography.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/template.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/theme.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/app/css/default.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/lineicons/css/LineIcons.min.css">
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/fonts/style.min.css">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/_core/_cdn/sidr/css/jquery.sidr.light.min.css">
    <link  href="<?php echo $app['url']; ?>/_core/_cdn/fancybox/css/jquery.fancybox.min.css" rel="stylesheet">
    <link  href="<?php echo $app['url']; ?>/_core/_cdn/fonts/logo/logofont.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $app['url']; ?>/app/cidade/css/style.php">
    <script src="<?php echo $app['url']; ?>/_core/_cdn/jquery/js/jquery.min.js"></script>
    <script src="<?php echo $app['url']; ?>/_core/_cdn/instantclick/js/instantclick.min.js"></script>
    <script src="<?php echo $app['url']; ?>/app/cidade/js/script.php"></script>

    <?php system_header(); ?>

  </head>
  
  <body>

  <div id="main" class="body-cidade">