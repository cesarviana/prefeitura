app.controller('ProblemasCtrl', ['$scope','$rootScope',  'backService', 'commonService', 'mensagensService',

	function($scope, $rootScope, backService, commonService, mensagensService)
	{
		$rootScope.titulo = "Problemas";
		$rootScope.url = 'problemas';

		var atualizar = function()
		{
			$scope.problema = {
				id : undefined,
				descricao : '',
				usuario : undefined,
				categoria : undefined	
			};

			mensagensService.alerta("");

			backService.getAll( 'problemas' ).then(
				function(response){
					$scope.problemas = response.data;
				}
			);
			backService.getAll( 'categorias' ).then(
				function(response){
					$scope.categorias = response.data;
				}
			);

		};

		

		$scope.salvar = function(){
			commonService.salvar( $scope.problema, 'problemas', atualizar );
		};


		$scope.excluir = function()
		{
			commonService.excluir($scope.problemas, 'problemas', atualizar );
		};


		$scope.editar = function(problema)
		{
			$scope.problema = problema;
		};



		$scope.selecionarTodos = function()
		{
			commonService.selecionarTodos( $scope.problemas, $scope.selecionados );
		};


		atualizar();

	
	}
]);