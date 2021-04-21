<?php

class Postagem 
{
 
    public static function listarTodasPostagem()
    {
        $sql = "SELECT * FROM postagem ORDER BY id DESC";
        $con = Conexao::getInstance()->prepare($sql);
        $con->execute();

        if ($con->rowCount() >= 1) {
        
        $resultado = array();
        
        while ($row = $con->fetchObject('Postagem')) 
        {
            $resultado[] = $row;
        }
        return $resultado;
        } else {
            throw new Exception("Ocorreu algum erro!");
        }
    }

    public static function selecionarId()
    {

        $dados = explode('/',filter_input(INPUT_GET,'pag',FILTER_SANITIZE_STRING));
        $id = $dados[2];

        $sql = "SELECT * FROM postagem WHERE id = :id";
        $con = Conexao::getInstance()->prepare($sql);
        $con->bindvalue('id',$id, PDO::PARAM_INT);
        $con->execute();
        
        $resultado = array();

        $resultado = $con->fetchObject('Postagem');

        if (empty($resultado)) {
            throw new Exception("Aconteceu algum erro");
        } else {
            $resultado->comentarios = Comentario::SelecionarComentarios($resultado->id);     
        } 
            
        return $resultado;
    }

    public static function insert()
    {
        $acao = filter_input(INPUT_POST, 'cadastrar', FILTER_SANITIZE_STRING);
        $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
        $conteudo = filter_input(INPUT_POST, 'conteudo', FILTER_SANITIZE_STRING);
        
        // Verifica se clicou no botão Cadastrar, se não tiver clicado e chamado o else
        if (!isset($acao)) 
        {
            throw new Exception("Pagina não encontrada");
        } else {
            //Verifica se esta passando Titulo e conteudo
           if (!isset($titulo) OR !isset($conteudo) OR empty($titulo) OR empty($conteudo)) 
           {
            throw new Exception("Pagina não encontrada");
           } else {
                $sql= "INSERT INTO postagem (titulo,conteudo) VALUES (:titulo, :conteudo)";
                $con = Conexao::getInstance()->prepare($sql);
                $con->bindvalue('titulo',$titulo,PDO::PARAM_STR);
                $con->bindvalue('conteudo',$conteudo,PDO::PARAM_STR);
                $con->execute();

                if ($con->rowCount() == 1) 
                {
                   return true;
                }
           }
        }
        
    }
}

?>