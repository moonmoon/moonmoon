<?php
if (isset($_POST['password'])) {
    setcookie('auth',md5($_POST['password']));
    header('Location: index.php');
}


$page_content = <<<FRAGMENT
            <form action="" method="post" class="login">
                <fieldset>
                    <p class="field">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password"/>
                        <input type="submit" class="submit" value="OK"/>
                    </p>
                </fieldset>
            </form>
FRAGMENT;

$footer_extra = <<<FRAGMENT
    <script type="text/javascript">
    <!--
    window.onload = function() {
        document.getElementById('password').focus();
    }
    -->
    </script>

FRAGMENT;

$page_id      = 'admin-login';
$admin_access = 0;
require_once dirname(__FILE__) . '/template.php';
