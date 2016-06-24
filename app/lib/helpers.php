<?php

/**
 * Redirect the user to a page.
 *
 * @param string $location
 */
function redirect($location = 'index.php')
{
  header('Location: '.$location);
  die();
}
