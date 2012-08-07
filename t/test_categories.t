<?php

require realpath(__DIR__ . '/../app/lib/testmore.php');
require realpath(__DIR__ . '/../app/classes/Planet.class.php');
require realpath(__DIR__ . '/../app/classes/PlanetConfig.php');

plan(6);

class FoolCategory {
    var $name = null;
    function __construct($name) { $this->name = $name; }
    function get_label() { return $this->name; }
}

class FoolItem
{
    function __construct($categories)
    {
        foreach ($categories as $c)
            $this->categories[] = new FoolCategory($c);
    }

    function get_categories() { return $this->categories; }
}

$items = array(
    new FoolItem(array('catA', 'catB', 'catC')),
    new FoolItem(array('catB')),
    new FoolItem(array('catA')),
    new FoolItem(array('catC'))
);

$categories = 'catA';

$p = new Planet();

$new_items = $p->_filterItemsByCategory($items, null);
is(count($new_items), count($items), 'Filter with null category.');

$new_items = $p->_filterItemsByCategory($items, ' ');
is(count($new_items), count($items), 'Filter with empty category.');

$new_items = $p->_filterItemsByCategory($items, 'catA');
is(count($new_items), 2, 'Filter with one category.');

$new_items = $p->_filterItemsByCategory($items, 'catC');
is(count($new_items), 2, 'Filter with one category.');

$new_items = $p->_filterItemsByCategory($items, 'catB,catC');
is(count($new_items), 3, 'Filter with two categories.');

$new_items = $p->_filterItemsByCategory($items, 'catD');
is(count($new_items), 0, 'Filter with a non-used category.');


