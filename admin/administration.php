<?php
require_once dirname(__FILE__).'/inc/auth.inc.php';

require_once dirname(__FILE__).'/../app/lib/lib.opml.php';
$opml = OpmlManager::load(dirname(__FILE__).'/../custom/people.opml');
$opml_people = $opml->getPeople();
?>
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
    <script>
    window.onload = function(){
        var formManage = document.getElementById('frmPurge');
        formManage.onsubmit = function(){
            return confirm('Are you sure you want to purge the cache?');
        }
    }
    </script>
</head>

<body id="admin-admin">
    <div id="page">
        <div id="header">
            <h1>moonmoon</h1>
            <p><a href="../">Back to main page</a></p>
        </div>
        
        <?php readfile("inc/nav.inc.php");  ?>

        <div id="content">
            <div class="widget">
                <h3>Purge cache</h3>
                <form action="purgecache.php" method="post" id="frmPurge">
                    <p><label>Purge cache:</label><input type="submit" class="submit delete" name="purge" id="purge" value="Purge" /></p>
                    <p class="help">Purging the cache will make moonmoon reload all feeds.</p>
                </form>
            </div>
            
            <div class="widget">
                <h3>Change administration password</h3>
                <form action="changepassword.php" method="post" id="frmPassword">
                    <p><label for="password">New password:</label> <input type="password" class="text" value="" name="password" id="password" size="20" /> <input type="submit" class="submit delete" name="changepwd" id="changepwd" value="Change password" /></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>