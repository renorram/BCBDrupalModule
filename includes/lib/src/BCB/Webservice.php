<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 05/07/17
 * Time: 17:43
 */

namespace BCB;

class Webservice {

  /** @var \SoapClient $soap */
  private $soap;

  const WSDL_URL = 'https://www3.bcb.gov.br/sgspub/JSP/sgsgeral/FachadaWSSGS.wsdl?WSDL';

  private static $instance;

  public function __construct($wsdl, $options = []) {

    try {

      $this->checkDependencies();

      $this->soap = new \SoapClient($wsdl, $options);

    } catch (\Exception $exception) {
      throw $exception;
    }
  }

  private function checkDependencies() {
    if (!version_compare(PHP_VERSION, '7.0.0', '<')) {
      throw new \Exception('Not compatible with PHP 7 Soap');
    }

    if (!class_exists('SoapClient')) {
      throw new \Exception('SoapClient library not loaded.');
    }
  }

  private function request($methodName, $data = []) {
    try {
      $result = $this->soap->__soapCall($methodName, $data);
    } catch (\Exception $exception) {
      throw $exception;
    }

    return $result;
  }

  /**
   * Get class instance
   *
   * @param array $options
   *
   * @return \BCB\Webservice
   */
  public static function getInstance($options = []) {
    if (NULL === self::$instance) {
      self::$instance = new Webservice(self::WSDL_URL, $options);
    }

    return self::$instance;
  }

  public function getValoresSeriesVO($codigosSeries, \DateTime $dataInicio, \DateTime $dataFim) {
    if (!is_array($codigosSeries) || (is_array($codigosSeries) && empty($codigosSeries))) {
      throw new \InvalidArgumentException('Parameter codigoSerie is not an array or is empty.');
    }


    $data = [
      'codigosSeries' => $codigosSeries,
      'dataInicio' => $dataInicio->format('d/m/Y'),
      'dataFim' => $dataFim->format('d/m/Y'),
    ];

    return $this->request('getValoresSeriesVO', $data);
  }

  public function getValoresSeriesXML($codigosSeries, \DateTime $dataInicio, \DateTime $dataFim) {
    if (!is_array($codigosSeries) || (is_array($codigosSeries) && empty($codigosSeries))) {
      throw new \InvalidArgumentException('Parameter codigoSerie is not an array or is empty.');
    }

    $data = [
      'codigosSeries' => $codigosSeries,
      'dataInicio' => $dataInicio->format('d/m/Y'),
      'dataFim' => $dataFim->format('d/m/Y'),
    ];

    return $this->request('getValoresSeriesXML', $data);
  }

  public function getUltimoValorVO($codigoSerie) {
    if (!is_int($codigoSerie)) {
      throw new \InvalidArgumentException('Parameter codigoSerie must be integer.');
    }

    $data = ['codigoSerie' => $codigoSerie];
    return $this->request('getUltimoValorVO', $data);
  }

  public function getUltimoValorXML($codigoSerie) {
    if (!is_int($codigoSerie)) {
      throw new \InvalidArgumentException('Parameter codigoSerie must be integer.');
    }

    $data = ['codigoSerie' => $codigoSerie];
    return $this->request('getUltimoValorXML', $data);
  }
}