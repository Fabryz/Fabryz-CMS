<?php echo doctype($configs["site_doctype"])."\n"; ?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="<?php echo $configs["site_lang"]; ?>" lang="<?php echo $configs["site_lang"]; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $configs["site_charset"]; ?>" />
    <title><?php

    if (!(empty($page_title)))
        echo $page_title." - ";
    echo $configs["site_title"];

    ?></title>
    <meta name="description" content="<?php echo $meta_description; ?>" />
    <meta name="keywords" content="<?php echo $meta_keywords; ?>" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" media="screen" />

<?php

    if (isset($head_queue)) {
        foreach ($head_queue as $h) {
            echo $h."\n";
        }
    }

?></head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1 id="site-title"><?php //echo $configs["site_title"];
                                    echo anchor( base_url(), $configs["site_title"]); ?></h1>
<?php  if (!empty($configs["site_subtitle"])) ?>
            <h2 id="site-subtitle"><?php echo $configs["site_subtitle"]; ?></h2>
        </div> <!-- /header -->
