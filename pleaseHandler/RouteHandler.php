<?php
    namespace pleaseHandler;

    class RouteHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('route',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){

                // Call Library Core
                $this->RegisterRouteLibrary();

                $this->Execute($this->argv[3],$this->argv[4]);


            }else{
                echo "Please Enter two params path example /foo/tuto and the handler name";
            }
        }

        private function Execute($path, $HandlerName)
        {
            $create = new CreteElement("Route/$HandlerName.php",array("_PATH_" => $path,'_HANDLER_'=>$HandlerName),'route',__DIR__.'/../ressources/src/Route.php','RegisterRoute');
            $create->CreateItem();
            die();

        }

        private function RegisterRouteLibrary()
        {
            CreteElement::CreateDirectory('Route');
            CreteElement::AddCallToFunctionIntoFile('/libs/BaseLibs.php','$this->RegisterRouterCore();','RegisterModules');
        }
    }