<?php
    trait HandlerTrait{
        public static function  getDoc()
        {
            return array(
                'trigger' => self::$_trigger,
                'params' => self::$params,
                'doc' => self::$doc,
            );
        }
    }