<?php

// WP Cores
wp_enqueue_script('jquery'); // From WP core
wp_enqueue_script("jquery-ui-autocomplete"); // From WP core
wp_enqueue_script("jquery-ui-tooltip");// From WP core
wp_enqueue_script("jquery-ui-dialog");// From WP core
wp_enqueue_style( 'wp-jquery-ui-dialog' );// From WP core



// locals
wp_register_style('bootstrap-css',plugins_url('/css/bootstrap.css',__FILE__),true);
wp_enqueue_style('bootstrap-css');

wp_register_style('dnb-css',plugins_url('/css/dnb.css',__FILE__),true);
wp_enqueue_style('dnb-css');

wp_register_script('bootstrap-min-js',plugins_url('/js/bootstrap.min.js',__FILE__),array('jquery'),null,true);
wp_enqueue_script('bootstrap-min-js');

wp_register_script('dnb-js',plugins_url('/js/dnb.js',__FILE__),array('jquery'),null,true);
wp_enqueue_script('dnb-js');

wp_register_script('bspaginator-js',plugins_url('/js/bootstrap-paginator.js',__FILE__),array('jquery'),null,true);
wp_enqueue_script('bspaginator-js');




echo('
<script language="javascript">
var ajax_nonce = "'. wp_create_nonce( "e07a28f40044fc1c5dceb1024fbb9f3a" ).'";
</script>
');
?>

<div class="dnb_container">
    <div class="row-fluid" style="padding-top:4px; border:1px solid grey; background-color: darkgray; color:white;">Dealers And Branches Panel</div>

            <div class="row-fluid"  id="" style="border:1px solid grey; padding-top:5px;">
                <div class="span6">
                    <input type="hidden" id="ifUpdateDnBId" value="">
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">Parent DnB:</div>
                            <div class="span9 alignLeftFix">
                                <input type="text" id="parent_dnb" placeholder="Parent of your dealer or branch. (Select from search results)">
                                <input type="hidden" id="parent_dnbID">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">DnB Name:</div>
                            <div class="span9 alignLeftFix"><input type="text" id="dnb_name" placeholder="Write your dealers or branches name"></div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">DnB Type:</div>
                            <div class="span8 alignLeftFix">
                                <select id="dnb_type" title="Select a DnB type. If there isn't a type that you are looking for, add it by clicking the + sign at right!">
                                    <option value="0">Please select a type</option>

                                </select>
                            </div>
                            <div class="span1"><span class="dashicons dashicons-plus-alt curPointer" id="add_dnb_type" title="Click to add a new DnB Type."></span></div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">Office Phone No:</div>
                            <div class="span9 alignLeftFix">
                                <input type="text" id="dnb_phone" placeholder="Left blank if not available or not exist." title="Only digits allowed"  onkeyup="dnb_clearNonDigits(this);">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">Office Fax No:</div>
                            <div class="span9 alignLeftFix">
                                <input type="text" id="dnb_fax" placeholder="Left blank if not available or not exist." title="Only digits allowed" onkeyup="dnb_clearNonDigits(this);">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">Email Address:</div>
                            <div class="span9 alignLeftFix">
                                <input type="text" id="dnb_email" placeholder="Left blank if not available or not exist">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 alignRightFix">Web Page URL:</div>
                            <div class="span9 alignLeftFix">
                                <input type="text" id="dnb_webpage_url" placeholder="Left blank if not available or not exist">
                            </div>
                        </div>
                        <div class="row-fluid" style="width: 100%; height: 200px;" id="map">

                        </div>
                    <script>
                        var map;
                        function initMap() {
                            map = new google.maps.Map(document.getElementById('map'), {
                                center: {lat: 39.91, lng: 32.85},
                                zoom: 8
                            });
                        }
                    </script>
                    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-IgigdWkNpe-QjSGh6bPjs0jp9oecjDA&callback=initMap"
                            async defer></script>
                </div>
                <div class="span6">

                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Country Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_country_name" placeholder="Like Turkey or U.S.A."> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">State Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_state_name" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Province Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_province_name" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">City Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_city_name" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">County Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_county_name" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">District Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_district_name" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Street Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_street_name" placeholder="Street name or avenue name"></div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Apartment Name:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_apartment_name"  placeholder="Left blank if not available or not exist. May be a exterior door number."> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Door No:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_door_no" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Postal Code:</div>
                        <div class="span9 alignLeftFix"><input type="text" id="dnb_postal_code" placeholder="Left blank if not available or not exist"> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span3 alignRightFix">Notes:</div>
                        <div class="span9 alignLeftFix"><textarea id="dnb_notes" placeholder="Please put any useful notes here."></textarea> </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span6 alignLeftFix"></div>
                        <div class="span6 alignRightFix" id="operationalButtons">

                            <button id="dnbSearch" type="button" class="btn btn-info" title="If the form is empty this click retrieves all DnB records."> Search The DnB</button>
                            <button id="dnbSave" type="button" class="btn btn-danger" title="Click to save all form data as a new DnB record."> Save New DnB</button>
                        </div>
                    </div>
                </div>


            </div>
            <div class="row-fluid" style="margin-top:3px">
                <div class="span9 alignLeftFix">
                    <span class="label label-info" style="font-weight: bold;">LAST SEARCHES:</span>
                    <span id="lastSearchesContainer" style="margin:0px; padding:0px; border:0px;">

                    </span>
                </div>
                <div class="span3 alignRightFix">
                    <button type="button" class="btn btn-mini" id="resetTheDnBFormButton" title="Clears the form for refresh for a new search or new dnb record.">REFRESH SEARCH AREA</button>
                </div>
            </div>
            <div class="row-fluid">
                <table class="dnb_table" id="dnbListTable">
                    <thead>
                    <tr>
                        <th width="4%" class="dnb_OrderType" data-search-field="id" data-sort-order="asc" title="Click for reversed order">ID</th>
                        <th width="15%" class="dnb_OrderType" data-search-field="dnb_name" data-sort-order="asc" title="Click for reversed order">DnB Name</th>
                        <th width="15%" class="dnb_OrderType" data-search-field="dnb_parent_id" data-sort-order="asc" title="Click for reversed order">DnB Parent Name</th>
                        <th width="10%" class="dnb_OrderType" data-search-field="dnb_type" data-sort-order="asc" title="Click for reversed order">Type</th>
                        <th width="8%" class="dnb_OrderType" data-search-field="dnb_create_date" data-sort-order="asc" title="Click for reversed order">Added Date</th>
                        <th width="">Address</th>
                        <th width="6%">Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="7">Loading...</td>
                    </tr>
                    </tbody>
                </table>
            </div>
</div>
<div id="dialog" title="" style="padding-right:0px;">
    <div class="row-fluid" id="moduleWindowBody">
    </div>
</div>