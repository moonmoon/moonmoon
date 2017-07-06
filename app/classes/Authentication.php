<?php

/**
 * Authentication-related functions.
 *
 * In moonmoon, the authentication was made using a cookie containing the hash
 * of the password of the user. To avoid its bruteforce in the scenario of
 * an attack leading to the stealing of the authentication cookie (XSS), this
 * mechanism has been replaced by a more "classic" one, based on the PHP sessions.
 * In addition, the bcrypt algorithm is now used as hashing function.
 *
 * @license BSD
 */
class Authentication
{
    protected $username = '';
    protected $password = '';

    /**
     * @var string Path to the file containing administrator's credentials
     */
    public $file =  __DIR__.'/../../admin/inc/pwd.inc.php';

    public function __construct()
    {
        session_start();
        $this->readCredentials();
    }

    /**
     * Redirect the user somewhere if the authentication cookie is invalid.
     *
     * @param string $location Page to redirect to if not authenticated
     */
    public function redirectIfNotAuthenticated($location = 'login.php')
    {
        if (!self::isAuthenticated()) {
            redirect($location);
        }
    }

    /**
     * Load the credentials from the file.
     *
     * @see Authentication::$file
     */
    public function readCredentials()
    {
        if (empty($this->username) || empty($this->password)) {
            include $this->file;
            $this->username = $login;
            $this->password = $password;
        }
    }

    /**
     * Is the password still in the old format?
     *
     * MD5 hashes length is 32 characters. This hashing function was
     * used in the last versions of moonmoon but we try to assure backward
     * compatibility for all the installations.
     *
     * @return boolean
     */
    protected function isOldFormat()
    {
        return strlen($this->password) === 32;
    }

    /**
     * Try to authenticate the user using the provided password.
     *
     * @param  string  $providedPassword
     * @return boolean
     */
    public function login($providedPassword)
    {
        // If the hash is still in the old format, replace it by
        // the same password but stored using bcrypt (obviously, we can do
        // it only if the provided password is the right one).
        if ($this->isOldFormat()) {
            if (hash_equals(md5($providedPassword), $this->password)) {
                $this->changePassword($providedPassword);
                $valid = true;
            }
        }

        // New authentication using bcrypt.
        if (password_verify($providedPassword, $this->password)) {
            $valid = true;
        }

        // If the user has been authenticated using one of the two means,
        // keep the username inside the session.
        if ($valid) {
            $_SESSION['user'] = $this->username;
            return true;
        }

        return false;
    }

    /**
     * Change the password of the current user.
     *
     * @param  string $new
     * @return integer
     */
    public function changePassword($new)
    {
        $out = '<?php $login="admin"; $password=\''.password_hash($new, PASSWORD_BCRYPT).'\'; ?>';
        return file_put_contents($this->file, $out);
    }

    /**
     * Is the user authenticated?
     *
     * @return boolean
     */
    public function isAuthenticated()
    {
        return !empty($_SESSION['user']);
    }

    /**
     * Destroy the current session and redirect the user to the login page.
     *
     * The related session file will be removed.
     */
    public function logout()
    {
        session_destroy();
        session_regenerate_id(true);
        redirect('login.php');
        die();
    }
}
