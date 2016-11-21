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
                        
                        return $currency->format($value);
                    },
                    "function(value){
                        return adapt.currency.format(value);
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

