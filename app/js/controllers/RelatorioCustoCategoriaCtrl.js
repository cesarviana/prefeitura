app.controller('RelatorioCustoCategoriaCtrl', ['$scope','$rootScope','backService', 

	function( $scope, $rootScope, backService )
	{
		$rootScope.titulo = "Relatório de Custo por Categoria";
		$rootScope.url = "custo_categoria";

		backService.getAll( 'RelatorioCustoCategoria' ).then(
			function( response ){
				$scope.linhas = response.data;
			}
		);
	}
	
]);