<?php

declare(strict_types=1);
// Carregue configurações sensíveis de um arquivo separado
require_once __DIR__ . '/../../app/config/config_secret.php';
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/functions/db.php';
require_once __DIR__ . '/../../app/models/FreteManager.php';

use App\Models\FreteManager;
use PHPUnit\Framework\TestCase;

class FreteManagerTest extends TestCase {

    private $freteManager;
    private $db_con;

    protected function setUp(): void {
        parent::setUp();
        //configura conexao com o banco de dados
        $db_con = \App\Functions\get_test_database_config();
        // Configuração do ambiente de teste instanciando a classe
        $this->freteManager = new FreteManager($db_con);
    }

    public function testNewFrete(): void {
        // Dados fictícios para o teste
        $estabelecimento = 1;
        $nome = 'Frete Teste';
        $valor = 20.50;
        $outros = 'Detalhes do frete';

        // Chama o método a ser testado
        $result = $this->freteManager->newFrete($estabelecimento, $nome, $valor, $outros);

        // Verifica se o método retornou true (inserção bem-sucedida)
        $this->assertTrue($result);

        // Verifica se a inserção está presente no banco de dados (opcional)
        $checkQuery = "SELECT * FROM frete WHERE nome = '$nome'";
        $checkResult = \App\Functions\get_test_database_config()->query($checkQuery);

        // Verifica se há pelo menos uma linha no resultado da consulta
        $this->assertGreaterThan(0, $checkResult->num_rows);
    }

    // Adicione outros métodos de teste conforme necessário

    protected function tearDown(): void {
        // Limpeza após cada teste, se necessário
        // Por exemplo, você pode excluir os dados de teste do banco de dados
        \App\Functions\get_test_database_config()->query("DELETE FROM frete WHERE nome = 'Frete Teste'");
    }
}
