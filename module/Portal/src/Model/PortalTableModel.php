<?php

namespace Portal\Model;

use Exception;
use Laminas\Http\Client;
use Laminas\Db\Sql\Select;

class PortalTableModel extends PortalTable
{
    public function getSql(): Select
    {
        $select = $this->sql->select('login_portal');
        $this->sql->buildSqlString($select);

        return $select;
    }

    public function getCompany($request): array
    {
        $validationCnpj = $this->validationCnpj($request->getPost()->cnpj);
        $cnpj = $this->validationRegex($request->getPost()->cnpj);

        if (!$validationCnpj) {
            throw new Exception("CNPJ inválido");
        }

        $statement = $this->adapter->query("
             SELECT
                empresa.id AS empresa_id,
				endereco_id,
                razao_social,
                cnpj,
                nome_fantasia,
                ddd_telefone,
                telefone,
                ddd_celular,
                celular
                FROM empresa
                JOIN contato ON empresa.contato_id = contato.id
                WHERE cnpj = $1
            ");

        $empresa = $statement->execute([
            'cnpj' => $cnpj,
        ]);

        return $this->response($empresa);
    }

    public function createCompany($request)
    {
        parse_str($request->getPost()->data, $data);


        $ddd_celular = (int)$this->validationRegex($data['ddd_celular']) ?: NULL;
        $celular = (int)$this->validationRegex($data['celular']) ?: NULL;
        $ddd_telefone = (int)$this->validationRegex($data['ddd_telefone']) ?: NULL;
        $telefone = (int)$this->validationRegex($data['telefone']) ?: NULL;
        $cep = filter_var($this->validationRegex($data['cep']), FILTER_SANITIZE_SPECIAL_CHARS);
        $razao_social = $data['razao_social'];
        $nome_fantasia = $data['nome_fantasia'];

        if (!$razao_social) {
            throw new Exception("Preencha o campo Razão Social");
        }

        if (!$nome_fantasia) {
            throw new Exception("Preencha o campo Nome Fantasia");
        }

        if (!$ddd_telefone && !$telefone) {
            throw new Exception("Preencha um Telefone para contato com (DDD)");
        }

        if (!$cep) {
            throw new Exception("Preencha o campo CEP");
        }

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                    INSERT INTO contato (ddd_celular, celular, ddd_telefone, telefone)
                    VALUES ($1, $2, $3, $4);
                ");

            $statement->execute([
                'ddd_celular' => $ddd_celular,
                'celular' => $celular,
                'ddd_telefone' => $ddd_telefone,
                'telefone' => $telefone,
            ]);

            $statement = $this->adapter->query("
                    INSERT INTO endereco(rua, numero, bairro, complemento, cidade, municipio, estado, cep)
                    VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
                ");

            $statement->execute([
                'rua' => $request->getPost('rua'),
                'numero' => $request->getPost('numero'),
                'bairro' => $request->getPost('bairro'),
                'complemento' => $request->getPost('complemento'),
                'cidade' => $request->getPost('cidade'),
                'municipio' => $request->getPost('municipio'),
                'estado' => $request->getPost('estado'),
                'cep' => $cep
            ]);

            $statement = $this->adapter->query("
                    INSERT INTO empresa (cnpj, razao_social, nome_fantasia,contato_id, endereco_id)
                    VALUES ($1, $2, $3, currval('contato_id_seq'), currval('endereco_id_seq'));
                ");

            $statement->execute([
                'cnpj' => $request->getPost('cnpj'),
                'razao_social' => $razao_social,
                'nome_fantasia' => $nome_fantasia,
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

            $cnpj = $this->validationRegex($request->getPost('cnpj'));

            $statement = $this->adapter->query("
             SELECT
                empresa.id AS empresa_id,
                endereco_id,
                razao_social,
                cnpj,
                nome_fantasia,
                ddd_telefone,
                telefone,
                ddd_celular,
                celular
                FROM empresa
                JOIN contato ON empresa.contato_id = contato.id
                WHERE cnpj = $1
            ");

            $empresa = $statement->execute([
                'cnpj' => $cnpj
            ]);

            return $this->response($empresa);


        } catch (Exception $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }

        return true;
    }

    public function getRepresentative($request): array
    {
        parse_str($request->getPost()->data, $data);

        $validaCpf = $this->validationCPF($data['doc1']);
        $cpf = $this->validationRegex($data['doc1']);

        if ($validaCpf == false) {
            throw new Exception("CPF inválido.");
        }

        $statement = $this->adapter->query("
            SELECT
                representante.id,
                empresa_id,
                contato_id,
                validacao_id,
                doc1,
                nome_completo
                FROM representante
                JOIN contato ON representante.contato_id = contato.id
                WHERE doc1 = $1
        ");

        $usuario = $statement->execute([
            'doc1' => $cpf
        ]);


        return $this->response($usuario);
    }

    public function createRepresentative($request)
    {
        parse_str($request->getPost()->data, $data);

        $frequencia = (int)$this->validationRegex($data['frequencia']) ?: NULL;
        $ddd_celular = (int)$this->validationRegex($data['ddd_celular']) ?: NULL;
        $celular = (int)$this->validationRegex($data['celular']) ?: NULL;
        $ddd_telefone = (int)$this->validationRegex($data['ddd_telefone']) ?: NULL;
        $telefone = (int)$this->validationRegex($data['telefone']) ?: NULL;
        $email = $data['email'];
        $empresa_id = $data['empresa_id'];
        $cpf = $this->validationRegex($data['doc1']);
        $nomeCompleto = (string)$this->validaNome($data['nome_completo']);

        $contatoCelular = $ddd_celular && $celular;
        $contatoTelefone = $ddd_telefone && $telefone;


        if (!$nomeCompleto) {
            throw new Exception('Preencha o campo Nome Completo.');
        }

        if (!$email) {
            throw new Exception('Preencha o campo E-mail.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('O e-mail não é válido.');
        }

        if (!$contatoCelular && !$contatoTelefone) {
            throw new Exception("Preencha um número para contato com (DDD).");
        }

        if (!$cpf) {
            throw new Exception("Preencha todos os campos.");
        }

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                    INSERT INTO contato (ddd_celular, celular, ddd_telefone, telefone, email)
                    VALUES ($1, $2, $3, $4, $5);
                ");

            $statement->execute([
                'ddd_celular' => $ddd_celular,
                'celular' => $celular,
                'ddd_telefone' => $ddd_telefone,
                'telefone' => $telefone,
                'email' => $email,
            ]);

            $statement = $this->adapter->query("
                INSERT INTO representante (doc1, nome_completo, frequencia, empresa_id, representante_tipo_id, contato_id)
                VALUES ($1, $2, $3, $4, $5, currval('contato_id_seq'));
            ");

            $statement->execute([
                'doc1' => $cpf,
                'nome_completo' => $nomeCompleto,
                'frequencia' => $frequencia,
                'empresa_id' => $empresa_id,
                'representante_tipo_id' => $data['representante_tipo_id']
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

            $statement = $this->adapter->query("
            SELECT
                representante.id,
                empresa_id,
                contato_id,
                validacao_id,
                representante_tipo_id,
                email,
                doc1,
                nome_completo,
                frequencia
                FROM representante
                JOIN contato ON representante.contato_id = contato.id
                WHERE doc1 = $1
                AND representante_tipo_id =  $2
        ");


            $usuario = $statement->execute([
                'doc1' => $cpf,
                'representante_tipo_id' => $data['representante_tipo_id']
            ]);
            return $this->response($usuario);

        } catch (Exception $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function httpClientValidationCep($request)
    {
        $cep = $this->validationCep($request->getPost()->cep);

        if (!$cep) {
            throw new Exception("CEP inválido");
        }

        $client = new Client('http://cep.mercatus.com.br/cep.php?',
            [
                'maxredirects' => 0,
                'tempo limite' => 30
            ]
        );

        $client->setParameterGet(
            [
                'cep' => $cep,
                'format' => 'json',
                'key' => 'f7a73a53e5e2866c49c57df8583ce1e5'
            ]
        );

        $result = $client->send()->getContent();

        return json_decode($result) ?? [];
    }


    public function formVisita()
    {
        $statement = $this->adapter->query("
             SELECT
                representante.id AS representante_id,
                razao_social,
                nome_completo,
                doc1,
                ddd_celular,
                celular,
                ddd_telefone,
                telefone
                FROM
                representante
                JOIN contato ON representante.contato_id = contato.id
                JOIN empresa ON representante.empresa_id = empresa.id
                WHERE representante.id = $1
            ");

        $visita = $statement->execute([
            'id_representante' => 'd64de4ee-b923-4ebc-9e60-407b9daab495',
        ]);

        return $this->response($visita);
    }

    public function uploadForm($request)
    {
        $tipo_arquivo = $request->getPost('file');

        $new = ($request->getPost('representante_id'));
        $folder = __DIR__ . "/../../../../data/uploads/{$new}";

        if (!file_exists($folder)) {
            mkdir($folder, 0755);
        }

        if ($files = $request->getFiles()) {
            $fileUpload = $files["file"];
            $allowedTypes = [
                "image/jpg",
                "image/jpeg",
                "image/png",
                "application/pdf"
            ];
            $newFilename = $tipo_arquivo . mb_strstr($fileUpload['name'], ".");
            if (in_array($fileUpload['type'], $allowedTypes)) {
                move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/../../../../data/uploads/{$new}/{$newFilename}");
            }
        }
    }

    public function validacaoUpload($resquest)
    {
        $new = $resquest->getPost('representante_id');

//        $doc = glob(__DIR__ . "/../../../../data/uploads/{$new}/doc.*");
        $foto = glob(__DIR__ . "/../../../../data/uploads/{$new}");

//        if (!$cartao) {
//            throw new Exception("Cartão de visita e foto são obrigatorios");
//        }
//
        if ($foto) {
            throw new Exception("Arquivo enviado com sucesso");
        }

        return [];
    }

    public function agendamentoVisita($request)
    {
        $request->getPost('data');

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
             SELECT data, representante_id
             FROM agendamento
                JOIN representante ON agendamento.representante_id = representante.id
                WHERE data = $1
            ");

            $agendamento = $statement->execute([
                'data' => $request->getPost('data'),
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

            return $this->response($agendamento);

        } catch (Exception $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }
        return true;
    }

    public function selectVisita($request)
    {
        $statement = $this->adapter->query("
                SELECT count(*)
                FROM agendamento
                JOIN representante ON agendamento.representante_id = representante.id
                JOIN horario ON agendamento.horario_id = horario.id
                WHERE representante.id = $1
                and horario = $2
            ");

        $agendamentoVisita = $statement->execute([
            'representante_id' => $request->getPost('represetante_id'),
            'horario' => $request->getPost('horario')
        ]);

//        if ($agendamentoVisita[0]['count'] > 0) {
//            throw new Exception("Não temos mais horario disponivel para essa data");
//        }

        return $this->response($agendamentoVisita);
    }

    public function cadastrarAgedamento($request)
    {
        $horario = $request->getPost('horario');

        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                   SELECT id
                   FROM horario
                   WHERE horario = $1
                ");

            $horario = $this->response(
                $statement->execute([
                    'horario' => $horario
                ])
            );

            $horario_id = $horario[0]['id'];

            $statement = $this->adapter->query("
                    INSERT INTO agendamento (data, representante_id, horario_id)
                    VALUES($1, $2, $3)
                ");

            $statement->execute([
                'data' => $request->getPost('data'),
                'representante_id' => $request->getPost('representante_id'),
                'horario' => $horario_id
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

            $statement = $this->adapter->query("
                SELECT count(*)
                FROM agendamento
                JOIN representante ON agendamento.representante_id = representante.id
                JOIN horario ON agendamento.horario_id = horario.id
                WHERE representante.id = $1
                and horario = $2
            ");

            $agendamentoVisita = $statement->execute([
                'representante_id' => $request->getPost('represetante_id'),
                'horario' => $request->getPost('horario')
            ]);

            return $this->response($agendamentoVisita);

        } catch (Exception $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }

        return true;
    }

    public function formAgendamentos($request): array
    {
        $representante_id = $request->getPost('representante_id');

        $statement = $this->adapter->query("
                SELECT agendamento.id AS agendamento_id, representante.id AS representante_id, nome_completo, data, horario
                FROM agendamento
                JOIN representante ON agendamento.representante_id = representante.id
                JOIN horario ON agendamento.horario_id = horario.id
                WHERE representante.id = $1
                AND agendamento.cancelamento_id IS NULL
            ");

        $formAgendamentos = $statement->execute([
            'representante_id' => $representante_id,
        ]);

        return $this->response($formAgendamentos);
    }

    public function cancelarAgendamento($request): array
    {
        $agendamento_id = $request->getPost()->agendamento_id;


        try {
            $this->adapter->getDriver()->getConnection()->beginTransaction();

            $statement = $this->adapter->query("
                INSERT INTO cancelamento (ts_cancelamento)
                VALUES (now())
            ");

            $statement->execute();

            $statement = $this->adapter->query("
                UPDATE agendamento
                SET cancelamento_id = currval('cancelamento_id_seq')
                FROM cancelamento
                WHERE agendamento.id = $1
            ");

            $statement->execute([
                'agendamento_id' => $agendamento_id
            ]);

            $this->adapter->getDriver()->getConnection()->commit();

        } catch (Exception $exception) {
            $this->adapter->getDriver()->getConnection()->rollback();
        }

        return [];
    }

    public function importarExcel($request): array
    {
        $request->getFiles()->file;

        print_r($request->getFiles()->file);

//        $allowedTypes = [
//            'application/vnd.ms-excel',
//            'text/plain',
//            'text/csv',
//            'text/tsv',
//            "image/jpg",
//            "image/jpeg",
//            "image/png",
//            "application/pdf",
//            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
//        ];
//
//        if (!empty($request->getFiles()->file) && in_array($request->getFiles()->file['type'], $allowedTypes)) {
//            if (is_uploaded_file($request->getFiles('file')['tmp_name'])) {
//                $file = fopen($request->getFiles('file')['tmp_name'], 'r+');
//                print_r($file);
//            }
//        }


//        if (!empty($request->getFiles('file')) && in_array($_FILES['file']['type'], $allowedTypes)) {
//            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
//                $csv_file = fopen($_FILES['file']['tmp_name'], 'r');
//                fgetcsv($csv_file);
//                while (($lines = fgetcsv($csv_file, null, ';'))) {
//                    echo "<pre>START";
//                    print_r($lines);
//                    echo "END</pre>";
//                }
//            }
//        }

//        $folder = __DIR__ . "/../../../../data/uploads/{$new}";
//
//        if (!file_exists($folder)) {
//            mkdir($folder, 0755);
//        }
//
//        if ($files = $importeExcel) {
//            $fileUpload = $files["file"];
//            $newFilename = $tipo_arquivo . mb_strstr($fileUpload['name'], ".");
//            if (in_array($fileUpload['type'], $allowedTypes)) {
//                move_uploaded_file($fileUpload['tmp_name'], __DIR__ . "/../../../../data/uploads/{$new}/{$newFilename}");
//            }


        return [];

    }

    public function createTable($request)
    {
        parse_str($request->getPost()->data, $data);


    }
}




