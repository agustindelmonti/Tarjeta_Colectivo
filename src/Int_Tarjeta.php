<?php

namespace Poli\Tarjeta;


interface Int_Tarjeta {
 public function pagar(Transporte $transporte, $fecha_y_hora);
 public function recargar($monto);
 public function saldo();
 public function viajesRealizados();
}


?>
