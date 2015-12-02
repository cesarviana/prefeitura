app.factory('backService', ['$http', 'PATHS', function($http, PATHS){
	
	var URL = PATHS.api;

	function getAll(what){
		return $http.get( URL + what ).then();
	}

	function getById( what, id ){
		return $http.get( URL + what +'/' +id).then();	
	}

	function getByFk( what, fk, fkValue ){
		return $http.get( URL + what +'/' +fk +'/' + fkValue ).then();	
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
		getById : getById,
		getByFk : getByFk,
		post : post,
		remove : remove
	};
	
}]);