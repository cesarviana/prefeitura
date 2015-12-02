app.controller('RelatorioProblemasMesCtrl', ['$scope','$rootScope','backService', 

	function( $scope, $rootScope, backService )
	{
		$rootScope.titulo = "Relatório de Problemas por Mês";
		$rootScope.url = "problemas_mes";

		backService.getAll( 'RelatorioProblemasMes' ).then(
			function( response ){
				$scope.linhas = response.data;
			}
		);
	}
	
]);