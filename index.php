<?php

use App\Class\UploadFoto;
use App\Class\UploadFile;

require "vendor/autoload.php";
require "vendor/autoload.php";

$upload = new UploadFoto('Foto.png');
echo $upload->upload();

$upload = new UploadFile('Foto.zip');
echo $upload->upload();

//$pessoa = new Pessoa();
//$pessoa->idade = 35 ;
//$pessoa->nome = "Allan Rodrigues";
//$pessoa->email = "allan@php.com.br " ;
//echo $pessoa->dados();

//$atividadesPessoa = new AtividadePessoa();
//$atividadesPessoa->pular();
//$atividadesPessoa->andar();