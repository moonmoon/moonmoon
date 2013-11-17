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
                    "title" TEXT, 
                    "author" TEXT,
                    "content" TEXT,
                    "feed_url" TEXT
                );';
            $db->query($query);

            $query_index = '
                CREATE INDEX "feed_url_index" ON "items" (feed_url);
            ';
            $db->query($query_index);
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
    public function getAll($where = array())
    {
        $query = "SELECT guid, permalink, date, title, author, content, feed_url as feedUrl FROM items";
        if (count($where)) {
            $query.= " WHERE " . join($where, " AND ");
        }
        $query.= " ORDER BY date DESC";
        $sth = $this->db->query($query);

        $out = array();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $out[] = new PlanetItem($row);
        }

        return $out;
    }

    /**
     * Get items for a given feed URL
     * @param String Feed URL
     * @return Array All items ordered by date
     */
    public function getItemsByFeed($feed_url)
    {
        return $this->getAll(
            array(
                'feed_url = "' . $feed_url . '"'
            )
        );
    }

    /**
     * Delete items for a given feed
     */
    public function deleteItemsByFeed($feed_url) {
        $query = 'DELETE FROM items WHERE feed_url = ?';
        $sth = $this->db->prepare($query);
        $sth->execute(array($feed_url));
    }
}
