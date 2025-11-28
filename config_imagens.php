<?php
// Aqui eu fiz uma lista (array) que liga nomes de carros à imagem deles
$config_imagens = [
    // Ford Maverick
    'Maverick' => 'ford_maverick_gt_1974_principal.jpg',
    'maverick' => 'ford_maverick_gt_1974_principal.jpg',
    'GT' => 'GT1.jpeg',
    
    // Volkswagen Fusca
    'Fusca' => 'fus1.jpeg',
    'fusca' => 'fus1.jpeg',
    
    // Chevrolet Opala
    'Opala' => 'che1.jpeg',
    'opala' => 'che1.jpeg',
    'Comodoro' => 'che1.jpeg',
    'comodoro' => 'che1.jpeg',
    
    // Chevrolet Chevette
    'Chevette' => 'chevrolet_chevette_hatch_1985_principal.jpg',
    'chevette' => 'chevrolet_chevette_hatch_1985_principal.jpg',
    
    // Ford Corcel
    'Corcel' => 'ford_corcel_it_1978_principal.jpg',
    'corcel' => 'ford_corcel_it_1978_principal.jpg',
    
    // Chevrolet Camaro
    'Camaro' => 'chevrolet_camaro_1970_principal.jpg',
    'camaro' => 'chevrolet_camaro_1970_principal.jpg',
    
    // Fiat Uno
    'Uno' => 'fiat_uno_1991_principal.jpeg',
    'uno' => 'fiat_uno_1991_principal.jpeg',
    
    // Brasília
    'Brasília' => 'bra1.jpeg',
    'brasília' => 'bra1.jpeg',
    'Brasilia' => 'bra1.jpeg',
    'brasilia' => 'bra1.jpeg'
];

// Função que retorna a imagem do carro baseado no modelo e na marca
function getImagemCarro($modelo, $marca) {
    global $config_imagens; // pega o array lá de cima
    
    // Aqui eu procuro se o modelo existe na lista
    foreach($config_imagens as $key => $imagem) {
        // stripos procura uma palavra dentro da outra sem diferenciar maiúsculas/minúsculas
        if (stripos($modelo, $key) !== false) {
            return $imagem; // se achar, volta a imagem certa
        }
    }
    
    // Se não achou pelo modelo, aqui tenta pela marca
    if (stripos($marca, 'ford') !== false) {
        return 'ford_maverick_gt_1974_principal.jpg';
    } elseif (stripos($marca, 'chevrolet') !== false) {
        return 'che1.jpeg';
    } elseif (stripos($marca, 'volkswagen') !== false) {
        return 'fus1.jpeg';
    } elseif (stripos($marca, 'fiat') !== false) {
        return 'fiat_uno_1991_principal.jpeg';
    }
    
    // Se não achou nada, volta uma imagem padrão
    return 'ford_maverick_gt_1974_principal.jpg';
}
?>
