<?php if(!isset($admin_access)) return; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=_g('moonmoon administration')?></title>
    <link rel="stylesheet" media="screen" type="text/css" href="default.css">
<!--[if lte IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

    <?=@$header_extra ?: ''; ?>

</head>

<body id="<?=@$page_id ?: ''; ?>">
    <div id="page">
        <header>
            <h1>moonmoon</h1>
            <p><a href="../"><?=_g('Back to main page')?></a></p>
        </header>

        <?php if($admin_access == 1) : ?>

        <p class="logout"><a href="logout.php"><?=_g('Logout')?></a></p>
        <nav>
            <ul>
                <li id="nav-feed"><a href="index.php"><?=_g('Feeds')?></a></li>
                <li id="nav-admin"><a href="administration.php"><?=_g('Administration')?></a></li>
            </ul>
        </nav>

        <?php endif; ?>



        <div id="content">

        <?=@$page_content ?: ''; ?>

        </div>
    </div>

<?=@$footer_extra ?: ''; ?>

</body>
</html>
