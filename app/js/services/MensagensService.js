app.factory('mensagensService', function($rootScope){
	
	function alerta( mensagem, codigo ){
		if(mensagem)
			alert( mensagem );
	}

	function resposta( response ){
		if( !response )
			$rootScope.mensagem = '';
		else
			alerta( response.data.message );
	}

	function confirmar( mensagem ){
		return confirm( mensagem );
	}

	return {
		resposta : resposta,
		alerta : alerta,
		confirmar : confirmar
	};
	
});