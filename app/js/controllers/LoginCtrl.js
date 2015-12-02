app.controller('LoginCtrl', ['$scope','$rootScope','authService', 'AUTH_EVENTS','mensagensService',
	function( $scope, $rootScope, authService, AUTH_EVENTS, mensagensService )
	{
		$rootScope.esconderMenu = true;
		$rootScope.texto = 'oi';

		$scope.login = authService.login;

		$rootScope.$on( AUTH_EVENTS.loginFailed, function(event, response ){
			console.log(response);
			$scope.erro = response.data.message ;
		});
	}
]);