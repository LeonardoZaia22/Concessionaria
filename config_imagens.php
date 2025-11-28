<?php
// CONFIGURAÇÃO SIMPLES DAS IMAGENS - MAPEAMENTO COMPLETO
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

function getImagemCarro($modelo, $marca) {
    global $config_imagens;
    
    // Procura pelo modelo no array
    foreach($config_imagens as $key => $imagem) {
        if (stripos($modelo, $key) !== false) {
            return $imagem;
        }
    }
    
    // Se não encontrou, tenta pela marca
    if (stripos($marca, 'ford') !== false) {
        return 'ford_maverick_gt_1974_principal.jpg';
    } elseif (stripos($marca, 'chevrolet') !== false) {
        return 'che1.jpeg';
    } elseif (stripos($marca, 'volkswagen') !== false) {
        return 'fus1.jpeg';
    } elseif (stripos($marca, 'fiat') !== false) {
        return 'fiat_uno_1991_principal.jpeg';
    }
    
    // Imagem padrão
    return 'ford_maverick_gt_1974_principal.jpg';
}
?>  