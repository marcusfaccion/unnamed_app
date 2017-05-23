var directions = {
    'N': 'norte',
    'NE': 'nordestet',
    'E': 'leste',
    'SE': 'sudeste',
    'S': 'sul',
    'SW': 'sudoeste',
    'W': 'oeste',
    'NW': 'norte'
};

var maneuvers = {
    '1': 'continue',
    '2': 'mantenha-se a direita',
    '3': 'vire a direita',
    '4': 'vire totalmente a direita',
    '5': 'retorno',
    '6': 'vire totalmente a esquerda',
    '7': 'vire a esquerda',
    '8': 'mantenha-se a esquerda',
    '9': 'ponto de rota',
    '10': 'parta',
    '11': 'entre na rotatória',
    '15': 'chegada'
};

var travelMode = {
    'mapbox.driving': ['inacessível', 'dirigindo', 'balsa', 'ponte móvel'],
    'mapbox.walking': ['inacessível', 'caminhando', 'balsa'],
    'mapbox.cycling': ['inacessível', 'pedalando', 'caminhando', 'balsa', 'trêm', 'ponte móvel']
};

var textInstructions = {
    '1': 'Continue[ na {way_name}]',
    '2': 'Mantenha-se a direita[ na {way_name}]',
    '3': 'Vire a direita[ na {way_name}]',
    '4': 'Vire totalmente a direita[ na {way_name}]',
    '5': 'Faça um retorno[ na {way_name}]',
    '6': 'Vire totalmente a esquerda[ na {way_name}]',
    '7': 'Vire a esquerda[ na {way_name}]',
    '8': 'Mantanha-se a esquerda[ na {way_name}]',
    '9': 'Mantenha a rota[ e continue na {way_name}]',
    '10': 'Siga para {direction}[ na {way_name}]',
    '11': 'Entre na rotatória[ e pegue a saída na {way_name}]',
    '15': 'Você chegou ao seu destino'
};