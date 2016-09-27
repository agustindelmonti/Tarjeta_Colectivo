<?php

namespace Poli\Tarjeta_Colectivo;


class Viaje {
	protected $horario,$transporte,$costo,$tipo,$tipos = array(1 => "Viaje en Colectivo" , 2 => "Viaje en Bici");

	public function __construct($horario,Transporte $transporte,$costo){
		$this->horario=$horario;
		$this->transporte=$transporte;
		$this->costo=$costo;
		$this->tipo=$transporte->getTipo();
	}
	public function getCosto(){
		return $this->costo;
	}
	public function getHorario(){
		return $this->horario;
	}
	public function getTransporte(){
		return $this->transporte;
	}

	public function getTipo(){
		return $this->tipos[$this->tipo];
	}
}


?>
