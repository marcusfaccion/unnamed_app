<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace marcusfaccion\helpers;

/**
 * Description of String
 *
 * @author Marcus
 */
class String {
    
    const PTBR_DIACR_SEARCH = [
        'á', 'à', 'ã', 'â',
        'é', 'è', 'ê',
        'í', 'ì', 'î',
        'ó', 'ò', 'õ', 'ô',
        'ú', 'ù', 'û',
        'ç',
    ];
    const PTBR_DIACR_REPLACE = [
        'a', 'a', 'a', 'a',
        'e', 'e', 'e',
        'i', 'i', 'i',
        'o', 'o', 'o', 'o',
        'u', 'u', 'u',
        'c',
    ];
    static function removeChars($string = '', $remove = []){
        ;;
    }
    
    /**
     * Troca caracteres ou substrings e retorna o texto final transformado
     * @param string $string Cadeias de caracteres para ser transformada
     * @param mixed $search  Array de caracteres ou substrings para serem substituidos
     * @param mixed $replace Array de caracteres ou substrings para serem incluídos
     * @param int $max_changes Número máximo de trocas efetuadas
     * @return string A String final transformada
     */
    static function changeChars($string = '', $search = [], $replace = [], $max_changes = null){
        return str_replace($search, $replace, $string, $count);
    }
}
