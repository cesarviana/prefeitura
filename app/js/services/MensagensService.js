app.factory('mensagensService', function($rootScope){
	
	function resposta( response ){
		$rootScope.mensagem = response.data.message ;
	}

	function alerta( mensagem, codigo ){
		if(mensagem)
			alert( mensagem );
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