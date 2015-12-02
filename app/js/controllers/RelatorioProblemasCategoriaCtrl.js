app.controller('RelatorioProblemasCategoriaCtrl', ['$scope','$rootScope','backService', 

	function( $scope, $rootScope, backService )
	{
		$rootScope.titulo = "Relatório de Problemas por Categoria";
		$rootScope.url = "problemas_categoria";

		backService.getAll( 'RelatorioProblemasCategoria' ).then(
			function( response ){
				$scope.linhas = response.data;
			}
		);
	}
	
]);