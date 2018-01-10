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
        public function __construct($trigger, $argv,$scope ='')
        {
            $this->trigger=$trigger;
            $this->argv=$argv;

            if($scope!=''){
                if($this->argv[1] != $scope) return false;
            }

            if($this->argv[2] == $this->trigger || ($scope=='' && $this->argv[1] == $this->trigger)) $this->match = true;


        }

        public function error($message){
            Console::log($message,'red');
        }


    }