<?php
    // Conexao com o bd. Host, usuario do bd, senha do bd, nome do bd
    $con = mysqli_connect('localhost','root','','sysfar');
      if(mysqli_connect_errno()){
          echo '1: Connect Failed'; //error code #1 = connection fail
          exit();
      }

    $dataAtual = date("Y-m-d");
    // Somente o necessario dos atributos do bd
    $sql = "SELECT barra, quantidade, pmc, preco, promocao, validade FROM Estoque WHERE quantidade > 0;";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $json["ean"] = $row["barra"];
            $json["estoque"] = $row["quantidade"];
            if ($row["validade"] >= $dataAtual){
                // Verifica se a promocao possui valor 0. Caso possua valor 0 ele verifica se o pmc
                // não é null ou 0 para atribuir ao preço
                if($row["promocao"] == 0 && $row["pmc"] != 0 && $row["pmc"] != NULL){
                    $json["preco"] = $row["pmc"];
                }else{
                    $json["preco"] = $row["promocao"];
                }
            }else{
                // Como há duas colunas, uma preço e uma pmc, verifica se o valor da coluna pmc é 
                // zero ou null. Caso seja, ele atribui o valor da coluna preco
                if($row["pmc"] == NULL or $row["pmc"] == 0){
                    $json["preco"] = $row["preco"];
                }else{
                    $json["preco"] = $row["pmc"];
                }
            }
            $list[] = $json;
        }

        echo json_encode($list);
    
    } else {
        echo "0 results";
    }
?>
