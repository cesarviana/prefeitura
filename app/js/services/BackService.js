app.factory('backService', ['$http', function($http){
	
	var URL = 'http://localhost/slimproj/prefeitura/api/index.php/';

	function getAll(what){
		return $http.get( URL + what ).then();
	}

	function post( what, toPost ){
		return $http.post( URL + what, toPost );
	}

	function remove( what, codes ){
		console.log( codes );
		return $http.delete( URL + what + '/' + codes.join() );
	}

	return {
		getAll : getAll,
		post : post,
		remove : remove
	};
	
}]);