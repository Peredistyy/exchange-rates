<?php

namespace ExchangeRates\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ExchangeRates\Entity\Rate;
use ExchangeRates\Model\RateActualizer;

class AppController extends AbstractController
{

    /**
     * Стартовая страница
     *
     * @return Response
     */
    public function homeAction()
    {
        $windowData = array(
            "grid" => array(
                "currencies" => $this->getCurrencies(),
                "rates" => $this->getRates()
            )
        );

        $response = $this->getTwig()
            ->render(
                "home.html.twig",
                array(
                    "data" => $windowData
                )
            );

        return new Response($response);
    }

    /**
     * Добавление новой валюты
     *
     * @param $currencyOf
     * @param $currencyIn
     * @return JsonResponse
     */
    public function addAction($currencyOf, $currencyIn)
    {
        $success = true;

        try {
            $rate = new Rate();
            $rate->setRate(0);
            $rate->setCurrencyOf($this->getEntityManager()->find('ExchangeRates\Entity\Currency', $currencyOf));
            $rate->setCurrencyIn($this->getEntityManager()->find('ExchangeRates\Entity\Currency', $currencyIn));

            $this->getEntityManager()->persist($rate);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $success = false;
        }

        $response = new JsonResponse();
        $response->setData(array(
            "success" => $success
        ));

        return $response;
    }

    /**
     * Удаление валюты
     *
     * @param $id
     * @return JsonResponse
     */
    public function removeAction($id)
    {
        $success = true;

        try {
            $rate = $this->getEntityManager()->find('ExchangeRates\Entity\Rate', $id);
            $this->getEntityManager()->remove($rate);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $success = false;
        }


        $response = new JsonResponse();
        $response->setData(array(
            "success" => $success
        ));

        return $response;
    }

    /**
     * Возвращает список курсов
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $success = true;
        $rates = array();

        try {
            $rates = $this->getRates(true);
        } catch (\Exception $e) {
            $success = false;
        }

        $response = new JsonResponse();
        $response->setData(array(
            "success" => $success,
            "data" => $rates
        ));

        return $response;
    }

    /**
     * Возвращает список доступных валют
     *
     * @return array
     */
    protected function getCurrencies()
    {
        $currencies = array();

        $currenciesObjects = $this->getEntityManager()
            ->getRepository('ExchangeRates\Entity\Currency')
            ->findAll();

        foreach ($currenciesObjects as $currencyObject) {
            $currencies[] = array(
                "id" => $currencyObject->getId(),
                "name" => $this->resolveCurrencyName($currencyObject->getName())
            );
        }

        return $currencies;
    }

    /**
     * Возвращает список курсов
     *
     * @param bool $actualize
     * @return array
     */
    protected function getRates($actualize = false)
    {
        $rates = array();

        if ($actualize) {
            $rateActualizer = new RateActualizer($this->getEntityManager());
            $rateActualizer->actualize();
        }

        $ratesObjects = $this->getEntityManager()
            ->getRepository('ExchangeRates\Entity\Rate')
            ->findAll();

        foreach ($ratesObjects as $rateObject) {
            $rates[] = array(
                "id" => $rateObject->getId(),
                "currencyOf" => $this->resolveCurrencyName($rateObject->getCurrencyOf()->getName()),
                "currencyIn" => $this->resolveCurrencyName($rateObject->getCurrencyIn()->getName()),
                "rate" => $rateObject->getRate()
            );
        }

        return $rates;
    }

    /**
     * Преобразовывает имя валюты
     *
     * @param $currencyName
     * @return string
     */
    private function resolveCurrencyName($currencyName)
    {
        $resolvedCurrencyName = $this->getTranslator()->trans($currencyName);
        return $resolvedCurrencyName . " (" . $currencyName . ")";
    }
}
