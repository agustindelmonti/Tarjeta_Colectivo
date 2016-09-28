<?php

namespace Poli\Tarjeta_Colectivo;


class Medio extends Tarjeta{

	public function __construct (){
		$this->saldo = 0;
		$this->porcentaje = 0.5;
	}

}


?>
