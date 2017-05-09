<?php

namespace adapt\currency{
    
    class model_currency extends \adapt\model{
        
        public function __construct($id = null, $data_source = null){
            parent::__construct('currency', $id, $data_source);
        }
        
        public function format($value){
            if ($value === null || $value == '') {
                return null;
            }

            $decimal_places = 2;
            $decimal_separator = '.';
            $thousands_separator = ',';
            $symbol_whole = '';
            $symbol_partial = '';
            
            if ($this->is_loaded){
                $decimal_places = $this->decimal_places;
                $decimal_separator = $this->decimal_separator;
                $thousands_separator = $this->thousands_separator;
                $symbol_whole = $this->symbol_whole_html;
                $symbol_partial = $this->symbol_partial_html;
            }
            
            return $symbol_whole . number_format($value, $decimal_places, $decimal_separator, $thousands_separator);
        }
        
    }
    
}