app.controller('ProblemasCtrl', ['$scope','$rootScope',  'backService', 'commonService', 'mensagensService',

	function($scope, $rootScope, backService, commonService, mensagensService)
	{
		$rootScope.titulo = "Problemas";
		$rootScope.url = 'problemas';

		$scope.dateInputOptions = { dateFormat: 'dd/mm/yy' };

		var atualizar = function()
		{
			$scope.problema = {
				id : undefined,
				descricao : '',
				usuario : undefined,
				categoria : undefined	
			};

			mensagensService.resposta("");

			backService.getAll( 'problemas' ).then(
				function(response)
				{
					$scope.problemas = response.data;
					// Converte datas de string para Date
					for (var i = $scope.problemas.length - 1; i >= 0; i--) 
						$scope.problemas[i].dataRegistro = new Date( $scope.problemas[i].dataRegistro.split(' ')[0] );
					
				}

			);
			
			backService.getAll( 'categorias' ).then(
				function(response){
					$scope.categorias = response.data;
				}

			);
			
			backService.getAll( 'bairros' ).then(
				function(response){
					$scope.bairros = response.data;
				}
			);

		};

		

		$scope.salvar = function(){
			$scope.problema.usuario = $scope.usuarioLogado();
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