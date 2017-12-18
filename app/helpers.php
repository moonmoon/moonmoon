<?php

/**
 * Register polyfills for old PHP versions.
 * 
 * This way, the real function will only be called if it
 * is available, and we won't force the use of our own 
 * implementation.
 */
function register_polyfills()
{
    if (!function_exists('hash_equals')) {
        function hash_equals($known_string, $user_string) {
            call_user_func_array('_hash_equals', func_get_args());
        }
    }
}

register_polyfills();

/**
 * Path to the _custom_ directory.
 *
 * @param  string $file Append this filename to the returned path.
 * @return string
 */
function custom_path($file = '')
{
    return __DIR__.'/../custom' . (!empty($file) ? '/'.$file : '');
}

/**
 * Path to the _views_ directory.
 *
 * @param  string $file Append this filename to the returned path.
 * @return string
 */
function views_path($file = '')
{
    return custom_path('views/install.tpl.php');
}

/**
 * Path to the _admin_ directory.
 *
 * @param  string $file Append this filename to the returned path.
 * @return string
 */
function admin_path($file = '')
{
    return __DIR__.'/../admin' . (!empty($file) ? '/'.$file : '');
}

/**
 * Is moonmoon installed?
 *
 * @return bool
 */
function is_installed()
{
    return file_exists(custom_path('config.yml')) && file_exists(custom_path('people.opml'));
}

/**
 * Shortcut to Simplel10n::getString().
 *
 * @param  string $str
 * @param  string $comment
 * @return string
 */
function _g($str, $comment='')
{
    return Simplel10n::getString($str, $comment);
}

/**
 * Reset the moonmoon instance.
 */
function removeCustomFiles()
{
    $toRemove = [
        custom_path('config.yml'),
        custom_path('people.opml'),
        custom_path('people.opml.bak'),
        custom_path('cache')
    ];

    foreach ($toRemove as $path) {
        if (file_exists($path)) {
            unlink($path);
        }
    }
}

/**
 * Compare two strings in a constant-time manner.
 *
 * It returns `true` if both strings are exactly the same
 * (same size and same value).
 *
 * @param  string $known_string
 * @param  string $user_string
 * @return bool
 */
function _hash_equals($known_string = '', $user_string = '')
{
    // In our case, it's not problematic if `$known_string`'s 
    // size leaks, we will only compare password hashes and 
    // CSRF tokens—their size is already somehow public.
    if (!is_string($known_string) || !is_string($user_string) 
         || strlen($known_string) !== strlen($user_string)) {
        return false;
    }

    $ret = 0;    

    // Do not stop the comparison when a difference is found,
    // always completely compare them.
    for ($i = 0; $i < strlen($known_string); $i++) {
        $ret |=  (ord($known_string[$i]) ^ ord($user_string[$i]));
    }   

    return !$ret;
}

