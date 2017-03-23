<?php

class FormBuilder
{
    public static function createHidden($name, $value, $params = array())
    {
        $item = "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
        
        if (isset($params['required']) && $params['required'] == true) {
            $item = "<input type=\"hidden\" id=\"{$name}Required\" name=\"{$name}Required\" value=\"on\" />";
        }
        
        return $item;
    }

    public static function createCountriesOptions($selectedValue = null)
    {
        $countries = json_decode(file_get_contents(__DIR__.'/var/countries.json'));

        $options = '';
        foreach ($countries as $countryID => $countryName) {
            $options .= '<option '.(($selectedValue == $countryID) ? 'selected="selected"' : '').' value="'.$countryID.'">'.$countryName.'</option>';
        }

        return $options;
    }

    public static function setOld($fields) {
        self::$oldVariables = $fields;     
    }

    public static function getOld($key, $default = '') {
        return (isset(self::$oldVariables[$key])) ? self::$oldVariables[$key] : $default;
    }

    private static $oldVariables = null;
}