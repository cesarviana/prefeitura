app.factory('authService', ['$http','$rootScope','Session','PATHS','AUTH_EVENTS',
	
	function($http, $rootScope, Session, PATHS, AUTH_EVENTS )
	{

		function login( credenciais ){

			return $http
			.post( PATHS.api + 'login', credenciais )
			.then(
				function( response ){
					var usuario = response.data.usuario;
					Session.set( 'usuario', usuario );
					Session.set( 'session_id', response.data.sessionId );

					$rootScope.$broadcast(AUTH_EVENTS.loginSuccess );
					
				},
				function( response ){
					$rootScope.$broadcast(AUTH_EVENTS.loginFailed, response );
				}
			);
		}

		function logout(){
			Session.destroy();
			$rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
		}

		function estaLogado(){
			return Session.get('usuario');
		}

		function temAcesso( tiposDeUsuarioPermitidos ){
			if(!angular.isArray(tiposDeUsuarioPermitidos)){
				tiposDeUsuarioPermitidos = [tiposDeUsuarioPermitidos];
			}
			return estaLogado() &&
				tiposDeUsuarioPermitidos.indexOf(Session.userRole) !== -1;
		}

		// retorna tipos de usuário com acesso à url destino.
		function retornaTipos(destino)
		{
			var tipos = [];
			if(destino && destino.data && destino.data.tiposUsuario){
				tipos = destino.data.tiposUsuario;	
			}
			return tipos;
		}

		function controlaAcessoGeral( path ){
			// sem acesso, tentando acessar
			if( ( path != PATHS.login ) && !estaLogado())
			{
				$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
				return false;
			}
			// tem acesso, mas está na tela de login
			else if( path == PATHS.login && estaLogado() ){
				$rootScope.$broadcast(AUTH_EVENTS.loginSuccess );
				return true;
			}
		}

		function controlaAcessoDestino( destino ){
			
			var tiposAutorizados = retornaTipos( destino );

			if( tiposAutorizados.length > 0 && !temAcesso(tiposAutorizados) )
			{
				if( !estaLogado() )
					$rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
				else
					$rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
				return false;
			}
			return true;
		}

		function filtraAcesso( origem, destino ){
			return controlaAcessoGeral( origem ) &&
				   controlaAcessoDestino( destino );
		}

		return {
			temAcesso : temAcesso,
			estaLogado : estaLogado,
			filtraAcesso : filtraAcesso,
			login : login,
			logout : logout
		};
	}	
]);