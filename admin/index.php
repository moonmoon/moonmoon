<?php

require_once __DIR__ . '/../app/app.php';
require_once __DIR__ . '/inc/auth.inc.php';

//Load configuration
$config_file = __DIR__ . '/../custom/config.yml';

if (is_file($config_file)){
    $conf = Spyc::YAMLLoad($config_file);
    $PlanetConfig = new PlanetConfig($conf);
} else {
    die('Config file (custom/config.yml) is missing.');
}

//Instantiate app
$Planet = new Planet($PlanetConfig);

//Load
if (0 < $Planet->loadOpml(__DIR__ . '/../custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}

$everyone     = $Planet->getPeople();
$count_feeds  = count($everyone);
$page_id      = 'admin-feed';
$footer_extra = <<<FRAGMENT
    <script>
    var allCheckboxes = function(status){
        var form = document.getElementById('feedmanage');
        var selectboxes = form.getElementsByTagName('input');
        for (var i=0; i<selectboxes.length; i++){
            if ('checkbox' == selectboxes[i].type){
                selectboxes[i].checked = status;
            }
        }
    }

    window.onload = function(){
        //Select/unselect rows
        var form = document.getElementById('feedmanage');
        var selectboxes = form.getElementsByTagName('input');
        for (var i=0; i<selectboxes.length; i++){
            if ('checkbox' == selectboxes[i].type) {
                selectboxes[i].onchange = function() {
                    var tr = this.parentNode.parentNode;
                    if (this.checked) {
                        tr.className += ' selected';
                    } else {
                        tr.className = tr.className.replace('selected','');
                    }
                }
            }
        }

        var btSelectall = document.getElementById('selectall');
        btSelectall.onclick = function(){
            allCheckboxes('checked');
        }

        var btSelectnone = document.getElementById('selectnone');
        btSelectnone.onclick = function(){
            allCheckboxes('');
        }
    }
    </script>
FRAGMENT;

ob_start();
?>

            <div class="widget">
                <h3><?=_g('Add Feed')?></h3>
                <form action="subscriptions.php" method="post" id="feedimport">
                    <fieldset>
                        <label for="url"><?=_g('Link:')?></label>
                        <input type="text" class="text" name="url" id="url" placeholder="http://" class="text" size="50" />
                        <input type="submit" class="submit add" name="add" value="<?=_g('Add Feed')?>" />
                    </fieldset>
                    <p class="help"><?=_g('Accepted formats are RSS and ATOM. If the link is not a feed, moonmoon will try to autodiscover the feed.')?></p>
                </form>
            </div>

            <div class="widget">
                <h3><?=_g('Manage existing feeds')?></h3>
                <form action="subscriptions.php" method="post" id="feedmanage">
                <p class="action">
                <span class="count"><?php echo sprintf(_g('Number of feeds: %s'), $count_feeds)?></span>
                <input type="submit" class="submit save" name="save" id="save" value="<?=_g('Save changes')?>" />
                <input type="submit" class="submit delete" name="delete" id="delete" value="<?=_g('Delete selected Feeds')?>" />
                </p>
                <p class="select"><?=_g('Select :')?> <a href="javascript:void(0);" id="selectall"><?=_g('All')?></a>, <a href="javascript:void(0);" id="selectnone"><?=_g('None')?></a></p>
                <table>
                    <thead>
                        <tr>
                            <th><span><?=_g('Selection')?></span></th>
                            <th><?=_g('Name')?></th>
                            <th><?=_g('Last entry')?></th>
                            <th><?=_g('Website link')?></th>
                            <th><?=_g('Feed link')?></th>
                            <th><?=_g('Unavailable')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($everyone as $opml_person){
                        $i++;
                        ?>
                        <tr class="<?=($i%2)?'odd':'even'; ?>">
                            <td><input type="checkbox" class="checkbox" name="opml[<?=$i; ?>][delete]" /></td>
                            <td><input type="text" size="10" class="text" name="opml[<?=$i; ?>][name]" value="<?=$opml_person->getName(); ?>" /></td>
                            <td>
                                <?php
                                $items = $opml_person->get_items();
                                if (count($items) > 0) {
                                    echo $items[0]->get_date();
                                } else {
                                    echo _g('Not in cache');
                                }
                                $check_is_down = $opml_person->getIsDown() === '1' ? 'checked="cheched"' : '';
                                ?>
                            </td>
                            <td><input type="text" size="30" class="text" name="opml[<?=$i; ?>][website]" value="<?=$opml_person->getWebsite(); ?>" /></td>
                            <td><input type="text" size="30" class="text" name="opml[<?=$i; ?>][feed]" value="<?=$opml_person->getFeed(); ?>" /></td>
                            <td><input type="checkbox" readonly="readonly" name="opml[<?=$i; ?>][isDown]" <?=$check_is_down?> value="1" /></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </form>
            </div>
<?php
$page_content = ob_get_contents();
ob_end_clean();

$admin_access = 1;
require_once __DIR__ . '/template.php';
