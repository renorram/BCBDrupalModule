<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 12/07/17
 * Time: 19:23
 */

namespace BCB\Rates;


abstract class AbstractRate {

  /**
   * Return the taxe name
   * @return mixed
   */
  public abstract function getName();

  /**
   * @return array
   */
  public abstract function getData();
  public abstract function setData(array $data);


  /**
   * @param array $data
   *
   * @throws \Exception
   */
  public function setNewData(array $data) {
    try {
      $this->validateData($data);
      $this->setData($data);
    } catch (\Exception $exception) {
      throw $exception;
    }

  }

  /**
   * Get the currency wich the rate is marketed.
   *
   * @param $rate
   *
   * @return mixed
   */
  public function getCurrency($rate) {
    return preg_replace('/([\/])|(u.m.c.)/', '', $rate['unidadePadrao']);
  }

  /**
   * Get rate by oid, in case none rate be found it returns false.
   *
   * @param $oid
   *
   * @return bool|array
   */
  public function getByOid($oid) {
    if (!is_int($oid)) {
      throw new \InvalidArgumentException('Parameter oid must be an integer.');
    }

    foreach ($this->getData() as $rate) {
      if ($oid === $rate['oid']) {
        return $rate;
      }
    }

    return FALSE;
  }

  /**
   * Checks if the date used for search isn't before the rate initial date
   * @param \DateTime $date
   * @param $rate
   *
   * @return bool
   * @throws \Exception
   */
  public function validateSearchDate(\DateTime $date, $rate) {
    $midnight = ' 00:00:00';
    $rateDate = \DateTime::createFromFormat('d/m/Y H:i:s', $rate['dataInicio'] . $midnight);
    if ($date < $rateDate) {
      throw new \Exception('The especified date is before the initial date.');
    }

    return TRUE;
  }

  public function validateData(array $data) {

    $data = array_filter($data, 'is_array');
    if (empty($data)) {
      throw new \InvalidArgumentException('Data array is empty');
    }

    $types = [
      'oid' => 'integer',
      'nomeAbreviado' => 'string',
      'unidadePadrao' => 'string',
      'periodicidadeSigla' => 'string',
      'dataInicio' => 'string',
    ];
    $exceptionMessage = 'Data array isn\'t on the mandatory format. The right format is ' . var_export($types, TRUE);

    $valid_keys = array_keys($types);
    foreach ($data as $rate) {
      if ($valid_keys !== array_keys($rate)) {
        throw new \InvalidArgumentException($exceptionMessage);
      }

      foreach ($rate as $key => $value) {
        if ($types[$key] != gettype($value)) {
          throw new \InvalidArgumentException($exceptionMessage);
        }
      }
    }

  }

}