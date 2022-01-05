<?php

require_once __DIR__ . '/../app/app.php';
require_once __DIR__ . '/inc/auth.inc.php';


$opml         = OpmlManager::load(__DIR__ . '/../custom/people.opml');
$opml_people  = $opml->getPeople();
$page_id      = 'admin-admin';
$header_extra = <<<"HTML"
    <script>
    window.onload = function(){
        var formManage = document.getElementById('frmPurge');
        formManage.onsubmit = function(){
            return confirm("{$l10n->getString('Are you sure you want to purge the cache?')}");
        }
    }
    </script>

HTML;

$repo_url = 'https://github.com/moonmoon/moonmoon';
$releases_url = "$repo_url/releases";
$link_url = "<a href='$releases_url'>$releases_url</a>";
$version_action = str_replace('%s', $link_url, $l10n->getString('Check for a more recent release from %s.'));

$page_content = <<<"FRAGMENT"

            <div class="widget">
                <h3>{$l10n->getString('Clear cache')}</h3>
                <form action="purgecache.php" method="post" id="frmPurge">
                    <input type="hidden" value="{$csrf->generate('frmPurge')}" name="_csrf">
                    <p><label>{$l10n->getString('Clear cache:')}</label><input type="submit" class="submit delete" name="purge" id="purge" value="{$l10n->getString('Clear')}" /></p>
                    <p class="help">{$l10n->getString('Clearing the cache will make moonmoon reload all feeds.')}</p>
                </form>
            </div>

            <div class="widget">
                <h3>{$l10n->getString('Change administrator password')}</h3>
                <form action="changepassword.php" method="post" id="frmPassword">
                    <input type="hidden" value="{$csrf->generate('frmPassword')}" name="_csrf">
                    <p><label for="password">{$l10n->getString('New password:')}</label> <input type="password" class="text" value="" name="password" id="password" size="20" /> <input type="submit" class="submit delete" name="changepwd" id="changepwd" value="{$l10n->getString('Change password')}" /></p>
                </form>
            </div>

            <div class="widget">
                <h3>{$l10n->getString('Version')} $moon_version </h3>
                <form>
                <p>{$version_action}</p>
                </form>
            </div>
FRAGMENT;

$footer_extra = '';
$admin_access = 1;
require_once __DIR__ . '/template.php';
