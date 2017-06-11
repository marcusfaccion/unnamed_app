var cardinal_directions = {
    'N': 'north',
    'NE': 'northeast',
    'E': 'east',
    'SE': 'southeast',
    'S': 'south',
    'SW': 'southwest',
    'W': 'west',
    'NW': 'northwest'
};

var maneuvers = {
    '1': 'continue',
    '2': 'bear right',
    '3': 'turn right',
    '4': 'sharp right',
    '5': 'u-turn',
    '6': 'sharp left',
    '7': 'turn left',
    '8': 'bear left',
    '9': 'waypoint',
    '10': 'depart',
    '11': 'enter roundabout',
    '15': 'arrive'
};

var travelMode = {
    'mapbox.driving': ['unaccessible', 'driving', 'ferry', 'movable bridge'],
    'mapbox.walking': ['unaccessible', 'walking', 'ferry'],
    'mapbox.cycling': ['unaccessible', 'cycling', 'walking', 'ferry', 'train', 'moveable bridge']
};

var textInstructions = {
    '1': 'Continue[ on {way_name}]',
    '2': 'Bear right[ onto {way_name}]',
    '3': 'Turn right[ onto {way_name}]',
    '4': 'Make a sharp right[ onto {way_name}]',
    '5': 'Make a U-turn[ onto {way_name}]',
    '6': 'Make a sharp left[ onto {way_name}]',
    '7': 'Turn left[ onto {way_name}]',
    '8': 'Bear left[ onto {way_name}]',
    '9': 'Reach waypoint[ and continue on {way_name}]',
    '10': 'Head {direction}[ on {way_name}]',
    '11': 'Enter the roundabout[ and take the exit onto {way_name}]',
    '15': 'You have arrived at your destination'
};