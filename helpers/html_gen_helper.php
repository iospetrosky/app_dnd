<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('htag')) {
    
    function htag($tag, $content, $attrib = false) {
        $ret = "<{$tag} ";
        if (is_object($attrib)) { $attrib = (array)$attrib; }
        foreach($attrib as $att => $val) {
            if ($att == 'style') {
                if (is_array($val)) {
                    $val = implode(';',$val);
                }
                $ret .= "{$att} = '{$val}' "; 
            } else {
                $ret .= "{$att} = '{$val}' ";
            }
        }
        $ret .= ">{$content}";        
        $ret .= "</{$tag}>";
        
        return $ret;
    }
    
}

if ( ! function_exists('span')) {

    function span($content, $attrib = false) {
        return htag('SPAN',$content,$attrib);
    }
    
}

if ( ! function_exists('div')) {

    function div($content, $attrib = false) {
        return htag('DIV',$content,$attrib);
    }
    
}