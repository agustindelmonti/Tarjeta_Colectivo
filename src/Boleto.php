<?php

namespace Poli\Tarjeta_Colectivo;

	class Boleto {
		protected $viaje,$trasbordo,$tarjeta;

		public function __construct( Tarjeta $trajeta, Viaje $viaje){
			$this->viaje = $viaje;
			$this->tarjeta = $tarjeta;
		}

		public function getBoleto(){
			return "ENTE DE LA MOVILIDAD DE ROSARIO ".$this->viaje->getTransporte()->getNombreEmpresa()."\n".
			$this->viaje->getHorario()."L:".$this->viaje->getTransporte()->getId()."\n".$this->tarjeta->getTipo()." $".$this->viaje->getCosto()."\nSaldo: $".$this->tarjeta->saldo();
		}
}

?>