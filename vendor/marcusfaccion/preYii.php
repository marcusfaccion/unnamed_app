<?php
/**
 *  Configurações personalizadas 
 *  Coloque aqui alterações nas variáveis Globais ($_SERVER, $_POST, $_GET, $_COOKIE ...)
 */
?>
<?php
$pre_route = explode('/', $_SERVER['REQUEST_URI']);

// Caso a requisição seja para a rota /index redireciona para raiz do controller
if($pre_route[count($pre_route)-1]=='index'){
    unset($pre_route[count($pre_route)-1]);
    header('Location: '.implode('/', $pre_route));
    exit(0);
}
?>