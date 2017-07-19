<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 13/07/17
 * Time: 17:56
 */

namespace BCB;


use Symfony\Component\Debug\Debug;

class Retriever {

  private $dataType;
  private $webservice;

  private $loadedClasses = [];

  public function __construct($options = []) {
    if (isset($options['debug']) && $options['debug'] === TRUE) {
      Debug::enable();
    }

    if(isset($options['data_type']) && ($options['data_type'] == 'object' || $options['data_type'] == 'xml')){
      $this->dataType = $options['data_type'];
    }else{
      $this->dataType = 'xml';
    }

    $this->webservice = Webservice::getInstance();
    $this->loadDataClasses();
  }

  /**
   * @param $short_name
   *
   * @return bool|\BCB\Rates\AbstractRate
   */
  public function getClassData($short_name) {
    if (isset($this->loadedClasses[$short_name])) {
      return $this->loadedClasses[$short_name];
    }
    else {
      if (($classPath = $this->searchByShortName($short_name))) {
        $this->loadedClasses[$short_name] = new $classPath;
        return $this->loadedClasses[$short_name];
      }
    }

    return FALSE;
  }

  private function loadDataClasses() {
    $classes = $this->availableDataClasses();
    foreach ($classes as $class) {
      $this->loadedClasses[$class['short_name']] = new $class['class_path'];
    }
  }

  public function availableDataClasses() {
    return [
      [
        'name' => 'Taxas Câmbio - Taxas Livres ou Administradas',
        'description' => 'Taxas de câmbio de moedas comercializadas no Brasil, com valores de compra e venda.',
        'short_name' => 'rates_administered_free',
        'class_path' => 'BCB\\Rates\\ExternalSector\\ExchangeRates\\AdministeredOrFree',
      ],
      [
        'name' => 'Taxas de juros',
        'description' => 'Taxas de juros',
        'short_name' => 'financial_capital_interest_rates',
        'class_path' => 'BCB\\Rates\\FinancialCapitalMarkets\\FinancialMarketIndicators\\InterestRates',
      ],
      [
        'name' => 'Indicadores de Atividade Econômica - Consumidor',
        'description' => 'Indicadores de Atividade Econômica como IPCA e IGP',
        'short_name' => 'economic_activity_prices_consumer',
        'class_path' => 'BCB\\Rates\\EconomicActivity\\Prices\\ConsumerPricesBroads',
      ],
      [
        'name' => 'Indicadores de Atividade Econômica - Geral',
        'description' => 'Indicadores de Atividade Econômica como IPCA e IGP',
        'short_name' => 'economic_activity_prices_general',
        'class_path' => 'BCB\\Rates\\EconomicActivity\\Prices\\GeneralPriceIndexMarket',
      ],
    ];
  }

  public function searchByShortName($short_name) {
    $classes = $this->availableDataClasses();
    foreach ($classes as $class) {
      if ($short_name == $class['short_name']) {
        return $class['class_path'];
      }
    }
    return FALSE;
  }

  public function searchLastSerieValue($serieOid) {
    if($this->dataType == 'object') {
      return Webservice::getInstance()->getUltimoValorVO($serieOid);
    }else if($this->dataType == 'xml') {
      return Webservice::getInstance()->getUltimoValorXML($serieOid);
    }
    return false;
  }


}