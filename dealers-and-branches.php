<?php
/*
*Plugin Name:  dealers-and-branches
*Plugin URI:   https://developer.wordpress.org/plugins/dealers-and-brunches/
*Description:  Dealing with dealers and branches list. CRUD etc.
*Version:      2018.1.0
*Author:       Caglar ORHAN
*Author URI:   https://www.co-scripts.com
*License:      GPL2
*License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*Text Domain:  dealers-and-branches
*Domain Path:  /languages
*/

// Exit if accessed directly
defined('ABSPATH') || exit;
$arrayOfFields=array(
    0=>'dnb.id',
    1=>'dnb.dnb_name',
    2=>'dnb.dnb_parent_id',
    3=>'dnb.dnb_type',
    4=>'dnb.dnb_phone',
    5=>'dnb.dnb_fax',
    7=>'dnb.dnb_email',
    8=>'dnb.dnb_webpage_url',
    9=>'dnb.dnb_street_name',
    10=>'dnb.dnb_city_name',
    11=>'dnb.dnb_county_name',
    12=>'dnb.dnb_district_name',
    13=>'dnb.dnb_province_name',
    14=>'dnb.dnb_state_name',
    15=>'dnb.dnb_apartment_name',
    16=>'dnb.dnb_country_name',
    17=>'dnb.dnb_postal_code',
    18=>'dnb.dnb_door_no',
    19=>'dnb.dnb_note',
    20=>'dnb2.dnb_name'
);


if(!class_exists('Dealers_And_Branches')){
    class Dealers_And_Branches{
        private static $instance=null;
        const TABLE_NAME = 'dealers_and_branches';
        const TABLE_NAME2 = Dealers_And_Branches::TABLE_NAME.'_vars';
        //-----------------------------------
        private function __construct(){
            $this->initializeHooks();
        }
        //-----------------------------------
        public static function getInstance()
        {
            if (self::$instance == NULL) {
                self::$instance = new self();
            }
            return self::$instance;
        }




        //----------------------------------
        private function initializeHooks(){
            //
            register_activation_hook(__FILE__, 'Dealers_And_Branches::activate');

            add_action('admin_menu',array($this,'dnb_CreateMenu'));

            //

       add_action('wp_ajax_nopriv_dnb_DnbSave','dnb_DnbSave'); //
       add_action('wp_ajax_dnb_DnbSave','dnb_DnbSave');

        add_action('wp_ajax_nopriv_refreshList','dnb_RefreshList'); //
        add_action('wp_ajax_dnb_RefreshList','dnb_RefreshList');

        add_action('wp_ajax_nopriv_dnb_Refresh_List','dnb_Refresh_List'); //
        add_action('wp_ajax_dnb_Refresh_List','dnb_Refresh_List');

        add_action('wp_ajax_nopriv_dnb_Autocompletes','dnb__Autocompletes'); //
        add_action('wp_ajax_dnb_Autocompletes','dnb_Autocompletes');

        add_action('wp_ajax_nopriv_dnbControl','dnb_Control'); //
        add_action('wp_ajax_dnb_Control','dnb_Control');

        add_action('wp_ajax_nopriv_dnb_DnbData','dnb_DnbData'); //
        add_action('wp_ajax_dnb_DnbData','dnb_DnbData');

        add_action('wp_ajax_nopriv_dnb_CreateNewDnBType','dnb_CreateNewDnBType'); //
        add_action('wp_ajax_dnb_CreateNewDnBType','dnb_CreateNewDnBType');

        add_action('wp_ajax_nopriv_dnb__DnbTypeSelectFiller','dnb_DnbTypeSelectFiller'); //
        add_action('wp_ajax_dnb_DnbTypeSelectFiller','dnb_DnbTypeSelectFiller');
        }



        // Adding Menu
        public static function dnb_CreateMenu(){
            add_menu_page(
                'Dealers And Branches',
                'DnB',
                'administrator',
                plugin_dir_path(__FILE__) . '/dnb_panel.php',
                null,
                plugin_dir_url(__FILE__) . '/images/icon_dnb_x_16.png',
                80
            );
        }


        //----------------------------------
        static function activate()
        {
            global $wpdb;
            $table = $wpdb->prefix. Dealers_And_Branches::TABLE_NAME;
            $table2 =  $wpdb->prefix.  Dealers_And_Branches::TABLE_NAME2;

            //
            load_plugin_textdomain('dealers_and_branches',false,basename(dirname(__FILE__)).'/languages');
            //

            $collate = '';
            if ($wpdb->has_cap('collation')) {
                if (!empty($wpdb->charset)) {
                    $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                }
                if (!empty($wpdb->collate)) {
                    $collate .= " COLLATE $wpdb->collate";
                }
            }


            $create_query = "CREATE TABLE IF NOT EXISTS $table (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `dnb_name` varchar(255) DEFAULT NULL,
						`dnb_parent_id` int(11) unsigned DEFAULT 0,
						`dnb_type` int(11) unsigned DEFAULT 0,
						`dnb_phone` varchar(255) DEFAULT NULL,
						`dnb_fax` varchar(255) DEFAULT NULL,
						`dnb_email` varchar(255)  DEFAULT 0,
						`dnb_webpage_url` varchar(255)  DEFAULT 0,
						`dnb_street_name` varchar(255) DEFAULT '',
						`dnb_county_name` varchar(255) DEFAULT '',
						`dnb_district_name` varchar(255) DEFAULT '',
						`dnb_city_name` varchar(255) DEFAULT '',
						`dnb_province_name` varchar(255) DEFAULT '',
						`dnb_state_name` varchar(255) DEFAULT '',
						`dnb_apartment_name` varchar(255) DEFAULT '',
						`dnb_country_name` varchar(255) DEFAULT '',
						`dnb_postal_code` varchar(255) DEFAULT NULL,
						`dnb_door_no` varchar(255) DEFAULT NULL,
						`dnb_note` varchar(400) DEFAULT '',
						`dnb_create_date` datetime DEFAULT now(),
                        PRIMARY key (`id`)) $collate";

            $result = $wpdb->query($create_query);


            $create_query2 = "CREATE TABLE IF NOT EXISTS $table2 (
                        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                        `var_type` varchar(255) DEFAULT NULL,
						`var_name` varchar(255)  DEFAULT NULL,
						`var_note` varchar(400) DEFAULT NULL,
						`var_create_date` datetime DEFAULT now(),
                        PRIMARY key (`id`)) $collate";

            $result2 = $wpdb->query($create_query2);


            if (!$result && !$result2) return false;

        }





    }


    // SHORT CODE
        function dnb_shortcode($atts, $content = null, $tag = '')
        {
    global $wpdb;
    global $arrayOfFields;
            $ajaxurl = admin_url( 'admin-ajax.php' );
            $table = $wpdb->prefix. Dealers_And_Branches::TABLE_NAME;
            if(current_user_can('administrator')){ return '<h2 style="color:red;" class="border border-warning">You have to logout from admin account to see regular clientside table!</h2>';}
            $atts = array_change_key_case((array)$atts, CASE_LOWER); //
                $wporg_atts = shortcode_atts(
                    array(
                        'title' => '',
                        'tablecaptions' => '',
                        'jsvars' => '',
                        'searchtitle'=>'',
                        'searchonlyin'=>'',
                        'filteringtitle'=>'',
                        'filteringarraynumberoffield'=>''
                    ),
                    $atts
                );
                //------------------
	        // SAMPLE: {'thWidthPercentage':20,'arrayNumbersOfFields':'1','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':true},
	        $tC = json_decode("[".str_replace('\'',"\"",$wporg_atts['tablecaptions'])."]");
	        // SAMPLE: {'recordsPerPage':5, 'pagingPosition':'B'}
	        $tJ = json_decode("[".str_replace('\'',"\"",$wporg_atts['jsvars'])."]");
	        $tJ = $tJ[0];
	        // searchOnlyIn
            $sOI = $wporg_atts['searchonlyin'];
            // filteringTitle
            $fT = $wporg_atts['filteringtitle'];
            // filteringArrayNumberOfField
            $fANOF = (int)$wporg_atts['filteringarraynumberoffield'];
            // searchTitle
            $sT = $wporg_atts['searchtitle'];


	        //-
            $recsPerPage = ($tJ->recordsPerpage)?$tJ->recordsPerpage:10;
            $pagingPos = ($tJ->pagingPositions)?$tJ->pagingPositions:'A';



            // start output
            $o = '';

            // start box
            $o .= '<div class="wporg-box">';

            // title
            $o .= '<h2>' . esc_html__($wporg_atts['title'], 'default') . '</h2>'; //

            // enclosing tags
            if (!is_null($content)) {
                // secure output by executing the_content filter hook on $content
                $o .= apply_filters('the_content', $content);

                // run shortcode parser recursively
                $o .= do_shortcode($content);
            }

            //
            //=======================================================
	        wp_enqueue_script('jquery');
//
	        wp_register_style('bootstrap-css',plugins_url('/css/bootstrap.css',__FILE__),true);
	        wp_enqueue_style('bootstrap-css');
	        // dashicons css eklenecek
            wp_enqueue_style('dashicons');

	        wp_register_script('bootstrap-min-js',plugins_url('/js/bootstrap.min.js',__FILE__),array('jquery'),null,true);
	        wp_enqueue_script('bootstrap-min-js');

            wp_register_style('dnb-css',plugins_url('/css/dnb.css',__FILE__),true);
            wp_enqueue_style('dnb-css');

            if(!current_user_can('administrator')){ // same css and js files distinct but different names must bu selected by admin login control!
                wp_register_script('dnb_user-js',plugins_url('/js/dnb_user.js',__FILE__),array('jquery'),null,true);
                wp_enqueue_script('dnb_user-js');
           }


            wp_register_script('bspaginator-js',plugins_url('/js/bootstrap-paginator.js',__FILE__),array('jquery'),null,true);
            wp_enqueue_script('bspaginator-js');

            //=======================================================
            // creating filter options
            //return 'SELECT DISTINCT('.$arrayOfFields[$fANOF].') AS theOption FROM '. $table. ' dnb ORDER BY '.$arrayOfFields[$fANOF].' ASC';
            $filteringOptions='';
            if($fANOF>0 && $fANOF!=='' && $fANOF!==null){
                $filterOptionsResult = $wpdb->get_results('SELECT DISTINCT('.$arrayOfFields[$fANOF].') AS theOption FROM '. $table. ' dnb ORDER BY '.$arrayOfFields[$fANOF].' ASC');
                foreach ($filterOptionsResult as $filterOption){
                    $filteringOptions.='<option value="'.$fANOF.','.$filterOption->theOption.'">'.$filterOption->theOption.'</option>';
                }
            }



            //-----------------------------------------------------------
            $o.='<div class="row-fluid">
        <div class="span5 alignRightFix">
    <select  id="dnb_FilterSelection" style="    background-color: #ffffff;
        border: 1px solid #cccccc; font-size:12px;line-height: 20px; width: 80%;
        height: 20px;
        margin-bottom: 10px;
        font-size: 11px;
        line-height: 20px;
        color: #555555;
        vertical-align: middle;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px; padding:1px; "><option value="0">'.$fT.':</option>'.$filteringOptions.'</select>
        </div>
    <div class="span1 alignLeftFix" style="margin-left:1px;"><span style="display:inline-block !important;" class="dashicons dashicons-filter"></span></div>
    <div class="span5 alignRightFix">'.$sT.':<input type="text" class="input-medium" id="dnb_searchString"></div>
    <div class="span1 alignLeftFix" style="margin-left:1px;"><span class="dashicons dashicons-search curPointer" title="Search this" id="dnb_searchButton"  data-search-fields="'.$sOI.'"></span></div>
                </div>
                   
                <table class="dnb_table table table-striped" id="dnbList" style="width:100%; border:1px solid grey; font-size:12px;" data-records-perpage="'.$recsPerPage.'" data-paging-position="'.$pagingPos.'">
                    <thead><tr>';
            $columnCount=count($tC);
	        // SAMPLE: {'thWidthPercentage':20,'arrayNumbersOfFields':'1','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':true},
            $fieldsForColumns=[];
	        foreach ($tC AS $tcc){ // each object in tableCaptions
		        $thClass='';
		        $thWidth='';
		        $dataSorting='';
		        $dataSearchField='';
				if(boolval($tcc->isSortable)){
					$thClass='dnb_OrderType';
					$dataSorting=($tcc->sortTypeDefaultsIs_Asc=="true")?"data-sort-order='asc'":"data-sort-order='desc'";
				}else{
					$thClass='';
				}
				if($tcc->thWidthPercentage>0 || $tcc->thWidthPercentage!==''){$thWidth=$tcc->thWidthPercentage;}else{$thWidth='';}

		        $o.='<th class="'.$thClass.'" width="'.$thWidth.'%" '.$dataSorting.' data-sort-field="'.$tcc->arrayNumbersOfFields.'">'.$tcc->columnCaption.'</th>';

	        }



	        $o.='
                   </tr>
          
                    </thead>
                        <tr><td colspan="'.$columnCount.'">Loading...</td></tr>
                    </tbody>  
                </table>';

            // end box
            $o .= '</div>';

            // return output
            return $o;


        }
        add_action('init', 'dnb_Shortcode');
        add_shortcode('dnb_list', 'dnb_Shortcode');


}




//init
Dealers_And_Branches::getInstance();



function dnb_DnbSave(){
	global $wpdb;
	$table = $wpdb->prefix. Dealers_And_Branches::TABLE_NAME;
    $message="";
    $dnbDC = $_POST['dnbDC']; // data container
    $oData=[];
    $oData['status']='NOK';
    if($dnbDC['ifUpdateDnBId']=="" || $dnbDC['ifUpdateDnBId']==null){
                    // other details saving
                    $wpdb->insert($table, array(
                        'dnb_name' => sanitize_text_field($dnbDC['dnbName']),
                        'dnb_parent_id' => sanitize_text_field($dnbDC['dnbParentDNB']),
                        'dnb_type' => sanitize_text_field($dnbDC['dnbType']),
                        'dnb_phone' => sanitize_text_field($dnbDC['dnbPhone']),
                        'dnb_fax' => sanitize_text_field($dnbDC['dnbFax']),
                        'dnb_email' => sanitize_email($dnbDC['dnbEmail']),
                        'dnb_webpage_url' => sanitize_text_field($dnbDC['dnbWebPageURL']),
                        'dnb_street_name' => sanitize_text_field($dnbDC['dnbStreetName']),
                        'dnb_city_name' => sanitize_text_field($dnbDC['dnbCityName']),
                        'dnb_county_name' => sanitize_text_field($dnbDC['dnbCountyName']),
                        'dnb_district_name' => sanitize_text_field($dnbDC['dnbDistrictName']),
                        'dnb_province_name' => sanitize_text_field($dnbDC['dnbProvinceName']),
                        'dnb_state_name' => sanitize_text_field($dnbDC['dnbStateName']),
                        'dnb_apartment_name' => sanitize_text_field($dnbDC['dnbApartmentName']),
                        'dnb_country_name' => sanitize_text_field($dnbDC['dnbCountryName']),
                        'dnb_postal_code' => sanitize_text_field($dnbDC['dnbPostalCode']),
                        'dnb_door_no' => sanitize_text_field($dnbDC['dnbDoorNo']),
                        'dnb_note' => sanitize_text_field($dnbDC['dnbNotes']),
                        'dnb_create_date' => current_time('mysql', 1)
                    ));
                    $last_id = $wpdb->insert_id;
                    if(!$last_id){
                        $message.='DnB couldn\'t saved.';
                    }else{
                        $message.='DnB saved.';
                    }
                        $oData['message']=$message;
                        $oData['doneWhat']='saved';
                        $oData['status']='OK';
    }else{
       // update
        $result = $wpdb->update($table, array(
            'dnb_name' => sanitize_text_field($dnbDC['dnbName']),
            'dnb_parent_id' => sanitize_text_field($dnbDC['dnbParentDNB']),
            'dnb_type' => sanitize_text_field($dnbDC['dnbType']),
            'dnb_phone' => sanitize_text_field($dnbDC['dnbPhone']),
            'dnb_fax' => sanitize_text_field($dnbDC['dnbFax']),
            'dnb_email' => sanitize_email($dnbDC['dnbEmail']),
            'dnb_webpage_url' => sanitize_text_field($dnbDC['dnbWebPageURL']),
            'dnb_street_name' => sanitize_text_field($dnbDC['dnbStreetName']),
            'dnb_city_name' => sanitize_text_field($dnbDC['dnbCityName']),
            'dnb_county_name' => sanitize_text_field($dnbDC['dnbCountyName']),
            'dnb_district_name' => sanitize_text_field($dnbDC['dnbDistrictName']),
            'dnb_province_name' => sanitize_text_field($dnbDC['dnbProvinceName']),
            'dnb_state_name' => sanitize_text_field($dnbDC['dnbStateName']),
            'dnb_apartment_name' => sanitize_text_field($dnbDC['dnbApartmentName']),
            'dnb_country_name' => sanitize_text_field($dnbDC['dnbCountryName']),
            'dnb_postal_code' => sanitize_text_field($dnbDC['dnbPostalCode']),
            'dnb_door_no' => sanitize_text_field($dnbDC['dnbDoorNo']),
            'dnb_note' => sanitize_text_field($dnbDC['dnbNotes']),
            'dnb_create_date' => current_time('mysql', 1)
        ), array('id'=>$dnbDC['ifUpdateDnBId']));
        if($result>0){
            $message="Updated successfuly!";
        }else{
            $message="Couldn\'t Update !!!";
        }
        $oData['message']=$message;
        $oData['doneWhat']='updated';
        $oData['status']='OK';
    }


    exit(json_encode($oData));
}


function dnb_RefreshList(){
// Admin Panels Resfresh List *FOR ADMIN*****
    global $wpdb;
// eventTriggerId:that.id,searchField:that.dataset.searchField, sortOrder:that.dataset.sortOrder

    $pageNum = esc_attr(sanitize_text_field(($_POST['pageNum'])));
    $searchField = esc_attr(sanitize_text_field(($_POST['searchField'])));
    $sortOrder = esc_attr(sanitize_text_field(($_POST['sortOrder'])));
    $recordsPerpage = esc_attr(sanitize_text_field($_POST['recordsPerpage']));
    // Set search criterias from POSt data for other fields

	global $wpdb;
	$table = $wpdb->prefix. Dealers_And_Branches::TABLE_NAME;
	$table2 =  $wpdb->prefix.  Dealers_And_Branches::TABLE_NAME2;

    $outgoingData =[];
    $allData = [];

    $recordsPerPage = $recordsPerpage?$recordsPerpage:10;
    $frontRecord = ($pageNum-1)*$recordsPerPage;



    if($pageNum==1){
        $frontRecordsNumber=0;
    }
    else{
        $frontRecordsNumber = ($pageNum-1)*$recordsPerPage;
    }


    $ekSQL=' WHERE dnb.id>0 '; //
    $ekSQL.= isset($_POST['dnbName'])&& $_POST['dnbName']!=="" ?' AND dnb.dnb_name LIKE "%'.sanitize_text_field($_POST['dnbName']).'%"':'';
    $ekSQL.= isset($_POST['dnbType'])&& $_POST['dnbType']!=="" && $_POST['dnbType']!=="0" ? ' AND dnb.dnb_type='.sanitize_text_field($_POST['dnbType']):'';
    $ekSQL.= isset($_POST['dnbPhone'])&& $_POST['dnbPhone']!==""?' AND dnb.dnb_phone="'.sanitize_text_field($_POST['dnbPhone']).'"':'';
    $ekSQL.= isset($_POST['dnbFax'])&& $_POST['dnbFax']!==""?' AND dnb.dnb_fax="'.sanitize_text_field($_POST['dnbFax']).'"':'';
    $ekSQL.= isset($_POST['dnbEmail'])&& $_POST['dnbEmail']!==""?' AND dnb.dnb_email="'.sanitize_email($_POST['dnbEmail']).'"':'';
    $ekSQL.= isset($_POST['dnbWebPageURL'])&& $_POST['dnbWebPageURL']!==""?' AND dnb.dnb_webpage_url="'.sanitize_text_field($_POST['dnbWebPageURL']).'"':'';
    $ekSQL.= isset($_POST['dnbParentDNB'])&& $_POST['dnbParentDNB']!==""&& $_POST['dnbParentDNB']!=="0" ?' AND dnb.dnb_parent_id='.sanitize_text_field($_POST['dnbParentDNB']):'';
    $ekSQL.= isset($_POST['dnbCountryName'])&& $_POST['dnbCountryName']!==""?' AND dnb.dnb_country_name="'.sanitize_text_field($_POST['dnbCountryName']).'"':'';
    $ekSQL.= isset($_POST['dnbStateName'])&& $_POST['dnbStateName']!==""?' AND dnb.dnb_state_name="'.sanitize_text_field($_POST['dnbStateName']).'"':'';
    $ekSQL.= isset($_POST['dnbCountyName'])&& $_POST['dnbCountyName']!==""?' AND dnb.dnb_county_name="'.sanitize_text_field($_POST['dnbCountyName']).'"':'';
    $ekSQL.= isset($_POST['dnbProvinceName'])&& $_POST['dnbProvinceName']!==""?' AND dnb.dnb_province_name="'.sanitize_text_field($_POST['dnbProvinceName']).'"':'';
    $ekSQL.= isset($_POST['dnbCityName'])&& $_POST['dnbCityName']!==""?' AND dnb.dnb_city_name="'.sanitize_text_field($_POST['dnbCityName']).'"':'';
    $ekSQL.= isset($_POST['dnbDistrictName'])&& $_POST['dnbDistrictName']!==""?' AND dnb.dnb_district_name="'.sanitize_text_field($_POST['dnbDistrictName']).'"':'';
    $ekSQL.= isset($_POST['dnbStreetName'])&& $_POST['dnbStreetName']!==""?' AND dnb.dnb_street_name LIKE "%'.sanitize_text_field($_POST['dnbStreetName']).'%"':'';
    $ekSQL.= isset($_POST['dnbApartmentName'])&& $_POST['dnbApartmentName']!==""?' AND dnb.dnb_apartment_name LIKE "%'.sanitize_text_field($_POST['dnbApartmentName']).'%"':'';
    $ekSQL.= isset($_POST['dnbDoorNo'])&& $_POST['dnbDoorNo']!==""&& $_POST['dnbDoorNo']!=="0" ?' AND dnb.dnb_door_no='.sanitize_text_field($_POST['dnbDoorNo']):'';
    $ekSQL.= isset($_POST['dnbPostalCode'])&& $_POST['dnbPostalCode']!=="" && $_POST['dnbPostalCode']!=="0" ? ' AND dnb.dnb_postal_code="'.sanitize_text_field($_POST['dnbPostalCode']).'"':'';
    $ekSQL.= isset($_POST['dnbNotes'])&& $_POST['dnbNotes']!==""?' AND dnb.dnb_note LIKE "%'.sanitize_text_field($_POST['dnbNotes']).'%"':'';



    $ekSQLOrder=' ORDER BY dnb.id DESC'; //

    if($searchField && $searchField!==''){
        $ekSQLOrder = ' ORDER BY dnb.'.$searchField.' '.$sortOrder;
    }




    $SQLi="SELECT dnb.id AS dnbID, dnb.dnb_name AS dnbNAME, dnb.dnb_parent_id AS dnbPARENTID, IFNULL(dnb2.dnb_name,'-') AS dnbPARENTNAME, vars.var_name AS dnbTYPENAME, dnb.dnb_type AS dnbTYPE, dnb.dnb_create_date AS dnbDATE,
     dnb.dnb_street_name AS dnbSTREET, dnb.dnb_county_name AS dnbCOUNTY, dnb.dnb_district_name AS dnbDISTRICT, dnb.dnb_city_name AS dnbCITY, dnb.dnb_province_name AS dnbPROVINCE, dnb.dnb_state_name AS dnbSTATE,
      dnb.dnb_apartment_name AS dnbAPARTMENT, dnb.dnb_country_name AS dnbCOUNTRY, dnb.dnb_postal_code AS dnbPOSTALCODE, dnb.dnb_door_no AS dnbDOORNO, dnb.dnb_note AS dnbNOTE" .
        " FROM $table AS dnb ".
        " LEFT JOIN " .$table." AS dnb2 ON dnb.dnb_parent_id=dnb2.id ".
        " LEFT JOIN ".$table2." AS vars ON dnb.dnb_type = vars.id ". $ekSQL ."  ". $ekSQLOrder;


//exit($SQLi);



//--
    $rowsAllData['rowData']='';
    $rowsAllData['rowAdditionalData']='';
    $additionalDatasTable='';
    $limitedQueryWithArguments = $wpdb->get_results($SQLi.' LIMIT '.$frontRecord.','.$recordsPerPage);
    $queryWithArguments = $wpdb->get_results($SQLi);
    $howManyRecordsThereAre = $wpdb->num_rows;


    foreach ($limitedQueryWithArguments as $dnb) {
        $rowData = [];
        $rowData[]= $dnb->dnbID;
        $rowData[]= esc_html($dnb->dnbNAME);
        $rowData[]= esc_html($dnb->dnbPARENTNAME);
        $rowData[]= esc_html($dnb->dnbTYPENAME);
        $rowData[]= esc_html($dnb->dnbDATE);
        $rowData[]= esc_html($dnb->dnbSTREET.' '.$dnb->dnbAPARTMENT.' '.$dnb->dnbDOORNO.' '.$dnb->dnbDISTRICT.' '.$dnb->dnbCOUNTY.' '.$dnb->dnbPROVINCE.' '.$dnb->dnbCITY.' '.$dnb->dnbSTATE.' '.$dnb->dnbCOUNTRY.' '.$dnb->dnbPOSTALCODE);
        $rowData[]= '<span class="dashicons dashicons-admin-tools curPointer" title="Edit" id="editDnB_id_'.$dnb->dnbID.'" data-item-id="'.$dnb->dnbID.'"></span>
                        <span class="dashicons dashicons-trash curPointer" title="Remove Completely" id="removeDnB_id_'.$dnb->dnbID.'" data-item-id="'.$dnb->dnbID.'"></span>';


            if($dnb->dnbNOTE==null || $dnb->dnbNote=""){
                $additionalDatasTable='<div class="span12 label label-default" style="margin:0px; padding:5px;"><span class="dashicons dashicons-warning"></span> No notes given yet.</div>';
            }else{
                $additionalDatasTable='<div class=" span12 label label-default" style="margin:0px; padding:5px;"><b>NOTES:</b> '.esc_html($dnb->dnbNOTE).'</div>';
            }





        $rowsAllData['rowData']=$rowData;
        $rowsAllData['rowAdditionalData']=$additionalDatasTable;
        $allData[]=$rowsAllData;
    }


    $outgoingData['status']='OK';
    $outgoingData['pageNum']=$pageNum;
    $outgoingData['recordCount']=$howManyRecordsThereAre;
    $rearRecord = ($pageNum*$recordsPerPage<=$howManyRecordsThereAre)?($pageNum*$recordsPerPage):$howManyRecordsThereAre;
    $outgoingData['rearRecord']=$rearRecord;
    $outgoingData['frontRecord']=$frontRecord+1;
    $pageResidue = $howManyRecordsThereAre % $recordsPerPage;
    $pagingMaxPage = ($pageResidue>0)?round(($howManyRecordsThereAre+($recordsPerPage-$pageResidue))/$recordsPerPage):($howManyRecordsThereAre/$recordsPerPage);
    $outgoingData['pageCount']= ($pagingMaxPage==0)?1:$pagingMaxPage;
    $outgoingData['tablesData']=json_encode($allData);
    exit(json_encode($outgoingData));

}



function dnb_Refresh_List(){
// For standart user -client- refresh list
	global $wpdb;
    global $arrayOfFields;
// eventTriggerId:that.id,searchField:that.dataset.searchField, sortOrder:that.dataset.sortOrder

    $pageNum = esc_attr(sanitize_text_field(($_POST['pageNum'])));
	$sortFieldNums = esc_attr(sanitize_text_field(($_POST['sortField'])));
	$sortOrder = esc_attr(sanitize_text_field(($_POST['sortOrder'])));
    $recordsPerpage = esc_attr(sanitize_text_field(($_POST['recordsPerpage'])));
    $fieldsToList = array_map('sanitize_text_field',$_POST['fieldsToList']);
    $filterBy = esc_attr(sanitize_text_field($_POST['filterBy']));
	// Set search criterias from POSt data for other fields


	$table = $wpdb->prefix. Dealers_And_Branches::TABLE_NAME;
	$table2 =  $wpdb->prefix.  Dealers_And_Branches::TABLE_NAME2;

	$outgoingData =[];
	$allData = [];

	$recordsPerPage = (isset($recordsPerpage))?$recordsPerpage:10;
	$frontRecord = ($pageNum-1)*$recordsPerPage;



	if($pageNum==1){
		$frontRecordsNumber=0;
	}
	else{
		$frontRecordsNumber = ($pageNum-1)*$recordsPerPage;
	}


	$ekSQL=' '; //
    if(isset($_POST['searchString']) && isset($_POST['searchOnlyIn'])){

        $searchStr = sanitize_text_field($_POST['searchString']);
        $searchIn = explode(',', sanitize_text_field($_POST['searchOnlyIn']));
        foreach ($searchIn as $sI){
            $ekSQL.=' '.$arrayOfFields[(int)$sI].' LIKE "%'.$searchStr.'%" OR';
        }
        $ekSQL.=' dnb.id<0';

    }else{
        $ekSQL=' dnb.id>0 '; //
    }
    $ekSQL=' WHERE '.$ekSQL;

    //if filterBy post data came
    if(isset($_POST['filterBy']) && $filterBy!==0 && $filterBy!=="0" && $filterBy!==null){
        $filterData = explode(',',$filterBy);

        $ekSQL.=' AND '.$arrayOfFields[$filterData[0]].'="'.$filterData[1].'"';
    }




    // search fields
      $ekSQLOrder=' ORDER BY dnb.id DESC'; //

    if(isset($sortFieldNums)){
        if(strpos($sortFieldNums,',')!==false){
            // there are numbers of order of fields array, just the first one take sort order parameter others get ASC ordered.
                $sFN = explode(',',$sortFieldNums);

                $sFn_0 = intval(array_shift($sFN));


            function beReady($xn){
                global $arrayOfFields;
                return $arrayOfFields[(int)$xn];
            }


                $sFNt= array_map("beReady",$sFN);
                $sFn_str = implode(',',$sFNt);



            $ekSQLOrder=' ORDER BY '.$arrayOfFields[$sFn_0].' '.$sortOrder.','. $sFn_str;
        }
        else{
            if(is_numeric($sortFieldNums)){
                $ekSQLOrder=' ORDER BY '.$arrayOfFields[$sortFieldNums].' '. $sortOrder ;
            }
        }
    }

    function dnb_miniConvert($in){
        $ins = explode('.',$in);
        $firstIn = array_shift($ins);
        $lastIn = str_replace('_','',implode('',$ins));
        return $firstIn.''.strtoupper($lastIn);
    }

    $selectedFields ='dnb.id AS dnbID, dnb.dnb_name AS dnbNAME, dnb.dnb_parent_id AS dnbPARENTID, IFNULL(dnb2.dnb_name,\'-\') AS dnbPARENTNAME, vars.var_name AS dnbTYPENAME, dnb.dnb_type AS dnbTYPE, dnb.dnb_create_date AS dnbDATE,
     dnb.dnb_street_name AS dnbSTREET, dnb.dnb_county_name AS dnbCOUNTY, dnb.dnb_district_name AS dnbDISTRICT, dnb.dnb_city_name AS dnbCITY, dnb.dnb_province_name AS dnbPROVINCE, dnb.dnb_state_name AS dnbSTATE,
      dnb.dnb_apartment_name AS dnbAPARTMENT, dnb.dnb_country_name AS dnbCOUNTRY, dnb.dnb_postal_code AS dnbPOSTALCODE, dnb.dnb_door_no AS dnbDOORNO, dnb.dnb_note AS dnbNOTE';

    if(isset($_POST['fieldsToList'])){
        $selectedFields='';
        foreach ($fieldsToList as $ftl){
            if(strpos($ftl,',')!==false){
                // if multiple fields arrayed then concat in sql
                $subFields = explode(',',$ftl);
                $selectedFields.=' CONCAT_WS(" ",';
                $asAliasForConcat='';
                foreach ($subFields as $subField){
                    $selectedFields.=$arrayOfFields[$subField].',' ;
                    $asAliasForConcat.=dnb_miniConvert($arrayOfFields[$subField]);
                }
                $selectedFields.=' " ") AS '.$asAliasForConcat.' ,';
            }else{
                // if single field selected standart selection set
                $selectedFields.=$arrayOfFields[$ftl].' AS ' . dnb_miniConvert($arrayOfFields[$ftl]). ', ';
            }


        }
    }
    $selectedFields.=' dnb.id AS dnbIDxxx';





    $SQLi="SELECT " . $selectedFields.
        " FROM $table AS dnb ".
        " LEFT JOIN " .$table." AS dnb2 ON dnb.dnb_parent_id=dnb2.id ".
        " LEFT JOIN ".$table2." AS vars ON dnb.dnb_type = vars.id ". $ekSQL ."  ". $ekSQLOrder;


//exit($SQLi);
// SELECT dnb.dnb_name AS dnbDNBNAME, dnb.dnb_parent_id AS dnbDNBPARENTID,  CONCAT(dnb.dnb_street_name,dnb.dnb_apartment_name,dnb.dnb_door_no,dnb.dnb_city_name, "") AS dnbDNBSTREETNAMEdnbDNBAPARTMENTNAMEdnbDNBDOORNOdnbDNBCITYNAME FROM wp_dealers_and_branches AS dnb  LEFT JOIN wp_dealers_and_branches AS dnb2 ON dnb.dnb_parent_id=dnb2.id  LEFT JOIN wp_dealers_and_branches_vars AS vars ON dnb.dnb_type = vars.id  WHERE  dnb.id>0    ORDER BY dnb.id DESC

//--
	$rowsAllData['rowData']='';
	$rowsAllData['rowAdditionalData']='';
	$additionalDatasTable='';
	$limitedQueryWithArguments = $wpdb->get_results($SQLi.' LIMIT '.$frontRecord.','.$recordsPerPage,ARRAY_A);
	$queryWithArguments = $wpdb->get_results($SQLi);
	$howManyRecordsThereAre = $wpdb->num_rows;

//exit(var_dump($limitedQueryWithArguments));
	foreach ($limitedQueryWithArguments as $dnb) {
        $rowData = [];

        if(isset($_POST['fieldsToList'])){

            foreach ($fieldsToList as $ftl){
                if(strpos($ftl,',')!==false){
                    $subFields = explode(',',$ftl);
                    $asAliasForConcat='';
                    foreach ($subFields as $subField){
                        $asAliasForConcat.=dnb_miniConvert($arrayOfFields[(int)$subField]);
                    }
                    $rowData[]= $dnb[$asAliasForConcat];
                }else{
                    // if single field selected standart selection set
                    $asAliasForField = dnb_miniConvert($arrayOfFields[(int) $ftl]);
                    $asAliasForField = (string)$asAliasForField;
                    $rowData[]= $dnb[$asAliasForField];

                }


            }
        }else{

        $rowData[]= esc_html($dnb->dnbNAME);
        $rowData[]= esc_html($dnb->dnbPARENTNAME);
        $rowData[]= esc_html($dnb->dnbTYPENAME);
        $rowData[]= esc_html($dnb->dnbSTREET.' '.$dnb->dnbAPARTMENT.' '.$dnb->dnbDOORNO.' '.$dnb->dnbDISTRICT.' '.$dnb->dnbCOUNTY.' '.$dnb->dnbPROVINCE.' '.$dnb->dnbCITY.' '.$dnb->dnbSTATE.' '.$dnb->dnbCOUNTRY.' '.$dnb->dnbPOSTALCODE);

        }






		$rowsAllData['rowData']=$rowData;
		$rowsAllData['rowAdditionalData']=$additionalDatasTable;
		$allData[]=$rowsAllData;
    }



	$outgoingData['status']='OK';
	$outgoingData['pageNum']=$pageNum;
	$outgoingData['recordCount']=$howManyRecordsThereAre;
	$rearRecord = ($pageNum*$recordsPerPage<=$howManyRecordsThereAre)?($pageNum*$recordsPerPage):$howManyRecordsThereAre;
	$outgoingData['rearRecord']=$rearRecord;
	$outgoingData['frontRecord']=$frontRecord+1;
	$pageResidue = $howManyRecordsThereAre % $recordsPerPage;
	$pagingMaxPage = ($pageResidue>0)?round(($howManyRecordsThereAre+($recordsPerPage-$pageResidue))/$recordsPerPage):($howManyRecordsThereAre/$recordsPerPage);
	$outgoingData['pageCount']= ($pagingMaxPage==0)?1:$pagingMaxPage;
	$outgoingData['tablesData']=json_encode($allData);
	exit(json_encode($outgoingData));

}


function dnb_Autocompletes(){
    global $wpdb;
    $rows = array();
    $table = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME;
    $searchWhat = esc_attr(sanitize_text_field($_POST['searchWhat']));
    $searchInWhere = esc_attr(sanitize_text_field($_POST['searchInWhere']));

    switch ($searchInWhere){
        case 'dnbName':
            $SQLi = 'SELECT dnb_name AS dnbNAME, id AS dnbID FROM '.$table.' WHERE dnb_name LIKE "%'.$searchWhat.'%" ORDER BY dnb_name';
            $dnbNames = $wpdb->get_results($SQLi);
            foreach ($dnbNames as $dnbName) {
	            $rows[] = array("value"=>esc_html($dnbName->dnbID), "label"=>esc_html($dnbName->dnbNAME),"kID"=>esc_html($dnbName->dnbID),"additionalData"=>0);
            }
            exit(json_encode($rows));
            break;
        case 'test':
            break;
         default;
    }



}



function dnb_DnbControl(){
    global $wpdb;
    $oData=[];
    $table = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME;
    $newDnBName = esc_attr(sanitize_text_field($_POST['newDnBName']));
    $dnbNameQuery = $wpdb->get_results('SELECT dnb_name FROM '.$table.' WHERE dnb_name="'.$newDnBName.'"');
    $rowsCount = $wpdb->num_rows;
    $oData['rowsCount']=esc_html($rowsCount);

    exit(json_encode($oData));
}


function dnb_DnbData(){
    global $wpdb;
    check_ajax_referer( 'e07a28f40044fc1c5dceb1024fbb9f3a', 'security' );
    $oData=[];
    $table = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME;
    $table2 = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME2;
    $targetDnBID = esc_attr(sanitize_text_field($_POST['targetDnBID']));
    $processType = esc_attr(sanitize_text_field($_POST['processType']));
    switch($processType){
        case "giveInfoOfDnB":
            $dnbInfos = $wpdb->get_results('SELECT * FROM '.$table.' WHERE id="'.$targetDnBID.'"');
            //exit('SELECT * FROM '.$table.' WHERE id="'.$targetDnBID.'"');
            foreach ($dnbInfos as $dnbInfo) {
                $oData['dnbID']=esc_html($dnbInfo->id);
                $oData['dnbName']=esc_html($dnbInfo->dnb_name);
                $oData['dnbType']=esc_html($dnbInfo->dnb_type);
                $oData['dnbPhone']=esc_html($dnbInfo->dnb_phone);
                $oData['dnbFax']=esc_html($dnbInfo->dnb_fax);
                $oData['dnbEmail']=esc_html($dnbInfo->dnb_email);
                $oData['dnbWebPageURL']=esc_html($dnbInfo->dnb_webpage_url);
                $oData['dnbParentDNB']=esc_html($dnbInfo->dnb_parent_id);
                $oData['dnbCountryName']=esc_html($dnbInfo->dnb_country_name);
                $oData['dnbStateName']=esc_html($dnbInfo->dnb_state_name);
                $oData['dnbCountyName']=esc_html($dnbInfo->dnb_county_name);
                $oData['dnbProvinceName']=esc_html($dnbInfo->dnb_province_name);
                $oData['dnbCityName']=esc_html($dnbInfo->dnb_city_name);
                $oData['dnbDistrictName']=esc_html($dnbInfo->dnb_district_name);
                $oData['dnbStreetName']=esc_html($dnbInfo->dnb_street_name);
                $oData['dnbApartmentName']=esc_html($dnbInfo->dnb_apartment_name);
                $oData['dnbDoorNo']=esc_html($dnbInfo->dnb_door_no);
                $oData['dnbPostalCode']=esc_html($dnbInfo->dnb_postal_code);
                $oData['dnbNotes']=esc_html($dnbInfo->dnb_note);
                $oData['dnbCreateDate']=esc_html($dnbInfo->dnb_create_date);
            };
			// Parent DnB Name
	        $parentDNBNAME = $wpdb->get_var("SELECT dnb_name FROM ".$table." WHERE id=".$oData['dnbParentDNB']);
	        $oData[' 
	        
	        ']=$parentDNBNAME;



            exit(json_encode($oData));
            break;
        case "removeDnB":
            $dnbRemoved = $wpdb->query('DELETE FROM '.$table.' WHERE id="'.$targetDnBID.'"');
            if($dnbRemoved>0){
                $oData['removedMessage']=esc_html($dnbRemoved).'(s) dnbs delete from database.';
                $oData['removeStatus']='OK';

            }else{
                $oData['removedMessage']='Not deleted!';
                $oData['removeStatus']='NOK';
            }
            exit(json_encode($oData));
            break;
            default;
    }
    exit(json_encode($oData));
}



function dnb_CreateNewDnBType(){
    global $wpdb;
    $oData=[];
    $table2 = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME2;
    $newDnBTypeName = esc_attr(sanitize_text_field($_POST['newDnBTypeName']));
    $oData['status']='OK';
    $typeCheck = $wpdb->get_var('SELECT COUNT(*) FROM '.$table2. ' WHERE var_type="dnbType" AND var_name="'.$newDnBTypeName.'"');
    if($typeCheck>0){
        $oData['status']='NOK';
        $oData['notes']='This DnB Type already exists in database.';
    }else{
        $wpdb->insert(
            $table2,
            array(
                'var_type' => 'dnbType',
                'var_name' => $newDnBTypeName,
                'var_note' => '',
                'var_create_date' => current_time('mysql', 1)
            )
        );
        $oData['notes']='New DnB Type Added!';
    }

exit(json_encode($oData));
}


function dnb_DnbTypeSelectFiller (){
    global $wpdb;
    $oData=[];
    $table2 = $wpdb->prefix . Dealers_And_Branches::TABLE_NAME2;
    $SQLi = 'SELECT * FROM '.$table2.' WHERE var_type="dnbType" ORDER BY var_name';
    $dnbTypes = $wpdb->get_results($SQLi);
    foreach ($dnbTypes as $dnbType) {
        $oType=[];
        $oType['id']=esc_html($dnbType->id);
        $oType['name']=esc_html($dnbType->var_name);
        $oData[] = $oType;
    }
    exit(json_encode($oData));
}