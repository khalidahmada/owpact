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

        protected $trigger_childes = array();
        /**
         * @var array
         */
        private $child_trigger;


        /**
         * HandlerPlease constructor.
         * @param $trigger
         * @param $argv
         * @param string $scope
         * @param array $child_trigger
         */
        public function __construct($trigger, $argv, $scope ='', $trigger_childes=array())
        {
            $this->trigger=$trigger;
            $this->argv=$argv;
            $this->trigger_childes=$trigger_childes;

            if($scope!=''){
                if($this->argv[1] != $scope){
                    return false;
                }
            }

            if($this->argv[2] == $this->trigger || ($scope=='' && $this->argv[1] == $this->trigger)) $this->match = true;



        }

        public function error($message){
            Console::log($message,'red');
        }

        /*
         * is in the Child Trigger Array
         */
        protected  function isChildTrigger($arg){
            return in_array($arg,$this->trigger_childes);
        }


    }