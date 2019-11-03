<?php

class Geral {

    private $CI;

    function __construct() {
        $this->CI = &get_instance();
    }

    function _monta_combos($arrayDados, $ordem) {
        $options = '';
        foreach ($arrayDados as $k => $v) {
            if ($k == $ordem && $ordem !== null && $ordem !== '') {
                $options .= '<option value="' . $k . '" selected="selected">' . $v . '</option>';
            } else {
                $options .= '<option value="' . $k . '">' . $v . '</option>';
            }
        }
        return $options;
    }

    function combo_status_orcamento($status = NULL, $retorno = 'option') {
        $params = array();
        $params['Agendar Visitas'] = 'Agendar Visitas';
        $params['Agendada Visitas'] = 'Agendada Visitas';
        $params['Aguardando Orçamentos'] = 'Aguardando Orçamentos';
        $params['Orçamentos Recebido'] = 'Orçamentos Recebidos';
        $params['Agendado Serviços'] = 'Agendado Serviços';
        $params['Serviços Realizados'] = 'Serviços Realizados';
        $params['Pagamento Não Efetuado'] = 'Pagamento Não Efetuado';
        $params['Pagamento Efetuado'] = 'Pagamento Efetuado';
        $params['Avaliação Realizada'] = 'Avaliação Realizada';

        if ($retorno == 'option') {
            $options = '';
            foreach ($params as $key => $value) {
                $dados[$key] = trim($value);
            }
            $options = $this->_monta_combos($dados, $status);
        } else {
            $options = $params[$status];
        }
        return $options;
    }

}
