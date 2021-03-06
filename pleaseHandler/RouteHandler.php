<?php
    namespace pleaseHandler;

    class RouteHandler extends HandlerPlease{
        protected $PathDist = 'Route';
        public function __construct($argv)
        {
            parent::__construct('route',$argv,'make');

            $this->Handler();
        }

        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){

                // Call Library Core
                $this->RegisterRouteLibrary();

                $parse =  $this->getFileAndDirNameAndPrepareDirectory($this->argv[4],$this->PathDist);
                $this->Execute($this->argv[3],$parse[1],$parse[0]);


            }else{
                $this->error("Please Enter two params path example /foo/tuto and the handler name");
                die();
            }
        }

        private function Execute($path, $HandlerName,$dist)
        {
            $file_dist = $this->getFullName($dist,$HandlerName,$this->PathDist);
            $create = new CreteElement($file_dist,array("_PATH_" => $path,'_HANDLER_'=>$HandlerName),'route',__DIR__.'/../ressources/src/Route.php','RegisterRoute');
            $create->CreateItem();
            die();

        }

        private function RegisterRouteLibrary()
        {
            CreteElement::CreateDirectory('Route');
            CreteElement::AddCallToFunctionIntoFile('/libs/BaseLibs.php','$this->RegisterRouterCore();','RegisterModules');
        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'route',
                'demo' => "php owp route YourPath (exemple /tuto/fufo/:param) and the controller Name as second param",
                'doc' => "Create specific Route to your own wordpress project is often used so we make this easy."
            );
        }
    }