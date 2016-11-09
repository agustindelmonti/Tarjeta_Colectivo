<?php

namespace Poli\Tarjeta_Colectivo;


class Tarjeta implements Int_Tarjeta{
	protected $saldo,$porcentaje,$plus=0,$valorPlus=0, $transbordo = False, $medio;
	protected $viajes,$boleto,$ultimafecha=0,$ultimabicipaga=0;

	public function __construct (){
		$this->saldo = 0;
		$this->porcentaje = 1;
		$this->medio = False;
	}

	public function pagar(Transporte $transporte, $fecha_y_hora){

		//COLECTIVO TIPO=1
		if($transporte->getTipo()==1){ 

			$aux1 = strtotime($fecha_y_hora);
			$aux2 = strtotime($this->ultimafecha);

			//Calculo costo boleto segun tiempo transcurrido (transbordo/normal)
			if($this->ultimafecha == 0 || $this->isTransbordo($aux1,$aux2) || $this->viajes[$this->ultimafecha]->getTransporte()->getId() == $transporte->getid()){ 
				$costo = $transporte->getCosto()*$this->porcentaje;
				$this->transbordo = False;
			} else {
				$costo = $transporte->getCostoTrans()*$this->porcentaje;
				$this->transbordo = True;
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
				$this->boleto = new Boleto ($this,$this->viajes[$fecha_y_hora]);
				$this->ultimafecha = $fecha_y_hora;
				return 1;
			} 
			//Si no puedo pagar
			else{
				return 0;
			}
		} 

		//BICICLETA TIPO=2
		else{ 

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

	public function isTransbordo($a,$b){
		//Lunes-Sabado(22hs a 6hs) ó Domingo [Tiempo Max.: 90min]
		if( (strftime("%H",$a)>=22 && strftime("%H",$a)<=06) || (strftime("%a",$a)=='dom') ){
			return $a-$b>5400;
		}//Sabado(14hs a 22hs) [Tiempo Max.: 90min]
		elseif ( strftime("%a",$a)=='sáb' && strftime("%H",$a)>=14 ){
			return $a-$b>5400;
		}
		//Lunes-Viernes(6hs a 22hs) ó Sabados(6hs a 14hs) [Tiempo Max.: 60min]
		else return $a-$b>3600;
	}

	public function getTipo(){
		if($this->plus == 1){
			return "PLUS";
		}
		elseif($this->plus == 2){
			return "ULT. PLUS";
		}
		elseif($this->transbordo){
			return "TRANSBORDO";
		}
		elseif($this->medio){
			return "MEDIO";
		}
		else return "NORMAL";
	}

	public function getBoleto(){
		return $this->boleto;
	}

	public function saldo(){
		return $this->saldo;
	}

	public function viajesRealizados(){
		return $this->viajes;
	}
}


?>