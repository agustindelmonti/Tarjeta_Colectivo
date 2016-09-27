<?php

namespace Poli\Tarjeta_Colectivo;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

	protected $tarjeta,$A,$B;
	public function setup(){
		$this->tarjeta = new Tarjeta();
		$this->A = new Colectivo("K","SEMTUR");
		$this->B = new Colectivo("145","Rosario Bus");
	}
	
	public function recargarTest1(){
		$this->tarjeta->recargar(600);
		$this->assertEquals($this->tarjeta->saldo(), 740, "Cuando cargo 600 deberia tener finalmente 740");
	}
	public function recargarTest2(){
		$this->tarjeta->recargar(350);
		$this->assertEquals($this->tarjeta->saldo(), 398, "Cuando cargo 350 deberia tener finalmente 398");
	}
	public function recargarTest3(){
		$this->tarjeta->recargar(250);
		$this->assertEquals($this->tarjeta->saldo(), 250, "Cuando cargo 250 deberia tener finalmente 250");
	}
}
?>
