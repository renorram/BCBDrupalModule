<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 18/07/17
 * Time: 11:38
 */

namespace BCB\Rates\EconomicActivity\Prices;


use BCB\Rates\AbstractRate;

class ConsumerPricesBroads extends AbstractRate {

  public function getName() {
    return 'Índice nacional de preços ao consumidor-amplo (IPCA)';
  }

  public function getData() {
    return $this->data;
  }

  public function setData(array $data) {
    try {
      $this->validateData($data);
      $this->data = $data;
    } catch (\Exception $exception) {
      throw $exception;
    }
  }

  private $data = [
    [
      'oid' => 433,
      'nomeAbreviado' => 'Índice nacional de preços ao consumidor-amplo (IPCA)',
      'unidadePadrao' => 'Var. % mensal',
      'periodicidadeSigla' => 'M',
      'dataInicio' => '02/01/1980',
    ],
  ];
}