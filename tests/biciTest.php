<?php

	namespace Poli\Tarjeta_Colectivo;

	class TarjetaTest extends \PHPUnit_Framework_TestCase {

		public function setup(){
			$this->tarjeta = new Tarjeta();
			$this->A = new Colectivo("K","SEMTUR");
			$this->B = new Colectivo("145","Rosario Bus");
			$this->C = new Bicicleta("1");
		}

		public function testPagarBici(){
			$this->tarjeta->recargar(30);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->assertEquals($this->tarjeta->saldo(),18, "El saldo de la tarjeta deberia ser de $18");	
		}

		public function testPagarDia(){
			$this->tarjeta->recargar(30);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->tarjeta->pagar($this->C,"2016/02/1 15:00");
			$this->assertEquals($this->tarjeta->saldo(),18, "El saldo de la tarjeta deberia ser de $18");
			$this->tarjeta->pagar($this->C,"2016/02/3 12:00");
			$this->assertEquals($this->tarjeta->saldo(),6, "El saldo de la tarjeta deberia ser de $6");
		}

		public function testPagarPlus(){
			$this->tarjeta->recargar(2.63);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->assertEquals($this->tarjeta->pagar($this->C,"2016/04/1 12:00"),1, "Deberia poder pagar");
			$this->assertEquals($this->tarjeta->saldo(),2.63, "El saldo deberia ser $2.63");
		}
		public function testSinPlus(){
			$this->tarjeta->recargar(2.63);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->tarjeta->pagar($this->C,"2016/04/1 12:00");
			$this->assertEquals($this->tarjeta->pagar($this->C,"2016/06/1 12:00"),0,"No deberia poder pagar");
		}
?>