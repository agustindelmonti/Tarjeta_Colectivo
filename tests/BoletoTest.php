<?php

namespace Poli\Tarjeta_Colectivo;

class BoletoTest extends \PHPUnit_Framework_TestCase {
	public function setup(){
		$this->tarjeta = new Tarjeta();
		$this->medio = new Medio();
		$this->A = new Colectivo("K","SEMTUR");
	}

	public function testNormal(){
		$this->tarjeta->recargar(8);
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->boleto->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nNORMAL $8\nSaldo: $0"," ");
	}

}

?>