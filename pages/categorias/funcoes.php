<?php
// Incluir o cabeçalho
require_once "../conexao.php";

$arquivo = $_SERVER['PHP_SELF'];

function cadastrar_categoria($nome, $tipo, $categoria_pai, $descricao, $status, $id_usuario)
{
    global $conn;
    global $arquivo;

    $stmt = $conn->prepare("SELECT nome FROM CATEGORIA WHERE nome = ? AND Tipo = ?");
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("ss", $nome, $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt->close();
        header("Location: categorias.php?result=existente");
        exit;
    }

    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO CATEGORIA (Nome, Tipo, Descricao, Ativa, ID_CategoriaPai, ID_Usuario) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("ssssii", $nome, $tipo, $descricao, $status, $categoria_pai, $id_usuario);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: categorias.php?result=sucesso&tipo=" . urlencode($tipo));
    } else {
        $stmt->close();
        header("Location: categorias.php?result=erro");
    }
    exit;
}

function exibir_categorias_form()
{
    global $conn;

    $categorias = [];
    $query = "SELECT ID_Categoria, ID_CategoriaPai, Nome, Tipo FROM CATEGORIA WHERE ID_CategoriaPai IS NULL ORDER BY Nome ASC";

    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = [
                'id' => $row['ID_Categoria'],
                'nome' => $row['Nome'],
                'tipo' => $row['Tipo']
            ];
        }
    }

    return $categorias; // Retorna o array de categorias\   
}

function exibir_categoria($tipo)
{
    global $conn;

    // Definindo tipo = 0 para despesas por padrão
    $sql = "SELECT ID_Categoria, Nome, Descricao, Ativa, Tipo, ID_CategoriaPai FROM CATEGORIA WHERE Ativa = 1 AND Tipo = ? AND ID_CategoriaPai IS NULL ORDER BY ID_Categoria DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        // Exibir categorias principais
        while ($categoria = $result->fetch_assoc()) {
            echo '<div class="list-group-item category-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="category-name">' . $categoria['Nome'] . '</span>
                        </div>
                        <div class="category-actions d-flex justify-content-end align-items-center">
                            <button class="btn btn-sm btn-link edit-category" 
                                    data-id="' . $categoria['ID_Categoria'] . '" 
                                    data-name="' . $categoria['Nome'] . '" 
                                    data-type="' . $categoria['Tipo'] . '" 
                                    data-parent="' . $categoria['ID_CategoriaPai'] . '" 
                                    data-description="' . $categoria['Descricao'] . '" 
                                    data-active="' . $categoria['Ativa'] . '">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-link text-danger delete-category" data-id="' . $categoria['ID_Categoria'] . '">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    ' . obter_subcategorias($categoria['ID_Categoria']) . '
                </div>';
        }

    } else {
        echo '<div class="list-group-item category-item">
                  <div class="d-flex justify-content-between align-items-center">
                      <div>
                            <span class="category-name">Nenhuma categoria cadastrada!</span>
                      </div>
                  </div>
              </div>';
    }

    $stmt->close();
}

function obter_subcategorias($id_pai)
{
    global $conn;

    $sql = "SELECT ID_categoria as id, Nome FROM CATEGORIA WHERE Ativa = 1 AND ID_CategoriaPai = ? ORDER BY Nome";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pai);
    $stmt->execute();
    $result = $stmt->get_result();

    $html = '';

    if ($result->num_rows > 0) {
        $html .= "<div class='subcategories'>";

        while ($subcategoria = $result->fetch_assoc()) {
            $html .= '<span class="badge badge-light mr-1" data-id="' . $subcategoria["id"] . '">' . $subcategoria["Nome"] . '</span>';
        }

        $html .= "</div>";
    } else {
        $html .= "";
    }

    $stmt->close();
    return $html;
}

function editar_categoria($ID_Categoria, $Nome, $Tipo, $ID_CategoriaPai, $Descricao, $Ativa)
{
    global $conn;
    global $arquivo;

    $stmt = $conn->prepare("UPDATE CATEGORIA SET Nome = ?, Tipo = ?, ID_CategoriaPai = ?, Descricao = ?, Ativa = ? WHERE ID_Categoria = ?");
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . $conn->error);
    }

    $stmt->bind_param("ssissi", $Nome, $Tipo, $ID_CategoriaPai, $Descricao, $Ativa, $ID_Categoria);

    if ($stmt->execute()) {
        header("Location: categorias.php?result=sucesso");
    } else {
        header("Location: categorias.php?result=erro");
    }
    exit;
}   

function exibir_categoria_desativada($tipo)
{
    global $conn;

    $sql = "SELECT ID_Categoria, Nome, Descricao, Ativa, Tipo, ID_CategoriaPai FROM CATEGORIA WHERE Ativa = 0 AND Tipo = ? ORDER BY Nome ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($categoria = $result->fetch_assoc()) {
            echo '<div class="list-group-item category-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="category-name">' . $categoria['Nome'] . '</span>
                        </div>
                    </div>
                </div>';
        }
    } else {
        echo '<div class="list-group-item category-item">
                  <div class="d-flex justify-content-between align-items-center">
                      <div>
                            <span class="category-name">Nenhuma categoria desativada!</span>
                      </div>
                  </div>
              </div>';
    }

    $stmt->close();
}
?>