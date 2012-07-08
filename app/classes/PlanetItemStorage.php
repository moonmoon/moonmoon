<?php

/**
 * Planet item
 */

class PlanetItemStorage
{
    private $db;

    public function __construct($db)
    {
        $this->db = new PDO('sqlite:' . $db);
    }

    public function save($item)
    {
        if ($this->db)
        {
            $data = array(
                $item->get_id(),
                $item->get_permalink(),
                $item->get_date() ? $item->get_date('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
                $item->get_title(),
                $item->get_author()? $item->get_author()->get_name() : '',
                $item->get_content(),
                $item->get_feed()->feed_url
            );

            $query = "
            INSERT OR IGNORE INTO items
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            $sth = $this->db->prepare($query);
            $sth->execute($data);
            error_log(print_r($sth->errorInfo(),1));
        }
    }

    public function getAll() {
        $query = "SELECT * FROM items ORDER BY date DESC";
        $sth = $this->db->query($query);
        return $sth->fetchAll();
    }
}
