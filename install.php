<?php

// This is an helper function returning an html table row to avoid code duplication
function installStatus($str, $msg, $result) {
    $class = ($result) ? 'ok' : 'fail';
    return '<tr><td>' . $str . '</td><td class="' . $class . '">' . $msg . '</td></tr>';
}

// If the password and config files exist, moonmoon is already installed
if (file_exists(dirname(__FILE__) . '/custom/config.yml')
    && file_exists(dirname(__FILE__) . '/admin/inc/pwd.inc.php')) {
    $status = 'installed';
} elseif (isset($_REQUEST['url'])) {
    require_once dirname(__FILE__) . '/app/app.php';
    $save = array();
    //Save config file
    $config = array(
        'url'           => filter_var($_REQUEST['url'],   FILTER_SANITIZE_ENCODED),
        'name'          => filter_var($_REQUEST['title'], FILTER_SANITIZE_SPECIAL_CHARS),
        'items'         => 10,
        'shuffle'       => 0,
        'refresh'       => 240,
        'cache'         => 10,
        'nohtml'        => 0,
        'postmaxlength' => 0,
        'cachedir'      => './cache'
    );

    $CreatePlanetConfig = new PlanetConfig($config);
    $save['config'] = file_put_contents(dirname(__FILE__).'/custom/config.yml', $CreatePlanetConfig->toYaml());

    //Save password
    $save['password'] = file_put_contents(dirname(__FILE__).'/admin/inc/pwd.inc.php', '<?php $login="admin"; $password="'.md5($_REQUEST['password']).'"; ?>');

    if (0 != ($save['config'] + $save['password'])) {
        $status = 'installed';
    }
} else {

    // Server requirements with advices in case there is something wrong
    $tests = array(
        'php5' => array(
            'file'       => false,
            'label'      => 'Server is running PHP5',
            'solution'   => 'Check your server documentation to activate PHP5.',
        ),
        'custom' => array(
            'file'       => '/custom',
            'label'      => '<code>./custom</code> is writable',
            'solution'   => 'Change the access rights for <code>./custom</code> with CHMOD'
        ),
        'opml' => array(
            'file'       => '/custom/people.opml',
            'label'      => '<code>./custom/people.opml</code> is writable',
            'solution'   => 'Change the access rights for <code>./custom/people.opml</code> with CHMOD'
        ),
        'changepassword' => array(
            'file'       => '/admin/inc/pwd.inc.php',
            'label'      => 'Administrator password can be changed',
            'solution'   => 'Change the access right for <code>./admin/inc/pwd.inc.php</code> with CHMOD'
        ),
        'cache' => array(
            'file'       => '/cache',
            'label'      => '<code>./cache</code> is writable',
            'solution'   => 'Make <code>./cache</code> writable with CHMOD'
        ),
    );

    // We start by malking sure we have PHP5 as a base requirement
    if(phpversion() >= 5) {
        $strInstall = installStatus($tests['php5']['label'], 'OK',true);
        $strRecommendation = '';
    } else {
        $strInstall = installStatus($tests['php5']['label'], 'FAIL',false);
        $strRecommendation = '<li>' . $tests['php5']['solution'] . '</li>';
    }

    // We now test that all required files are writable
    foreach ($tests as $k => $v) {
        if ($tests[$k]['file']) {
            if(is_writable(dirname(__FILE__) . $tests[$k]['file'])) {
                $strInstall .= installStatus($tests[$k]['label'], 'OK', true);
            } else {
                $strInstall .= installStatus($tests[$k]['label'], 'FAIL',false);
                $strRecommendation .= '<li>' . $tests[$k]['solution'] . '</li>';
             }
        }
    }

    // We can now decide if we install moonmoon or not
    $status = ($strRecommendation != '') ? 'error' : 'install';

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>moonmoon install</title>
    <style>
    body {
        font: normal 1em sans-serif;
        width: 500px;
        margin: 0 auto;
    }

    /* Error */
    td.ok {
        color: #090;
    }

    td.fail {
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

    </style>
</head>

<body>
    <h1>moonmoon installation</h1>

    <?php if ($status == 'error') : ?>
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
            <?php echo $strRecommendation; ?>
        </ul>
    </div>

    <?php elseif ($status == 'install') : ?>
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

    <?php elseif ($status =='installed'): ?>

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
</body>
</html>
