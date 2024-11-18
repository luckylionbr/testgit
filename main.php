<?php
// Conexão ao banco de dados
$host = 'localhost';
$dbname = 'tabeladeprodutos';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}

// Buscar dados da tabela
$query = $pdo->query("SELECT id, codigo_barras FROM produtos");
$produtos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerador de Códigos de Barras</title>
    <link rel="shortcut icon" href="icone.jpg">
    <link rel="stylesheet" href="style3.css">    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>

<body>

<div id="cabecalho">
Gerador de codigo de Barras
</div>

<div class="table-container" id="tabela">
<table>
    <thead>
        <tr>
            <th id="id">ID</th>
            <th id="nome">Nome do Produto</th>
            <th id="adicao">Data de Adição</th>
            <th id="producao">Data de Produção</th>
            <th id="empresa">Empresa</th>
            <th id="versao">Versão</th>
            <th id="codigo">Código de Barras</th>
        </tr>
    </thead>
    <tbody>
    <?php
        try {
            // Conexão com o banco de dados usando PDO
            $conexao = new PDO("mysql:host=localhost;dbname=tabeladeprodutos", "root", "");
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta SQL para selecionar os produtos
            $sql = "SELECT id, nome_produto, data_adicao, data_producao, empresa, versao, codigo_barras FROM produtos";
            $resultado = $conexao->query($sql);

            if ($resultado->rowCount() > 0) {
                // Exibir os dados em cada linha da tabela onclick="mostrarCodigoBarras('<?php echo $produto['codigo_barras'];
                foreach ($resultado as $row) {
                    echo "<tr class='barcode-row' data-barcode='" . $row["codigo_barras"] . "' onclick='mostrarCodigoBarras(". $row["codigo_barras"] .")'>";
                    echo "<td>".$row["id"]."</td>";
                    echo "<td>".$row["nome_produto"]."</td>";
                    echo "<td>".$row["data_adicao"]."</td>";
                    echo "<td>".$row["data_producao"]."</td>";
                    echo "<td>".$row["empresa"]."</td>";
                    echo "<td>".$row["versao"]."</td>";
                    echo "<td>".$row["codigo_barras"]."</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum produto encontrado</td></tr>";
            }
        } catch(PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
        }
        ?>
    </tbody>
</table>
</div>

<div id="meiomid">
    <div id="meioleft">
        <h2>Código de barras:</h2>
        <div id="codigoBarrasDiv">CODIGO DE BARRAS</div>
    </div>
    <div id="botao">
        <p id="txbotao">Selecione a</br> quantidade:</p> <input type="number" id="qtd" value="1"> <button id="barcode-button">Gerar PDF</button>
    </div>

</div>
<script>
    // Adiciona um evento de clique a todas as linhas da tabela
    var rows = document.getElementsByClassName('barcode-row');
    for (var i = 0; i < rows.length; i++) {
        rows[i].addEventListener('click', function() {
            // Ao clicar, obtém o valor do código de barras armazenado no atributo data da linha e o exibe no botão
            var barcode = this.getAttribute('data-barcode');
            document.getElementById('barcode-button').setAttribute('data-barcode', barcode);
        });
    }

    function mostrarCodigoBarras(codigo) {
            document.getElementById('codigoBarrasDiv').innerHTML = codigo;
        }
        function gerarPDF() {
    var quantidade = document.getElementById('quantidade').value;
    var bgColor = document.getElementById('background-color').value;
    var textColor = document.getElementById('text-color').value;

    // Capturar o conteúdo da célula de código de barras
    var codigoBarras = document.querySelector('.codigo-barras') ? document.querySelector('.codigo-barras').innerText : '';

    if (codigoBarras === '') {
        alert('Nenhuma célula de código de barras encontrada.');
        return;
    }

    // Carregar o módulo da biblioteca jsPDF
    const { jsPDF } = window.jspdf;

    // Criar uma nova instância do PDF com tamanho de página padrão
    var doc = new jsPDF('landscape', 'px', [1240, 877]);

    // Dimensões de cada bloco
    var blockWidth = 600;
    var blockHeight = 400;
    var blocksPerRow = 2; // 2 blocos por linha
    var blocksPerColumn = 4; // 4 linhas por página

    // Adicionar o conteúdo em blocos
    for (var i = 0; i < quantidade; i++) {
        if (i > 0 && i % (blocksPerRow * blocksPerColumn) === 0) {
            doc.addPage(); // Adicionar uma nova página após preencher a atual
        }

        // Calcular a posição do bloco na página
        var row = Math.floor((i % (blocksPerRow * blocksPerColumn)) / blocksPerRow);
        var col = i % blocksPerRow;
        var xOffset = col * blockWidth;
        var yOffset = row * blockHeight;

        // Desenhar o retângulo de fundo para o bloco específico
        doc.setFillColor(bgColor);
        doc.rect(xOffset, yOffset, blockWidth, blockHeight, 'F');

        // Centralizar o texto no bloco
        doc.setTextColor(textColor);
        doc.setFontSize(24);
        var textX = xOffset + blockWidth / 2;
        var textY = yOffset + blockHeight / 2;
        doc.text(codigoBarras, textX, textY, { align: 'center', baseline: 'middle' });
    }

    // Baixar o PDF
    doc.save('documento.pdf');
}

</script>













</body>
</html>