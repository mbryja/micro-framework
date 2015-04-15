<?php
namespace your\namespac\App;

class Database extends \Mysqli
{
    const DATABASE_HOST     = "db host";
    const DATABASE_USERNAME = "db user";
    const DATABASE_PASSWORD = "db password";
    const DATABASE_NAME     = "db name";

    public static $instance = null;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }

        $db = new Database(self::DATABASE_HOST, self::DATABASE_USERNAME, self::DATABASE_PASSWORD, self::DATABASE_NAME);

        if (mysqli_connect_errno()) {
            throw new \Exception("Could not connect to database");
        }

        self::$instance = $db;

        return $db;
    }

    public function query($query)
    {
        $result = parent::query($query);

        if (!$result || $this->error) {
            throw new \Exception("Error " . $this->error . " :: " . str_replace(array("\n", "\t"), " ", $query));
        }

        return $result ?: false;
    }

    public function safeQuote($value)
    {
        if (null === $value) {
            $value = "null";
        } else {
            if ((substr($value, 0, 1) == 0 || !is_numeric($value) || is_infinite($value))) {
                $value = "'" . mysqli_real_escape_string($this, $value) . "'";
            }
        }

        return $value;
    }
}
