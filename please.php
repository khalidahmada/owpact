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
                                         $this->CreateAjaxController($argv[3],$argv[4]);
                                    }else{
                                        echo "Please Enter two params AjaxController Name and the action name";
                                    }
                                break;
                            case 'hook':

                                if(isset($argv[3]) && isset($argv[4])){

                                    $this->CreateHookController($argv[3],$argv[4]);
                                }else{
                                    echo "Please Enter two params Hook Name and the function callback name";
                                }
                            break;
                            case 'repo':
                                if(isset($argv[3])){
                                    $post_type = false;
                                    if(isset($argv[4])){
                                        $post_type = $argv[4];
                                    }
                                    $this->CreateRepoController($argv[3],$post_type);
                                }else{
                                    echo "Please enter Repo Name";
                                }
                            case 'helper':
                                if(isset($argv[3])){
                                    $this->CreateHelperController($argv[3]);
                                }else{
                                    echo "Please enter Helper Name";
                                }
                            default:
                                echo $error_not_valide_make;
                        }
                    }else{
                        echo $error_not_valide_make;
                    }
                }else{
                    if($current_cmd == 'publish'){
                        $this->RegisterModule('pleaseHandler/PublishHandler');
                    }else{
                        echo "please Enter valide command";
                    }
                }





            }else{
                echo "Please enter valide command";
            }
        }


        private function CreateAjaxController($file_name, $action_name)
        {
            $create = new CreteElement("Ajax/$file_name.php",array("_NAME_" => $file_name,'_ACTION_'=>$action_name),'Ajax',__DIR__.'/ressources/src/Ajax.php','RegisterAjax');
            $create->CreateItem();
            die();

        }

        private function CreateHookController($hook_name,$fnc_name)
        {
            $create = new CreteElement("Hooks/$fnc_name.php",array("_HOOK_NAME" => $hook_name,'__CALLBACK_NAME'=>$fnc_name),'Hooks',__DIR__.'/ressources/src/Hook.php','RegisterHooks');
            $create->CreateItem();
            die();
        }

        private function CreateRepoController($RepoName,$post_type)
        {
            $replaces = array("__NAME__" => $RepoName);

            if($post_type){
                $replaces['_POST_TYPE'] = $post_type;
            }



            $create = new CreteElement("Repo/$RepoName.php",$replaces,'Repo',__DIR__.'/ressources/src/Repo.php','RegisterRepo');
            $create->CreateItem();
            die();
        }

        private function CreateHelperController($file_name)
        {
            $replaces = array(
                '__NAME__' => $file_name
            );

            $create = new CreteElement("Helpers/$file_name.php",$replaces,'Helpers',__DIR__.'/ressources/src/Helper.php','RegisterHelpers');
            $create->CreateItem();
            die();
        }

    }

    new OWPactPlease();