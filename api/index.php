<? 
  require_once("../bootstrap.inc");

  function postToHash($post){
    return array(
      id => $post->id,
      content => $post->content,
      user => array(
        name => $post->getUser()->name,
        uri => $post->getUser()->url
      ),
      created_at => $post->created_at
    );
  }

  function decodePost(){
    return json_decode(file_get_contents('php://input'));
  }
  
  $uri = $_SERVER["REQUEST_URI"];
  
	header('Content-type: application/json');
  
  if(preg_match('/^.api.newsfeed/', $uri)){
  	$posts = $Posts->order_by_desc('created_at')->find_many();
    echo json_encode(array_map('postToHash', $posts));
  }
  
  if(preg_match('/^.api.post$/', $uri)){
    $r = decodePost();

		$post = $Posts->create();
		$post->created_at = date("y-m-d g:i:s");
		$post->content = $r->content;
		$post->id = uuid();
		$post->user_id = 1;
		$post->save();
    
    echo json_encode(postToHash($post));
  }
  
  if(preg_match('/^.api.post..+/', $uri)){
    list($null, $api, $action, $id) = split("/", $uri);
    $post = $Posts->where_equal('id', $id)->find_one();
    echo json_encode(postToHash($post));
  }

  # echo phpinfo();
?>