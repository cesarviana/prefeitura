var app = angular.module('prefeituraApp',['ngRoute','LocalStorageModule','ui.date','ngMessages']);

app.config(['$routeProvider','TIPOS_USUARIO',
	function($routeProvider, TIPOS_USUARIO) 
	{
		$routeProvider
		.when('/login',{
			templateUrl : 'js/templates/login.html',
			controller : 'LoginCtrl'
		}).when('/medidas/:id_problema',{
			templateUrl : 'js/templates/form_medida.html',
			controller : 'MedidasCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin, TIPOS_USUARIO.analista]
			}
		}).when('/problemas',{
			templateUrl : 'js/templates/form_problema.html',
			controller : 'ProblemasCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin, TIPOS_USUARIO.analista]
			}
		}).when('/categorias',{
			templateUrl : 'js/templates/form_categoria.html',
			controller : 'CategoriasProblemasCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).when('/bairros',{
			templateUrl : 'js/templates/form_bairro.html',
			controller : 'BairrosCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).when('/usuarios',{
			templateUrl : 'js/templates/form_usuario.html',
			controller : 'UsuariosCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).when('/inicio',{
			templateUrl : 'js/templates/inicio.html',
			controller : 'InicioCtrl'
		}).when('/relatorios/problemas_mes',{
			templateUrl : 'js/templates/rel_problemas_mes.html',
			controller : 'RelatorioProblemasMesCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).when('/relatorios/problemas_categoria',{
			templateUrl : 'js/templates/rel_problemas_categoria.html',
			controller : 'RelatorioProblemasCategoriaCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).when('/relatorios/custo_categoria',{
			templateUrl : 'js/templates/rel_custo_categoria.html',
			controller : 'RelatorioCustoCategoriaCtrl',
			data : {
				tiposUsuario : [TIPOS_USUARIO.admin]
			}
		}).otherwise({
			redirectTo : '/inicio'
		});
	}
])
.run(['$rootScope','$location','authService','AUTH_EVENTS','PATHS',
	
	function($rootScope,$location,authService, AUTH_EVENTS, PATHS)
	{
		$rootScope.$on('$routeChangeStart',
			function(event, destino)
			{
				$rootScope.esconderMenu = false;
				var origem = $location.path();
				authService.filtraAcesso( origem, destino ) ;
			}
		);

		var toHome = function() { 
			$location.path(PATHS.home); 
		};
		var toLogin = function(){ 
			$location.path(PATHS.login); 
		};

		$rootScope.$on( AUTH_EVENTS.loginSuccess, toHome );

		$rootScope.$on( AUTH_EVENTS.notAuthorized, toHome );

		$rootScope.$on( AUTH_EVENTS.notAuthenticated, toLogin );

		$rootScope.$on( AUTH_EVENTS.logoutSuccess, toLogin );
	}

])
.constant('TIPOS_USUARIO', 
	{
		administrador : 'administrador',
		analista : 'analista'
	}
)
.constant( 'PATHS', 
	{
		home : '/inicio',
		login: '/login',
		api : 'http://localhost/slimproj/prefeitura/api/index.php/'
	}
)
.constant('AUTH_EVENTS', 
	{
		loginSuccess: 'auth-login-success',
		loginFailed: 'auth-login-failed',
		logoutSuccess: 'auth-logout-success',
		sessionTimeout: 'auth-session-timeout',
		notAuthenticated: 'auth-not-authenticated',
		notAuthorized: 'auth-not-authorized'
	}
);