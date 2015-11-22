app.factory('commonService', ['backService','mensagensService', function(backService, mensagensService){


	function salvar( obj, entidade, sucesso ){
		
		backService.post( entidade, obj ).then(

			function()
			{
				sucesso();
				mensagensService.alerta("O item foi salvo.");
			},

			function()
			{
				mensagneService.resposta( response );
			}

		);
	
	}
	

	function excluir( itens, entidade, sucesso )
	{

		if( !mensagensService.confirmar("Tem certeza de que deseja excluir os itens selecionados?") ) return;

		var codigosDeletar = [];

		for (var i = itens.length - 1; i >= 0; i--) {
			if( itens[i].selecionado ){
				codigosDeletar.push( itens[i].id );
			}
		}

		backService.remove( entidade, codigosDeletar ).then(

			function()
			{
				mensagensService.alerta("Remoção efetuada.");
				sucesso();
			},

			function( response ) 
			{ 
				mensagensService.resposta( response );
			}

			);
	}


	function selecionarTodos(itens, selecionar)
	{
		for (var i = itens.length - 1; i >= 0; i--) {
			itens[i].selecionado = selecionar;
		}
	}

	return {
		salvar : salvar,
		excluir : excluir,
		selecionarTodos : selecionarTodos
	};
}]);