var app = angular.module('prefeituraApp',['ngRoute']);

app.config(['$routeProvider',function($routeProvider) {
	$routeProvider
	.when('/medidas',{
		templateUrl : 'js/templates/form_medida.html',
		controller : 'MedidasCtrl'
	})
	.when('/problemas',{
		templateUrl : 'js/templates/form_problema.html',
		controller : 'ProblemasCtrl'
	}).when('/categorias',{
		templateUrl : 'js/templates/form_categoria.html',
		controller : 'CategoriasProblemasCtrl'
	}).when('/bairros',{
		templateUrl : 'js/templates/form_bairro.html',
		controller : 'BairrosCtrl'
	}).when('/usuarios',{
		templateUrl : 'js/templates/form_usuario.html',
		controller : 'UsuariosCtrl'
	}).when('/inicio',{
		templateUrl : 'js/templates/inicio.html',
		controller : 'InicioCtrl'
	}).otherwise({
		redirectTo : '/inicio'
	});
}]);
