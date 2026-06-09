<?php
// conexao/conexao.php

// CORRIGIDO: Use a instância correta do seu SQL Server
$serverName = "localhost\SQLEXPRESS";  // <- DESCOMENTE ESTA LINHA e comente as outras

// Remova ou comente as outras opções de servidor
// $serverName = "localhost";
// $serverName = "127.0.0.1";

$connectionOptions = array(
    "Database" => "teste_IESB",
    "Uid" => "teste_IESB",
    "PWD" => "9Vu2JZGc9es5",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true,
    "Encrypt" => false,
    "ReturnDatesAsStrings" => true
);

// Conectar ao SQL Server (sem mensagens de sucesso para não interferir)
$conn = sqlsrv_connect($serverName, $connectionOptions);



// Função para executar queries
function executarQuery($sql, $params = [])
{
    global $conn;

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    return $stmt;
}

function executarProcedure($procedure, $params = [])
{
    global $conn;

    $placeholders = [];

    foreach ($params as $param) {
        $placeholders[] = '?';
    }

    $sql = "EXEC {$procedure}";

    if (!empty($placeholders)) {
        $sql .= ' ' . implode(',', $placeholders);
    }

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        throw new Exception(print_r(sqlsrv_errors(), true));
    }

    // Buscar o resultado da procedure
    $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    // Liberar o statement
    sqlsrv_free_stmt($stmt);
    
    return $result;
}

?>