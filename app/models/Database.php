<?php



class Database {

  private static $query	= null;
  private static $sth 	= null;
  private static $dbh		= null;

    public function __construct($dns=null, $user=null, $pass=null)
    {
    try {	// establish connection
      # MySQL with PDO_MYSQL
      self::$dbh = new PDO($dns, $user, $pass,
        array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
      );
    }
    catch(PDOException $e) {
      echo $e->getMessage();
      exit;
    }
    }

  // return db connection object
  public static function dbh()
  {
    return self::$dbh;
  }

  // return PDOStatement or query count
  public static function sth($query=null)
  {
    if ( !empty($query) )
      return self::$dbh->exec($query);

    return self::$sth;
  }

  public static function result($query, array $data = array() )
  {
    $result	= self::select($query, $data, true);
    return empty($result) ? null: end($result);
  }

  // return single result or all results from SELECT query
  public static function select($query, array $data = array(), $single=false)
  {
    $query		= trim($query);
    if ( !empty($data) )// && !isset($data[0]) )
    {
      self::$sth 	= self::$dbh->prepare( $query );
      $rows		= self::$sth->execute( $data );
      //$rows		= self::customExec($query, $data);
    }
    else
      self::$sth	= self::$dbh->query($query);

    $results	= self::$sth->fetchAll( PDO::FETCH_ASSOC );
    if ( empty($results) )
      return array();

    return substr( strtoupper($query), -7, 7 ) == 'LIMIT 1' || $single === true ? $results[0]: $results;
  }

  // returns mysql_num_rows()
  public static function numRows()
  {
    return self::$sth->rowCount();
  }

  // counts total rows in a table, depending on WHERE clausle
  public static function count($table, $where=null)
  {
    $query	= "SELECT COUNT(1) FROM `" . $table . "`" . self::getWhere( $where ) . " LIMIT 1";
    return intval( self::result($query) );
  }

  // INSERT data by associative array $data (or, if not array, it is a query) into $table, returns mysql_insert_id()
  public static function insert($table, array $data = array() )
  {
    if ( empty($data) && strpos( strtolower($table), 'insert') === 0 )	// no data given, $table = query
      self::$dbh->exec($table);
    else
    {
      $query	= "INSERT INTO " . $table . " (`" . implode('`, `', array_keys($data) ) . "`) VALUES (?" . str_repeat(", ?", (count($data)-1)) . ")";
      self::customExec($query, $data);
    }

    return self::$dbh->lastInsertId();
  }

  // UPDATE $data in table $table, based on $where clausule, returns affected rows
  public static function update($table, array $data, $where=null, $limit=null )
  {
    $fields	= array();
    foreach ( array_keys($data) as $key )
      $fields[]	= '`' . $key . '` = ?';

    return self::modify("UPDATE `" . $table . "` SET " . implode(', ', $fields), $where, $limit, $data);
  }

  // DELETE from table $table based on $where clausule
  public static function delete($table, $where, $limit=null)
  {
    return self::modify("DELETE FROM `" . $table . "`", $where, $limit);
  }

  public static function quoteVal( $string )
  {
    return is_array($string) ? '': self::$dbh->quote( $string );
  }

  // private modify method
  private static function modify($query, $where=null, $limit=null, array $data=array() )
  {
    if ( !is_array($where) && ctype_digit('' . $where) )
      $limit	= 1;

    $query	.=	self::getWhere( $where ) . ( empty($limit) ? null: ' LIMIT ' . $limit );

    self::customExec($query, $data);
    return self::$sth->rowCount();
  }

  private static function getWhere($where)
  {
    if ( is_array($where) )
    {
      $temp	= array();
      foreach ( $where as $key => $val )
        $temp[]	= "`" . $key . "` = " . self::$dbh->quote( $val );

      $where	= implode(' AND ', $temp);
    }
    else if ( ctype_digit('' . $where) )
      $where	= 'id=' . intval($where);

    return $where === null ? null: " WHERE " . $where;
  }
  private static function customExec($query, $data)
  {
    if ( !empty($query) && $query != self::$query )
    {
      self::$query	= $query;
      self::$sth 		= self::$dbh->prepare($query);
    }
    return self::$sth->execute( array_values($data) );
  }
}

?>
