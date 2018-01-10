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

        private function Handler()
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

        private function Execute()
        {
            $themeName = OWPactConfig::getCurrentDistVal();
            Console::log("Switch is done! your current Theme know is $themeName",'green');
            die();

        }

        /*
         * Prepare paths
         */
        private function prepare($theme_to_switch)
        {
            $current = OWPactConfig::getCurrentDistVal();

            if($current == $theme_to_switch) {
                Console::log("You are already on theme $theme_to_switch",'brown');
                die();
            }

            if( ! isset(OWPactConfig::$project_config->{$theme_to_switch}) ){

                $this->error("Theme $theme_to_switch is not found in your project.".
                             "json please declare your theme into your project.".
                             "json file as 'themename':'path_to_theme'"
                );

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