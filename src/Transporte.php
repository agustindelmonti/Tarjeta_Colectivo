<?php

namespace Poli\Tarjeta_Colectivo;

abstract class Transporte {
	protected $id,$costo,$tipo,$costotrans;

	public function getId(){
		return $this->id;
	}
	public function getTipo(){
		return $this->tipo;
	}
	public function getCosto(){
		return $this->costo;
	}

	public function getCostoTrans(){
		return $this->costotrans;
	}


}


?>
