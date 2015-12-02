app.service('Session', ['localStorageService',function(localStorageService){

	this.get = function(key){
		return localStorageService.get(key);
	};
	this.set = function(key, value){
		return localStorageService.set(key, value);	
	};
	
	this.destroy = function(){
		localStorageService.clearAll();
	};

}]);