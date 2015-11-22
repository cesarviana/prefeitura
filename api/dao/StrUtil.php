<?php 
/**
 * Funções úteis para manipulação de strings.
 */
class StrUtil
{
	function __construct() {}

	// Quebra a string em um array de várias palavras, considerando uma lista de delimitadores
	public static function multiexplode ($delimiters,$string) {
    	$ready = str_replace($delimiters, $delimiters[0], $string);
    	$launch = explode($delimiters[0], $ready);
    	return  $launch;
	}
}
 ?>