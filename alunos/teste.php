<?php
// alunos/teste_conexao.php
require_once __DIR__ . '/../conexao/conexao.php';

echo "<h2>Teste de Conexão - Correto</h2>";

// Testar consulta
$sql = "SELECT * FROM vw_status_academico";
$result = executarQuery($sql);

echo "<table border='1' cellpadding='5'>";
echo "<tr style='background:#667eea; color:white;'>";
echo "<th>Matrícula</th><th>Nome</th><th>Curso</th><th>Campus</th><th>Status</th><th>Acesso</th>";
echo "</tr>";

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $cor = $row['acesso_liberado'] == 'LIBERADO' ? '#d4edda' : '#f8d7da';
    echo "<tr style='background:{$cor}'>";
    echo "<td>" . ($row['matricula'] ?? '') . "</td>";
    echo "<td>" . ($row['nome'] ?? '') . "</td>";
    echo "<td>" . ($row['curso'] ?? '') . "</td>";
    echo "<td>" . ($row['campus'] ?? '') . "</td>";
    echo "<td>" . ($row['status_aluno'] ?? '') . "</td>";
    echo "<td>" . ($row['acesso_liberado'] ?? '') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Testar tipos de solicitações
echo "<h3>Tipos de Solicitações Cadastrados:</h3>";
$sql = "SELECT * FROM tipos_solicitacoes ORDER BY id";
$result = executarQuery($sql);

echo "<ul>";
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    echo "<li><strong>ID {$row['id']}:</strong> {$row['descricao']}</li>";
}
echo "</ul>";

// Testar inserção de uma solicitação
echo "<h3>Teste de Inserção de Solicitação:</h3>";
$sql = "EXEC sp_criar_solicitacao ?, ?, ?";
$params = array(1, 1, 'Teste de solicitação via PHP');
$result = executarQuery($sql, $params);

if ($result) {
    $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    echo "<div style='background:#d4edda; padding:10px; border-radius:5px;'>";
    echo "✅ " . ($row['mensagem'] ?? 'Solicitação criada com sucesso!');
    if (isset($row['id_solicitacao'])) {
        echo " - ID da solicitação: " . $row['id_solicitacao'];
    }
    echo "</div>";
}
?>