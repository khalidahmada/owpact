<?php

    /* Helpers Tools
    * User: khalid ahmada
    * @author @khalidoahmada
    * Date: 23/02/16
    * Time: 15:50
    */


    /**
     * Convert a comma separated file into an associated array.
     * The first row should contain the array keys.
     *
     * Example:
     *
     * @param string $filename Path to the CSV file
     * @param string $delimiter The separator used in the file
     * @return array
     * @link http://gist.github.com/385876
     * @author Jay Williams <http://myd3.com/>
     * @copyright Copyright (c) 2010, Jay Williams
     * @license http://www.opensource.org/licenses/mit-license.php MIT License
     */
    function csv_to_array($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }


    function get_excerpt_max_charlength($charlength , $id) {
        global $post;

        $temp = $post;
        $post = get_post( $id );
        setup_postdata( $post );

        $excerpt =  get_excerpt($charlength , $id);

        wp_reset_postdata();
        $post = $temp;

        return $excerpt;
    }

    function get_excerpt($count , $ID){
        $excerpt = get_the_excerpt($ID);
        $excerpt = truncate_str($count,$excerpt , "...");
        return $excerpt;
    }

    function strip_str($count, $excerpt){
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $count);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = $excerpt.'...';
        return $excerpt;
    }

    /*
    * is USer Agent Robot
    **/

    function isrobot(){

        if (    ( isset($_SERVER['HTTP_USER_AGENT']) &&  preg_match('#Googlebot|bingbot|Googlebot-Mobile|Baiduspider|Yahoo|YahooSeeker|DoCoMo|Twitterbot|TweetmemeBot|Twikle|Netseer|Daumoa|SeznamBot|Ezooms|MSNBot|Exabot|MJ12bot|sogou\sspider|YandexBot|bitlybot|ia_archiver|proximic|spbot|ChangeDetection|NaverBot|MetaJobBot|magpie-crawler|Genieo\sWeb\sfilter|Qualidator.com\sBot|Woko|Vagabondo|360Spider|ExB\sLanguage\sCrawler|AddThis.com|aiHitBot|Spinn3r|BingPreview|GrapeshotCrawler|CareerBot|ZumBot|ShopWiki|bixocrawler|uMBot|sistrix|linkdexbot|AhrefsBot|archive.org_bot|SeoCheckBot|TurnitinBot|VoilaBot|SearchmetricsBot|Butterfly|Yahoo!|Plukkie|yacybot|trendictionbot|UASlinkChecker|Blekkobot|Wotbox|YioopBot|meanpathbot|TinEye|LuminateBot|FyberSpider|Infohelfer|linkdex.com|Curious\sGeorge|Fetch-Guess|ichiro|MojeekBot|SBSearch|WebThumbnail|socialbm_bot|SemrushBot|Vedma|alexa\ssite\saudit|SEOkicks-Robot|Browsershots|BLEXBot|woriobot|AMZNKAssocBot|Speedy|oBot|HostTracker|OpenWebSpider|WBSearchBot|FacebookExternalHit#i', $_SERVER['HTTP_USER_AGENT']) ) ||
            ( isset( $_SERVER['QUERY_STRING'] ) && strpos( $_SERVER['QUERY_STRING'],'_escaped_fragment_=') !== false )
        ) {
            // test fragment
            return true;
        }else {
            return false;
        }
    }


    function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = true) {
        if (is_array($ending)) {
            extract($ending);
        }

        if ($considerHtml) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen($ending);
            $openTags = array();
            $truncate = '';
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }

        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($considerHtml) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }

        $truncate   = explode(' ', $truncate);
        array_pop($truncate);

        $truncate = implode(' ', $truncate) . $ending;

        if ($considerHtml) {
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }



        return $truncate;
    }

    /*
     * Get Stream Twitter
     */
    function getSteamTwitter(){
        $url = get_template_directory_uri()."/twitter.php?url=search&q=".get_field("hashtag_twitter","option")."&count=3&_=1427212431365";
        $homepage = file_get_contents($url);
        return $homepage;
    }

    /*
     * generate twitter URl
     */
    function getTwitterURl($user_id , $id_str){
        return "http://twitter.com/" .  $user_id . "/status/" .$id_str . "";
    }



    /*
    * Get page by templte
    **/
    function getPageByTemplate($tpl){
        $args = array(
            'suppress_filters'  => FALSE,
            'post_type'         => 'page',
            'meta_query' => array(
                array(
                    'key'       => '_wp_page_template',
                    'value'     => $tpl.'.php',
                    'compare'   => 'LIKE',
                )
            )
        );
        $pages = get_posts( $args );
        foreach($pages as $page){
            return get_permalink($page->ID);
        }
        return '#';
    }

    function getPageByTemplatedatas($tpl){
        $args = array(
            'suppress_filters'  => FALSE,
            'post_type'         => 'page',
            'posts_per_page'    => 1,
            'meta_query' => array(
                array(
                    'key'       => '_wp_page_template',
                    'value'     => $tpl.'.php',
                    'compare'   => 'LIKE',
                )
            )
        );
        $pages = get_posts( $args );
        foreach($pages as $page){
            return array(
                'url'   => get_permalink($page->ID),
                'title' => get_the_title($page->ID),
                'id'    => $page->ID,
            );
        }
    }

// Generate the list of Languages OF Site
    function icl_post_languages(){

        $languages = icl_get_languages('skip_missing=1');
        if(1 < count($languages)){

            foreach($languages as $l){
                if(!$l['active']) $langs[] = '<li><a href="'.$l['url'].'">'.$l['translated_name'].'</a></li>';
            }
            echo join(', ', $langs);
        }
    }

    /*
     * Get the Route Admin and send Action
     */
    function getAjaxRoute($route){
        return admin_url( '/admin-ajax.php' )."?action=".$route;
    }


    function wp_list_categories_for_post_type($post_type,$args, $type) {
        $exclude = array();

        // Check ALL categories for posts of given post type
        foreach (get_categories() as $category) {
            $posts = get_posts(array('post_type' => $post_type, 'salle' => $category->cat_ID));

            // If no posts found, ...
            if (empty($posts))
                // ...add category to exclude list
                $exclude[] = $category->cat_ID;
        }

        // Set up args
        if (! empty($exclude)) {
            $args .= ('' === $args) ? '' : '&';
            $args .= 'exclude='.implode(',', $exclude);
        }

        // List categories
        return  $args;
    }


    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }


    function truncate_str($length = 100,$text, $ending = '...', $exact = true, $considerHtml = true) {
        if (is_array($ending)) {
            extract($ending);
        }

        if ($considerHtml) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen($ending);
            $openTags = array();
            $truncate = '';
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }

        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($considerHtml) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }

        $truncate   = explode(' ', $truncate);
        array_pop($truncate);

        $truncate = implode(' ', $truncate) . $ending;

        if ($considerHtml) {
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }

        return $truncate;
    }


    /*
     * Filters
     */

    function nofilter($content,$nowrap=true,$echo = true) {
        $str =  str_replace("&amp;", "&", $content);
        if($nowrap) {
            remove_filter('the_content', 'wpautop' );
            $str = apply_filters('the_content', $str);
            add_filter('the_content', 'wpautop' );
            $tags = array("<p>", "</p>");
            $str = str_replace($tags, "", $str);
        }
        if($echo):
            echo $str;
        else:
            return $str;
        endif;
    }


    function getImageSizesT($id,$size){
        $imgdata = wp_get_attachment_image_src( $id, $size );
        $imgurl = $imgdata[0]; // the url of the thumbnail picture
        $imgwidth = $imgdata[1]; // thumbnail's width
        $imgheight = $imgdata[2]; // thumbnail's height

        return array(
            "width" => $imgwidth,
            "height" => $imgheight
        );
    }


    function s_field($field , $fd = 'option'){

        $prifex = ICL_LANGUAGE_CODE;
        $prifex.= '_';

        if(ICL_LANGUAGE_CODE != 'fr'):
            return f($prifex.$field , $fd);
        else:
            return f($field , $fd);
        endif;
    }

    function option($field_id, $echo=true,$key="option"){

        if( $echo ){
            the_field($field_id,$key);
        }else{
            return get_field($field_id,$key);
        }

    }


// Return the Media
    function getMediaT($size = "cover",$idimg = 0 , $id = false , $def = false,$placeholder=false)
    {
        $idattache = 0;
        if ($idimg == 0)
            $idattache = get_post_thumbnail_id($id);
        else
            $idattache = $idimg;

        $imgUrl = wp_get_attachment_image_src($idattache, $size);

        if (isset($imgUrl[0])) {
            return $imgUrl[0];
        }

        if($def){
            return $def;
        }

        if($placeholder){
            return "http://via.placeholder.com/$placeholder/3BB086/ffffff/?text=$placeholder";
        }

        return false;

    }


    function get_params($url,$param){
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str($query, $params);
        $value = isset( $params[$param] ) ? $params[$param] : false;
        return $value;
    }

    function extractYoutubeVideoID($url){
        $video = $url;
        $vurl = get_params($url,'v');
        if($vurl):
            $video =  $vurl;
        endif;

        return $video;
    }


    function get_day_ofdate($ldate){
        $ldate = date_create((string)$ldate);
        return date_format($ldate, "d");
    }


    function clng(){
        return apply_filters( 'wpml_current_language', NULL );
    }


    function date_add_days($nb){

        $oneday = 3600 * 24;
        $current_date = time();
        $date = $current_date + ($oneday * $nb);
        $day = date_i18n("l d M", $date);

        return $day;
    }


    function curPageURL() {
        $pageURL = 'http';
        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    function get_querys(){
        $cquery = curPageURL();
        $cquery = explode("?", $cquery);

        if( is_array($cquery) && count($cquery) > 1){
            return "?" . $cquery[1];
        }else{
            return "";
        }
    }

    function getYoutubeWhachUrl($video_id = ""){
        return "https://www.youtube.com/watch?v=".$video_id;
    }

    function get_ops($field , $field_ops = 'option'){
        //return get_option($field);

        var_dump( icl_object_id($field , 'option'));
        die();
        if( ICL_LANGUAGE_CODE ){
            return get_field($field.'_'.ICL_LANGUAGE_CODE ,$field_ops);
        }else{
            return get_field($field , $field_ops);
        }
    }

    function get_today(){
        return $current_date   = strtotime( time() );
    }


    function get_page_parents($id){
        $posts =  get_post($id);
        if($posts && $posts->post_parent){
            return $posts->post_parent;
        }

        return false;
    }

    function get_children_by_template($id , $template){

        $id = get_page_parents($id);
        $args = array(
            'post_type'  => 'page',

        );
        $my_wp_query = new WP_Query();
        $all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));
        $my_wp_query = new WP_Query($args);

        if($my_wp_query)
            return get_children( $id );

        return false;
    }

    /*
     * Check select if key exist
     */
    function selected_by_key($val,$checker){

        if(isset($checker) && is_array($checker) ):
            if ( in_array($val , $checker) ):
                return ' selected="selected" ';
            endif;
        endif;

        return '';
    }

    /*
     * Check select if key exist
     */
    function selected_is_key($val,$checker){

        if($val == $checker ):
            return ' selected="selected" ';
        endif;

        return '';
    }

    /*
     * posts to ids
     */
    function get_ids_from_posts($posts){
        $ars = array();
        if($posts){
            foreach($posts as $item){
                $ars[] = $item->ID;
            }
        }
        return $ars;
    }

    function push_intoarray_ifnot_exist($arr1,$arr2){
        if(is_array($arr1) && is_array($arr2) ){
            foreach($arr2 as $item ){
                if(! in_array($item,$arr1)){
                    array_push($arr1 , $item);
                    echo "pushed";
                    var_dump( $arr1  );
                }
            }
        }
    }


    function make_links_blank($text)
    {
        $text   = str_replace('target="_blank"','',$text);
        return preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $text);
    }

    function emailize($text)
    {
        $regex = '/(\S+@\S+\.\S+)/';
        $replace = '<a href="mailto:$1">$1</a>';

        return preg_replace($regex, $replace, $text);
    }



    function registerPostType($name, $title ,$icon, $sports = false,$capability_type=false){

        if($sports == false){
            $sports = array( 'title','editor', 'trackbacks', 'custom-fields', 'thumbnail', 'author', 'page-attributes');
        }

        $conf = array(
            'label' => $title,
            'description' => $title,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            //'capability_type' => 'post',
            'menu_icon' => $icon,
            'map_meta_cap' => true,
            'hierarchical' => false,
            'rewrite' => array('slug' => $name, 'with_front' => true),
            'query_var' => true,
            'supports' => $sports
        );

        if($capability_type){
            $conf['capability_type'] = $capability_type;
            $conf['map_meta_cap'] = true;
        }

        register_post_type($name,$conf );


    }

    /*
     * Helper duplicate thumb_nel
     */
    function duplicate_thumbnil($post_type , $lng = array('en')){
        $args = array(
            'post_type' => $post_type,
            'suppress_filters' => true,
            'posts_per_page' => -1
        );

        $query = new WP_Query($args);
        $posts_fr = $query->posts;

        foreach($posts_fr as $item){
            $thumbNil = get_post_thumbnail_id($item->ID);

            //echo $thumbNil." founed id is <br/>";

            foreach($lng as $ln){

                $id = icl_object_id($item->ID, $post_type, true, $ln);

                //echo "Translated Id " . $id . "<br/>";

                if($id != $item->ID){
                    set_post_thumbnail( $id, $thumbNil );
                    //echo "seted ID Thumb";
                }
            }


        }
    }

    /*
     * Default dateformat
     */
    function defaultDateForamt(){
        return 'dd/mm/yy';
    }


    function curl_load_file( $url ) {
        $ch         = curl_init();
        $timeout    = 300;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


    /*
     * Split to files
     */
    function split_to($str , $spliter = '|'){
        $arrays = explode($spliter,(string)$str);
        if($arrays && count($arrays)>=2){
            return $arrays;
        }else{
            return $str;
        }

        return false;
    }


    /*
     * Breadcrumbs
     */
    function get_breadcrumbs(){
        /* === OPTIONS === */
        $text['home']     = __('Accueil','owpact'); // text for the 'Home' link
        $text['category'] = '%s'; // text for a category page
        $text['tax'] 	  = '%s'; // text for a taxonomy page
        $text['search']   = 'Recherche pour "%s"'; // text for a search results page
        $text['tag']      = 'Posts Tagged "%s"'; // text for a tag page
        $text['author']   = 'Articles Posted by %s'; // text for an author page
        $text['404']      = 'Error 404'; // text for the 404 page
        $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
        $showOnHome  = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
        $delimiter   = ''; // delimiter between crumbs
        $before      = '<li><span class="current">'; // tag before the current crumb
        $after       = '</span></li>'; // tag after the current crumb
        /* === END OF OPTIONS === */


        /*<ol class="breadcrumb h-hide-xs-max" aria-label="breadcrumb" role="navigation" itemscope itemtype="http://schema.org/BreadcrumbList">
                    <?php foreach ($aBreadcrumb as $key => $breadcrumbItem) :?>
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" href="<?php echo $breadcrumbItem['link']; ?>" aria-level="<?php echo $key + 1; ?>"><span itemprop="name"><?php echo $breadcrumbItem['label']; ?></span></a>
                <meta itemprop="position" content="<?php echo $key + 1; ?>" />
            </li>
        <?php endforeach; ?>
        </ol>*/

        global $post;
        $homeLink = home_url() . '/';
        $linkBefore = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        $linkAfter = '</li>';
        $linkAttr = ' rel="v:url" property="v:title"';
        $link = $linkBefore . '<a itemprop="item" aria-level="%3$s" href="%1$s">%2$s</a><meta itemprop="position" content="%3$s" />' . $linkAfter;
        $empty_link = $linkBefore . '<span itemprop="item" aria-level="%3$s" href="%1$s">%2$s</span><meta itemprop="position" content="%3$s" />' . $linkAfter;
        $levelCounter = 1;
        if (is_home() || is_front_page()) {
            if ($showOnHome == 1) echo '<ul class="" aria-label="breadcrumb" role="navigation" itemscope itemtype="http://schema.org/BreadcrumbList"><li><a href="'.get_bloginfo('url').'">'.$text['home'].'</a></li></ul>';
        } else {
            echo '<ul class="breadcrumbs" aria-label="breadcrumb" role="navigation" itemscope itemtype="http://schema.org/BreadcrumbList">' . '<li><a href="'.get_bloginfo('url').'">'.$text['home'].'</a></li>';

            if ( is_category() ) {
                $thisCat = get_category(get_query_var('cat'), false);
                if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo $cats;
                }
                echo $before . sprintf($text['category'], single_cat_title('', false),$levelCounter) . $after;
            } elseif( is_tax() ){
                $thisCat = get_category(get_query_var('cat'), false);
                /*if ($thisCat->parent != 0) {
                    $cats = get_category_parents($thisCat->parent, TRUE, $delimiter);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                    echo $cats;
                }*/
                echo $before . sprintf($text['tax'], single_cat_title('', false),$levelCounter) . $after;

            }elseif ( is_search() ) {
                echo $before . sprintf($text['search'], get_search_query(),$levelCounter) . $after;
            } elseif ( is_day() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
                echo $before . get_the_time('d') . $after;
            } elseif ( is_month() ) {
                echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
                echo $before . get_the_time('F') . $after;
            } elseif ( is_year() ) {
                echo $before . get_the_time('Y') . $after;
            } elseif ( is_single() && !is_attachment() ) {
                //if ( get_post_type() != 'post' ) {

                $post_type = get_post_type();
                /*  $type_item  = get_field('type');
                  $post_type = $type_item;

                  if($type_item && $types){
                      foreach($types as $itype){
                          $typeToPage[$itype->name] = array('name'=> $itype->name , 'slug' => $itype->slug);
                      }
                  }*/

                $agenda = getPageByTemplatedatas('page-list-events');
                $activites = getPageByTemplatedatas('page-activites');
                $actualites = getPageByTemplatedatas('page-actualites');
                $hebergements = getPageByTemplatedatas('liste-hebergement');
                $professionnel = getPageByTemplatedatas('liste-socio-professionnels');

                $array_types = array(
                    'agenda' => array(
                        'name' => $agenda['title'],
                        'id' => $agenda['id'],
                        'slug' => 'agenda',
                        'url'  => $agenda['url'],
                    ),
                    'activite' => array(
                        'name' => $activites['title'],
                        'id' => $activites['id'],
                        'slug' => 'activite',
                        'url'  => $activites['url'],
                    ),
                    'post' => array(
                        'name' => $actualites['title'],
                        'id' => $actualites['id'],
                        'slug' => 'post',
                        'url'  => $actualites['url'],
                    ),
                    'hebergement' => array(
                        'name' => $hebergements['title'],
                        'id' => $hebergements['id'],
                        'slug' => 'hebergement',
                        'url'  => $hebergements['url'],
                    ),
                    'professionnel' => array(
                        'name' => $professionnel['title'],
                        'id' => $professionnel['id'],
                        'slug' => 'professionnel',
                        'url'  => $professionnel['url'],
                    )
                );

                if($array_types[$post_type]){
                    $typeToPage[$post_type] = $array_types[$post_type];
                }


                if( isset($typeToPage[$post_type]) ){
                    if( isset($_REQUEST['related']) ){
                        $title_page_related = get_the_title($_REQUEST['related']);
                        $link_page_related = get_the_permalink($_REQUEST['related']);
                        if( !empty($title_page_related) && !empty($link_page_related) ) {
                            ?>
                            <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" aria-level="1" href="<?php echo $link_page_related; ?>">
                                    <?php echo $title_page_related; ?>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                    <!--li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                            <a itemprop="item" aria-level="1" href="<?php echo $typeToPage[$post_type]['url']; ?>">
                                <?php echo $typeToPage[$post_type]['name'];?>
                            </a>
                        </li-->
                    <?php
                    $back = false;

                    if( isset($typeToPage[$post_type]['id']) ){
                        $back       = array();
                        $back[]     = get_post($typeToPage[$post_type]['id']);
                    }
                    if( isset($back[0]) ){
                        $prentPage = $back[0];
                        if($prentPage->post_parent){
                            $parent_id      = $prentPage->post_parent;
                            $breadcrumbs    = array();
                            if(is_singular('hebergement')){
                                $typeHebergement = get_field('type');
                                if($typeHebergement){
                                    $posts = get_posts(array(
                                        'numberposts'	=> 1,
                                        'post_type'		=> 'page',
                                        'meta_query'	=> array(
                                            array(
                                                'key'	 	=> 'categorie',
                                                'value'	  	=> array($typeHebergement),
                                                'compare' 	=> 'IN',
                                            ),

                                        )
                                    ));
                                    if($posts){
                                        $breadcrumbs[] = sprintf($link, get_permalink($posts[0]->ID), $posts[0]->post_title, ++$levelCounter);
                                    }
                                }
                            }else{
                                $breadcrumbs[]  = sprintf($link, get_permalink($prentPage->ID), get_the_title($prentPage->ID), ++$levelCounter);
                            }

                            while ($parent_id) {
                                $page           = get_page($parent_id);
                                $breadcrumbs[]  = sprintf($link, get_permalink($page->ID), get_the_title($page->ID), ++$levelCounter);
                                $parent_id      = $page->post_parent;
                            }
                            $breadcrumbs = array_reverse($breadcrumbs);
                            for ($i = 0; $i < count($breadcrumbs); $i++) {
                                echo $breadcrumbs[$i];
                                if ($i != count($breadcrumbs)-1) echo $delimiter;
                            }
                        }else{
                            echo sprintf($link, get_permalink($prentPage->ID), get_the_title($prentPage->ID), ++$levelCounter);
                        }
                    }

                }

                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
                /*} else {
                    $cat = get_the_category(); $cat = $cat[0];
                    $cats = get_category_parents($cat, TRUE, $delimiter);
                    if ($showCurrent == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats, $levelCounter);
                    $cats = str_replace('</a>', '</a>' . $linkAfter, $cats, $levelCounter);
                    echo $cats;
                    if ($showCurrent == 1) echo $before . get_the_title() . $after;
                }*/
            } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
                $post_type = get_post_type_object(get_post_type());
                echo $before . $post_type->labels->singular_name . $after;
            } elseif ( is_attachment() ) {
                $parent = get_post($post->post_parent);
                $cat = get_the_category($parent->ID); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                $cats = str_replace('<a', $linkBefore . '<a' . $linkAttr, $cats);
                $cats = str_replace('</a>', '</a>' . $linkAfter, $cats);
                echo $cats;
                printf($link, get_permalink($parent), $parent->post_title);
                if ($showCurrent == 1) echo $delimiter . $before . get_the_title() . $after;
            } elseif ( is_page() && !$post->post_parent ) {
                //if ($showCurrent == 1) echo sprintf($link, get_permalink($post->ID), get_the_title($post->ID), ++$levelCounter);
                if ($showCurrent == 1)
                    echo sprintf($empty_link, get_permalink($post->ID), get_the_title($post->ID), ++$levelCounter);
            } elseif ( is_page() && $post->post_parent ) {
                $parent_id  = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    //$levelCounter++;
                    $page           = get_page($parent_id);
                    $breadcrumbs[]  = sprintf($link, get_permalink($page->ID), get_the_title($page->ID), ++$levelCounter);
                    $parent_id      = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
                //$levelCounter++;
                //if ($showCurrent == 1) echo sprintf($link, get_permalink($post->ID), get_the_title($post->ID), ++$levelCounter);
                if ($showCurrent == 1) echo sprintf($empty_link, get_permalink($post->ID), get_the_title($post->ID), ++$levelCounter);
            } elseif ( is_tag() ) {
                echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
            } elseif ( is_author() ) {
                global $author;
                $userdata = get_userdata($author);
                echo $before . sprintf($text['author'], $userdata->display_name) . $after;
            } elseif ( is_404() ) {
                echo $before . $text['404'] . $after;
            }
            if ( get_query_var('paged') ) {
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) ;//echo ' (';
                //echo __('Page') . ' ' . get_query_var('paged');
                echo sprintf($link, get_permalink($post->ID), __('Page') . ' ' . get_query_var('paged'), ++$levelCounter);
                if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ); //echo ')';
            }
            echo '</ul>';
        }
    }



    /*
     * IncludeImage
     */
    function IncludeSvg($id){
        $mimeType       = get_post_mime_type($id);
        $imageUrl       = wp_get_attachment_image_url( $id );
        $imageBaseUrl   = get_attached_file( $id );

        if($mimeType == 'image/svg+xml') {
            if(file_exists($imageBaseUrl)){
                include $imageBaseUrl;
            }
        }else {
            return "<img src='" . getMediaT('full', $id, $id, false) . "'/>";
        }
    }

    /*
    <<<<<<< HEAD
     * Render Template
     * @param file to template exemple '/
     * @return the file content with variable passed
     */
    function render($filename, $vars = array() , $return_val = false) {

        $path = get_stylesheet_directory().'/';
        $uri = get_template_directory_uri().'/';
        $current_page_id = get_the_ID();
        $filename = $path.$filename;
        // adding paths
        $vars['path'] = $path;
        $vars['uri'] = $uri;
        $vars['current_page_id'] = ( $current_page_id);

        if (is_file($filename)){

            ob_start();
            extract($vars);
            include $filename;
            if($return_val)
                return ob_get_clean();
            else{
                echo ob_get_clean();
            }

        }

        return false;
    }


    /*
     * Pagination functions
     */
    function render_numeric_pagination($wp_query,$class="pagination") {
        $page_string = ___("Page ");
        $page_string = "<span class='p-text'>$page_string</span>";
        /** Stop execution if there's only 1 page */
        if( $wp_query->max_num_pages <= 1 )
            return;

        $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max   = intval( $wp_query->max_num_pages );

        /**	Add current page to the array */
        if ( $paged >= 1 )
            $links[] = $paged;

        /**	Add the pages around the current page to the array */
        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<ul class="'.$class.'">' . "\n";

        /**	Previous Post Link */
        if ( 1 < $paged )
            printf( '<li class="prev"><a href="%s" rel="prev"><span class="icon-left-arrow"></span>'.__('Précedent').'</a></li>' . "\n", esc_url( get_pagenum_link( $paged - 1 ) ), __('Â« prÃ©cÃ©dent','owpact') );

        /**	Link to first page, plus ellipses if necessary */
        if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class=" active"' : 'class=""';

            printf( '<li %s ><a  href="%s">'.$page_string.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

            if ( ! in_array( 2, $links ) )
                echo '<li class="dots"><a>...</a></li>';
        }

        /**	Link to current page, plus 2 pages in either direction if necessary */
        sort( $links );
        foreach ( (array) $links as $link ) {
            $class = $paged == $link ? ' class=" active"' : 'class=""';
            printf( '<li %s><a  href="%s">'.$page_string.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        }

        /**	Link to last page, plus ellipses if necessary */
        if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) )
                echo '<li class="dots"><a>...</a></li>' . "\n";

            $class = $paged == $max ? ' class=" active"' : 'class=""';
            printf( '<li %s><a href="%s">'.$page_string.'%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
        }

        /**	Next Post Link */
        if ( $max > $paged )
            printf( '<li class="next"><a href="%s" rel="next"><span class="icon-right-arrow"></span>'.__('Suivant').'</a></li>' . "\n", esc_url( get_pagenum_link( $paged + 1 ) ), __('suivant Â»','owpact') );

        echo '</ul>' . "\n";
    }


    /*
     * convert Email
     */
    function emailToHtml($text)
    {
        $regex = '/(\S+@\S+\.\S+)/';
        $replace = '<a href="mailto:$1">$1</a>';

        return preg_replace($regex, $replace, $text);
    }

    /*
     * insert terms if is array or is val
     */
    function insert_into_temrs($vals,$id,$term , $spliter='#'){
        $categories = split_to($vals,$spliter);

        if(is_array($categories)){
            foreach($categories as $catg){
                wp_add_object_terms($id, $catg ,$term);
            }
        }else{
            wp_add_object_terms($id, $vals ,$term);
        }
    }

    /*
     * duplicate char ntime
     */
    function duplicate_char($char,$times){
        $str = "";
        for($i=0;$i<$times;$i++){
            $str .= $char;
        }
        return $str;
    }


    /*
     * Add fields repeately
     */
    function insertRow($repeater ,$field, $id , $values){

        $value = get_field($repeater, $id);
        if($values){
            foreach($values as $item){
                $value[] = array($field => $item);
            }
        }

        update_field( $repeater, $value, $id );
    }

    /*
     * insert Row if value is less then
     */

    function insertRowIfisLessThen($repeater,$field,$id,$values , $num){

        $val = get_field($repeater , $id);

        // if thre is new value insert
        if( !$val || count($val) < count($values) ){
            // Insert item
            insertRow($repeater , $field,$id,$values);
        }

    }

    /*
     * function if acf have less then given value
     */
    function acf_repeater_is_not_updated($repeater,$id,$vals){

        $val = get_field($repeater, $id);
        if( ! $val){
            return true;
        }else{
            if(count($val) < count($vals)-1){
                return true;
            }
        }

        return false;
    }

    /*
     * filter
     */
    function filter_str($string){
        $string = split_to($string , '#');

        if( is_array($string) && count($string)){
            $string = trim($string[1]);
        }
        return $string;
    }

    /*
     * convert item to paragraph
     */
    function split_to_p($str , $split = '#'){
        $html = "";
        $items =  split_to($str , $split);
        if(is_array($items)){
            $items = array_map('trim',$items);
            $items = array_unique($items);
            foreach($items  as $item){
                $html.="<p>$item</p>";
            }
            return $html;
        }else{
            return "<p>$str</p>";
        }

    }
    /*
     * Helper array
     */
    function filter_array_if_less($array,$ls=6){

        $narray  = array();

        if($array){
            foreach($array as $item){
                if(strlen($item) > $ls){
                    $narray[]=$item;
                }
            }
        }
        return $narray;
    }


    /*
     * to etoile
     */
    function to_stars($field = 'nombre_detoiles'){
        $nb_etoils = get_field($field);
        $st = "";
        if($nb_etoils){
            $ars =  split_to($nb_etoils ,' ');
            if(is_array($ars) && count($ars)>1){
                $num =(int)$ars[0];
                for($i = 0 ; $i<$num;$i++){
                    $st.="*";
                }
            }
        }
        return $st;
    }


    function get_the_content_by_id($post_id) {
        $page_data = get_page($post_id);
        if ($page_data) {
            return $page_data->post_content;
        }
        else {
            return false;
        }
    }

    function min_one_is_exist($array){

        if($array){
            foreach($array as $fnd){
                if($fnd ){
                    return true;
                }
            }
        }

        return false;
    }


    function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }


    /*
    * -------- function arguments --------
    *   $array ........ array of objects
    *   $sortby ....... the object-key to sort by
    *   $direction ... 'asc' = ascending
    * --------
    */

    function sort_arr_of_obj($array, $sortby, $direction='asc') {

        $sortedArr = array();
        $tmp_Array = array();

        foreach($array as $k => $v) {
            $tmp_Array[] = strtolower($v->$sortby);
        }

        if($direction=='asc'){
            asort($tmp_Array);
        }else{
            arsort($tmp_Array);
        }

        foreach($tmp_Array as $k=>$tmp){
            $sortedArr[] = $array[$k];
        }

        return $sortedArr;

    }

    function pre_p($val,$kill = false){
        echo "<pre>";
        print_r($val);
        echo "</pre>";

        if($kill){
            die();
        }
    }


    function getChildrenPages($id){
        // Set up the objects needed

        $children     = query_posts(array(
                'showposts' => 100,
                'post_parent' => $id ,
                'posts_per_page' => -1,
                'numberposts'   => -1,
                'orderby'       =>'menu_order',
                'order'=>'ASC',
                'post_type' => 'page')
        );

        return $children;
    }

    function isTemplate($template){

        $pageTemplate   = get_page_template();
        $pageArray      = explode("/", $pageTemplate);
        $pageTemplate   = end($pageArray);

        return ($template.'.php' == $pageTemplate);
    }


    /*
     * heighlight search
     */
    function h_search($word ,$start = '<b>' ,$end = '</b>'){

        if(! $word || empty($word))
            return $word;

        if(isset($_GET['s'])){
            $s = $_GET['s'];
            $replate_to = (' '.$start.$s.$end);
            $serach = ' '.$s;

            $word = preg_replace( '/('.$serach.')/iu' ,$replate_to, $word);

            return $word;
        }
        return $word;
    }

    /*
     * Add Class if vars get exist and equeal samec lass
     */
    function activeByRequest($req,$val,$valiftrue='active',$valifnot='' , $notsize = false){

        if(isset($_REQUEST[$req])){
            if($_REQUEST[$req] == $val){
                return $valiftrue;
            }
        }else{
            if($notsize){
                return $valiftrue;
            }
        }

        return $valifnot;
    }

    function getitemArrbykeyCompare($arr,$key,$val,$cible){
        if(!isset($key)){
            $key = '';
        }
        if($arr){
            foreach($arr as $item){
                if($item[$key] == $val){
                    return ( $item[$cible] ) ;
                }
            }
        }
        return '';
    }


    function _get_terms($slug, $array = array(
        'orderby' => 'count',
        'hide_empty' => true
    )
    ){

        $categories = get_terms($slug, $array);

        return $categories;

    }


    function selectedIfExistintGet($type,$cat , $retval=' checked="checked" '){
        $selected = "";
        if( $type && isset($_GET[$type]) ){

            if( is_array($_GET[$type]) ){
                if( in_array($cat->slug, $_GET[$type]) ){
                    $selected = $retval;
                }else{
                    $selected = " ";
                }
            }else{
                if($_GET[$type] == $cat->slug){
                    $selected = $retval;
                }else{
                    $selected = " ";
                }
            }

        }

        return $selected;
    }


    function selectSelectIfEist($type,$val, $def = false){
        $selected = "";


        if( $type && isset($_GET[$type]) ){
            if($val == $_GET[$type]){
                return ' selected="selected" ';
            }
        }


        if($def && $val == $def){
            return ' selected="selected" ';
        }


        return $selected;
    }


// Function to get the client IP address
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }


    function offre_groupe_by_magasin_groupby($groupby) {
        global $wpdb;
        //echo "<pre>";
        //print_r($wpdb);
        $groupby =  $wpdb->postmeta . '.meta_value ';
        return $groupby;
    }

    function tesms_to_string($id , $taxonomie){
        $str = "";
        $terms = get_the_terms($id , $taxonomie);
        if($terms && is_array($terms)){
            foreach($terms as $ter){
                $str.= $ter->name.' ';
            }
        }

        if($str == ''){
            return false;
        }

        return $str;
    }

    function getUrlWithoutFilter($type, $valeur){
        $link = urldecode($_SERVER['REQUEST_URI']);
        //var_dump($link, $type, $valeur);
        $link = preg_replace('#(&)*'.$type.'(\[\])*='.$valeur.'#','',$link);
        return $link;
    }

    function getNameWithTermSlug($taxonomy,$slug){
        $term = get_term_by('slug', $slug, $taxonomy);
        if($term){
            return $term->name;
        }
        return $slug;
    }


    function ptime($id , $format = 'j F Y'){
        return get_post_time(
            $format,      // format
            TRUE,          // GMT
            $id,  // Post ID
            TRUE           // translate, use date_i18n()
        );
    }



    function morz_get_next_posts_link( $label = null, $max_page = 0 ) {
        global $paged, $wp_query;

        if ( !$max_page )
            $max_page = $wp_query->max_num_pages;

        if ( !$paged )
            $paged = 1;

        $nextpage = intval($paged) + 1;

        if ( null === $label )
            $label = __( 'Next Page &raquo;' );

        if ( !is_single() && ( $nextpage <= $max_page ) ) {
            /**
             * Filters the anchor tag attributes for the next posts page link.
             *
             * @since 2.7.0
             *
             * @param string $attributes Attributes for the anchor tag.
             */
            $attr = apply_filters( 'next_posts_link_attributes', '' );

            return '<a class="link-prev" href="' . next_posts( $max_page, false ) . "\" $attr>" . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
        }
    }

    function e($text ,$namespace="owpact"){
        _e($text,$namespace);
    }

    function ___($text,$namespace="owpact"){
        return __($text,$namespace);
    }

    /*
     * pages child
     */
    function getPagesChild($id){
        $args = array(
            'post_parent' => $id,
            'post_type'   => 'page',
            'numberposts' => -1,
            'orderby' => 'menu_order',
            'order'   => 'ASC',
            'post_status' => 'publish',
        );
        $subPages   = get_children($args);
        return $subPages;
    }


    function tobddDate($val,$sep='/'){
        $val    = explode($sep,$val);
        $dateFrom       = $val[2].$val[1].$val[0];
        return $dateFrom;
    }




    function SortArrayByKey(&$array,$orderby,$sort=SORT_ASC){

        $sortArray = array();

        foreach($array as $person){
            foreach($person as $key=>$value){
                if(!isset($sortArray[$key])){
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }



        array_multisort($sortArray[$orderby],$sort,$array);
    }

    function str_without($nedle, $str){
        return str_replace($nedle , '', $str);
    }

    /*
    * Return the ids of page child of given page
    * @param int $page_parent_id
    * @return flase | array(posts_pages)
    **/
    function getPagesChildsOfPage($page_parent_id){

        /*
        $children = get_children( $args );
        */

        $all_wp_pages = new WP_Query(array('post_type' => 'page', 'posts_per_page' => -1));

        if( $all_wp_pages->found_posts   ){

            $ar = array();

            foreach($all_wp_pages->posts as $post){


                if(
                    isset($post->post_parent) &&
                    $post->post_parent === $page_parent_id
                ){
                    $ar[] = $post;
                }

            }


            return $ar;

        }

        return false;

    }

    /*
     * Get template directrory URI
     */
    function uri($file = '',$echo  =true){

        $path  = get_template_directory_uri().'/'.$file;
        if($echo){
            echo $path;
        }
        return $path;
    }

    function d_auto_p($status=true){
        if($status){
            remove_filter('the_content', 'wpautop' );
        }else{
            add_filter('the_content', 'wpautop' );
        }
    }

    function ReplaceHashTags($text, $tag = 'strong'){
        return preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<'.$tag.'>#\2</'.$tag.'>', $text);
    }

    function ReplaceHashTagToLink($text)
    {
        return preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1#<a href="http://twitter.com/search?q=%23\2">\2</a>', $text);
    }


    function isLocal()
    {
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : false;

        $exp = explode('.',$host);

        if($exp){
            $exp = $exp[count($exp)-1];
        }
        if($exp){
            if(in_array($exp,array('dev','local','test'))) return true;
        }

        return false;
    }

    function ParseNumbers($tel){
        $words=str_split($tel,2);
        return join(' ',$words);
    }


    /*
     * get_field
     */
    function f($name,$id = false){

        if(!$id){
            $id = get_the_id();
        }
        return get_field($name,$id );

    }

    function ops($field_id, $echo=false,$key="option"){
        if( $echo ){
            the_field($field_id,$key);
        }else{
            return get_field($field_id,$key);
        }
    }


    function eops($field_id, $echo=true,$key="option"){
        if( $echo ){
            the_field($field_id,$key);
        }else{
            return get_field($field_id,$key);
        }
    }


    function HasTemplate($post_id,$template_name){
        $template = get_post_meta($post_id,'_wp_page_template');

        if(!$template) return false;

        return $template[0] == $template_name;
    }

    function pp($var, $die=false){
        pre_p($var,$die);
    }

    function dd($var){
        pre_p($var,true);
    }


    function sprintf_array($format, $arr)
    {
        return call_user_func_array('sprintf', array_merge((array)$format, $arr));
    }

    /**
     * Return attribute class with classes given
     * @param array $classes
     * @param $echo
     * @return string or void
     */
    function classes_attribute(array $classes,$echo = true){

        $class_str =  'class="' . join(' ',$classes). '"';
        if($echo) echo $class_str;
        return $class_str;

    }


    /**
     * Return Array of three items $src,$srcset and sizes
     *
     * @param $image_id
     * @param bool $post_id
     * @param string $size
     * @return array|bool
     */
    function MediaWidthSrcSet($image_id, $post_id=false, $size=''){

        if(!$image_id && $post_id) $image_id = get_post_thumbnail_id($post_id);
        $src = getMediaT('',$image_id);
        if(!$src) return false;
        $srcset = wp_get_attachment_image_srcset($image_id,$size);
        $sizes = wp_get_attachment_image_sizes($image_id,$size);

        return array($src,$srcset,$sizes);
    }




