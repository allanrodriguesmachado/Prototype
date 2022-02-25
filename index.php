<?php

require "vendor/autoload.php";

use App\classes\UploadFotos;

$upload = new UploadFotos();

$upload->file('foto.png');
$upload->extension();
$upload->rename();
echo $upload->upload();




//$atividadesPessoa = new AtividadesPessoa();
//echo $atividadesPessoa->pular();
//echo $atividadesPessoa->andar();


//$pessoa = new Pessoa();
//$pessoa->idade = 26;
//$pessoa->nome = "Allan Rodrigues";
//$pessoa->email = "Allan@php.com.br";
//echo $pessoa->dados();
//
//$pessoa->idade = 35;
//$pessoa->nome = "Rodrigues Machado";
//$pessoa->email = "Allan@php.com.br";
//echo $pessoa->dados();