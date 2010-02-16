<?php
require_once dirname(__FILE__).'/inc/auth.inc.php';

include_once(dirname(__FILE__).'/../app/classes/Planet.class.php');

//Load configuration
if (is_file(dirname(__FILE__).'/../custom/config.yml')){
    $conf = Spyc::YAMLLoad(dirname(__FILE__).'/../custom/config.yml');
    $PlanetConfig = new PlanetConfig($conf);
} else {
    die('Config file (custom/config.yml) is missing.');
}

//Instantiate app
$Planet = new Planet($PlanetConfig);

//Load
if (0 < $Planet->loadOpml(dirname(__FILE__).'/../custom/people.opml')) {
    $Planet->loadFeeds();
    $items = $Planet->getItems();
}

$everyone = $Planet->getPeople();

header('Content-type: text/HTML; charset=UTF-8');
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
</head>

<body id="admin-feed">
    <div id="page">
        <div id="header">
            <h1>moonmoon</h1>
            <p><a href="../">Back to main page</a></p>
        </div>
        
        <?php readfile("inc/nav.inc.php");  ?>

        <div id="content">
            <div class="widget">
                <h3>Add a new feed</h3>
                <form action="subscriptions.php" method="post" id="feedimport">
                    <fieldset>
                        <label for="url">Link:</label>
                        <input type="text" class="text" name="url" id="url" value="http://" class="text" size="50" />
                        <input type="submit" class="submit add" name="add" value="Add feed" />
                    </fieldset>
                    <p class="help">Accepted formats are RSS and ATOM. If the link is not a feed, moonmoon will try to autodiscover the feed.</p>
                </form>
            </div>

            <div class="widget">
                <h3>Manage existing feeds</h3>
                <form action="subscriptions.php" method="post" id="feedmanage">
                <p class="action">
                <span class="count">Number of feeds: <?php echo count($everyone); ?></span> 
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
                        <tr class="<?php echo ($i%2)?'odd':'even'; ?>">
                            <td><input type="checkbox" class="checkbox" name="opml[<?php echo $i; ?>][delete]" /></td>
                            <td><input type="text" size="10" class="text" name="opml[<?php echo $i; ?>][name]" value="<?php echo $opml_person->getName(); ?>" /></td>
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
                            <td><input type="text" size="30" class="text" name="opml[<?php echo $i; ?>][website]" value="<?php echo $opml_person->getWebsite(); ?>" /></td>
                            <td><input type="text" size="30" class="text" name="opml[<?php echo $i; ?>][feed]" value="<?php echo $opml_person->getFeed(); ?>" /></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
    
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
</body>
</html>
