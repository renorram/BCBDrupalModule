<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 18/07/17
 * Time: 14:12
 */

namespace BCB\Rates\EconomicActivity\Prices;


use BCB\Rates\AbstractRate;

class GeneralPriceIndexMarket extends AbstractRate {

  public function getName() {
    return 'Índice geral de preços do mercado (IGP-M)';
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
      'oid' => 189,
      'nomeAbreviado' => 'Índice geral de preços do mercado (IGP-M)',
      'unidadePadrao' => 'Var. % mensal',
      'periodicidadeSigla' => 'M',
      'dataInicio' => '30/06/1989',
    ],
  ];
}