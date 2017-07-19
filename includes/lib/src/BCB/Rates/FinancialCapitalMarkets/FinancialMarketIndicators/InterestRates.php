<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 17/07/17
 * Time: 18:44
 */

namespace BCB\Rates\FinancialCapitalMarkets\FinancialMarketIndicators;


use BCB\Rates\AbstractRate;

class InterestRates extends AbstractRate {
  public function getName() {
    return 'Taxas de juros';
  }

  public  function getData() {
    return $this->data;
  }

  public function setData(array $data) {
    try{
      $this->validateData($data);
      $this->data = $data;
    }catch (\Exception $e){
      throw $e;
    }
  }

  private $data = [
    [
      'oid' => 11,
      'nomeAbreviado' => 'Selic',
      'unidadePadrao' => '% a.d.',
      'periodicidadeSigla' => 'D',
      'dataInicio' => '04/06/1986',
    ],
    [
      'oid' => 432,
      'nomeAbreviado' => 'Taxa de juros - Meta Selic definida pelo Copom',
      'unidadePadrao' => '% a.a.',
      'periodicidadeSigla' => 'D',
      'dataInicio' => '05/03/1999',
    ],
    [
      'oid' => 1178,
      'nomeAbreviado' => 'Taxa de juros - Selic anualizada base 252',
      'unidadePadrao' => '% a.a.',
      'periodicidadeSigla' => 'D',
      'dataInicio' => '04/06/1986',
    ],
  ];
}