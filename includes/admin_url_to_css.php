<?php 
global $action;
if($action != 'generated-invoice'): ?>
<link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="<?=CDN_URL; ?>css/admin/bootstrap.min.css?v=<?=ASSETS_VERSION; ?>">
<!-- fontawesome CSS -->
<link rel="stylesheet" type="text/css" href="<?=CDN_URL ?>fa/css/all.min.css?v=<?=ASSETS_VERSION; ?>" crossorigin="anonymous">
<!-- Our sidebar CSS -->
<?php if(is_mobile() || is_tablet()): ?>
<link rel="stylesheet" href="<?=CDN_URL; ?>css/admin/style3.css?v=<?=ASSETS_VERSION; ?>">
<?php else: ?>
<link rel="stylesheet" href="<?=CDN_URL; ?>css/admin/style2.css?v=<?=ASSETS_VERSION; ?>">
<?php endif; ?>
<!-- Scrollbar Custom CSS -->
<link rel="stylesheet" href="<?=CDN_URL; ?>css/admin/jquery.mCustomScrollbar.min.css?v=<?=ASSETS_VERSION; ?>">
<!-- animate css -->
<link href="<?=CDN_URL; ?>css/admin/animate.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<!-- toastr css -->
<?php endif; ?>
<link href="<?=CDN_URL; ?>css/admin/toastr.min.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<?php if($action != 'generated-invoice'): ?>
<link href="<?=CDN_URL; ?>css/admin/dataTables.min.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet" type="text/css">
<?php endif; ?>
<?php if(($action == 'employee-leaves') || ($action == 'manager-leaves') || ($action == 'admin-apply-leave') || ($action == 'mod-domestic-clients')): ?>
    <link href="<?=CDN_URL; ?>css/admin/summernote.min.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<?php endif; ?>
<!-- lightgallery -->
<link type="text/css" rel="stylesheet" href="<?=CDN_URL;?>lb/css/lightbox.min.css" />

<!-- lightgallery plugins -->
<!-- <link type="text/css" rel="stylesheet" href="<?=CDN_URL;?>css/lg-zoom.min.css" />
<link type="text/css" rel="stylesheet" href="<?=CDN_URL;?>css/lg-thumbnail.min.css" /> -->
<?php if(($action == 'chat') || ($action == 'manager-chat') || ($action == 'admin-chat') || ($action == 'sadmin-chat') || ($action == 'employee-chat')): ?>
    <link href="<?=CDN_URL; ?>css/admin/chat.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<?php endif; ?>
<!-- custom css -->
<link href="<?=CDN_URL; ?>css/admin/main.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<link href="<?=CDN_URL; ?>css/admin/fpage_loader.css?v=<?=ASSETS_VERSION; ?>" rel="stylesheet">
<!-- title x-iccon -->
<link rel="shortcut icon" href="<?=COMPANY_TITLE_LOGO_PATH; ?>" type="image/x-icon">