<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
    'profissional/avaliar' =>
        array(
            array(
                'field' => 'url',
                'label' => 'URL',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'nota',
                'label' => 'Nota',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'comentario',
                'label' => 'Comentario',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/planos/inserirOpcao' =>
        array(
            array(
                'field' => 'opcao',
                'label' => 'Nome da opção',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'ordem',
                'label' => 'Ordem de exibição',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'login/redefinirSenha' =>
        array(
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|required|xss_clean|valid_email'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/planos/inserir' =>
        array(
            array(
                'field' => 'plano',
                'label' => 'Nome do plano',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'preco',
                'label' => 'Preço',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/cidades/inserir' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome do cidade',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'link',
                'label' => 'Link',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/faq/inserir' =>
        array(
            array(
                'field' => 'tipo',
                'label' => 'Tipo',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'pergunta',
                'label' => 'Pergunta',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'resposta',
                'label' => 'Resposta',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/parceiros/inserir' =>
        array(
            array(
                'field' => 'parceiro',
                'label' => 'Nome do parceiro',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/feedback/inserir' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'texto',
                'label' => 'Texto',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/categorias/inserir' =>
        array(
            array(
                'field' => 'categoria',
                'label' => 'Nome da categoria',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/categorias/inserirSubcategoria' =>
        array(
            array(
                'field' => 'id_categoria',
                'label' => 'Categoria',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'subcategoria',
                'label' => 'Nome da subcategoria',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/deleta' =>
        array(
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'tabela',
                'label' => 'tabela',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'campo',
                'label' => 'campo',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/alterarStatus' =>
        array(
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'tabela',
                'label' => 'tabela',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'campo',
                'label' => 'campo',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'restrita/login' =>
        array(
            array(
                'field' => 'email',
                'label' => 'email',
                'rules' => 'trim|required|xss_clean|valid_email'
            ),
            array(
                'field' => 'senha',
                'label' => 'senha',
                'rules' => 'trim|required|xss_clean'
            )
    ),
    'newsletter' =>
        array(
            array(
                'field' => 'emailNews',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )
    ),
    'recuperarSenha' =>
        array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )
    ),
    'contato' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'sobrenome',
                'label' => 'Sobrenome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'endereco',
                'label' => 'Endereço',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'mensagem',
                'label' => 'Mensagem',
                'rules' => 'trim|required'
            )
    ),
    'agendamentos/agendar' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )
        ),
    'pedidos/realizar' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )
        ),
    'pedidos/servico' =>
        array(
            array(
                'field' => 'id_orcamento',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'id_servico',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'qntd',
                'label' => 'Qunatidade',
                'rules' => 'trim|required'
            )
            
        ),
    'cadastro/comum' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            )

        ),
    'cadastro' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'categoria',
                'label' => 'Categoria',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'subcategoria',
                'label' => 'Sub Categoria',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'estado',
                'label' => 'Estado',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'cidade',
                'label' => 'Cidade',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'plano',
                'label' => 'Plano',
                'rules' => 'trim|required'
            )
        ),
    'restrita/cadastroUsuario' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => 'trim|required'
            )
        ),
    'restrita/atualizaCadastroProfissional' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'categoria',
                'label' => 'Categoria',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'subcategoria',
                'label' => 'Sub Categoria',
                'rules' => 'trim|required'
            )
        ),
    'restrita/atualizaCadastro' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'cpf',
                'label' => 'CPF',
                'rules' => 'trim|required'
            )
        ),
    'restrita/dados/atualiza' =>
        array(
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email'
            ),

            array(
                'field' => 'cidade',
                'label' => 'Cidade',
                'rules' => 'trim|required'
            )
        ),
    'restrita/anunciar' =>
        array(
            array(
                'field' => 'subcategoria',
                'label' => 'Subcategoria',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'plano',
                'label' => 'Plano',
                'rules' => 'trim|required'
            )
        ),
    'restrita/dados/anuncio' =>
        array(
            array(
                'field' => 'id_profissional',
                'label' => 'Profissional',
                'rules' => 'trim|required'
            )
        ),
    'restrita/avaliacao/alterar' =>
        array(
            array(
                'field' => 'id_nota',
                'label' => 'Nota',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'data',
                'label' => 'Data',
                'rules' => 'trim|required'
            )
        ),
    'restrita/estatica/alterar' =>
        array(
            array(
                'field' => 'id',
                'label' => 'id',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'texto',
                'label' => 'Texto',
                'rules' => 'trim|required'
            )
        )
);
