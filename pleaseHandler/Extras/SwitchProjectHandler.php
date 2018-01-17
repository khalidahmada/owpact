<?php
    namespace pleaseHandler;


    use Console;
    use OWPactConfig;

    class SwitchProjectHandler extends HandlerPlease{


        public function __construct($argv)
        {

            parent::__construct('switch',$argv);

            $this->Handler();
        }

        /*
         * Handler
         */
        protected function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[2])){
                $themeName = $this->argv[2];

                if($this->prepare($themeName)){
                    $this->Execute();
                }else{
                    $this->error("Owpact can't switch to your theme please check your project.json");
                }


            }else{
                $this->error("Please Enter your theme to switch");
            }
        }

        /*
         * Execute Logic
         */
        private function Execute()
        {
            $themeName = OWPactConfig::getCurrentDistVal();
            $this->success("Switch is done! your current theme now is $themeName");
            die();

        }

        /*
         * Prepare paths
         */
        private function prepare($theme_to_switch)
        {

            $current = OWPactConfig::getCurrentDistVal();

            if($current == $theme_to_switch) {
                $this->warning("You are already on theme $theme_to_switch");
                die();
            }

            $listProject = OWPactConfig::getListProjectAvailable();

            if( ! in_array($theme_to_switch,$listProject) || !count($listProject)){

                $this->error("Theme $theme_to_switch is not found in your project.".
                             "json please declare your theme into your project.".
                             "json file as 'themename':'path_to_theme'"
                );

                if($listProject){
                    $list = OWPactConfig::getListProjectAvailable();
                    $this->success("Here is list of your available project:");
                    $this->warning("==> ".(join("\n ==> " , $list)));
                }


                die();

            }else{
                // Update Object
                $status = OWPactConfig::SwitchToTheme($theme_to_switch);
                if($status) return true;
            }

            return false;

        }


        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'switch',
                'demo' => "php owp switch Theme name (theme should declare into your project.json)",
                'doc' => "Switch owpact to your between your current themes",
            );
        }



    }