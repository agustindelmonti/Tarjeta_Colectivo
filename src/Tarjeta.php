<?php

namespace Poli\Tarjeta_Colectivo;


class Tarjeta implements Int_Tarjeta{
	protected $saldo,$porcentaje,$plus=0,$valorPlus=0;
	protected $viajes,$ultimafecha=0,$ultimabicipaga=0;

	public function __construct (){
		$this->saldo = 0;
		$this->porcentaje = 1;
	}

	public function pagar(Transporte $transporte, $fecha_y_hora){

		//COLECTIVO TIPO=1
		if($transporte->getTipo()==1){ 

			$aux1 = strtotime($fecha_y_hora);
			$aux2 = strtotime($this->ultimafecha);

			//Determino $costo segun transbordo o normal
			if($this->ultimafecha == 0 || ($aux1-$aux2>3600) || $this->viajes[$this->ultimafecha]->getTransporte()->getId() == $transporte->getid()){ 
				$costo = $transporte->getCosto()*$this->porcentaje;
			} else {
				$costo = $transporte->getCostoTrans()*$this->porcentaje;
			}
			if($costo+$this->valorPlus <= $this->saldo && $this->plus>0){
				$this->saldo -= $this->valorPlus;
				$this->plus = 0;
				$this->valorPlus = 0;
			}
			if($costo<=$this->saldo || $this->plus<2){
				if($costo>$this->saldo && $this->plus<2){
					$this->plus++;
					$this->valorPlus += $costo;
				}
				else{
					$this->saldo -= $costo;
				}

				$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
				$this->ultimafecha = $fecha_y_hora;
				return 1;
			} 
			else{
				return 0;
			}
		} 

		//BICICLETA TIPO=2
		if($transporte->getTipo()==2){ 

			$aux1 = strtotime($fecha_y_hora);
			$aux2 = strtotime($this->ultimabicipaga);
			$costo = $transporte->getCosto();

			if(($this->saldo && $this->plus>0) >= $costo+$this->valorPlus){
				$this->saldo -= $this->valorPlus;
				$this->plus = 0;
				$this->valorPlus = 0;
			}
			if($this->ultimabicipaga == 0 || ($aux1-$aux2>86400) || $this->plus<2){
				if($this->plus<2){
					$this->plus++;
					$this->valorPlus += $costo;
				}
				else{
					$this->saldo -= $costo;
					$this->ultimabicipaga = $fecha_y_hora;
				}
			} 
			else {
				$costo = 0;
			}

			$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
			return 1;
		}
	}

	public function recargar($monto){
		if($monto>=500){
			$monto+=140;
		} else if($monto>=272){
			$monto+=48;
		}
		$this->saldo+=$monto;
	}

	public function saldo(){
		return $this->saldo;
	}

	public function viajesRealizados(){
		return $this->viajes;
	}
}


?>