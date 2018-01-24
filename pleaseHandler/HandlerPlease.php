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
        public $scope = '';

        protected $trigger_childes = array();

        abstract public static function getDoc();
        abstract protected function Handler();

        /**
         * @var array
         */
        private $child_trigger;


        public function __construct($trigger, $argv, $scope ='', $trigger_childes=array())
        {
            $this->trigger=$trigger;
            $this->argv=$argv;
            $this->scope=$scope;
            $this->trigger_childes=$trigger_childes;

            $this->isMatch();


        }

        /*
         * Test if is the handler taker
         */
        protected function isMatch()
        {
            if($this->scope!=''){
                if($this->argv[1] != $this->scope){
                    return false;
                }
            }

            if($this->argv[2] == $this->trigger || ($this->scope=='' && $this->argv[1] == $this->trigger)) {
                $this->match = true;
            }

        }

        public function error($message){
            Console::log($message,'red');
        }

        public function success($message){
            Console::log($message,'green');
        }

        public function warning($message){
            Console::log($message,'brown');
        }

        /*
         * is in the Child Trigger Array
         */
        protected  function isChildTrigger($arg){
            return in_array($arg,$this->trigger_childes);
        }

        /*
         * Parse arg to extract dir_name & file_name
         * then create dir
         */
        protected function getFileAndDirNameAndPrepareDirectory($arg,$folderParent){

            $baseDir = dirname($arg);
            $nameFile = basename($arg);



            if($baseDir && $baseDir!= '.' && $baseDir!= '/'){
                CreteElement::CreateDirectory("$folderParent/".$baseDir);
            }else{
                $baseDir = '';
            }

            return array($baseDir,$nameFile);

        }

        protected function getFullName($baseDir,$filename,$folder){


            if($baseDir && !empty($baseDir)){
                $file_dist = "$folder/$baseDir/$filename.php";
            }else{
                $file_dist = "$folder/$filename.php";
            }

            return $file_dist;
        }


    }