<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?php echo APP_DESC ?>" />
  <meta name="author" content="<?php echo APP_AUTHOR ?>">

  <title><?php echo APP_NAME ?></title>

  <link rel="shortcut icon" type="image/png" href="<?php echo base_url().FAVICON_IMAGE;?>"/>
  
   <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

  <!-- Bootstrap Core Css -->
  <link href="<?php echo base_url('assets/')?>vendor/bootstrap/css/bootstrap.css" rel="stylesheet">

  <!-- Waves Effect Css -->
  <link href="<?php echo base_url('assets/')?>vendor/node-waves/waves.css" rel="stylesheet" />

  <!-- Animation Css -->
  <link href="<?php echo base_url('assets/')?>vendor/animate-css/animate.css" rel="stylesheet" />

  <!-- Bootstrap Select Css -->
  <link href="<?php echo base_url('assets/')?>/vendor/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />


  <!-- Custom Css -->
  <link href="<?php echo base_url('assets/')?>css/admin/style.css" rel="stylesheet">

  <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
  <link href="<?php echo base_url('assets/')?>css/admin/themes/all-themes.css" rel="stylesheet" />


</head>

<body class="theme-blue" onload="menuActive('<?php echo $this->router->fetch_class().'_'.$this->router->fetch_method() ?> ')" >
