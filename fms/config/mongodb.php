<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------------
| This file will contain the settings needed to access your Mongo database.
|
|
| ------------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| ------------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['write_concerns'] Default is 1: acknowledge write operations.  ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['journal'] Default is TRUE : journal flushed to disk. ref(http://php.net/manual/en/mongo.writeconcerns.php)
|	['read_preference'] Set the read preference for this connection. ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|	['read_preference_tags'] Set the read preference for this connection.  ref (http://php.net/manual/en/mongoclient.setreadpreference.php)
|
| The $config['mongo_db']['active'] variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
*/

$config['mongo_db']['active'] = 'test';

// $config['mongo_db']['default']['no_auth'] = FALSE;
// $config['mongo_db']['default']['hostname'] = 'dds-bp16b9155fd3cdd42.mongodb.rds.aliyuncs.com';
// $config['mongo_db']['default']['port'] = '3717';
// $config['mongo_db']['default']['username'] = 'sunshichao';
// $config['mongo_db']['default']['password'] = '19860127s';
// $config['mongo_db']['default']['database'] = 'reading';
// $config['mongo_db']['default']['db_debug'] = TRUE;
// $config['mongo_db']['default']['return_as'] = 'array';
// $config['mongo_db']['default']['write_concerns'] = (int)1;
// $config['mongo_db']['default']['journal'] = TRUE;
// $config['mongo_db']['default']['read_preference'] = NULL;
// $config['mongo_db']['default']['read_preference_tags'] = NULL;

$config['mongo_db']['test']['no_auth'] = FALSE;
$config['mongo_db']['test']['hostname'] = '127.0.0.1';
$config['mongo_db']['test']['port'] = '27017';
$config['mongo_db']['test']['username'] = '';
$config['mongo_db']['test']['password'] = '';
$config['mongo_db']['test']['database'] = 'test';
$config['mongo_db']['test']['db_debug'] = TRUE;
$config['mongo_db']['test']['return_as'] = 'array';
$config['mongo_db']['test']['write_concerns'] = (int)1;
$config['mongo_db']['test']['journal'] = TRUE;
$config['mongo_db']['test']['read_preference'] = NULL;
$config['mongo_db']['test']['read_preference_tags'] = NULL;

// Persistant connections
$config['mongo_db']['test']['mongo_persist'] = TRUE;
$config['mongo_db']['test']['mongo_persist_key'] = 'ci_mongo_persist';


// When you run an insert/update/delete how sure do you want to be that the database has received the query? // safe = the database has receieved and executed the query // fysnc = as above + the change has been committed to harddisk <- NOTE: will introduce a performance penalty 
$config['mongo_db']['test']['mongo_query_safety'] = 'safe';

// If you are having problems connecting try changing this to TRUE
$config['mongo_db']['test']['host_db_flag'] = FALSE;

/*=================================================================*/
$config['mongo_db']['yxdata']['no_auth'] = FALSE;
$config['mongo_db']['yxdata']['hostname'] = '127.0.0.1';
$config['mongo_db']['yxdata']['port'] = '27017';
$config['mongo_db']['yxdata']['username'] = '';
$config['mongo_db']['yxdata']['password'] = '';
$config['mongo_db']['yxdata']['database'] = 'yxdata';
$config['mongo_db']['yxdata']['db_debug'] = TRUE;
$config['mongo_db']['yxdata']['return_as'] = 'array';
$config['mongo_db']['yxdata']['write_concerns'] = (int)1;
$config['mongo_db']['yxdata']['journal'] = TRUE;
$config['mongo_db']['yxdata']['read_preference'] = NULL;
$config['mongo_db']['yxdata']['read_preference_tags'] = NULL;

// Persistant connections
$config['mongo_db']['yxdata']['mongo_persist'] = TRUE;
$config['mongo_db']['yxdata']['mongo_persist_key'] = 'ci_mongo_persist';


// When you run an insert/update/delete how sure do you want to be that the database has received the query? // safe = the database has receieved and executed the query // fysnc = as above + the change has been committed to harddisk <- NOTE: will introduce a performance penalty 
$config['mongo_db']['yxdata']['mongo_query_safety'] = 'safe';

// If you are having problems connecting try changing this to TRUE
$config['mongo_db']['yxdata']['host_db_flag'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */


//
//// Generally will be localhost if you're querying from the machine that Mongo is installed on
//$config['mongo_host'] = 'dds-bp16b9155fd3cdd42.mongodb.rds.aliyuncs.com';
//
//// Generally will be 27017 unless you've configured Mongo otherwise
//$config['mongo_port'] = 3717;
//
//// The database you want to work from (required)
//$config['mongo_db'] = 'admin';
//
//// Leave blank if Mongo is not running in auth mode
//$config['mongo_user'] = 'root';
//$config['mongo_pass'] = 'Reading2015';
//
//// Persistant connections
//$config['mongo_persist'] = TRUE;
//$config['mongo_persist_key'] = 'ci_mongo_persist';
//
//// Get results as an object instead of an array
//$config['mongo_return'] = 'array'; // Set to object
//
//// When you run an insert/update/delete how sure do you want to be that the database has received the query?
//// safe = the database has receieved and executed the query
//// fysnc = as above + the change has been committed to harddisk <- NOTE: will introduce a performance penalty
//$config['mongo_query_safety'] = 'safe';
//
//// Supress connection error password display
//$config['mongo_supress_connect_error'] = TRUE;
//
//// If you are having problems connecting try changing this to TRUE
//$config['host_db_flag'] = FALSE;
//