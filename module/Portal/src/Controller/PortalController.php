<?php

namespace Portal\Controller;

use Exception;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Portal\Model\PortalTableModel;


class PortalController extends AbstractActionController
{
    private PortalTableModel $PortalTableModel;

    public function __construct(PortalTableModel $PortalTableModel)
    {
        $this->PortalTableModel = $PortalTableModel;
    }

    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    public function cadastroAction(): ViewModel
    {
        return new ViewModel();
    }

    public function visitaAction(): ViewModel
    {
        return new ViewModel();
    }

    public function agendamentosAction(): ViewModel
    {
        return new ViewModel();
    }

    public function tabelaAction(): ViewModel
    {
        return new ViewModel();
    }

    public function criartabelaAction(): ViewModel
    {
        return new ViewModel();
    }

    public function getCompanyAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->getCompany($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function createCompanyAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            try {
                $result = $this->PortalTableModel->createCompany($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => $result ?? NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function httpClientValidationCepAction(): JsonModel
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->httpClientValidationCep($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => $result ?? NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function getRepresentativeAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {

            try {
                $result = $this->PortalTableModel->getRepresentative($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function createRepresentativeAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->createRepresentative($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function formVisitaAction(): jsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->formVisita($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function uploadFormAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->uploadForm($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function agendamentoVisitaAction(): jsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->agendamentoVisita($request);
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function selectVisitaAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->selectVisita($request);
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }
        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true ?? false,
            'message' => $message ?? NULL,
        ]);
    }

    public function validacaoUploadAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->validacaoUpload($request);
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }
        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true ?? false,
            'message' => $message ?? NULL,
        ]);
    }


    public function cadastrarAgendamentoAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->cadastrarAgedamento($request);
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true ?? false,
            'message' => $message ?? NULL
        ]);
    }

    public function formAgendamentosAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->formAgendamentos($request);
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => true ?? false,
            'message' => $message ?? NULL
        ]);
    }

    public function cancelarAgendamentoAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->cancelarAgendamento($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel ([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL
        ]);
    }

    public function importarExcelAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->importarExcel($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel ([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL
        ]);
    }

    public function createTableAction(): JsonModel
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                $result = $this->PortalTableModel->createTable($request);
                $success = true;
            } catch (Exception $exception) {
                $message = $exception->getMessage();
            }
        }

        return new JsonModel([
            'data' => isset($result) ? array_values($result) : NULL,
            'success' => $success ?? false,
            'message' => $message ?? NULL
        ]);
    }
}
