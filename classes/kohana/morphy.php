<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Адаптация для Kohana Framework отличной библиотеки морфологического анализа - phpMorphy
 *     http://phpmorphy.sourceforge.net/dokuwiki/
 * описание API: 
 *    http://phpmorphy.sourceforge.net/dokuwiki/manual
 * 
 * @author Rumata Estorsky <rumata@sputnikchess.ru>
 */
class Kohana_Morphy {

    private static $instance;
        
    /**
     * Создает и возвращает ссылку на объект phpMorphy
     * @param $lang string опередляет язык и словарь для анализа, если не указан, то берется из конфига.
     * @return  object
     */
    public static function factory($lang = '') {
        
        if(substr(Kohana::VERSION, 0, 3) >= '3.2') {
            $config = Kohana::$config->load('morphy');
        } else {
	    $config = Kohana::config('morphy');
        }
        
        // если передали язык в метод, то берем его, иначе берем язык из конфига
        $language = $lang === '' ? $config->language : $lang;
        
        // создаем экземпляр класса phpMorphy
        // обратите внимание: все функции phpMorphy являются throwable т.е.
        // могут возбуждать исключения типа phpMorphy_Exception (конструктор тоже)
        try {
            return new phpMorphy($config->dicts_dir, $language, $config->options);
        } catch (phpMorphy_Exception $e) {
            die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
        }
    }

    /**
     * Объект-одиночка для доступа к phpMorphy
     *
     * @param $lang string опередляет язык и словарь для анализа, если не указан, то берется из конфига.
     * @return  object
     */
    public static function instance($lang = '')  {
        // если объект уже создан вернем ссылку на него
        if(!empty(self::$instance)) {
            return self::$instance;
        }  
        
        // если обхект еще не был создан, то создадим его
        self::$instance = self::factory($lang);
                
        return self::$instance;
    }
    
}
