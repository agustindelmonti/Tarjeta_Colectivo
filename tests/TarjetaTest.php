<?php

namespace Poli\Tarjeta_Colectivo;

class TarjetaTest extends \PHPUnit_Framework_TestCase {

	//Cada vez que aumente el colectivo se cambia estos parametros para cambiar los tests
	protected $boleto_colectivo = 8 ,$boleto_trans_colectivo = 2.64; 

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
	public function testViaje(){
		$this->tarjeta->recargar(20);
		$this->tarjeta->pagar($this->B,"2016/02/1 12:00");
		//Pruebo todas las funciones de la case viajes
		$this->assertEquals($this->tarjeta->viajesRealizados()["2016/02/1 12:00"]->getCosto(),$this->boleto_colectivo,"El valor del boleto de colectivo es $".$this->boleto_colectivo);
		$this->assertEquals($this->tarjeta->viajesRealizados()["2016/02/1 12:00"]->getHorario(),"2016/02/1 12:00"," ");
		$this->assertEquals($this->tarjeta->viajesRealizados()["2016/02/1 12:00"]->getTransporte()->getNombreEmpresa(),"Rosario Bus"," ");
		$this->assertEquals($this->tarjeta->viajesRealizados()["2016/02/1 12:00"]->getTipo(),"Viaje en Colectivo","Es un colectivo");
	}

	public function testPagarColectivo1(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//DOS VIAJES, MISMO COLECTIVO (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->A,"2016/02/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),20-2*$this->boleto_colectivo, "Saldo deberia ser $".(20-2*$this->boleto_colectivo));
		//DOS VIAJES, MISMO COLECTIVO (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->A,"2016/02/1 12:02");
		$this->assertEquals($this->medio->saldo(),20-$this->boleto_colectivo, "Saldo deberia ser $".(20-$this->boleto_colectivo));
	}

	public function testPagarColectivo2(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//DOS VIAJES, COLECTIVOS A y B, NO TRANSBORDO (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->B,"2016/03/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),20-2*$this->boleto_colectivo, "Saldo tarjeta deberia ser $".(20-2*$this->boleto_colectivo));
		//DOS VIAJES, COLECTIVOS A y B, NO TRANSBORDO (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->B,"2016/03/1 12:02");
		$this->assertEquals($this->medio->saldo(),20-$this->boleto_colectivo, "Saldo tarjeta deberia ser $".(20-$this->boleto_colectivo));
	}

	public function testPagarPlus(){
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->assertEquals($this->tarjeta->saldo(),0,"El saldo deberia ser $0");
		$this->assertEquals($this->tarjeta->pagar($this->A,"2016/04/1 12:00"),1,"Deberia poder pagar");
		//NO TENGO MAS PLUS
		$this->tarjeta->pagar($this->A,"2016/04/1 12:00");
		$this->assertEquals($this->tarjeta->pagar($this->A,"2016/04/1 12:00"),0,"No deberia poder pagar");
	}

	public function testCargarPlus(){
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->A,"2016/03/1 12:00");
		$this->tarjeta->recargar(30);
		$this->tarjeta->pagar($this->A,"2016/04/1 12:00");
		$this->assertEquals($this->tarjeta->saldo(),30-3*$this->boleto_colectivo, "El saldo deberia ser $".(30-3*$this->boleto_colectivo));
	}

	public function testPagarTransbordo(){
		$this->tarjeta->recargar(20);
		$this->medio->recargar(20);
		//TRANSBORDO ENTRE A y B (NORMAL)
		$this->tarjeta->pagar($this->A,"2016/02/1 12:00");
		$this->tarjeta->pagar($this->B,"2016/02/1 12:02");
		$this->assertEquals($this->tarjeta->saldo(),(20-$this->boleto_colectivo-$this->boleto_trans_colectivo), "Saldo tarjeta deberia ser $".(20-$this->boleto_colectivo-$this->boleto_trans_colectivo));
		//TRANSBORDO ENTRE A y B (MEDIO)
		$this->medio->pagar($this->A,"2016/02/1 12:00");
		$this->medio->pagar($this->B,"2016/02/1 12:02");
		$this->assertEquals($this->medio->saldo(),(20-$this->boleto_colectivo/2-$this->boleto_trans_colectivo/2), "Saldo tarjeta deberia ser $".(20-$this->boleto_colectivo/2-$this->boleto_trans_colectivo/2));
	}

	public function testTransbordoDom(){
		$this->tarjeta->recargar(20);
		//TRANSBORDO ENTRE A y B [Lunes-Sabado(22hs a 6hs) ó Domingo]
		$this->tarjeta->pagar($this->A,"2016/02/1 23:00");
		$this->tarjeta->pagar($this->B,"2016/02/1 23:20");
		$this->assertEquals($this->tarjeta->saldo(),(20-$this->boleto_colectivo-$this->boleto_trans_colectivo), "Saldo tarjeta deberia ser $".(20-$this->boleto_colectivo-$this->boleto_trans_colectivo));
	}

	public function testTransbordoSab(){
		$this->tarjeta->recargar(20);
		//TRANSBORDO ENTRE A y B [Sabado(14hs a 22hs)]
		$this->tarjeta->pagar($this->A,"2016/11/12 15:00");
		$this->tarjeta->pagar($this->B,"2016/11/12 15:20");
		$this->assertEquals($this->tarjeta->saldo(),(20-$this->boleto_colectivo-$this->boleto_trans_colectivo), "Saldo tarjeta deberia ser $".(20-$this->boleto_colectivo-$this->boleto_trans_colectivo));
	}

}
?>