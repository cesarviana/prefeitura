app.controller('UsuariosCtrl', ['$scope','$rootScope','backService','commonService','mensagensService',

	function($scope, $rootScope, backService, commonService, mensagensService)
	{

		$rootScope.titulo = "Usuários";
		$rootScope.url = "usuarios";

		
		var atualizar = function()
		{
			
			$scope.usuario = { 
				nome: '',
				login:'',
				senha: '',
				tipo: { id: 0 }
			};

			// limpa mensagem
			mensagensService.alerta("");
			
			backService.getAll( 'usuarios' ).then( 
				function(response){
					$scope.usuarios = response.data;
				}
			);
			
			backService.getAll('tiposUsuario').then(
				function(response){
					$scope.tiposUsuario = response.data;
				}
			);

		};



		$scope.salvar = function(){
			commonService.salvar( $scope.usuario, 'usuarios', atualizar );
		};


		
		$scope.excluir = function()
		{
			commonService.excluir($scope.usuarios, 'usuarios', atualizar );
		};

		
	
		$scope.editar = function(usuario)
		{
			$scope.usuario = usuario;
		};

		

		$scope.selecionarTodos = function()
		{
			commonService.selecionarTodos( $scope.usuarios, $scope.selecionados );
		};

		atualizar();

	}

]);