app.controller('MedidasCtrl', ['$scope','$rootScope', '$routeParams', 'backService', 'commonService', 'mensagensService',

	function($scope, $rootScope, $routeParams, backService, commonService, mensagensService)
	{
		$rootScope.titulo = "Medidas";
		$rootScope.url = 'medidas';

		
		var atualizar = function()
		{
			$scope.medida = {
				id : undefined,
				descricao : undefined,
				usuario : undefined,
				problema : undefined,
				solucao : false
			};

			backService.getById( 'problemas', $routeParams.id_problema ).then(
				function( response )
				{
					$scope.problema = response.data;
				}
			);

			backService.getByFk( 'medidas', 'id_problema', $routeParams.id_problema ).then(
				function( response )
				{
					$scope.medidas = response.data;
				}
			);
		};

		
		$scope.salvar = function()
		{
			console.log( $scope.medida );
			$scope.medida.problema = $scope.problema;
			$scope.medida.usuario = $scope.usuarioLogado();
			commonService.salvar( $scope.medida, 'medidas', atualizar );
		};


		$scope.excluir = function()
		{
			commonService.excluir( $scope.medidas, 'medidas', atualizar );
		};


		$scope.editar = function(medida)
		{
			$scope.medida = medida;
		};


		atualizar();
	}

]);