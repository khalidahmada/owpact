#!/usr/bin/php
<?php
    use pleaseHandler\CreteElement;

    class OWPactPlease{

        /**
         * OWPactPlease constructor.
         */
        public function __construct()
        {
            $this->boot();
        }

        private function boot()
        {

            $this->RegisterModules();
            $this->HandlerCLI();




        }

        private function RegisterModules()
        {
            $this->RegisterModule('base');
            $this->RegisterModule('pleaseHandler/CreateElement');



        }

        private function RegisterModule($module)
        {
            require_once $module.'.php';
        }

        private function HandlerCLI()
        {

            global $argv;

            if($argv){
                $current_cmd = $argv[1];

                if($current_cmd == 'make'){

                    $error_not_valide_make = "Please Enter valide Entry example : (ajax , hook , helper repo , module )";
                    if(isset($argv[2])){
                        switch($argv[2]){
                            case 'ajax':
                                    if(isset($argv[3]) && isset($argv[4])){
                                         $this->CeateAjaxController($argv[3],$argv[4]);
                                    }else{
                                        echo "Please Enter two params AjaxController Name and the action name";
                                    }
                                break;
                            default:
                                echo $error_not_valide_make;
                        }
                    }else{
                        echo $error_not_valide_make;
                    }
                }



                if($current_cmd == 'publish'){
                    $this->RegisterModule('pleaseHandler/PublishHandler');
                }

            }else{
                echo "Please enter valide command";
            }
        }


        private function CeateAjaxController($file_name, $action_name)
        {
            $create = new CreteElement("Ajax/$file_name.php",array("_NAME_" => $file_name,'_ACTION_'=>$action_name),'Ajax',__DIR__.'/ressources/src/Ajax.php','RegisterAjax');
            $create->CreateItem();
            die();

        }

    }

    new OWPactPlease();