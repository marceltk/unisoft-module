<?php

namespace Unisoft\Crawler\Service;

use Application\Entity\Approbo;
use Doctrine\ORM\EntityManager;

class CrawlerCronService
{

    public $arrTempo = [];
    public $cron = [];
    public $executar_robo;
    public $timestamp;
    public $data;

    public function setParams(array $cron)
    {
        $cron['ano'] = date("Y");
        $cron['segundo'] = date("s");
        $this->cron = $cron;
        $this->arrTempo = $this->calculaTempo();
    }

    /**
     * @return boolean
     */
    public function validate(Approbo $Approbo, EntityManager $entityManager)
    {
        if ($Approbo->getIdeExecutando() == "S") {
            return false;
        }

        if ($Approbo->getIdeAgendar() == "S") {
            $Approbo->setIdeAgendar("N");
            $entityManager->persist($Approbo);
            $entityManager->flush();

            return true;
        }

        $crontabParams = [
            "min" => $Approbo->getMinuto(),
            "hora" => $Approbo->getHora(),
            "dia" => $Approbo->getDia(),
            "mes" => $Approbo->getMes(),
            "dia_semana" => $Approbo->getDiaSemana(),
        ];

        return $this->canExecute($crontabParams);
    }

    public function canExecute($crontabParams)
    {
        if (!count($crontabParams)) {
            return false;
        }

        $this->setParams($crontabParams);

        $this->timestamp = $timestamp = mktime();
        $this->data = date("d/m/Y H:i:s", $this->timestamp);

        $exec = [];
        foreach ($this->arrTempo as $key => $value) {
            if ($value || $value === 0 || $value === "0") {
                $valores = explode(",", $value);
                $exec[$key] = false;

                if ($key == "min") {
                    if (in_array((int) date("i", $timestamp), $valores)) {
                        $exec[$key] = true;
                    }
                }

                if ($key == "hora") {
                    if (in_array((int) date("H", $timestamp), $valores)) {
                        $exec[$key] = true;
                    }
                }

                if ($key == "dia") {
                    if (in_array((int) date("d", $timestamp), $valores)) {
                        $exec[$key] = true;
                    }
                }

                if ($key == "mes") {
                    if (in_array((int) date("m", $timestamp), $valores)) {
                        $exec[$key] = true;
                    }
                }

                if ($key == "dia_semana") {
                    if (in_array((int) date("w", $timestamp), $valores)) {
                        $exec[$key] = true;
                    }
                }
            }
        }

        $exec['segundo'] = $exec['ano'] = true;

        $retorno = true;
        foreach ($exec as $bool) {
            if (!$bool) {
                $retorno = false;
            }
        }

        $this->executar_robo = $retorno;

        return $retorno;
    }

    public function calculaTempo()
    {
        foreach ($this->cron as $key => $value) {
            // Corrigido problema de tratar o 0 como * em 25/10/2013
            if (!$value) {
                $value = "0";
            }
            $valor = $value;

            if ($value == "*") {
                $valor = $this->calculaValorUnico($key);
            }

            // Para divisão de tempo
            if (strpos($value, "/")) {
                $valor = $this->calculaValorDivisor($key, $value);
            }

            // Para divisão de tempo
            if (strpos($value, "-")) {
                $valor = $this->calculaValorIntervalo($key, $value);
            }

            $arr[$key] = $valor;
        }

        return $arr;
    }

    /**
     * CALCULAR INTERVALO ENTRE TEMPO
     */
    public function calculaValorIntervalo($key, $value)
    {
        $arr = explode("-", $value);

        /**
         * Calculos de horas
         */
        if ($key == "hora") {
            $horas = [];
            for ($i = (int) $arr[0]; $i <= $arr[1]; $i++) {
                $horas[] = $i;
            }
            $horas = implode(",", $horas);

            return $horas;
        }

        /**
         * Calculos de horas
         */
        if ($key == "min") {
            $mins = [];
            for ($i = (int) $arr[0]; $i <= $arr[1]; $i++) {
                $mins[] = $i;
            }
            $mins = implode(",", $mins);

            return $mins;
        }

        /**
         * Calculos de dias
         */
        if ($key == "dia") {
            $dias = [];
            for ($i = (int) $arr[0]; $i <= $arr[1]; $i++) {
                $dias[] = $i;
            }
            $dias = implode(",", $dias);

            return $dias;
        }

        /**
         * Calculos de dias semana
         */
        if ($key == "dia_semana") {
            $dias = [];
            $arr[0] = $arr[0] < 0 ? 0 : $arr[0];
            $arr[1] = $arr[1] > 6 ? 6 : $arr[1];
            for ($i = (int) $arr[0]; $i <= $arr[1]; $i++) {
                $dias[] = $i;
            }
            $dias = implode(",", $dias);

            return $dias;
        }
    }

    public function calculaValorDivisor($key, $value)
    {
        $arr = explode("/", $value);

        //retorna todos os dias da semana
        if ($key == "dia_semana") {
            return "0,1,2,3,4,5,6";
        }

        //Horas
        if ($key == "hora") {
            $horas = [];
            for ($i = 0; $i <= 23; $i++) {
                if ($i % $arr[1] == 0) {
                    $horas[] = $i;
                }
            }
            $horas = implode(",", $horas);

            return $horas;
        }

        //Minutos
        if ($key == "min") {
            $mins = [];
            for ($i = 0; $i <= 59; $i++) {
                if ($i % $arr[1] == 0) {
                    $mins[] = $i;
                }
            }
            $mins = implode(",", $mins);

            return $mins;
        }

        //Meses
        if ($key == "mes") {
            $meses = [];
            for ($i = 1; $i <= 12; $i++) {
                if ($i % $arr[1] == 0) {
                    $meses[] = $i;
                }
            }
            $meses = implode(",", $meses);

            return $meses;
        }

        // Dias
        if ($key == "dia") {
            $dias = [];
            for ($i = 1; $i <= date("t"); $i++) {
                if ($i % $arr[1] == 0) {
                    $dias[] = $i;
                }
            }
            $dias = implode(",", $dias);

            return $dias;
        }
    }

    /**
     * Retorna todos os valores possíveis para o * de cada campo.
     */
    public function calculaValorUnico($key)
    {
        // Minutos
        if ($key == "min") {
            for ($i = 0; $i < 60; $i++) {
                $mins[] = $i;
            }

            return implode(",", $mins);
        }

        // Horas
        if ($key == "hora") {
            for ($i = 0; $i < 24; $i++) {
                $horas[] = $i;
            }

            return implode(",", $horas);
        }

        // Dias
        if ($key == "dia") {
            for ($i = 1; $i <= date("t"); $i++) {
                $dias[] = $i;
            }

            return implode(",", $dias);
        }

        // Meses
        if ($key == "mes") {
            for ($i = 1; $i <= 12; $i++) {
                $mes[] = $i;
            }

            return implode(",", $mes);
        }

        // Dias da Semana
        if ($key == "dia_semana") {
            for ($i = 0; $i <= 6; $i++) {
                $dias_semana[] = $i;
            }

            return implode(",", $dias_semana);
        }

        return null;
    }
}
