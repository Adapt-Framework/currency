<?php

namespace adapt\currency{
    
    class bundle_currency extends \adapt\bundle{
        
        public function __construct($data){
            parent::__construct('currency', $data);
        }
        
        public function boot(){
            if (parent::boot()){
                // Make the currency data available to the front end
                $default_currency = $this->setting('currency.default');
                $currency = new model_currency();

                if ($default_currency){    
                    if ($currency->load_by_name($default_currency)){
                        $keys = [
                            'name', 'iso_code', 'decimal_places', 'decimal_separator',
                            'thousands_separator', 'symbol_whole', 'symbol_whole_html',
                            'symbol_partial', 'symbol_partial_html'
                        ];
                        foreach($keys as $key){
                            $this->dom->head->add(new html_meta(['class' => 'setting', 'name' => 'currency.' . $key, 'content' => $currency->$key]));
                        }
                    }
                }
                
                // Add the javascript to the page
                $this->dom->head->add(new html_script(['type' => 'text/javascript', 'src' => "/adapt/{$this->name}/{$this->name}-{$this->version}/static/js/currency.js"]));
                
                
                // Add validator
                $this->sanitize->add_validator('currency', "^((-)?[0-9]+|((-)?[0-9])*\.[0-9]+)$");

                // Add validator for positive non-zero
                $this->sanitize->add_validator(
                    'currency_positive_non_zero',
                    function($value) {
                        if (!is_numeric($value)) return false;
                        if ($value < 0.01) return false;
                        if ((($value * 100) - (floor($value * 100))) > 0) return false;
                        return true;
                    },
                    "function(value){
                        value = parseFloat(value);
                        if (value === NaN) return false;
                        if (value < 0.01) return false;
                        if (((value * 100) - (Math.floor(value * 100))) > 0) return false;
                        return true;
                    }"
                );

                // HRN-130 - remove decimal places from opportunity numbers
                $this->sanitize->add_validator(
                    'currency_positive_non_zero_zero_dp',
                    function($value) {
                        if (!is_numeric($value)) return false;
                        if ($value < 0) return false;
                        if (ceil($value) != $value) return false;
                        return true;
                    },
                    "function(value){
                        value = parseFloat(value);
                        if (value === NaN) return false;
                        if (value <= 0) return false;
                        if (Math.ceil(value) != value) return false;
                        return true;
                    }"
                );
                
                // Add formatter
                $this->sanitize->add_format(
                    'currency',
                    function($value){
                        $adapt = $GLOBALS['adapt'];
                        $default_currency = $adapt->setting('currency.default');
                        $currency = new model_currency();
                        
                        if ($default_currency){    
                            $currency->load_by_name($default_currency);
                        }

                        if ($value === null) {
                            $value = 0;
                        }
                        
                        return $currency->format($value);
                    },
                    "function(value){
                        return adapt.currency.format(value);
                    }"
                );

                // HRN-130 - Whole number formatter
                $this->sanitize->add_format(
                    'currency_positive_non_zero_zero_dp',
                    function($value) {
                        $adapt = $GLOBALS['adapt'];
                        $default_currency = $adapt->setting('currency.default');
                        $currency = new model_currency();

                        if ($default_currency){
                            $currency->load_by_name($default_currency);
                        }

                        if ($value === null) {
                            $value = 0;
                        }

                        return $currency->format(floor($value));
                    },
                    "function(value){
                        return adapt.currency.format(Math.floor(value));
                    }"
                );
                    
                // Add unformatter
                $this->sanitize->add_unformat(
                    'currency',
                    function($value){
                        return preg_replace("/[^-.0-9]/", "", $value);
                    },
                    "function(value){
                        return value.toString().replace(/[^-.0-9]/g, '');
                    }"
                );
                
                return true;
            }
            
            return false;
        }
    }
    
}

