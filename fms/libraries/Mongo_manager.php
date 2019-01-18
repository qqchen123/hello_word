<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @desc mongo manager
 * @other 参考Mongo_db.php 进行的封装  适用版本 php7.1
 * @auther wish 
 * @date 2018.8.2
 */
class Mongo_manager 
{
	private $CI;
    private $config_file = 'mongodb';
    
    private $connection;
    private $db;
    private $connection_string;
    
    private $host;
    private $port;
    private $user;
    private $pass;
    private $dbname;
    private $persist;
    private $persist_key;
    private $query_safety = 'safe';
    
    private $selects = array();
    public  $wheres = array(); // Public to make debugging easier
    private $sorts = array();
    
    private $limit = 999999;
    private $offset = 0;

    public $table_name = '';

	/**
	 * @name 构造函数
	 */
	function __construct($param)
	{
		$this->CI =& get_instance();
		$this->table_name = $param['table_name'];

        if (!empty($param['key'])) {
            $this->connection_string($param['key']);
        } else {
            $this->connection_string();
        }
        $this->connect();
	}

	/**
	 * @name 链接mongo
	 */
	private function connect()
	{
		$this->connection = new MongoDB\Driver\Manager($this->connection_string);
		return ($this);
	}

	/**
	 * @name 查询
	 * @param array $filter [key=>value...]
	 * @param array $options 
	 * @return array 
	 */
	public function find()
	{
		$options = $this->build_options();
		$query = new MongoDB\Driver\Query($this->wheres, $options);
        Slog::log($options);
		$cursor = $this->connection->executeQuery($this->dbname . '.' . $this->table_name, $query);
		return $this->as_array($cursor);
	}

    /**
     * @name 查询 一个最新的记录
     * @param array $filter [key=>value...]
     * @param array $options 
     * @return array 
     */
    public function find_one($table_name = '')
    {
        if (empty($table_name)) {
            $table_name = $this->table_name;
        }
        $this->order_by(['ctime', 'DESC']);
        $options = $this->build_options();
        $query = new MongoDB\Driver\Query($this->wheres, $options);
        $cursor = $this->connection->executeQuery($this->dbname . '.' . $table_name, $query);
        return $this->one_array($this->as_array($cursor));
    }

	/**
	 * @name 插入
	 * @param array $data [key => value]
	 * @return boolean
	 */
	public function insert($data, $table_name = '')
	{
        if (empty($table_name)) {
            $table_name = $this->table_name;
        }
		$bulk = new MongoDB\Driver\BulkWrite;
		$document = array_merge(['_id' => new MongoDB\BSON\ObjectID], $data, ['ctime' => date('Y-m-d H:i:s')]);
		$_id = $bulk->insert($document);

		$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
		$result = $this->connection
			->executeBulkWrite($this->dbname . '.' . $table_name, $bulk, $writeConcern);
		$result = $result->getInsertedCount();
		if ($result) {
			Slog::log('mongo 数据插入成功');
		} else {
			Slog::log('mongo 数据插入失败');
		}
		return $result;
	}

    /**
     * @name 更新
     * @param array $key 匹配条件
     * @praam array $data 更新的内容
     */
    public function update($key, $data)
    {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            $key,
            ['$set' => array_merge($data, ['lutime' => date('Y-m-d H:i:s')])],
            ['multiple' => false, 'upsert' => false]
        );

        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
        $result = $this->connection
            ->executeBulkWrite($this->dbname . '.' . $this->table_name, $bulk, $writeConcern);
        $result = $result->getModifiedCount();
        if ($result) {
            Slog::log('mongo 数据更新成功');
        } else {
            Slog::log('mongo 数据更新失败');
        }
        return $result;
    }


	/**
	 * @name 以数组形式返回
	 * @return array
	 */
	private function as_array($cursor)
	{
		$result = [];
		foreach ($cursor as $document) {
			$tmp = $this->object_to_array($document);
			if (!empty($tmp['_id'])) {
				$tmp['_id'] = !empty($tmp['_id']['oid']) ? $tmp['_id']['oid'] : $tmp['_id']; 
			}
		    $result[] = $tmp;
		}
		return $result;
	}


    private function one_array($result)
    {
        return !empty($result[0]) ? $result[0] : [];
    }

	/**
	 * @name 对象转数组
	 * @param object $e
	 * @return array
	 */
	private function object_to_array($e)
	{
	    $e = (array)$e;
	    foreach($e as $k => $v){
	        if( gettype($v) == 'resource' ) return;
	        if( gettype($v) == 'object' || gettype($v) == 'array' )
	            $e[$k] = (array)$this->object_to_array($v);
	    }
	    return $e;
	}

	/** 
	 * @name 处理配置文件
	 * @return array
	 */
	public function build_options()
	{
		//deal select 
		$select = [];
		if (!empty($this->selects)) {
			$options['projection'] = $this->selects;
		} else {
			$options['projection'] = ['_id' => 1];
		}

		if (!empty($this->sorts)) {
			$options['sort'] = $this->sorts;
		}
		
        if (!empty($this->limit)) {
            $options['limit'] = $this->limit;
        }

         if (!empty($this->offset)) {
            $options['skip'] = $this->offset;
        }

		return $options;
	}

	/**
    *    --------------------------------------------------------------------------------
    *    WHERE PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents based on these search parameters.  The $wheres array should 
    *    be an associative array with the field as the key and the value as the search
    *    criteria.
    *
    *    @usage : $this->mongo_db->where(array('foo' => 'bar'))->get('foobar');
    */
    
    public function where($wheres = array())
    {
        foreach ($wheres as $wh => $val)
        {
             $this->wheres[$wh] = $val;
        }
        return ($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    OR_WHERE PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field may be something else
    *
    *    @usage : $this->mongo_db->or_where(array( array('foo'=>'bar', 'bar'=>'foo' ))->get('foobar');
    */
    
    public function or_where($wheres = array())
    {
        if (count($wheres) > 0)
        {
            if ( ! isset($this->wheres['$or']) || ! is_array($this->wheres['$or']))
            {
                $this->wheres['$or'] = array();
            }
            
            foreach ($wheres as $wh => $val)
            {
                $this->wheres['$or'][] = array($wh=>$val);
            }
        }
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE_IN PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is in a given $in array().
    *
    *    @usage : $this->mongo_db->where_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
    */
    
    public function where_in($field = "", $in = array())
    {
        $this->_where_init($field);
        $this->wheres[$field]['$in'] = $in;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE_IN_ALL PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is in all of a given $in array().
    *
    *    @usage : $this->mongo_db->where_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
    */
    
    public function where_in_all($field = "", $in = array())
    {
        $this->_where_init($field);
        $this->wheres[$field]['$all'] = $in;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE_NOT_IN PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is not in a given $in array().
    *
    *    @usage : $this->mongo_db->where_not_in('foo', array('bar', 'zoo', 'blah'))->get('foobar');
    */
    
    public function where_not_in($field = "", $in = array())
    {
        $this->_where_init($field);
        $this->wheres[$field]['$nin'] = $in;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE GREATER THAN PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is greater than $x
    *
    *    @usage : $this->mongo_db->where_gt('foo', 20);
    */
    
    public function where_gt($field = "", $x)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$gt'] = $x;
        return ($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    WHERE GREATER THAN OR EQUAL TO PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is greater than or equal to $x
    *
    *    @usage : $this->mongo_db->where_gte('foo', 20);
    */
    
    public function where_gte($field = "", $x)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$gte'] = $x;
        return($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    WHERE LESS THAN PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is less than $x
    *
    *    @usage : $this->mongo_db->where_lt('foo', 20);
    */
    
    public function where_lt($field = "", $x)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$lt'] = $x;
        return($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    WHERE LESS THAN OR EQUAL TO PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is less than or equal to $x
    *
    *    @usage : $this->mongo_db->where_lte('foo', 20);
    */
    
    public function where_lte($field = "", $x)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$lte'] = $x;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE BETWEEN PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is between $x and $y
    *
    *    @usage : $this->mongo_db->where_between('foo', 20, 30);
    */
    
    public function where_between($field = "", $x, $y)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$gte'] = $x;
        $this->wheres[$field]['$lte'] = $y;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE BETWEEN AND NOT EQUAL TO PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is between but not equal to $x and $y
    *
    *    @usage : $this->mongo_db->where_between_ne('foo', 20, 30);
    */
    
    public function where_between_ne($field = "", $x, $y)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$gt'] = $x;
        $this->wheres[$field]['$lt'] = $y;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE NOT EQUAL TO PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents where the value of a $field is not equal to $x
    *
    *    @usage : $this->mongo_db->where_not_equal('foo', 1)->get('foobar');
    */
    
    public function where_ne($field = '', $x)
    {
        $this->_where_init($field);
        $this->wheres[$field]['$ne'] = $x;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    WHERE NOT EQUAL TO PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Get the documents nearest to an array of coordinates (your collection must have a geospatial index)
    *
    *    @usage : $this->mongo_db->where_near('foo', array('50','50'))->get('foobar');
    */
    
    function where_near($field = '', $co = array())
    {
        $this->__where_init($field);
        $this->where[$what]['$near'] = $co;
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    LIKE PARAMETERS
    *    --------------------------------------------------------------------------------
    *    
    *    Get the documents where the (string) value of a $field is like a value. The defaults
    *    allow for a case-insensitive search.
    *
    *    @param $flags
    *    Allows for the typical regular expression flags:
    *        i = case insensitive
    *        m = multiline
    *        x = can contain comments
    *        l = locale
    *        s = dotall, "." matches everything, including newlines
    *        u = match unicode
    *
    *    @param $enable_start_wildcard
    *    If set to anything other than TRUE, a starting line character "^" will be prepended
    *    to the search value, representing only searching for a value at the start of 
    *    a new line.
    *
    *    @param $enable_end_wildcard
    *    If set to anything other than TRUE, an ending line character "$" will be appended
    *    to the search value, representing only searching for a value at the end of 
    *    a line.
    *
    *    @usage : $this->mongo_db->like('foo', 'bar', 'im', FALSE, TRUE);
    */
    
    public function like($field = "", $value = "", $flags = "i", $enable_start_wildcard = TRUE, $enable_end_wildcard = TRUE)
     {
         $field = (string) trim($field);
         $this->where_init($field);
         $value = (string) trim($value);
         $value = quotemeta($value);
         
         if ($enable_start_wildcard !== TRUE)
         {
             $value = "^" . $value;
         }
         
         if ($enable_end_wildcard !== TRUE)
         {
             $value .= "$";
         }
         
         $regex = "/$value/$flags";
         $this->wheres[$field] = new MongoRegex($regex);
         return ($this);
     }

    /**
    *    --------------------------------------------------------------------------------
    *    SELECT FIELDS
    *    --------------------------------------------------------------------------------
    *
    *    Determine which fields to include OR which to exclude during the query process.
    *    Currently, including and excluding at the same time is not available, so the 
    *    $includes array will take precedence over the $excludes array.  If you want to 
    *    only choose fields to exclude, leave $includes an empty array().
    *
    *    @usage: $this->mongo_db->select(array('foo', 'bar'))->get('foobar');
    */
    
    public function select($includes = array(), $excludes = array())
    {
        if ( ! is_array($includes))
        {
            $includes = array();
        }
         
        if ( ! is_array($excludes))
        {
            $excludes = array();
        }
         
        if ( ! empty($includes))
        {
            foreach ($includes as $col)
            {
                $this->selects[$col] = 1;
            }
        }
        else
        {
            foreach ($excludes as $col)
            {
                $this->selects[$col] = 0;
            }
        }
        return ($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    ORDER BY PARAMETERS
    *    --------------------------------------------------------------------------------
    *
    *    Sort the documents based on the parameters passed. To set values to descending order,
    *    you must pass values of either -1, FALSE, 'desc', or 'DESC', else they will be
    *    set to 1 (ASC).
    *
    *    @usage : $this->mongo_db->where_between('foo', 20, 30);
    */
    
    public function order_by($fields = array())
    {
        foreach ($fields as $col => $val)
        {
            if ($val == -1 || $val === FALSE || strtolower($val) == 'desc')
            {
                $this->sorts[$col] = -1; 
            }
            else
            {
                $this->sorts[$col] = 1;
            }
        }
        return ($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    LIMIT DOCUMENTS
    *    --------------------------------------------------------------------------------
    *
    *    Limit the result set to $x number of documents
    *
    *    @usage : $this->mongo_db->limit($x);
    */
    
    public function limit($x = 99999)
    {
        if ($x !== NULL && is_numeric($x) && $x >= 1)
        {
            $this->limit = (int) $x;
        }
        return ($this);
    }
    
    /**
    *    --------------------------------------------------------------------------------
    *    OFFSET DOCUMENTS
    *    --------------------------------------------------------------------------------
    *
    *    Offset the result set to skip $x number of documents
    *
    *    @usage : $this->mongo_db->offset($x);
    */
    
    public function offset($x = 0)
    {
        if ($x !== NULL && is_numeric($x) && $x >= 1)
        {
            $this->offset = (int) $x;
        }
        return ($this);
    }

    /**
    *    --------------------------------------------------------------------------------
    *    WHERE INITIALIZER
    *    --------------------------------------------------------------------------------
    *
    *    Prepares parameters for insertion in $wheres array().
    */
    
    private function _where_init($param)
    {
        if ( ! isset($this->wheres[$param]))
        {
            $this->wheres[ $param ] = array();
        }
    }

















	/**
    *    --------------------------------------------------------------------------------
    *    BUILD CONNECTION STRING
    *    --------------------------------------------------------------------------------
    *
    *    Build the connection string from the config file.
    */
    
    private function connection_string($key = '') 
    {
        $this->CI->config->load($this->config_file);
        $mongo_config_tmp = $this->CI->config->item('mongo_db');
        $key = empty($key) ? $mongo_config_tmp['active'] : $key;
        $mongo_config = $mongo_config_tmp[$key];

        $this->host = trim($mongo_config['hostname']);
        $this->port = trim($mongo_config['port']);
        $this->user = trim($mongo_config['username']);
        $this->pass = trim($mongo_config['password']);
        $this->dbname = trim($mongo_config['database']);
        $this->persist = trim($mongo_config['mongo_persist']);
        $this->persist_key = trim($mongo_config['mongo_persist_key']);
        $this->query_safety = trim($mongo_config['mongo_query_safety']);
        $dbhostflag = (bool)$mongo_config['host_db_flag'];
        
        $connection_string = "mongodb://";
        
        if (empty($this->host))
        {
            show_error("The Host must be set to connect to MongoDB", 500);
        }
        
        if (empty($this->dbname))
        {
            show_error("The Database must be set to connect to MongoDB", 500);
        }
        
        if ( ! empty($this->user) && ! empty($this->pass))
        {
            $connection_string .= "{$this->user}:{$this->pass}@";
        }
        
        if (isset($this->port) && ! empty($this->port))
        {
            $connection_string .= "{$this->host}:{$this->port}";
        }
        else
        {
            $connection_string .= "{$this->host}";
        }
        
        if ($dbhostflag === TRUE)
        {
            $this->connection_string = trim($connection_string) . '/' . $this->dbname;
        }
        
        else
        {
            $this->connection_string = trim($connection_string);
        }
    }


}