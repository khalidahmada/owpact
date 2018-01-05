<?php
    namespace pleaseHandler;


    class TableViewHandler extends HandlerPlease{


        public function __construct($argv)
        {
            parent::__construct('tableview',$argv);

            $this->Handler();
        }

        private function Handler()
        {
            if(!$this->match) return;

            if(isset($this->argv[3]) && isset($this->argv[4])){
                $this->CreateRequired();
                $this->Execute($this->argv[3],$this->argv[4]);
            }else{
                echo "Please Enter the table name (without prefix) and the page title";
            }
        }

        private function Execute($tableName,$titre)
        {
            $replaces = array(
                                "_NAME_" => $tableName,
                                "_TITLE_"=>$titre
            );

            $create = new CreteElement("TableViews/".$tableName."TableView.php",$replaces,
                                        'TableView',__DIR__.'/../ressources/src/TableView.php','RegisterTableViews'
            );
            $create->CreateItem();
            die();

        }

        private function CreateRequired()
        {
            CreteElement::CreateDirectory('TableViews');
        }

        /*
         * get The Documentation
         */
        public static function  getDoc()
        {
            return array(
                'trigger' => 'tableview',
                'demo' => "php owp tableview tableName (without prefix ) and the NameOf Your ControllerTable view",
                'doc' => "If you want to create your own table view for specific table example newletter or other entry"
            );
        }


    }