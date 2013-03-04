<?

  class Post extends Model{
  	function getUser(){
  		return Model::factory('User')->find_one();
  	}
  }

  $Posts = Model::factory('Post');
  
?>