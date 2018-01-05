<?php
    namespace pleaseHandler;


    class FunctionsHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('function',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3])){

                $baseDir = dirname($this->argv[3]);
                $nameFile = basename($this->argv[3]);

                CreteElement::CreateDirectory('Functions');

                if($baseDir && $baseDir!= '.' && $baseDir!= '/'){
                    $this->PrepareForSetUpFunctions($baseDir);
                }else{
                    $baseDir = '';
                }


                $this->Execute($nameFile,$baseDir);

            }else{
                echo "Please enter your function file name";
            }
        }

        private function Execute($file_name,$baseDir)
        {
            $replaces = array("_NAME_" => $file_name);

            $file_dist="";

            if($baseDir && !empty($baseDir)){
                $file_dist = "Functions/$baseDir/$file_name.php";
            }else{
                $file_dist = "Functions/$file_name.php";
            }


            $create = new CreteElement(
                $file_dist,
                $replaces,'Functions',__DIR__.'/../ressources/src/Function.php',
                'RegisterFunctions'
            );

            $create->CreateItem();
            die();

        }

        private function PrepareForSetUpFunctions($nameModule)
        {
            CreteElement::CreateDirectory('Functions/'.$nameModule);

        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'function',
                'demo' => "php owp make function FunctionsFileName ",
                'doc' => "Create your separates file functions if you need separate functions into files
                           Note that your can create files into specific path exemple tuto/fufo/functionfilename
                           will create file into folder Functions with given path",
            );
        }


    }