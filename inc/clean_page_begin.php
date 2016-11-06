<?php 
/* Clean page begin file  */
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Site metadata / Page title -->
    <meta name="description" content="Test site">
    <meta name="author" content="Jack Hansard">
    <title><?php echo isset($page_title) ? $page_title : SITE_BRAND; ?></title>

    <!-- Bootstrap core CSS -->
    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.min.css">

    <!-- Icon fonts -->
    <link type="text/css" rel="stylesheet" href="/assets/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/css/ionicons.min.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo STYLE_ROOT; ?>/assessment.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="/assets/img/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/img/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/img/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/img/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/img/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/img/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/img/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/assets/img/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon-16x16.png">
    <link rel="manifest" href="assets/manifest.json">
    <meta name="msapplication-TileColor" content="#F0AD4E">
    <meta name="msapplication-TileImage" content="/assets/img/ms-icon-144x144.png">
    <meta name="theme-color" content="#F0AD4E">
</head>
<body data-spy="scroll" data-target=".bd-sidenav-active">