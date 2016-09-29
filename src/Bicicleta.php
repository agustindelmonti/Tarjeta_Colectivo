<?php

namespace Poli\Tarjeta_Colectivo;

class Bicicleta extends Transporte{
	public function __construct($id){
		$this->id=$id;
		$this->costo=12;
		$this->tipo=2;
	}
}


?>
