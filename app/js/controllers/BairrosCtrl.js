app.controller('BairrosCtrl', ['$scope','$rootScope',  'backService', 'commonService', 'mensagensService',

	function($scope, $rootScope, backService, commonService, mensagensService)
	{
		$rootScope.titulo = "Bairros";
		$rootScope.url = 'bairros';

		var atualizar = function()
		{
			$scope.bairro = {
				id : '',
				nome : ''
			};

			mensagensService.resposta("");

			backService.getAll( 'bairros' ).then(
				function(response){
					$scope.bairros = response.data;
				}
			);

		};

		

		$scope.salvar = function(){
			commonService.salvar( $scope.bairro, 'bairros', atualizar );
		};


		$scope.excluir = function()
		{
			commonService.excluir($scope.bairros, 'bairros', atualizar );
		};


		$scope.editar = function(bairro)
		{
			$scope.bairro = bairro;
		};



		$scope.selecionarTodos = function()
		{
			commonService.selecionarTodos( $scope.bairros, $scope.selecionados );
		};


		atualizar();

	}

]);