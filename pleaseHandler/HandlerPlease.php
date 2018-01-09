<?php
    namespace pleaseHandler;

    use Console;

    abstract class HandlerPlease{

        //use \HandlerTrait;

        public $trigger;
        public static $doc;
        public static $params;

        public static $_trigger;

        public $argv;
        public $match = false;

        /**
         * HandlerPlease constructor.
         * @param $trigger
         * @param $argv
         */
        public function __construct($trigger, $argv)
        {
            $this->trigger=$trigger;
            $this->argv=$argv;

            if($this->argv[2] == $this->trigger) $this->match = true;


        }

        public function error($message){
            Console::log($message,'red');
        }


    }