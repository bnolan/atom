<? 
	require_once("lib/idiorm.php");
	require_once("lib/paris.php");

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

    class User extends Model{
	}
	$Users = Model::factory('User');

    class Post extends Model{
    	function getUser(){
    		return Model::factory('User')->find_one();
    	}
    }
	$Posts = Model::factory('Post');

	
	function uuid(){
		return uniqid("1234", true);
	}

	function h($text){
		echo htmlspecialchars($text);
	}

	if($_REQUEST['content']){
		// $user = $Users->create();
		// $user->id = 1;
		// $user->name = "Ben Nolan";
		// $user->url = "http://atom.localhost/";
		// $user->save();

		$post = $Posts->create();
		$post->content = $_REQUEST['content'];
		$post->id = uuid();
		$post->user_id = 1;
		$post->save();

		echo "<created />";
		exit();
	}

	$author = $Users->find_one();
	$posts = $Posts->find_many();

	header('Content-type: application/xml');
	echo '<?xml version="1.0" encoding="utf-8"?>'; 
?>
 
<feed xmlns="http://www.w3.org/2005/Atom">
 
        <title><? h($author->name) ?>s Feed</title>
        <link href="<? h($author->url); ?>" rel="self" />
 
 	<? foreach($posts as $post){ ?>
        <entry>
                <title><? h($post->title); ?></title>
                <link href="http://example.org/2003/12/13/atom03" />
                <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-<? h($post->id) ?></id>
                <updated><? h($post->created_at) ?></updated>
                <summary><? h($post->content); ?></summary>
                <author>
                      <name><? h($post->getUser()->name); ?></name>
                      <uri><? h($post->getUser()->url) ?></uri>
                </author>
        </entry>
    <? } ?>

</feed>
