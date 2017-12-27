<?php
    namespace pleaseHandler;

    class EmailHandler extends HandlerPlease{

        public function __construct($argv)
        {
            parent::__construct('email',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            $argv = $this->argv;
            if(isset($argv[3]) && isset($argv[4])){
                $this->Execute($argv[3],$argv[4]);
            }else{
                echo "Please enter email name and email template name";
            }
        }

        private function Execute($file_name, $templateName)
        {

            $create = new CreteElement("Emails/$file_name.php",
                                        array("__NAME__" => $file_name,
                                              '__TEMPLATE_NAME_'=>$templateName
                                        ),'Emails',__DIR__.'/../ressources/src/Email.php','RegisterEmails',
                                        array(
                                            '/Emails/templates/'=>$templateName
                                        )
                );
            $create->CreateItem();
            die();

        }
    }