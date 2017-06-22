
adapt.currency = {
    format: function(value){
        var format = {
            name: '',
            iso_code: '',
            decimal_places: 2,
            decimal_separator: '.',
            thousands_separator: ',',
            symbol_partial: '',
            symbol_whole: '',
            symbol_partial_html: '',
            symbol_whole_html: ''
        };
        
        if (adapt.setting('currency.name')){
            format.name = adapt.setting('currency.name');
        }
        
        if (adapt.setting('currency.iso_code')){
            format.iso_code = adapt.setting('currency.iso_code');
        }
        
        if (adapt.setting('currency.decimal_places')){
            format.decimal_places = adapt.setting('currency.decimal_places');
        }
        
        if (adapt.setting('currency.decimal_separator')){
            format.decimal_separator = adapt.setting('currency.decimal_separator');
        }
        
        if (adapt.setting('currency.thousands_separator')){
            format.thousands_separator = adapt.setting('currency.thousands_separator');
        }
        
        if (adapt.setting('currency.symbol_whole_html')){
            format.symbol_whole_html = adapt.setting('currency.symbol_whole_html');
        }
        
        if (adapt.setting('currency.symbol_partial_html')){
            format.symbol_partial_html = adapt.setting('currency.symbol_partial_html');
        }
        
        if (adapt.setting('currency.symbol_whole')){
            format.symbol_whole = adapt.setting('currency.symbol_whole');
        }
        
        if (adapt.setting('currency.symbol_partial')){
            format.symbol_partial = adapt.setting('currency.symbol_partial');
        }
        
        return this.format_with_format(value, format);
    },
    
    format_with_format: function(value, format){
        value = parseFloat(value).toFixed(format.decimal_places);
        
        var string_value = value.toString().split(format.decimal_separator);
        if (string_value[0].length >= 4){
            string_value[0] = string_value[0].replace(/(\d)(?=(\d{3})+$)/g, '$1' + format.thousands_separator);
        }
        if (string_value[1] && string_value[1].length >= 4){
            string_value[1] = string_value[1].replace(/(\d{3})/g, '$1');
        }
        
        return format.symbol_whole + string_value.join(format.decimal_separator);
    }
};
