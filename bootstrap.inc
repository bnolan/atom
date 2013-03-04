<?
  require_once("lib/idiorm.php");
  require_once("lib/paris.php");

  date_default_timezone_set('UTC');

    // Connect to the demo database file
  ORM::configure('mysql:host=localhost;dbname=atom-dev');
  ORM::configure('username', 'root');

  $db = ORM::get_db();
  
  $db->exec("
      CREATE TABLE IF NOT EXISTS post (
          id VARCHAR(64) PRIMARY KEY, 
          name TEXT, 
          title TEXT,
          content TEXT,
          created_at DATETIME,
          user_id INTEGER 
      );

      CREATE TABLE IF NOT EXISTS user (
          id INTEGER PRIMARY KEY, 
          name TEXT, 
          url TEXT,
          user_id INTEGER 
      );
  ");

  require_once("app/models/post.php");
  require_once("app/models/user.php");

  /* Some helpful functions */
  
	function uuid(){
		return uniqid("1234", true);
	}

	function h($text){
		echo htmlspecialchars($text);
	}


?>