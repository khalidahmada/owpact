<?php
namespace pleaseHandler\DBGroupHandler;


use Console;
use OWPactConfig;
use pleaseHandler\ChildHandler;
use pleaseHandler\CreteElement;
use pleaseHandler\HandlerPlease;

class exportDbHandler extends ChildHandler{

    public function __construct($argv)
    {

        parent::__construct('export',$argv,'');

        $this->isTrigger();

        $this->Handler();
    }

    /*
     * is Trigger
     */
    protected function isTrigger()
    {
        return $this->argv[2] == $this->trigger;
    }

    /*
     * Handler
     */
    protected function Handler()
    {
        if(!$this->isTrigger()) return;

        if(isset($this->argv[3])){

            $this->createFilterDirectory();
            $source_replacement = isset($this->argv[4]) ? $this->argv[4] : false;
            $this->Execute($this->argv[3],$source_replacement);
            die();

        }else{
            $this->error("Please enter the dist key");
        }
    }

    /*
     * The logic to Execute this
     */
    protected function Execute($alias,$source_replacement)
    {

        $current_project = OWPactConfig::getCurrentProject();

        if(isset($current_project->bdd)){
            $bdd  = $current_project->bdd;
            if(isset($bdd->{$alias})){
                $alias_name = $bdd->{$alias};
                if(isset($alias_name->url)){
                    $this->ProcedExecuteExport($alias,$current_project,$bdd,$alias_name,$source_replacement);
                }else{
                    $this->error("There is no url attribute on this aliase $alias_name");
                }
            }else{
                $this->error("there is no alias $alias on bdd objec of please check config.json");
            }
        }else{
            $current_alais  = OWPactConfig::currentAlias();
            $this->error("No bdd object four the current project $current_alais please cehck your config.json and create one");
        }
        die();

    }

    /*
     * get The Documentation
     */
    public static function  getDoc()
    {
        return array(
            'trigger' => 'export',
            'demo' => "php owp db export",
            'doc' => "Export bdd with given alias into config",
        );
    }

    private function createFilterDirectory()
    {
        //CreteElement::CreateDirectory('../../..');
    }

    private function ProcedExecuteExport($alias,$current_project, $bdd, $alias_name, $source_replacement)
    {
        $this->LoadWP_config();
        if(!defined('ABSPATH')){
                $this->error("Cant's load wp-config.php of the project $alias");
        }else{
            // find the name of the file
            CreteElement::CreateDirectory(ABSPATH . 'bdd');
            $store_path = ABSPATH . 'owp-extract-bdd';

            if(!is_dir($store_path)){
                mkdir($store_path,0777,true);
            }



        }
    }

    public static function PrepareExportMysqlCmd()
    {
        $user = DB_USER;
        $bdd = DB_NAME;
        $pwd = DB_PASSWORD;
        return " mysqldump -u$user -p$pwd $bdd > __NAME__.sql";
    }

    private function LoadWP_config()
    {
        $path = OWPactConfig::getGlobalPath();
        require(__DIR__."../../".$path."../../../wp-config.php");
    }


}