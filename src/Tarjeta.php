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

			//Calculo costo boleto segun tiempo transcurrido (transbordo/normal)
			if($this->ultimafecha == 0 || ($aux1-$aux2>3600) || $this->viajes[$this->ultimafecha]->getTransporte()->getId() == $transporte->getid()){ 
				$costo = $transporte->getCosto()*$this->porcentaje;
			} else {
				$costo = $transporte->getCostoTrans()*$this->porcentaje;
			}
			//Puede pagar el boleto y los plus, se pagan los plus
			if($costo+$this->valorPlus <= $this->saldo && $this->plus>0){
				$this->saldo -= $this->valorPlus;
				$this->plus = 0;
				$this->valorPlus = 0;
			}
			//No tengo saldo pero tengo plus, se computa el plus
			if($costo<=$this->saldo || $this->plus<2){
				if($costo>$this->saldo && $this->plus<2){
					$this->plus++;
					$this->valorPlus += $costo;
				}
				//Sino se descuenta el boleto del saldo
				else{
					$this->saldo -= $costo;
				}
				//Se crea el viaje, boleto y se puede viajar
				$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
				$this->ultimafecha = $fecha_y_hora;
				return 1;
			} 
			//Si no puedo pagar
			else{
				return 0;
			}
		} 

		//BICICLETA TIPO=2
		if($transporte->getTipo()==2){ 

			$aux1 = strtotime($fecha_y_hora);
			$aux2 = strtotime($this->ultimabicipaga);

			//Calculo costo boleto segun tiempo transcurrido 
			if($this->ultimabicipaga == 0 || ($aux1-$aux2>86400)){
				$costo = $transporte->getCosto();
			} else {
				$costo = 0;
			}
			//Puede pagar el boleto y los plus, se pagan los plus
			if(($this->saldo >= $costo+$this->valorPlus)&& $this->plus>0){
				$this->saldo -= $this->valorPlus;
				$this->plus = 0;
				$this->valorPlus = 0;
			}
			//Tengo saldo pero tengo plus, se computa el plus
			if($costo<=$this->saldo || $this->plus<2){
				if($costo>$this->saldo && $this->plus<2){
					$this->plus++;
					$this->valorPlus += $costo;
				}
				//Sino se descuenta el boleto del saldo
				else{
					$this->saldo -= $costo;
					$this->ultimabicipaga = $fecha_y_hora;
				}
				//Se crea el viaje, boleto y se puede viajar
				$this->viajes[$fecha_y_hora] = new Viaje($fecha_y_hora,$transporte,$costo);
				return 1;
			}
			else{
				//Si no puedo pagar
				return 0;
			}
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