<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Front {
    public function __construct() {
		parent::__construct();
        $this->load->library('PagSeguroLibrary/PagSeguroLibrary.php');
    }

    public function transacoesPagseguro() {
        $code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ?
            trim($_POST['notificationCode']) : null);
        $type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ?
            trim($_POST['notificationType']) : null);

        if($code && $type) {
            $notificationType = new PagSeguroNotificationType($type);
            $strType = $notificationType->getTypeFromValue();

            switch($strType) {
                case 'TRANSACTION':
                    $this->transactionNotification($code);
                    break;
                case 'APPLICATION_AUTHORIZATION':
                    //self::authorizationNotification($code);
                    break;
                case 'PRE_APPROVAL':
                    //self::preApprovalNotification($code);
                    break;
                default:
                    //LogPagSeguro::error("Unknown notification type [" . $notificationType->getValue() . "]");

            }

            //self::printLog($strType);
        } else {
            //LogPagSeguro::error("Invalid notification parameters.");
            //self::printLog();
        }
    }

    private function transactionNotification($notificationCode) {
        $credentials = PagSeguroConfig::getAccountCredentials();

        try {
            $transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);

            $statusTransaction = $transaction->getStatus()->getValue();

            switch($statusTransaction) {
                case 3:
                    // Aprovado
                    $status = 'a';
                    break;
                case 7:
                    // Cancelado
                    $status = 'c';
                    break;
                default:
                    // Pendente
                    $status = 'p';
                    break;
            }

            $this->superModel->salvaLog('Notificação do PagSeguro', array('status' => $statusTransaction));

            $idFatura = $transaction->getReference();

            $condicoes = array(
                array(
                    'campo' => 'id_profissional_fatura',
                    'valor' => $idFatura
                )
            );

            $fatura = array(
                'situacao' => $status,
                'id_transacao' => $transaction->getCode()
            );

            if($status === 'a') {
                $fatura['status'] = 'a';
            }

            $this->superModel->update('profissional_fatura', $fatura, $condicoes);
        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
}
