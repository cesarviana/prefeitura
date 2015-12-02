app.controller('InicioCtrl', 
	['$scope', '$rootScope', 'backService', 'authService', 'Session', 'TIPOS_USUARIO',

	function($scope, $rootScope, backService, authService, Session, TIPOS_USUARIO )
	{
		$rootScope.titulo='Prefeitura';
		$rootScope.url = 'inicio';

		$scope.usuarioLogado = function(){ 
			return Session.get('usuario'); 
		};
		$scope.tiposUsuario = TIPOS_USUARIO;
		$scope.temAcesso = authService.temAcesso;
		$scope.logout = authService.logout;

		$scope.setUsuario = function( usuario ){
			$scope.usuarioLogado = usuario;
		};

	}

]);