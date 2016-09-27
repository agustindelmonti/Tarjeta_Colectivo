<?php

namespace Tarjeta_Colectivo;

class Colectivo extends Transporte{
	protected $empresa;
	public function __construct($id,$empresa){
		$this->id=$id;
		$this->empresa=$empresa;
		$this->costo=8;
		$this->costotrans=2.64;
		$this->tipo=1;
	}

	public function getNombreEmpresa(){
		$this->empresa;
	}
}

?>
