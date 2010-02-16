<?php
if (isset($_POST['password'])) {
    setcookie('auth',md5($_POST['password']));
    header('Location: index.php');
}
header('Content-type: text/html; charset=UTF-8');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
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
        
        <div id="content">
            <form action="" method="post" class="login">
                <fieldset>
                    <p class="field">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password"/>
                        
                        <input type="submit" class="submit" value="OK"/>
                    </p>
                </fieldset>
            </form>
        </div>
    </div>
    <script type="text/javascript">
    <!--
    window.onload = function() {
        document.getElementById('password').focus();
    }
    -->
    </script>
</body>
</html>