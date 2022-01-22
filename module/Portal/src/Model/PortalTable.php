<?php

namespace Portal\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Adapter\Driver\ResultInterface;

abstract class PortalTable
{
    protected Adapter $adapter;
    protected Sql $sql;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->sql = new Sql($this->adapter);
    }

    protected function response(ResultInterface $results = null): array
    {
        if ($results instanceof ResultInterface && $results->isQueryResult()) {
            $returns = (new ResultSet)
                ->initialize($results)
                ->toArray();
        }
        return $returns ?? [];
    }


    protected function validationCnpj($cnpj): bool
    {
        $cnpj = $this->validationRegex($cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    protected function validationCPF($cpf): bool
    {
        $cpf = $this->validationRegex($cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        if ($cpf == " ") {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return $cpf;
    }

    protected function validationCep($cep)
    {
        $cep = $this->validationRegex($cep);

        if (preg_match('/(\d)\1{8}/', $cep)) {
            return false;
        }

        if (strlen($cep) != 8) {
            return false;
        }

        return $cep;
    }

    protected function validationRegex($input)
    {
        return preg_replace('/[^0-9]/is', '', $input);
    }

    protected function validaNome($nome)
    {
        return preg_replace('/^[a-zA-Z]+$/i', '', $nome);
    }
}
