<?php
    namespace pleaseHandler;


    class FunctionsHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('function',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
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

                $fn_name = false;

                if(isset($this->argv[4])){
                    $fn_name = $this->argv[4];
                    if($fn_name == '@') $fn_name = $nameFile;
                }


                $this->Execute($nameFile,$baseDir,$fn_name);

            }else{
                $this->error("Please enter your function file name");
                die();
            }
        }

        private function Execute($file_name,$baseDir,$fn_name)
        {

            $replacement_fn = '';

            if($fn_name){
                    $replacement_fn = $this->getFunctionReplacement($fn_name);
            }

            $replaces = array(
                                '_NAME_' => $file_name,
                                '//__fn__create'=> $replacement_fn
            );

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
                'demo' => "php owp make function FunctionsFileName (option name of function if you want use the same name type @)",
                'doc' => "Create your separates file functions if you need separate functions into files".
                           "Note that your can create files into specific path exemple tuto/fufo/functionfilename".
                           "will create file into folder Functions with given path",
            );
        }

        private function getFunctionReplacement($fn_name)
        {
            return "function $fn_name(){\n//Your function logic here\n\n}";
        }


    }