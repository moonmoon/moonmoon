<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8"/>
<head>
    <title><?=_g('moonmoon installation')?></title>
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
<h1><?=_g('moonmoon installation')?></h1>

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
                <p class="field">
                    <label for="locale">Language:</label>
                    <select name="locale" id="locale">
                        <option selected="selected" value="en">English</option>
                        <option value="es">Español</option>
                        <option value="fr">Français</option>
                    </select>
                </p>
                <p>
                    <input type="submit" class="submit" value="Install"/>
                </p>
            </fieldset>
        </form>
    </div>

<?php elseif ($status =='installed'): ?>

    <p><?=_g('Congratulations! Your moonmoon is ready.')?></p>
    <h3><?=_g("What's next?")?></h3>
    <ol>
        <li>
            <?=_g('<strong>Delete</strong> <code>install.php</code> with your FTP software.')?>
        </li>
        <li>
            <?=_g('Use your password to go to the <a href="./admin/">administration panel</a>')?>
        </li>
    </ol>
<?php endif; ?>
</body>
</html>
