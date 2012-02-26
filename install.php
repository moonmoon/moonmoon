<?php
include (dirname(__FILE__).'/app/classes/Planet.class.php');

define('CONFIG_FILE', dirname(__FILE__).'/custom/config.yml');
define('PWD_FILE', dirname(__FILE__).'/admin/inc/pwd.inc.php');
define('OPML_FILE',dirname(__FILE__).'/custom/people.opml');

$status = 'install';

if (file_exists(CONFIG_FILE) && file_exists(PWD_FILE)) {
    //Nothing to do, already installed;
    $status = 'installed';
} elseif (isset($_REQUEST['url'])) {
    $save = Array();

    //Save config file
    $config = Array(
        'url' => $_REQUEST['url'],
        'name' => $_REQUEST['title'],
        'items' => 10,
        'shuffle' => 0,
        'refresh' => 240,
        'cache' => 10,
        'nohtml' => 0,
        'postmaxlength' => 0,
        'cachedir' => './cache'
    );
    $planet_config = new PlanetConfig($config);
    $save['config'] = file_put_contents(dirname(__FILE__).'/custom/config.yml', $planet_config->toYaml());

    //Save password
    $save['password'] = file_put_contents(dirname(__FILE__).'/admin/inc/pwd.inc.php', '<?php $login="admin"; $password="'.hash('sha256', $_REQUEST['password']).'"; ?>');

    if (0 != ($save['config'] + $save['password'])) {
        $status = 'installed';
    }
} else {

    //Requirements
    $tests = array(
        'php5' => array(
            'label'=>'Server is running PHP5',
            'solution' => 'Check your server documentation to activate PHP5.'
        ),
        'custom' => array(
            'label' => '<code>./custom</code> is writable',
            'solution' => 'Change the access rights for <code>./custom</code> with CHMOD'
        ),
        'opml' => array(
            'label'=>'<code>./custom/people.opml</code> is writable',
            'solution' => 'Change the access rights for <code>./custom/people.opml</code> with CHMOD'
        ),
        'changepassword' => array(
            'label'=>'Administrator password can be changed',
            'solution' => 'Change the access right for <code>./admin/inc/pwd.inc.php</code> with CHMOD'
        ),
        'cache' => array(
            'label'=>'<code>./cache</code> is writable',
            'solution' => 'Make <code>./cache</code> writable with CHMOD'
        ),
    );

    $tests['php5']['result'] = (5 <= phpversion());
    $tests['custom']['result'] = is_writable(dirname(__FILE__).'/custom');
    $tests['opml']['result'] = is_writable(dirname(__FILE__).'/custom/people.opml');
    $tests['changepassword']['result'] = is_writable(dirname(__FILE__).'/admin/inc/pwd.inc.php');
    $tests['cache']['result'] = is_writable(dirname(__FILE__).'/cache');

    $bInstallOk = true;
    $strInstall = '';
    $strRecommendation = '';
    foreach ($tests as $test) {
        $bInstallOk = $bInstallOk && $test['result'];
        $strInstall .= "
        <tr>
            <td>".$test['label']."</td>
            <td>".(($test['result'])?'<span class="ok">OK</span>':'<span class="fail">FAIL</span>')."</td>
        </tr>";
        if (!$test['result']) {
            $strRecommendation .= '<li>'.$test['solution'].'</li>';
        }
    }

    if ($bInstallOk) {
        $status = 'install';
    } else {
        $status = 'error';
    }
}
header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <title>moonmoon install</title>
    <style type="text/css">
    body {
        font: normal 1em sans-serif;
    }

    .section {
        width: 500px;
        margin: 0 auto;
    }

    /* Error */
    span.ok {
        color: #090;
    }
    span.fail {
        color: #900;
        font-weight: bold;
    }
    th {
        text-align: left;
    }

    /* Install */
    .field label {
        display: block;
    }

    .submit {
        font-size: 2em;
    }

    /* Installed */
    </style>
</head>

<body>
<div class="section">
    <h1>moonmoon installation</h1>

    <?php if ('error' == $status) : ?>
    <div id="compatibility">
        <h2>Sorry, your server is not compatible with moonmoon.</h2>

        <h3>Your server does not fulfill the requirements</h3>
        <table>
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $strInstall ?>
            </tbody>
        </table>

        <h3>Troubleshooting</h3>
        <p>To install moonmoon, try the following changes:</p>
        <ul>
            <ul><?php echo $strRecommendation; ?></ul>
        </ul>
    </div>

    <?php elseif ('install' == $status) : ?>
    <div>
        <form method="post" action="">
            <fieldset>
                <input type="hidden" id="url" name="url" value="" readonly="readonly"/>
                <script>
                <!--
                document.forms[0].elements[1].value = document.URL.replace('install.php','');
                -->
                </script>

                <p class="field">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="My website"/>
                </p>
                <!--
                <p class="field">
                    <label>Administrator login:</label> <code>admin</code>
                </p>
                -->
                <p class="field">
                    <label for="password">Administrator password:</label>
                    <input type="text" id="password" name="password" class="text password" value="admin" />
                </p>
                <p>
                    <input type="submit" class="submit" value="Install"/>
                </p>
            </fieldset>
        </form>
    </div>

    <?php elseif ('installed' == $status): ?>

    <p>Congratulations! Your moonmoon is ready.</p>
    <h3>What's next?</h3>
    <ol>
        <li>
            <strong>Delete</strong> <code>install.php</code> with your FTP software.
        </li>
        <li>
            Use your password to go to the
            <a href="./admin/">administration panel</a>
        </li>
    </ol>
    <?php endif; ?>
</div>
</body>
</html>
