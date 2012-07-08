<?php

/**
 * PlanetItemStorage
 */
class PlanetItemStorage
{
    private $db;

    /**
     * PlanetItemStorage constructor
     * @param string $filepath Sqlite3 database filepath
     */
    public function __construct($db)
    {
        $this->db = new PDO('sqlite:' . $db);
    }

    /**
     * initialize
     * Create the sqlite database
     * @param string $filepath Database file path
     *
     * @return PDO Newly created database
     */
    public static function initialize($filepath)
    {
        $db = null;
        $sqliteAvailable = extension_loaded('pdo_sqlite');
        $noDatabase = !is_file($filepath);

        if ($sqliteAvailable && $noDatabase) {
            $db = new PDO('sqlite:' . $filepath);
            $query = '
                CREATE TABLE "items" (
                    "guid" TEXT PRIMARY KEY  NOT NULL ,
                    "permalink" TEXT,
                    "date" DATETIME NOT NULL ,
                    "title" TEXT, "author" TEXT,
                    "content" TEXT,
                    "feed_url" text
                );';
            $db->query($query);
        }
        return $db;
    }

    /**
     * save
     * Save one item to database
     * @param PlanetItem $item
     */
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
        }
    }

    /**
     * getAll
     * Get all items from database
     * @return Array All items ordered by date
     */
    public function getAll()
    {
        $query = "SELECT * FROM items ORDER BY date DESC";
        $sth = $this->db->query($query);
        return $sth->fetchAll();
    }
}
