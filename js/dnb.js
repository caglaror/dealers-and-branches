
(function($) {
    $(function() {
        // More code using $ as alias to jQuery

        $(document).ready(function(){




            $('#dnbSave').on('click',function(){
                // Variables, Controls and dataContainer creation
                // Variables
                let ifUpdateDnBId = $('#ifUpdateDnBId').val();
                let dnbParentDNB = $('#parent_dnbID').val();
                let dnbName = $('#dnb_name').val();
                let dnbType = $('#dnb_type').val();
                let dnbPhone = $('#dnb_phone').val();
                let dnbFax = $('#dnb_fax').val();
                let dnbEmail = $('#dnb_email').val();
                let dnbWebPageURL = $('#dnb_webpage_url').val();
                let dnbCountryName = $('#dnb_country_name').val();
                let dnbStateName = $('#dnb_state_name').val();
                let dnbProvinceName = $('#dnb_province_name').val();
                let dnbCityName = $('#dnb_city_name').val();
                let dnbCountyName = $('#dnb_county_name').val();
                let dnbDistrictName = $('#dnb_district_name').val();
                let dnbStreetName = $('#dnb_street_name').val();
                let dnbApartmentName = $('#dnb_apartment_name').val();
                let dnbDoorNo = $('#dnb_door_no').val();
                let dnbPostalCode = $('#dnb_postal_code').val();
                let dnbNotes = $('#dnb_notes').val();


                // Controls
                    //dnbName emptyness control
                    if(!dnbName || dnbName===''){
                        alert("DnB Name can\'t be empty. Please check and try again.");
                        $('#dnb_name').focus();
                        return false;
                    }
                    //dnbType emptyness control
                    if(!dnbType || dnbType=='' || dnbType==0){
                        alert("You must select the DnB type!");
                        $('#dnb_type').focus();
                        return false;
                    }

                    // dnbEmail pattern control
                    // let regexpEMAIL = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/;
                    // if(!regexpEMAIL.test(dnbEmail)){alert('Something wrong with your email address... Please check and try again.');$('#dnb_email').focus(); return false;}
                    //

                let regExpOnlyDigits =/^\d+$/;
                    // dnbFax only digit control
                if($('#dnb_fax').val()){
                    if(!regExpOnlyDigits.test($('#dnb_fax').val())){alert('Only digits allowed in fax number!'); $('#dnb_fax').focus();  return false;}
                }
                // dnbPhone only digit control
                if($('#dnb_phone').val()){
                    if(!regExpOnlyDigits.test($('#dnb_phone').val())){alert('Only digits allowed in phone number!'); $('#dnb_phone').focus();  return false;}
                }



                // dataContainer : DC
                var dnb_DC = {};
                dnb_DC['ifUpdateDnBId']=ifUpdateDnBId;
                dnb_DC['dnbName'] = dnbName;
                dnb_DC['dnbType'] = dnbType;
                dnb_DC['dnbPhone'] = dnbPhone;
                dnb_DC['dnbFax'] = dnbFax;
                dnb_DC['dnbEmail'] = dnbEmail;
                dnb_DC['dnbWebPageURL'] = dnbWebPageURL;
                dnb_DC['dnbParentDNB'] = dnbParentDNB;
                dnb_DC['dnbCountryName']=dnbCountryName;
                dnb_DC['dnbStateName']=dnbStateName;
                dnb_DC['dnbCountyName']=dnbCountyName;
                dnb_DC['dnbProvinceName']=dnbProvinceName;
                dnb_DC['dnbCityName']=dnbCityName;
                dnb_DC['dnbDistrictName']=dnbDistrictName;
                dnb_DC['dnbStreetName']=dnbStreetName;
                dnb_DC['dnbApartmentName']=dnbApartmentName;
                dnb_DC['dnbDoorNo']=dnbDoorNo;
                dnb_DC['dnbPostalCode']=dnbPostalCode;
                dnb_DC['dnbNotes']=dnbNotes;

                //
                $.ajax({
                    type:"POST",
                    url: ajaxurl,
                    data: {
                        action: "dnb_DnbSave",
                        dnbDC: dnb_DC
                    },
                    success:function(data){
                        if(!data){
                            alert('Something went wrong, there is no data retrieved from server!'); return false;
                        }else{
                            let iData = JSON.parse(data);
                            if(iData.status=='OK'){
                                switch (iData.doneWhat){
                                    case "saved":
                                        dnb_ResetDnbForm();
                                        dnb_RefreshList(1);
                                        alert(iData.message);
                                        break;
                                    case "updated":
                                        dnb_ResetDnbForm();
                                        dnb_RefreshList(1);
                                        alert(iData.message);
                                        // change the button back
                                        $('#dnbSave').toggleClass('btn-danger').toggleClass('btn-warning');
                                        $('#dnbSave').attr('title','Click to save all form data as a new DnB record.');
                                        $('#dnbSave').text(' Save New DnB');

                                        break;
                                    default:

                                }

                            }else{
                                alert('Something went wrong! There must be a tachnical problem. Please report it.'); return false;
                            }
                        }

                    },
                    error: function(errorThrown){
                        alert(errorThrown);
                    }

                });

            });



            //DnBSearch
            $('#dnbSearch').on('click',function(){
                // Variables
                let dnbParentDNBName = $('#parent_dnb').val();
                let dnbParentDNB = $('#parent_dnbID').val();
                let dnbName = $('#dnb_name').val();
                let dnbType = $('#dnb_type').val();
                let dnbPhone = $('#dnb_phone').val();
                let dnbFax = $('#dnb_fax').val();
                let dnbEmail = $('#dnb_email').val();
                let dnbWebPageURL = $('#dnb_webpage_url').val();
                let dnbCountryName = $('#dnb_country_name').val();
                let dnbStateName = $('#dnb_state_name').val();
                let dnbProvinceName = $('#dnb_province_name').val();
                let dnbCityName = $('#dnb_city_name').val();
                let dnbCountyName = $('#dnb_county_name').val();
                let dnbDistrictName = $('#dnb_district_name').val();
                let dnbStreetName = $('#dnb_street_name').val();
                let dnbApartmentName = $('#dnb_apartment_name').val();
                let dnbDoorNo = $('#dnb_door_no').val();
                let dnbPostalCode = $('#dnb_postal_code').val();
                let dnbNotes = $('#dnb_notes').val();

                // control
                if(!dnbParentDNB && !dnbName && dnbType==0 && !dnbPhone && !dnbFax && !dnbEmail && !dnbParentDNB && !dnbWebPageURL && !dnbCountryName && !dnbStateName && !dnbCountyName && !dnbProvinceName && !dnbCityName && !dnbDistrictName && !dnbStreetName && !dnbApartmentName && !dnbDoorNo && !dnbPostalCode && !dnbNotes){
                    //alert('There is no entry to for searching...');
                    dnb_RefreshList(1);
                    return false;
                }
                // dataContainer : DC
                var dnb_DC = {};
                dnb_DC['dnbParentDNBName'] = dnbParentDNBName;
                dnb_DC['dnbName'] = dnbName;
                dnb_DC['dnbType'] = dnbType;
                dnb_DC['dnbPhone'] = dnbPhone;
                dnb_DC['dnbFax'] = dnbFax;
                dnb_DC['dnbEmail'] = dnbEmail;
                dnb_DC['dnbWebPageURL'] = dnbWebPageURL;
                dnb_DC['dnbParentDNB'] = dnbParentDNB;
                dnb_DC['dnbCountryName']=dnbCountryName;
                dnb_DC['dnbStateName']=dnbStateName;
                dnb_DC['dnbCountyName']=dnbCountyName;
                dnb_DC['dnbProvinceName']=dnbProvinceName;
                dnb_DC['dnbCityName']=dnbCityName;
                dnb_DC['dnbDistrictName']=dnbDistrictName;
                dnb_DC['dnbStreetName']=dnbStreetName;
                dnb_DC['dnbApartmentName']=dnbApartmentName;
                dnb_DC['dnbDoorNo']=dnbDoorNo;
                dnb_DC['dnbPostalCode']=dnbPostalCode;
                dnb_DC['dnbNotes']=dnbNotes;

                // localstorage
                if(!localStorage.getItem('lastSearches')) {
                    console.log('Local storage a rastlanmadı yeni oluşturulacal');
                    var lastSearches=[];
                    lastSearches.unshift(dnb_DC);
                    localStorage.setItem('lastSearches', JSON.stringify(lastSearches));
                } else {
                    console.log('localStorage varmış alınıp açıldı')
                    var lastSearches = JSON.parse(localStorage.getItem('lastSearches'));
                    lastSearches.unshift(dnb_DC);
                    localStorage.setItem('lastSearches', JSON.stringify(lastSearches));
                }
                console.log(JSON.parse(localStorage.getItem('lastSearches')));
                // last search butonları güncellenecek ve resfresList Çağırılacak

                var lastSearches = JSON.parse(localStorage.getItem('lastSearches'));
                dnb_CreateLastSearchesButtons();
                // şimdi arama son storage için yapılıyor
                dnb_RefreshList(1,lastSearches[0]);
            });


            //  call to createLastSearchesButtons();
            dnb_CreateLastSearchesButtons();

            //load the list table
            dnb_RefreshList(1);

            // DnB Type Select Filler Function
            dnb_DnbTypeSelectFiller();

            // autocompletes
            dnb_Autocompletes('parent_dnb','parent_dnbID','document','dnbName',);

            //
            $('#add_dnb_type').on('click',function(){
                if(confirm('You are about to add a new DnB type to database, do you confirm?')){
                    var opt = {
                        autoOpen: false,
                        modal: true,
                        width: 350,
                        height:130,
                        title: 'Create New DnB Type',
                        draggable:false,
                        modal:true,
                        closeOnEscape:true,
                        create: function () {
                            // style fix for WordPress admin
                            $('.ui-dialog-titlebar-close').addClass('ui-button');
                        },

                    };
                    var newTypeDialog = $("#dialog").dialog(opt).dialog("open");
                    $('#moduleWindowBody').html(' <div class="span4 alignRightFix">New Type:</div>\n' +
                        '        <div class="span8 alignLeftFix"><input type="text" id="new_dnb_type"></div>' +
                        '<div class="row-fluid alignRightFix"><button id="newTypeCreationButton" class="btn btn-mini btn-danger">Create</button> </div>');


                    $('#newTypeCreationButton').on('click',function(){
                        let newDnBTypeName=$('#new_dnb_type').val();
                        if(!newDnBTypeName || newDnBTypeName==''){
                            $('#new_dnb_type').focus();
                            alert('You have to type something!');
                            return false;
                        }
                        //
                        $.ajax({
                            type:"POST",
                            url: ajaxurl,
                            data: {
                                action: "dnb_CreateNewDnBType",
                                newDnBTypeName: newDnBTypeName
                            },
                            success:function(data){
                                let iData = JSON.parse(data);
                                if(iData.status=='OK'){
                                    $('#moduleWindowBody').html(iData.notes);
                                    dnb_DnbTypeSelectFiller();
                                    window.setTimeout(function(){
                                        newTypeDialog.dialog("close");
                                    },1500);
                                }else{
                                    alert(iData.notes);
                                    $('#new_dnb_type').select();
                                    return;
                                }

                            },
                            error: function(errorThrown){
                                alert(errorThrown);
                            }

                        });


                    })


                }else{return false;}


            });



$('#resetTheDnBFormButton').on('click',function(){
    dnb_ResetDnbForm();
})


        });


        function dnb_ResetDnbForm(){
            $('#ifUpdateDnBId').val('');
            $('#parent_dnb').val('');
            $('#parent_dnbID').val(0);
            $('#dnb_name').val('');
            $('#dnb_type').val(0);
            $('#dnb_phone').val('');
            $('#dnb_fax').val('');
            $('#dnb_email').val('');
            $('#dnb_webpage_url').val('');
            $('#dnb_country_name').val('');
            $('#dnb_state_name').val('');
            $('#dnb_province_name').val('');
            $('#dnb_city_name').val('');
            $('#dnb_district_name').val('');
            $('#dnb_county_name').val('');
            $('#dnb_street_name').val('');
            $('#dnb_apartment_name').val('');
            $('#dnb_door_no').val(0);
            $('#dnb_postal_code').val(0);
            $('#dnb_notes').val('');
        }


        function dnb_RefreshList(pageNum,iArgs){
            var oArgs ={};
            for(var x in iArgs){ oArgs[x] = iArgs[x]}
            oArgs.action="dnb_RefreshList";
            oArgs.pageNum = pageNum;

            dnb_ConvertJSON2table('dnbListTable',1,'A',pageNum,ajaxurl,oArgs,1,dnb_SetAfterReRender);
        }

        function dnb_SetAfterReRender(){
            // to remove dnb, click event assign
            $('.dashicons-trash').on('click',function(event){
                let that = $(this);
                that = that[0];
                var sourceTarget = event.target;
                $.ajax({
                    type:"POST",
                    url: ajaxurl,
                    data: {
                        action: "dnb_DnbData",
                        targetDnBID: that.dataset.itemId,
                        processType:'giveInfoOfDnB',
                        security:ajax_nonce
                    },
                    success:function(data){
                        let iData = JSON.parse(data);
                        if(iData.dnbName && iData.dnbName!==''){
                            if(confirm('Are you sure to remove' +iData.dnbName+ ' DnB with '+ iData.dnbID +' id number?')){
                                $.ajax({
                                    type:"POST",
                                    url: ajaxurl,
                                    data: {
                                        action: "dnb_DnbData",
                                        targetDnBID: iData.dnbID,
                                        processType:'removeDnB',
                                        security:ajax_nonce
                                    },
                                    success:function(data){
                                        let iData = JSON.parse(data);
                                        alert(iData.removedMessage);
                                        if(iData.removeStatus=='OK'){
                                            var tR = sourceTarget.parentNode.parentNode; // TR


                                            $('#'+tR.id).hide( "slow", function() {
                                                $('#'+tR.id).remove();
                                            });
                                        }

                                    },
                                    error: function(errorThrown){
                                        alert(errorThrown);
                                    }

                                });
                            }else{
                                return false;
                            }
                        }
                    },
                    error: function(errorThrown){
                        alert(errorThrown);
                    }

                });
                // console.log(that.dataset.itemId);

            });

            // to edit dnb record, click event assign
            $('.dashicons-admin-tools').on('click',function(event){
                let that = $(this);
                that = that[0];
                var sourceTarget = event.target;
                var dnbID= sourceTarget.dataset.itemId;
                $.ajax({
                    type:"POST",
                    url: ajaxurl,
                    data: {
                        action: "dnb_DnbData",
                        targetDnBID: that.dataset.itemId,
                        processType:'giveInfoOfDnB',
                        security:ajax_nonce
                    },
                    success:function(data){
                        let iData = JSON.parse(data);
                        if(iData.dnbName && iData.dnbName!==''){
                            if(confirm('Are you sure to open ' +iData.dnbName+ ' DnB with '+ iData.dnbID +' id number for updating?')){
                                // load the form with retrieved DnB data
                                $('#parent_dnb').val(iData.dnbParentDNBNAME);
                                $('#parent_dnbID').val(iData.dnbParentDNB);
                                $('#dnb_name').val(iData.dnbName);
                                $('#dnb_type').val([iData.dnbType]);
                                $('#dnb_phone').val(iData.dnbPhone);
                                $('#dnb_fax').val(iData.dnbFax);
                                $('#dnb_email').val(iData.dnbEmail);
                                $('#dnb_webpage_url').val(iData.dnbWebPageURL);
                                $('#dnb_country_name').val(iData.dnbCountryName);
                                $('#dnb_state_name').val(iData.dnbStateName);
                                $('#dnb_province_name').val(iData.dnbProvinceName);
                                $('#dnb_city_name').val(iData.dnbCityName);
                                $('#dnb_county_name').val(iData.dnbCountyName);
                                $('#dnb_district_name').val(iData.dnbDistrictName);
                                $('#dnb_street_name').val(iData.dnbStreetName);
                                $('#dnb_apartment_name').val(iData.dnbApartmentName);
                                $('#dnb_door_no').val(iData.dnbDoorNo);
                                $('#dnb_postal_code').val(iData.dnbPostalCode);
                                $('#dnb_notes').val(iData.dnbNotes);
                                //
                                // set the target update id
                                var sourceTarget = event.target;
                                var dnbID= sourceTarget.dataset.itemId;
                                $('#ifUpdateDnBId').val(dnbID);
                                $('#dnbSave').toggleClass('btn-danger').toggleClass('btn-warning');
                                $('#dnbSave').attr('title','Click to update selected DnB record with the updated form data above.');
                                $('#dnbSave').text('UPDATE!');

                            }else{
                                return false;
                            }
                        }
                    },
                    error: function(errorThrown){
                        alert(errorThrown);
                    }

                });
                // console.log(that.dataset.itemId);

            });

        }










        function dnb_Ny(givenId){
            if(document.getElementById(givenId)){
                return document.getElementById(givenId);
            }

        }



//
        function dnb_ConvertJSON2table(tableID, addPaging, pagingPosition, whichPage, targetQueryURL, queryArguments, addAdditionalData, footerFunctionName, footerFunctionsArgumentsAsObject, headerFunctionName, headerFunctionsArgumentsAsObject){

            // tanimlamalar
            var incomingJSONData;
            var pagingArgumnets;
            var tableData;
            var incomingData;
            var rowCount;
            var oRow;
            var cellCount;
            var rowCounter;
            var cellCounter;
            var totalColumnCount;
            var additionalDataRow;
            var recordCount;
            var pageCount;
            var frontRecord;
            var rearRecord;
            var oPage;
            var currentTarget;
            var pages;
            //


            //
            // sunucudan veri talebi
            // önce sayfa nosunu sorguParametrelerine ekliyoruz.

            queryArguments.pageNum=whichPage;
            if(typeof headerFunctionName == 'function'){headerFunctionName(headerFunctionsArgumentsAsObject);}
            jQuery.post(
                targetQueryURL,
                queryArguments,
                function(data){
                    var rowData;
                    var rowAdditionalData;
                    // -----------  Şimdi gelen veriyle tablo yerleştirme ve sayfalama işlemleri yapılacak
                    incomingData = JSON.parse(data);
                    // tablonun içini doldurma
                    if(incomingData.tablesData==null){
                        // tableData null geldiyse
                        incomingData.tablesData=[];
                    }
                    tableData = JSON.parse(incomingData.tablesData); // tableData boş gelirse ne yapılacak, null gelince hata veriyor fonksiyon

                    rowCount = tableData.length;

                    jQuery('#'+tableID+' > tbody').html('');

                    if(Boolean(addAdditionalData) && jQuery('#'+tableID+' > thead').data('tHeadGuncelmi')!==true){ // ek veri yüklenecekse temsili kolon ekleniyor
                        jQuery('#'+tableID+' > thead').data('tHeadGuncelmi',true);
                        jQuery('#'+tableID+' > thead').find('tr:first').prepend('<th class="ortayaHizalaYapistir"><span class="dashicons dashicons-hidden curPointer" title="Hepsini kapat" id="'+tableID+'_kapatici"></span><span class="dashicons dashicons-visibility curPointer" title="Hepsini aç" id="'+tableID+'_acici"></span></th>');

                        jQuery('#'+tableID+'_acici').on('click',function(){ // ilk satırdaki header içindeki tüm gözleri temsil eden ve tıklandığında hepsine işlem yapan göz ikonları
                            jQuery('#'+tableID+' > tbody').find('.dashicons-visibility').click();
                        })
                        jQuery('#'+tableID+'_kapatici').on('click',function(){
                            jQuery('#'+tableID+' > tbody').find('.dashicons-hidden').click();
                        })
                    }


                    rowCounter=0;
                    for(rowCounter=0;rowCounter<rowCount;rowCounter++){

                        oRow = tableData[rowCounter];

                        jQuery('#'+tableID+' > tbody').append('<tr id="satir_'+rowCounter+'"></tr>');
                        rowData=[];
                        rowAdditionalData='';
                        rowData = oRow.rowData;
                        rowAdditionalData = oRow.rowAdditionalData;
                        //
                        // satıra ek veri data() ile ekleniyor, eklenen veri tabloyu düzenleyen (bu fonksiyonu çağıran fonksiyon) fonksiyon ele alacak
                        jQuery('#satir_'+rowCounter).data('rowAdditionalData',rowAdditionalData);

                        //                        //

                        // Eğer ekSatirVerisi isteniyorsa tablonun en soluna bir sütun daha eklenecek
                        if(Boolean(addAdditionalData)){
                            jQuery('#'+tableID+' > tbody').find('tr:last').append('<td class="ortayaHizalaYapistir"><span class="dashicons dashicons-visibility" title="Show details" id="acKapa_'+rowCounter+'" style="cursor:pointer"></span></td>'); // sonuncu satıra ilk hücreyi ekledik
//console.log('ilk hücreye göz yerleştirdik. Şimdi fonksiyon atayacağız tıklanma eventına.');
                            // ilk eklenen o hücreye bilgi satırını açıp kapama olayını bağlayacağız
                            jQuery('#'+tableID+' > tbody #satir_'+rowCounter).on('click','#acKapa_'+rowCounter, function(){
                                console.log('Hedeflenmiş element id: '+this.id);
                                var bST = this.id;
                                if(dnb_Ny(bST).className=='dashicons dashicons-hidden'){
                                    console.log('Kapalıymış, şimdi açılması gerek');
                                    dnb_Ny(bST).className='dashicons dashicons-visibility';
                                    jQuery(this).closest('tr').next().css('display','none');
                                }
                                else{
                                    console.log('Açıkmış, şimdi kapanması gerek');
                                    dnb_Ny(this.id).className='dashicons dashicons-hidden';
                                    //jQuery(this).closest('tr').next().toggle();
                                    jQuery(this).closest('tr').next().css('display','table-row');
                                }


                            });
                        }
                        //
                        //
                        cellCount = rowData.length;
                        for(cellCounter=0; cellCounter<cellCount; cellCounter++){
                            jQuery('#'+tableID+' > tbody').find('tr:last').append('<td>'+rowData[cellCounter]+'</td>');
                        }
                        totalColumnCount = (parseInt(cellCounter)).toString();
                        // şimdi ek veri için istendiyse bir boş satır ekleyip içine ek veriyi koyacağız
                        if(Boolean(addAdditionalData)){
                            totalColumnCount = (parseInt(cellCounter)+1).toString();
                            jQuery('#'+tableID+' > tbody').append('<tr style="display:none;" id="satir_ekVeri_'+rowCounter+'"><td colspan="'+ totalColumnCount +'">'+rowAdditionalData+'</td></tr>');
                        }


                    }


                    if(addPaging){
                        recordCount = incomingData.recordCount;
                        frontRecord = incomingData.frontRecord;
                        rearRecord = incomingData.rearRecord;
                        pageCount = incomingData.pageCount;

                        additionalDataRow='From total <span id="toplKayit" class="badge badge-inverse beyazYaz">'+recordCount+'</span>';
                        additionalDataRow+=' results, between <span id="frontRecord" class="badge badge-info beyazYaz">'+frontRecord+'</span>';
                        additionalDataRow+=' and <span id="rearRecord" class="badge badge-info beyazYaz">'+rearRecord+'</span> are shown.';
                        if(!jQuery.isNumeric(totalColumnCount)){
                            totalColumnCount=jQuery('#'+tableID+' > thead').find('> tr:first > th').length;
                            jQuery('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'">There is no result according to this filter.</td></tr>');
                        }
                        jQuery('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'">'+additionalDataRow+'</td></tr>');

// son fonksiyona parametre olarak gönderilebilecek veriler


                        if(footerFunctionsArgumentsAsObject && footerFunctionsArgumentsAsObject.sayfaSayisi==undefined){
                            footerFunctionsArgumentsAsObject['pageCount']=incomingData.pageCount;
                            footerFunctionsArgumentsAsObject['recordCount']=incomingData.recordCount;
                        }

                        // sayfalama
                        oPage = whichPage?whichPage:1;
                        pagingArgumnets = {
                            currentPage: oPage,
                            alignment:'right',
                            useBootstrapTooltip:true,
                            size:'small',
                            totalPages: pageCount,
                            onPageClicked: function(e,originalEvent,type,page){
                                e.stopImmediatePropagation();
                                currentTarget = jQuery(e.currentTarget);
                                pages = currentTarget.bootstrapPaginator("getPages");
                                dnb_ConvertJSON2table(tableID, addPaging, pagingPosition, page, targetQueryURL, queryArguments, addAdditionalData, footerFunctionName,footerFunctionsArgumentsAsObject, headerFunctionName, headerFunctionsArgumentsAsObject);
                            },
                            onPageChanged: function(e,oldPage,newPage){
                                //console.log("Eski sayfa: "+oldPage+" yenisayfa: "+newPage);
                                //newPage ile alıp paginatorAktifSayfa değişkenine atıyoruz. Bu değişken kullanılan sayfanın jssi içinde tanımlanmalı. Default olarak bir yere koymadık. ilgili js dosyasında en üstlerde
                                // var paginatorAktifSayfa=1; // şeklinde tanımlanmalı ki içi değer ile doldurulabilsin.
                                paginatorAktifSayfa = newPage;
                            }
                        }
                        //
                        if(!jQuery('#'+tableID+'_pagingCarier').length){
                            switch(pagingPosition){
                                case "Y":
                                    // Sayfalama yukarıda olsun isteniyorsa
                                    jQuery('#'+tableID).before('<div id="' + tableID + '_pagingCarier">...</div>');
                                    break;
                                case "A":
                                    // Sayfalama aşağıda olsun isteniyorsa
                                    jQuery('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'"><div id="' + tableID + '_pagingCarier">...</div></td></tr>');
                                    break;

                            }
                            jQuery('#'+tableID+'_pagingCarier').css({marginTop:'0px', marginBottom:'0px'});
                        }
                        jQuery('#'+tableID+'_pagingCarier').bootstrapPaginator(pagingArgumnets);


                    }
                    //------------
                    if(typeof footerFunctionName == 'function'){footerFunctionName(footerFunctionsArgumentsAsObject);}
                    rowCounter=0;
                }
            );

        }



        function dnb_Autocompletes(elementID,elementIDNum,attachToWhere,searchInWhere,additionalValueElementId,afterSearchFunctionName,additionalSearchArguments,dynamicVariableElementSupplierFunctionName){
            //elementID --> aramanın yapıldığı textbox idsi
            //elementIDNum --> arama sonucu seçilen seçimin değeri gizli inputa aktarılması için gizli inputun idsi
            // attachToWhere --> arama esnasında oluşan divin z-index ve pozisyon sorunu yaşamaması için sabitleneceği anaç element idsi
            // searchInWhere --> Aramanın yapılacağı tablo için belirgin bir isim, tablo ve searchInWhere anahtarı sunucu tarafında eşlenecek. Bu tablo ismi değildir, özel bir sqli bile tarif edebilir.
            // Alt satırda on keyup sonrası eğer kutunun içi boş ise id değerini sıfırlayan bir eventbind var

            $('#'+elementID).on('keyup',function(){
                if($('#'+elementID).val()==''){$('#'+elementIDNum).val('');}
            })
            //
            $('#'+elementID).autocomplete(
                {
                    appendTo: "#"+attachToWhere,
                    source: function(request, response) {
                        response([{ label: "Searching...", loading: true}]);
                        $.ajax({
                            url: ajaxurl,
                            data: {action:'dnb_Autocompletes' ,searchInWhere:searchInWhere,searchWhat:$('#'+elementID).val(), additionalValue:$('#'+additionalValueElementId).val(), additionalSearchArguments:additionalSearchArguments, dddNVFA:dynamicVariableElementSupplierFunctionName},
                            dataType: "json",
                            type: "POST",
                            success: function(data){
                                //=========================================================<
                                response($.map( data, function( item ){
                                        return {
                                            label: item.label,
                                            value: item.label,
                                            kID:item.kID,
                                            additionalData:item.additionalData,
                                            loading:false
                                        }
                                    }
                                ));


                                //=========================================================<
                            }

                        });
                    },
                    select: function(event, ui) {
                        console.log(ui.item.additionalData);
                        $('#'+elementIDNum).val(ui.item.kID);
                        $('#'+elementID).val(ui.item.label);
                        if(additionalValueElementId){
                            switch((document.getElementById(additionalValueElementId).tagName).toLowerCase()){
                                case "input":
                                    $('#'+additionalValueElementId).val(ui.item.additionalData);
                                    break;
                                case "textarea":
                                    $('#'+additionalValueElementId).val(ui.item.additionalData);
                                    break;
                                case "div":
                                    $('#'+additionalValueElementId).html(ui.item.additionalData);
                                    break;
                                case "span":
                                    $('#'+additionalValueElementId).html(ui.item.additionalData);
                                    break;
                            }
                        }


                        if(typeof  afterSearchFunctionName=='function'){afterSearchFunctionName(ui.item);}
                        return false;
                    },
                    focus: function(event, ui) {
                        return false;
                    },
                    minLength:3
                });

        }







        $('.dnb_OrderType').on('click',function(){
            let that = $(this);
            that = that[0];
            dnb_RefreshList(1,{searchField:that.dataset.searchField, sortOrder:that.dataset.sortOrder});
            that.dataset.sortOrder=(that.dataset.sortOrder=='asc')?'desc':'asc';
        });



        function dnb_DnbTypeSelectFiller(){
            $.ajax({
                type:"POST",
                url: ajaxurl,
                data: {
                    action: "dnb_DnbTypeSelectFiller"
                },
                success:function(data){
                    let iData = JSON.parse(data);
                    let iSelect = dnb_Ny('dnb_type');
                    iData.forEach(function(i){
                        let option = document.createElement("option");
                        option.text = i.name;
                        option.value = i.id;
                        iSelect.add(option);
                    });

                },
                error: function(errorThrown){
                    alert(errorThrown);
                }

            });
        }



        function dnb_CreateLastSearchesButtons(){
            if(!localStorage.getItem('lastSearches')){return false;}
            var lastSearches = JSON.parse(localStorage.getItem('lastSearches'));
            var lastSearchesLength = lastSearches.length;
            if(lastSearchesLength>25){lastSearchesLength=25;}
            var lastSearchesBadges='';
            for(var lastSearchesIndex=0; lastSearchesIndex<lastSearchesLength; lastSearchesIndex++){
                lastSearchesBadges+='<span class="lastSearches badge badge-info curPointer" title="Click to reload your last '+(lastSearchesIndex+1).toString()+'nth search" data-lastsearch-index="'+lastSearchesIndex.toString()+'">'+(lastSearchesIndex+1).toString()+'</span>';
            }
            $('#lastSearchesContainer').html(lastSearchesBadges +'<span id="clearDnBSearchHistoryButton" class="badge badge-warning curPointer dashicons dashicons-trash" style="padding:0px" title="Click to clear all dnb search history"></span>');
            // oluşan spanlar için click eventi atanıyor
            $('.lastSearches').on('click',function () {
                var that = $(this)[0];

                var oDC = lastSearches[that.dataset.lastsearchIndex];
                console.log(oDC);
                // data container to form filling
                $('#parent_dnb').val(oDC.dnbParentDNBName);
                $('#parent_dnbID').val(oDC.dnbParentDNB);
                $('#dnb_name').val(oDC.dnbName);
                $('#dnb_type option[value='+oDC.dnbType+']').prop('selected',true);
                $('#dnb_phone').val(oDC.dnbPhone);
                $('#dnb_fax').val(oDC.dnbFax);
                $('#dnb_email').val(oDC.dnbEmail);
                $('#dnb_webpage_url').val(oDC.dnbWebPageURL);
                $('#dnb_country_name').val(oDC.dnbCountryName);
                $('#dnb_state_name').val(oDC.dnbStateName);
                $('#dnb_province_name').val(oDC.dnbProvinceName);
                $('#dnb_city_name').val(oDC.dnbCityName);
                $('#dnb_county_name').val(oDC.dnbCountyName);
                $('#dnb_district_name').val(oDC.dnbDistrictName);
                $('#dnb_street_name').val(oDC.dnbStreetName);
                $('#dnb_apartment_name').val(oDC.dnbApartmentName);
                $('#dnb_door_no').val(oDC.dnbDoorNo);
                $('#dnb_postal_code').val(oDC.dnbPostalCode);
                $('#dnb_notes').val(oDC.dnbNotes);

//console.dir(localStorage.getItem('lastSearches'));
                // Search function to refres table list
                dnb_RefreshList(1,lastSearches[that.dataset.lastsearchIndex]);
            });


            $('#clearDnBSearchHistoryButton').on('click',function(){
                if(confirm('You are about to clear all DnB list searching history, do you confirm?')){
                    localStorage.removeItem('lastSearches');
                    $('#lastSearchesContainer').html('');
                }else{
                    return false;
                }

            });
        }





        // More code using $ as alias to jQuery
    });
})(jQuery);





function dnb_clearNonDigits(sourceElement){
    sourceElement.value = (sourceElement.value).replace(/\D/g, "");
}
