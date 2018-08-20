# dealers-and-branches
Dealers and Branches (DnB) coded for whom may need a list of own dealers or branches of any kind of bussiness.

=== Dealers And Branches===
Contributors: caglaror
Donate link: http://www.co-scripts.com
Tags: Dealers and Branches, Dealers List, Braches List, List of Dealers
Requires at least: 4.6
Tested up to: 4.7
Stable tag: 4.3
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Dealers and Branches (DnB) coded for whom may need a list of own dealers or branches of any kind of bussiness.

== Description ==




== Installation ==

How to install Dealers and Branches Plugin and make it work.

1. Upload the plugin files to the `/wp-content/plugins/dealers-and-branches` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
2. Find "DnB" menu option in the main WP Admin menu at left side on the screen.
3. Fill the form to add a dealer or a branch to the database. Or you can use the plugin for any purpose about any list which includes hierarchical realtions between its items.
4. Every new list item will be shown on the users screen (client side) as a table row on the top of the list.


== Frequently Asked Questions ==

= Who needs such a plugin like this? =
You may need if you ...
* If you have a business which needs to list your branches on your WP web page.
* If you are a wholesaler, and you want to list retailers at the country on your web WP web pages.
* Or if you want to list any items which has hierarchical relations between them (parent-child like hierarchy).

== How to create shortcode? ==
Parts of ShortCode
Name: dnb_list (static name, not changable)
title: any title you want
tableCaptions: table captions (columns) and other properties of columns

== Sample ShortCode ==

`[
    dnb_list title="Title of Table"
    tableCaptions="
                {'thWidthPercentage':20,'arrayNumbersOfFields':'1','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':true},
                {'thWidthPercentage':20,'arrayNumbersOfFields':'1','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':false},
                {'thWidthPercentage':10,'arrayNumbersOfFields':'2','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':true},
                {'thWidthPercentage':50,'arrayNumbersOfFields':'9,1,3','columnCaption':'Caption of Column','isSortable':true,'sortTypeDefaultIs_Asc':true}
                  "
    jsVars="{'recordsPerpage':5, 'pagingPosition':'A'}" searchTitle="Search" searchOnlyIn="1,2,3,9" filteringTitle="Filtered By:" filteringArrayNumberOfField=10
]`



== Elements of a ShortCode ==
dnb_list: name of short code and you can not change it
tableCaptions : every parameters and arguments setting in this element. Every object in tableCaptions are represents a column.
Inside the column object you can adjust
* `thWidthPercentage` (width of solumn),
* `arrayNumbersOfFields` (choose the related database tables column from the list of `$arrayOfFields` section below,
* `columnCaption` (caption of the column of table,
* `isSortable` (if column captions click sorts fields ascendens or descendens),
* `sortTypeDefaultIs_Asc` (if first click sorting asc or desc, true and default is ASC)

jsVars: At this object properties are adjusting some javascript variables by default.
* `recordsPerpage` (count of records per page)
* `pagingPosition` (adjusting the position of paging navigation buttons)

searchOnlyIn: Choosing searchable fields numbers from the list of `$arrayOfFields` section below, this is comma separated list.

filteringTitle: Tell user to use filter on which field of data.

filteringArrayNumberOfField: Choose a field to make a select filter from the list of `$arrayOfFields` section below.

== Array Numbers of Fields Array ==
List of fields from database table. Read only list can be use in every corner of shortcode.
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

== Screenshots ==

1. screenshot-1.png

== Changelog ==

= 0.1 =
* A very first version.

= 0.2 =
* Minor debuggings.

== Upgrade Notice ==
* Minor js bugs solved
