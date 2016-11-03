<?php

	namespace Poli\Tarjeta_Colectivo;

	class biciTest extends \PHPUnit_Framework_TestCase {

		protected $tarjeta,$A,$B,$C;

		public function setup(){
			$this->tarjeta = new Tarjeta();
			$this->A = new Colectivo("K","SEMTUR");
			$this->B = new Colectivo("145","Rosario Bus");
			$this->C = new Bicicleta("1");
		}

		public function testPagarBici(){
			$this->tarjeta->recargar(30);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->assertEquals($this->tarjeta->saldo(),18, "Saldo deberia ser $18");	
		}

		public function testPagarDia(){
			$this->tarjeta->recargar(30);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00");
			$this->tarjeta->pagar($this->C,"2016/02/1 15:00"); //Bicicleta mismo dia ($0)
			$this->assertEquals($this->tarjeta->saldo(),18, "Saldo deberia ser $18");
			$this->tarjeta->pagar($this->C,"2016/02/3 12:00"); //Bicicleta otro dia ($0)
			$this->assertEquals($this->tarjeta->saldo(),6, "Saldo deberia ser $6");
		}
		public function testPagarPlus(){
			$this->tarjeta->recargar(2.63);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00"); //plus 1
			$this->assertEquals($this->tarjeta->pagar($this->C,"2016/04/1 12:00"),1, "Deberia poder pagar"); //plus 2
			$this->assertEquals($this->tarjeta->saldo(),2.63, "Saldo deberia ser $2.63");
		}
		public function testSinPlus(){
			$this->tarjeta->recargar(2.63);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00"); //plus 1
			$this->tarjeta->pagar($this->C,"2016/04/1 12:00"); //plus 2
			$this->assertEquals($this->tarjeta->pagar($this->C,"2016/06/1 12:00"),0,"No deberia poder pagar");
		}
		public function testRecargarPlus(){
			$this->tarjeta->recargar(2.63);
			$this->tarjeta->pagar($this->C,"2016/02/1 12:00"); //plus 1
			$this->tarjeta->pagar($this->C,"2016/04/1 12:00"); //plus 2
			$this->tarjeta->recargar(40); //Recargo de nuevo
			$this->tarjeta->pagar($this->C,"2016/06/1 12:00");
			$this->assertEquals($this->tarjeta->saldo(),6.63,"Saldo deberia ser $6.63")//Pago plus($24) y un boleto($12)
		}
	}
?>