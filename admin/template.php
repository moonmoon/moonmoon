<?php if(!isset($admin_access)) return; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/HTML; charset=UTF-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Language" content="en" />

    <title>moonmoon administration</title>
    <link rel="stylesheet" media="screen" type="text/css" href="default.css" />
    <?=@$header_extra ?: ''; ?>
</head>

<body id="<?=@$page_id ?: ''; ?>">
    <div id="page">
        <div id="header">
            <h1>moonmoon</h1>
            <p><a href="../">Back to main page</a></p>
        </div>

        <?php if($admin_access == 1) : ?>

        <p class="logout"><a href="logout.php">Logout</a></p>
        <ul id="nav">
            <li id="nav-feed"><a href="index.php">Feeds</a></li>
            <li id="nav-admin"><a href="administration.php">Administration</a></li>
        </ul>

        <?php endif; ?>

        <div id="content">

        <?=@$page_content ?: ''; ?>

        </div>
    </div>

<?=@$footer_extra ?: ''; ?>

</body>
</html>
