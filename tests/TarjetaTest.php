<?php

namespace Poli\Tarjeta_Colectivo;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

	protected $tarjeta,$A,$B;
	public function setup(){
		$this->tarjeta = new Tarjeta();
		$this->medio = new Medio();
		$this->A = new Colectivo("K","SEMTUR");
		$this->B = new Colectivo("145","Rosario Bus");
		$this->C = new Bicicleta("1");
	}
	
	public function testTarjeta1(){
		$this->tarjeta->recargar(600);
		$this->assertEquals($this->tarjeta->saldo(), 740, "Cuando cargo 600 deberia tener finalmente 740");
	}
	public function testTarjeta2(){
		$this->tarjeta->recargar(350);
		$this->assertEquals($this->tarjeta->saldo(), 398, "Cuando cargo 350 deberia tener finalmente 398");
	}
	public function testTarjeta3(){
		$this->tarjeta->recargar(250);
		$this->assertEquals($this->tarjeta->saldo(), 250, "Cuando cargo 250 deberia tener finalmente 250");
	}

	public function testPagarColectivo1(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//DOS VIAJES, MISMO COLECTIVO (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->A,"2016/02/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),4, "El saldo de la tarjeta deberia ser de $4");
		//DOS VIAJES, MISMO COLECTIVO (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->A,"2016/02/1 12:02");
		$this->assertEquals($this->medio->saldo(),12, "El saldo de la tarjeta deberia ser de $12");
	}

	public function testPagarColectivo2(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//DOS VIAJES, COLECTIVOS A y B, NO TRANSBORDO (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->B,"2016/03/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),4, "El saldo de la tarjeta deberia ser de $4");
		//DOS VIAJES, COLECTIVOS A y B, NO TRANSBORDO (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->B,"2016/03/1 12:02");
		$this->assertEquals($this->medio->saldo(),12, "El saldo de la tarjeta deberia ser de $12");
	}

	public function testPagarTransbordo(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//TRANSBORDO ENTRE A y B (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->B,"2016/02/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),9.36, "El saldo de la tarjeta deberia ser de $9.36");
		//TRANSBORDO ENTRE A y B (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->B,"2016/02/1 12:02");
		$this->assertEquals($this->medio->saldo(),14.68, "El saldo de la tarjeta deberia ser de $14.68");
	}
}
?>