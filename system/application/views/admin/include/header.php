<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en-en" lang="en-en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php    if (!(empty($page_title)))
                        echo $page_title." - ";
                    echo CMS_TITLE." ( ".$configs["site_title"]." )"; ?></title>

    <link rel="stylesheet" href="<?php echo base_url(); ?>css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/admin.css" type="text/css" media="screen" />

<?php

    if (isset($head_queue)) {
        foreach ($head_queue as $h) {
            echo $h."\n";
        }
    }

?></head>
<body>
    <div id="admin-wrapper">
        <div id="admin-header">
            <h1 id="admin-site-title"><?php echo CMS_TITLE." ".CMS_VERSION ?> (Working title)</h1>
            <h2 id="admin-site-subtitle"><?php echo CMS_SUBTITLE ?></h2>
        </div> <!-- /header -->
