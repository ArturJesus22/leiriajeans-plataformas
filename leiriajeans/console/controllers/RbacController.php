<?php

namespace console\controllers;

use yii\console\controller;
use yii;

class RbacController extends Controller
{
    public function actionInit()
    {

        //                 * Permissões de todos
        //                 *  - Ver, Editar os seus dados pessoais
        //                 *
        //                 * Permissões de admin
        //                 *  - Ver, Editar, Criar e Apagar Colaboradores/Users
        //                 *  - Ver, Editar, Criar empresas
        //                 *  - Herda as permissoes de funcionário
        //                 *
        //                 * Permissões de Funcionário
        //                 *  - Ver, Editar encomendas
        //                 *  - Ver, Editar, Criar e Apagar produtos
        //                 *  - Ver, Editar, Criar e Apagar categorias
        //                 * - Ver, Apagar avaliações
        //                 * - Ver, Editar, Criar e Apagar ivas
        //                 *  - Ver, Editar, Criar e Apagar Clientes
        //


        $auth = Yii::$app->authManager;

        //consultar dados
        $permission_verUsers = $auth->createPermission('verUsers');
        $auth->add($permission_verUsers);
        $permission_verProdutos = $auth->createPermission('verProdutos');
        $auth->add($permission_verProdutos);
        $permission_verCategorias = $auth->createPermission('verCategorias');
        $auth->add($permission_verCategorias);
        $permission_verAvaliacoes = $auth->createPermission('verAvaliacoes');
        $auth->add($permission_verAvaliacoes);
        $permission_verEncomendas = $auth->createPermission('verEncomendas');
        $auth->add($permission_verEncomendas);
        $permission_verFaturas = $auth->createPermission('verFaturas');
        $auth->add($permission_verFaturas);
        $permission_verIvas = $auth->createPermission('verIvas');
        $auth->add($permission_verIvas);
        $permission_verEmpresa = $auth->createPermission('verEmpresa');
        $auth->add($permission_verEmpresa);

        //backend-acess
        $permission_backendAccess = $auth->createPermission('backendAccess');
        $auth->add($permission_backendAccess);

        //users
        $permission_editarUsers = $auth->createPermission('editarUsers');
        $auth->add($permission_editarUsers);
        $permission_criarUsers = $auth->createPermission('criarUsers');
        $auth->add($permission_criarUsers);
        $permission_apagarUsers = $auth->createPermission('apagarUsers');
        $auth->add($permission_apagarUsers);

        //produtos
        $permission_editarProdutos = $auth->createPermission('editarProdutos');
        $auth->add($permission_editarProdutos);
        $permission_criarProdutos = $auth->createPermission('criarProdutos');
        $auth->add($permission_criarProdutos);
        $permission_apagarProdutos = $auth->createPermission('apagarProdutos');
        $auth->add($permission_apagarProdutos);

        //categorias
        $permission_editarCategorias = $auth->createPermission('editarCategorias');
        $auth->add($permission_editarCategorias);
        $permission_criarCategorias = $auth->createPermission('criarCategorias');
        $auth->add($permission_criarCategorias);
        $permission_apagarCategorias = $auth->createPermission('apagarCategorias');
        $auth->add($permission_apagarCategorias);

        //avaliações
        $permission_apagarAvaliacoes = $auth->createPermission('apagarAvaliacoes');
        $auth->add($permission_apagarAvaliacoes);

        //faturas
        $permission_editarFaturas = $auth->createPermission('editarFaturas');
        $auth->add($permission_editarFaturas);

        //Permissões para editar dados de ivas
        $permission_editarIvas = $auth->createPermission('editarIvas');
        $auth->add($permission_editarIvas);
        $permission_criarIvas = $auth->createPermission('criarIvas');
        $auth->add($permission_criarIvas);
        $permission_apagarIvas = $auth->createPermission('apagarIvas');
        $auth->add($permission_apagarIvas);

        //Permissões para editar dados de empresas
        $permission_editarEmpresa = $auth->createPermission('editarEmpresa');
        $auth->add($permission_editarEmpresa);

        //dados pessoais
        $permission_editarDadosPessoais = $auth->createPermission('editarDadosPessoais');
        $auth->add($permission_editarDadosPessoais);

        //encomendas
        $permission_fazerEncomendas = $auth->createPermission('fazerEncomendas');
        $auth->add($permission_fazerEncomendas);

        //==========

        //ADD ROLE CLIENTE

        $role_cliente = $auth->createRole('cliente');
        $auth->add($role_cliente);
        $auth->addChild($role_cliente, $permission_fazerEncomendas);
        $auth->addChild($role_cliente, $permission_editarDadosPessoais);
        $auth->addChild($role_cliente, $permission_verProdutos);

        //ADD ROLE FUNCIONARIO

        $role_funcionario = $auth->createRole('funcionario');
        $auth->add($role_funcionario);
        //Users
        $auth->addChild($role_funcionario, $permission_verUsers);
        $auth->addChild($role_funcionario, $permission_editarUsers);
        $auth->addChild($role_funcionario, $permission_criarUsers);
        $auth->addChild($role_funcionario, $permission_apagarUsers);
        //Produto
        $auth->addChild($role_funcionario, $permission_verProdutos);
        $auth->addChild($role_funcionario, $permission_editarProdutos);
        $auth->addChild($role_funcionario, $permission_criarProdutos);
        $auth->addChild($role_funcionario, $permission_apagarProdutos);
        //Categoria
        $auth->addChild($role_funcionario, $permission_verCategorias);
        $auth->addChild($role_funcionario, $permission_editarCategorias);
        $auth->addChild($role_funcionario, $permission_criarCategorias);
        $auth->addChild($role_funcionario, $permission_apagarCategorias);
        //Avaliações
        $auth->addChild($role_funcionario, $permission_verAvaliacoes);
        $auth->addChild($role_funcionario, $permission_apagarAvaliacoes);
        //Iva
        $auth->addChild($role_funcionario, $permission_verIvas);
        $auth->addChild($role_funcionario, $permission_editarIvas);
        $auth->addChild($role_funcionario, $permission_criarIvas);
        $auth->addChild($role_funcionario, $permission_apagarIvas);
        //Encomendas
        $auth->addChild($role_funcionario, $permission_verEncomendas);
        //Fatura
        $auth->addChild($role_funcionario, $permission_verFaturas);
        $auth->addChild($role_funcionario, $permission_editarFaturas);
        //BackEnd
        $auth->addChild($role_funcionario, $permission_backendAccess);

        //ADD ROLE ADMIN

        $role_admin = $auth->createRole('admin');
        $auth->add($role_admin);
        //herda todas as permissoes de funcionario
        $auth->addChild($role_admin, $role_funcionario);
        //Empresa
        $auth->addChild($role_admin, $permission_verEmpresa);
        $auth->addChild($role_admin, $permission_editarEmpresa);


        $auth->assign($role_admin, 1);
        $auth->assign($role_funcionario, 2);
        $auth->assign($role_cliente, 3);


    }
}