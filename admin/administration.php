<?php

require_once dirname(__FILE__) . '/inc/auth.inc.php';
require_once dirname(__FILE__) . '/../app/lib/lib.opml.php';

$opml         = OpmlManager::load(dirname(__FILE__) . '/../custom/people.opml');
$opml_people  = $opml->getPeople();
$page_id      = 'admin-admin';
$header_extra = <<<"HTML"
    <script>
    window.onload = function(){
        var formManage = document.getElementById('frmPurge');
        formManage.onsubmit = function(){
            return confirm('Are you sure you want to purge the cache?');
        }
    }
    </script>

HTML;

$page_content = <<<"FRAGMENT"

            <div class="widget">
                <h3>Clear cache</h3>
                <form action="purgecache.php" method="post" id="frmPurge">
                    <p><label>Clear cache:</label><input type="submit" class="submit delete" name="purge" id="purge" value="Clear" /></p>
                    <p class="help">Clearing the cache will make moonmoon reload all feeds.</p>
                </form>
            </div>

            <div class="widget">
                <h3>Change administrator password</h3>
                <form action="changepassword.php" method="post" id="frmPassword">
                    <p><label for="password">New password:</label> <input type="password" class="text" value="" name="password" id="password" size="20" /> <input type="submit" class="submit delete" name="changepwd" id="changepwd" value="Change password" /></p>
                </form>
            </div>

FRAGMENT;

$footer_extra = '';
$admin_access = 1;
require_once dirname(__FILE__) . '/template.php';
