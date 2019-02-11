<?php

class TranslationHelper
{
    /**
     * Returns the translation for a key
     *
     * @param $string
     * @param bool $return
     *
     * @return string
     */
    public static function _t($string, $return = false)
    {
        $locale = AuthHelper::getUserLocale();

        $translationFile = TRANSLATIONDIR . '/'.$locale.'.ini';
        if (!file_exists($translationFile)) {
            $translationFile = TRANSLATIONDIR . '/en.ini';
        }
        
        $trans = parse_ini_file($translationFile, false);
        if ($trans !== false) {

            if (array_key_exists($string, $trans)) {
                if ($return) {
                    return $trans[$string];
                } else {
                    echo $trans[$string];
                    return '';
                }
            } else {
                $nf = 'Translation &quot;' . $string . '&quot; not found!';
                if ($return) {
                    return $nf;
                } else {
                    echo $nf;
                }
            }
        }
        return 'Translation not found!';
    }
}