<? 
  require "bootstrap.inc";
  
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
