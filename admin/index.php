<?php

require_once dirname(__FILE__) . '/inc/auth.inc.php';
require_once dirname(__FILE__) . '/../app/app.php';

//Load configuration
$config_file = dirname(__FILE__) . '/../custom/config.yml';

if (is_file($config_file)){
    $conf = Spyc::YAMLLoad($config_file);
    $PlanetConfig = new PlanetConfig($conf);
} else {
    die('Config file (custom/config.yml) is missing.');
}

//Instantiate app
$Planet = new Planet($PlanetConfig);

//Load
if (0 < $Planet->loadOpml(dirname(__FILE__) . '/../custom/people.opml')) {
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
                <h3>Add a new feed</h3>
                <form action="subscriptions.php" method="post" id="feedimport">
                    <fieldset>
                        <label for="url">Link:</label>
                        <input type="text" class="text" name="url" id="url" placeholder="http://" class="text" size="50" />
                        <input type="submit" class="submit add" name="add" value="Add feed" />
                    </fieldset>
                    <p class="help">Accepted formats are RSS and ATOM. If the link is not a feed, moonmoon will try to autodiscover the feed.</p>
                </form>
            </div>

            <div class="widget">
                <h3>Manage existing feeds</h3>
                <form action="subscriptions.php" method="post" id="feedmanage">
                <p class="action">
                <span class="count">Number of feeds: <?=$count_feeds?></span>
                <input type="submit" class="submit save" name="save" id="save" value="Save changes" />
                <input type="submit" class="submit delete" name="delete" id="delete" value="Delete selected" />
                </p>
                <p class="select">Select : <a href="javascript:void(0);" id="selectall">All</a>, <a href="javascript:void(0);" id="selectnone">None</a></p>
                <table>
                    <thead>
                        <tr>
                            <th><span>Selection</span></th>
                            <th>Name</th>
                            <th>Last entry</th>
                            <th>Website link</th>
                            <th>Feed link</th>
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
                                    echo "Not in cache";
                                }
                                ?>
                            </td>
                            <td><input type="text" size="30" class="text" name="opml[<?=$i; ?>][website]" value="<?=$opml_person->getWebsite(); ?>" /></td>
                            <td><input type="text" size="30" class="text" name="opml[<?=$i; ?>][feed]" value="<?=$opml_person->getFeed(); ?>" /></td>
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
require_once dirname(__FILE__) . '/template.php';
