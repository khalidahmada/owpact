<?php
namespace pleaseHandler\DBGroupHandler;


use Console;
use OWPactConfig;
use pleaseHandler\ChildHandler;
use pleaseHandler\CreteElement;
use pleaseHandler\HandlerPlease;

class exportDbHandler extends ChildHandler{

    protected $cmd;
    protected $store_path;

    protected $reference_object_bdd;
    protected $processing_object_bdd;

    protected $current_file;

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
                    $current_alias  = OWPactConfig::currentAlias();

                    if($source_replacement){
                        $this->checkTheReferenceAvailability($source_replacement);
                    }else{
                        $this->checkTheReferenceAvailability('default');
                    }

                    if($this->reference_object_bdd){
                        $this->processing_object_bdd = $alias_name;
                        $this->ProcedExecuteExport($alias,$current_project,$bdd,$alias_name,$source_replacement,$current_alias);
                    }else{
                        if($source_replacement){
                            $this->error("No reference called $source_replacement in your bdd object for the project $current_alias");
                        }else{
                            $this->error("You should create your default entry into bdd object for the project $current_alias");
                        }

                    }

                }else{
                    $this->error("There is no url attribute on this alias $alias_name");
                }
            }else{
                $this->error("there is no alias $alias on bdd object please check config.json");
            }
        }else{
            $current_alias  = OWPactConfig::currentAlias();
            $this->error("No bdd object four the current project $current_alias please check your config.json and create one");
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

    private function ProcedExecuteExport($alias,$current_project, $bdd, $alias_name, $source_replacement,$current_alias)
    {
        $this->LoadWP_config();
        if(!defined('ABSPATH')){
                $this->error("Cant's load wp-config.php of the project $alias");
        }else{
            // find the name of the file
            CreteElement::CreateDirectory(ABSPATH . 'bdd');

            $this->store_path = ABSPATH . 'owp-extract-bdd';

            if(!is_dir($this->store_path)){
                mkdir($this->store_path,0777,true);
            }

            $export_name = $this->replaceNamePattern($alias_name,$alias,$current_project,$current_alias);
            $cmd = $this->PrepareExportMysqlCmd();
            // current File

            $this->current_file = $this->store_path . '/' . $export_name . '.sql';

            $cmd = str_replace("__NAME__",$export_name,$cmd);

            $cmd = str_replace("__PATH__",$this->store_path,$cmd);

            $this->cmd = $cmd;

            $output = $this->ExecuteExportCmdLine();
            echo $output;
            $this->ProcessReplacingIntoBDD();


        }
    }


    private function ExecuteExportCmdLine(){
        $output = shell_exec($this->cmd);
        return $output;
    }
    /**
     * prepare mysqldump CMD
     * @return string
     */
    private function PrepareExportMysqlCmd()
    {
        $user   = DB_USER;
        $bdd    =  DB_NAME;
        $pwd    =  DB_PASSWORD;
        return "sudo mysqldump -u$user -p$pwd $bdd > __PATH__/__NAME__.sql";
    }

    /**
     * Load WP-config of the current Project
     * To Extract the const
     */
    private function LoadWP_config()
    {
        $path = OWPactConfig::getGlobalPath();
        require(__DIR__."../../".$path."../../../wp-config.php");
    }

    /**
     * Return name pattern
     * @param $alias_name
     * @param $alias
     * @param $current_project
     * @param $current_alias
     * @return mixed|string
     */
    private function replaceNamePattern($alias_name, $alias, $current_project,$current_alias)
    {
        $pattern = "";

        $date = date('d-m-Y');
        $time = date('h-m');
        $timestamp = time();

        if(isset($alias_name->export)){
            $export = $alias_name->export;
            $export = str_replace("%alias%" , $alias,$export);
            $export = str_replace("%date%" , $date,$export);
            $export = str_replace("%h-m%" , $time,$export);
            $export = str_replace("%timestamp%" , $timestamp,$export);
            $export = str_replace("%project%" , $current_alias,$export);
            if(isset($alias_name->url)){
                $export = str_replace("%url%" ,$alias_name->url ,$export);
            }

            $pattern = $export;

        }else{
            $pattern = $alias_name->url . '-' .$date;
        }

        return $pattern;
    }

    private function ProcessReplacingIntoBDD()
    {
        $content = file_get_contents($this->current_file);
        $current_object  = $this->processing_object_bdd;
        $reference = $this->reference_object_bdd;
        $content = str_replace($reference->url,$current_object->url,$content);
        $content = str_replace('utf8mb4_unicode_520_ci','utf8_general_ci',$content);
        $content = str_replace('utf8_unicode_520_ci','utf8_general_ci',$content);
        $content = str_replace('utf8mb4','utf8',$content);


            if(isset($current_object->replaces) && is_array($current_object->replaces)){
                foreach($current_object->replaces as $key => $replace_to){
                    foreach($replace_to as $t=>$el){
                        $content = str_replace($t,$replace_to->{$t},$content);
                    }
                }
            }

        $status = file_put_contents($this->current_file,$content);


        if($status){
            $this->success("the file is saved into $this->current_file");
            die();
        }
    }

    private function checkTheReferenceAvailability($source_replacement)
    {
        $current_project = OWPactConfig::getCurrentProject();
        $alias = $source_replacement;
        if(isset($current_project->bdd)){
            $bdd  = $current_project->bdd;
            if(isset($bdd->{$alias})){
                $alias_name = $bdd->{$alias};
                if(isset($alias_name->url)){
                    $this->reference_object_bdd = $alias_name;
                   return true;
                }else{
                    $this->error("There is no url attribute on this alias $alias_name");
                    die();
                }
            }else{
                $this->error("there is no alias $alias on bdd object please check config.json");
                die();
            }
        }else{
            $current_alias  = OWPactConfig::currentAlias();
            $this->error("No bdd object four the current project $current_alias please check your config.json and create one");
            die();
        }
    }


}