<?php
    if (!class_exists('WP_List_Table')) {
        require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }

    class _NAME_TableListView extends WP_List_Table
    {

        /*
         * INPUT COLUMNS HERE //////////////////////////
         */
        private $fields = array(
            'id' => "ID",
            //your colum here
            //'column_name' => 'LABEL',
            // End label
            'date' => "La date"
        );
        /*
         * INPUT COLUMNS HERE //////////////////////////
         */

        private $key;

        function __construct()
        {

            global $status, $page;

            //Set parent defaults
            parent::__construct(array(
                'singular' => sanitize_title('_NAME_'),
                'plural'   => sanitize_title('_NAME_s'),
                'ajax'     => true
            ));

            $this->key = sanitize_title('_NAME_');

        }

        function column_default($item, $column_name)
        {
            return $item[$column_name];
        }

        function column_firstname($item)
        {

            //Build row actions
            $actions = array();

            //Return the title contents
            return sprintf('%1$s %2$s',
                $item['number'],
                $this->row_actions($actions)
            );
        }

        function column_cb($item)
        {
            return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />',
                /*$1%s*/
                $this->_args['singular'],
                /*$2%s*/
                $item['ID']
            );
        }

        function get_columns()
        {
            $columns = array(
                'cb'    => '<input type="checkbox" />',
            );
            $columns = array_merge($columns,$this->fields);


            return $columns;
        }

        function get_sortable_columns()
        {
            $sortable_columns = array(
                'id' => array('id', false),
                'date'  => array('date', false)
            );
            return $sortable_columns;
        }

        function get_bulk_actions()
        {
            $actions = array(
                'delete'     => __('Delete'),
                'export'     => __('Export'),
                'export-all' => __('Export all'),
            );
            return $actions;
        }

        public function current_action()
        {
            if (isset($_REQUEST['filter_action']) && !empty($_REQUEST['filter_action']))
                return false;

            if (isset($_REQUEST['action']))
                return $_REQUEST['action'];

            if (isset($_REQUEST['action2']))
                return $_REQUEST['action2'];

            return false;
        }

        function process_bulk_action()
        {
            $upload_dir = wp_upload_dir();
            $date       = date("d-m-Y");
            global $wpdb;

            // security check!
            if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])) {
                $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
                $action = 'bulk-' . $this->_args['plural'];
                if (!wp_verify_nonce($nonce, $action))
                    wp_die('Nope! Security check failed!');
            }

            $cible_ids = array();
            if (isset($_REQUEST[$this->key])) {
                $cible_ids = (is_array($_REQUEST[$this->key])) ? $_REQUEST[$this->key] : array($_REQUEST[$this->key]);
            }
            $action = $this->current_action();


            $outlabels =  array();

            foreach($this->fields as $itm => $label){
                $outlabels[] = utf8_decode($label);
            }

            switch ($action) {

                case 'delete':
                    if ($cible_ids) {
                        if ($table = $this->get_table()) {
                            foreach ($cible_ids as $id) {
                                $id = absint($id);
                                $wpdb->query("DELETE FROM $table WHERE id = $id");
                            }
                            $this->redirect();
                        }
                    } else {
                        $this->redirect();
                    }
                    break;
                case 'export':
                    if ($cible_ids) {
                        //Set page header
                        header("Content-Encoding: UTF-8");
                        header("Content-Type: application/csv-tab-delimited-table; charset=UTF8");
                        header("Content-Disposition: attachment; filename=export-".$this->key."-" . $date . ".csv");
                        header("Content-Transfer-Encoding: binary");
                        header("Cache-Control: max-age=0");
                        header("Pragma: public");

                        $out = fopen($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv', 'w');


                        //Headers
                        fputcsv(
                            $out,
                            $outlabels,
                            ';'
                        );





                        $wpdb->show_errors();
                        if ($table = $this->get_table()) {
                            $results = $wpdb->get_results("SELECT * FROM $table WHERE id IN (" . implode(",", $cible_ids) . ")");
                            foreach ($results as $item) {
                                $ar_entries = array();
                                foreach($this->fields as $keyval => $label){
                                    $ar_entries[] = $item->{$keyval};
                                }
                                $entry = $ar_entries;
                                $entry = array_map('utf8_decode', $entry);
                                fputcsv($out, $entry, ';');
                            }
                        }
                        fclose($out);
                        readfile($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv');
                        @unlink($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv');
                        die();
                    } else {
                        wp_redirect('?page=' . $_REQUEST['page']);
                        exit;
                    }
                    break;
                case 'export-all':
                    //Set page header
                    header("Content-Encoding: UTF-8");
                    header("Content-Type: application/csv-tab-delimited-table; charset=UTF8");
                    header("Content-Disposition: attachment; filename=export-".$this->key."-" . $date . ".csv");
                    header("Content-Transfer-Encoding: binary");
                    header("Cache-Control: max-age=0");
                    header("Pragma: public");

                    $out = fopen($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv', 'w');
                    //Headers
                    fputcsv(
                        $out,
                        $outlabels,
                        ';'
                    );

                    $wpdb->show_errors();
                    if ($table = $this->get_table()) {
                        $results = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
                        foreach ($results as $item) {
                            $ar_entries = array();
                            foreach($this->fields as $keyval => $label){
                                $ar_entries[] = $item->{$keyval};
                            }
                            $entry = $ar_entries;
                            $entry = array_map('utf8_decode', $entry);
                            fputcsv($out, $entry, ';');
                        }
                    }
                    fclose($out);
                    readfile($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv');
                    @unlink($upload_dir['path'] . '/export-'.$this->key.'-' . $date . '.csv');
                    die();
                    break;

                case '-1';
                    $this->redirect();
                    break;
                default:
                    // do nothing or something else
                    return;
                    break;
            }

            return;
        }

        function redirect()
        {
            wp_redirect('?page=' . $_REQUEST['page']);
            exit;
        }

        function get_table()
        {
            global $wpdb;
            $table = '';
            $table = $wpdb->prefix . '_NAME_';

            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
                return $table;
            }
            return false;
        }

        function prepare_items()
        {
            global $wpdb;

            $per_page = 10;
            $columns  = $this->get_columns();
            $hidden   = array();
            $sortable = $this->get_sortable_columns();
            $data     = array();
            $table    = $this->get_table();

            $this->_column_headers = array($columns, $hidden, $sortable);

            $this->process_bulk_action();

            /*
             * Get data from database
             */
            $wpdb->show_errors();

            if ($table) {
                $results = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");

                if ($results) {
                    foreach ($results as $item) {
                        $ar_entries = array();
                        foreach($this->fields as $keyval => $label){

                            if($keyval == 'date'){
                                $ar_entries[$keyval]  = date_i18n('d F Y H:i', strtotime($item->updated));
                            }else{
                                $ar_entries[$keyval] = $item->{$keyval};
                            }


                        }
                        $entry = $ar_entries;
                        $data[] = $ar_entries;
                    }
                }
            }

            function usort_reorder($a, $b)
            {
                $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'date';
                $order   = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc';
                $result  = strcmp($a[$orderby], $b[$orderby]);
                return ($order === 'asc') ? $result : -$result;
            }

            usort($data, 'usort_reorder');
            $current_page = $this->get_pagenum();
            $total_items  = count($data);
            $data         = array_slice($data, (($current_page - 1) * $per_page), $per_page);
            $this->items  = $data;

            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));
        }
    }


    class _NAME_TableListViewConfig{

        /**
         * __NameView_TaleViewConfig constructor.
         */
        // Page title and Menu
        private $ListViewName = '_TITLE_';

        ////////// YOUR ICON NAME  HERE/////
        private $icon = 'dashicons-list-view';
        //////////////////////////////////////

        public function __construct()
        {
            add_action('admin_menu', array($this,'add_menu_item'));
            add_action('init', array($this,'plug_output_buffer'));
        }

        public function add_menu_item()
        {
            add_menu_page(
                $this->ListViewName, $this->ListViewName,
                'publish_posts', sanitize_title($this->ListViewName),
                array($this,'render_list'),
                $this->icon
            );
        }

        public function plug_output_buffer()
        {
            ob_start();
        }

        public function render_list()
        {

            $cibleListTableView = new _NAME_TableListView();
            $cibleListTableView->prepare_items();
            ?>
            <div class="wrap">
                <h1><?php echo $this->ListViewName;?></h1>

                <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
                <form id="email-filter" method="get">
                    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
                    <input type="hidden" name="noheader" value="true"/>
                    <!-- Now we can render the completed list table -->
                    <?php $cibleListTableView->display() ?>
                </form>

            </div>
            <?php
        }
    }

    new _NAME_TableListViewConfig();










