app.controller('CategoriasProblemasCtrl', ['$scope','$rootScope',  'backService', 'commonService', 'mensagensService',

	function($scope, $rootScope, backService, commonService, mensagensService)
	{
		$rootScope.titulo = "Categorias";
		$rootScope.url = 'categorias';

		var atualizar = function()
		{
			$scope.categoria = {
				id : '',
				nome : ''
			};

			mensagensService.alerta("");

			backService.getAll( 'categorias' ).then(
				function(response){
					$scope.categorias = response.data;
				}
			);

		};

		

		$scope.salvar = function(){
			commonService.salvar( $scope.categoria, 'categorias', atualizar );
		};


		$scope.excluir = function()
		{
			commonService.excluir($scope.categorias, 'categorias', atualizar );
		};


		$scope.editar = function(categoria)
		{
			$scope.categoria = categoria;
		};



		$scope.selecionarTodos = function()
		{
			commonService.selecionarTodos( $scope.categorias, $scope.selecionados );
		};


		atualizar();

	}

]);