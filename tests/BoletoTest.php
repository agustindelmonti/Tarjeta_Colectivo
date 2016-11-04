<?php

namespace Poli\Tarjeta_Colectivo;

class BoletoTest extends \PHPUnit_Framework_TestCase {
	public function setup(){
		$this->tarjeta = new Tarjeta();
		$this->medio = new Medio();
		$this->A = new Colectivo("K","SEMTUR");
		$this->B = new Colectivo("145","Rosario Bus");
	}
	public function testPlus(){
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->tarjeta->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nPLUS $8\nSaldo: $0"," ");
	}
	public function testUltPlus(){
		$this->medio->pagar($this->A,"2016/01/1 12:00");
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->medio->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nULT. PLUS $4\nSaldo: $0"," ");
	}
	public function testMedio(){
		$this->medio->recargar(20);
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->medio->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nMEDIO $4\nSaldo: $16"," ");
	}
	public function testTransbordo(){
		$this->tarjeta->recargar(30);
		$this->tarjeta->pagar($this->B,"2016/02/1 11:55");
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->tarjeta->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nTRANSBORDO $2.64\nSaldo: $19.36"," ");
	}
	public function testTransbordoMedio(){
		$this->medio->recargar(30);
		$this->medio->pagar($this->B,"2016/02/1 11:55");
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->medio->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nTRANSBORDO $1.32\nSaldo: $24.68"," ");
	}
	public function testNormal(){
		$this->tarjeta->recargar(8);
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->tarjeta->getBoleto()->getBoleto(),
			"ENTE DE LA MOVILIDAD DE ROSARIO SEMTUR\n2016/02/1 12:00 L:K\nNORMAL $8\nSaldo: $0"," ");
	}

}

?>