<?php

class CSRF
{
    /** @var string */
    const HMAC_ALGORITHM = 'sha1';

    /** @var string */
    const SESSION_KEY_NAME = '_csrf_key';

    /**
     * Ensure that a CSRF token is valid for a given action.
     *
     * @param  string $token
     * @param  string $action
     * @return bool
     */
    public static function verify($token = '', $action = null)
    {
        if (!is_string($token) || !is_string($action)) {
            return false;
        }

        $known = self::generate($action);
        return hash_equals($known, $token);
    }

    /**
     * Generate a CSRF token for a given action.
     *
     * @param  string $action
     * @throws InvalidArgumentException
     * @return string
     */
    public static function generate($action = null)
    {
        if (!is_string($action)) {
            throw InvalidArgumentException('A valid action must be defined.');
        }
        return hash_hmac(self::HMAC_ALGORITHM, $action, self::getKey());
    }

    /**
     * Get HMAC key.
     *
     * @return string
     */
    public static function getKey()
    {
        if (empty($_SESSION[self::SESSION_KEY_NAME])) {
            $_SESSION[self::SESSION_KEY_NAME] = random_bytes(16);
        }
        return $_SESSION[self::SESSION_KEY_NAME];
    }
}
