<?php

/**
 * PlanetItemStorage
 */
class PlanetItemStorage
{
    private $db;

    /**
     * PlanetItemStorage constructor
     *
     * Connect to given SQLite database. If the database doesn't exists,
     * it is created.
     *
     * @param string $filepath Sqlite3 database filepath
     */
    public function __construct($filepath)
    {
        $sqliteAvailable = extension_loaded('pdo_sqlite');
        $noDatabase = !is_file($filepath);

        if ($sqliteAvailable && $noDatabase) {
            $dbh = new PDO('sqlite:' . $filepath);

            //Create tables if needed
            if ($noDatabase) {
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
                $dbh->query($query);

                $query_index = '
                    CREATE INDEX "feed_url_index" ON "items" (feed_url);
                ';
                $dbh->query($query_index);
            }

            $this->db = $dbh;
        } else {
            $this->db = new PDO('sqlite:' . $filepath);
        }
    }

    /**
     * Save an item to database
     *
     * @param PlanetItem $item Item to be saved
     *
     * @todo return value
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
     * Fetch items from database
     *
     * @param string[] $where Array of where clauses
     * @param int $limit Number of items to fetch
     * @param int $offset Offet for fetching items
     * @return PlanetItem[] Array of PlanetItem ordered by date (most recent first)
     *
     * @todo should have feeds as parameter
     */
    public function getAll($where = array(), $limit = null, $offset = null)
    {
        $query = "SELECT guid, permalink, date, title, author, content, feed_url as feedUrl FROM items";
        if (count($where)) {
            $query.= " WHERE " . join($where, " AND ");
        }
        $query.= " ORDER BY date DESC";
        if ($limit) {
            $limit = (int) $limit;
            $query.= " LIMIT $limit";
            if ($offset) {
                $offset = (int) $offset;
                $query.= " OFFSET $offset";
            }
        }
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
