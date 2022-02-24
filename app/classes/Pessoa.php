<?php

class Pessoa
{
    public $idade;
    public $nome;
    public $email;

    public function dados()
    {
        return "Meu nome é {$this->nome} tenho {$this->idade} anos e meu E-mail é {$this->email}";
    }

}
