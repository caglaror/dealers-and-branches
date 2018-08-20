var ajaxurl = "../wp-admin/admin-ajax.php";

(function($) {
    $(function() {
        // More code using $ as alias to $

$(document).ready(function(){
    //load the list table
    dnb_Refresh_List(1);

});



function dnb_Refresh_List(pageNum,iArgs){
    // iArgs sample {sortField:that.dataset.sortField, sortOrder:that.dataset.sortOrder, pagingPosition:oTable.dataset.pagingPosition, recordsPerpage:oTable.dataset.recordPerpage}
    //
    if(!document.getElementById('dnbList')){return false;}
    let oTable = document.getElementById('dnbList');
    let oArgs ={};
    let fieldsToList = [];
    let thTgs = oTable.getElementsByTagName('th');
    for(let tgz = 0; tgz<thTgs.length; tgz++){
        fieldsToList.push(thTgs[tgz].dataset.sortField);
    }
    console.log('Fields to list in table:'+fieldsToList);


    for(var x in iArgs){ oArgs[x] = iArgs[x]}
    oArgs.action="dnb_Refresh_List";
    oArgs.pageNum = pageNum;
    oArgs.pagingPosition=oTable.dataset.pagingPosition;
    oArgs.recordsPerpage=oTable.dataset.recordsPerpage;
    oArgs.fieldsToList=fieldsToList;
    dnb_ConvertJSON2table('dnbList',1,oArgs.pagingPosition,pageNum,ajaxurl,oArgs,0);
}


function dnb_Ny(givenId){
    if(document.getElementById(givenId)){
        return document.getElementById(givenId);
    }
}


//
function dnb_ConvertJSON2table(tableID, addPaging, pagingPosition, whichPage, targetQueryURL, queryArguments, addAdditionalData, footerFunctionName, footerFunctionsArgumentsAsObject, headerFunctionName, headerFunctionsArgumentsAsObject){
console.log(queryArguments);
    // tanimlamalar
    var incomingJSONData;
    var pagingArguments;
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
    $.post(
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

            $('#'+tableID+' > tbody').html('');

            if(Boolean(addAdditionalData) && $('#'+tableID+' > thead').data('tHeadGuncelmi')!==true){ // ek veri yüklenecekse temsili kolon ekleniyor
                $('#'+tableID+' > thead').data('tHeadGuncelmi',true);
                $('#'+tableID+' > thead').find('tr:first').prepend('<th class="ortayaHizalaYapistir"><span class="dashicons dashicons-hidden curPointer" title="Hepsini kapat" id="'+tableID+'_kapatici"></span><span class="dashicons dashicons-visibility curPointer" title="Hepsini aç" id="'+tableID+'_acici"></span></th>');

                $('#'+tableID+'_acici').on('click',function(){ // ilk satırdaki header içindeki tüm gözleri temsil eden ve tıklandığında hepsine işlem yapan göz ikonları
                    $('#'+tableID+' > tbody').find('.dashicons-visibility').click();
                })
                $('#'+tableID+'_kapatici').on('click',function(){
                    $('#'+tableID+' > tbody').find('.dashicons-hidden').click();
                })
            }


            rowCounter=0;
            for(rowCounter=0;rowCounter<rowCount;rowCounter++){

                oRow = tableData[rowCounter];

                $('#'+tableID+' > tbody').append('<tr id="satir_'+rowCounter+'"></tr>');
                rowData=[];
                rowAdditionalData='';
                rowData = oRow.rowData;
                rowAdditionalData = oRow.rowAdditionalData;
                //
                // satıra ek veri data() ile ekleniyor, eklenen veri tabloyu düzenleyen (bu fonksiyonu çağıran fonksiyon) fonksiyon ele alacak
                $('#satir_'+rowCounter).data('rowAdditionalData',rowAdditionalData);

                //                        //

                // Eğer ekSatirVerisi isteniyorsa tablonun en soluna bir sütun daha eklenecek
                if(Boolean(addAdditionalData)){
                    $('#'+tableID+' > tbody').find('tr:last').append('<td class="ortayaHizalaYapistir"><span class="dashicons dashicons-visibility" title="Show details" id="acKapa_'+rowCounter+'" style="cursor:pointer"></span></td>'); // sonuncu satıra ilk hücreyi ekledik
//console.log('ilk hücreye göz yerleştirdik. Şimdi fonksiyon atayacağız tıklanma eventına.');
                    // ilk eklenen o hücreye bilgi satırını açıp kapama olayını bağlayacağız
                    $('#'+tableID+' > tbody #satir_'+rowCounter).on('click','#acKapa_'+rowCounter, function(){
                        console.log('Hedeflenmiş element id: '+this.id);
                        var bST = this.id;
                        if(dnb_Ny(bST).className=='dashicons dashicons-hidden'){
                            console.log('Kapalıymış, şimdi açılması gerek');
                            dnb_Ny(bST).className='dashicons dashicons-visibility';
                            $(this).closest('tr').next().css('display','none');
                        }
                        else{
                            console.log('Açıkmış, şimdi kapanması gerek');
                            dnb_Ny(this.id).className='dashicons dashicons-hidden';
                            //$(this).closest('tr').next().toggle();
                            $(this).closest('tr').next().css('display','table-row');
                        }


                    });
                }
                //
                //
                cellCount = rowData.length;
                for(cellCounter=0; cellCounter<cellCount; cellCounter++){
                    $('#'+tableID+' > tbody').find('tr:last').append('<td>'+rowData[cellCounter]+'</td>');
                }
                totalColumnCount = (parseInt(cellCounter)).toString();
                // şimdi ek veri için istendiyse bir boş satır ekleyip içine ek veriyi koyacağız
                if(Boolean(addAdditionalData)){
                    totalColumnCount = (parseInt(cellCounter)+1).toString();
                    $('#'+tableID+' > tbody').append('<tr style="display:none;" id="satir_ekVeri_'+rowCounter+'"><td colspan="'+ totalColumnCount +'">'+rowAdditionalData+'</td></tr>');
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
                if(!$.isNumeric(totalColumnCount)){
                    totalColumnCount=$('#'+tableID+' > thead').find('> tr:first > th').length;
                    $('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'">There is no result according to this filter.</td></tr>');
                }
                $('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'">'+additionalDataRow+'</td></tr>');

// son fonksiyona parametre olarak gönderilebilecek veriler


                if(footerFunctionsArgumentsAsObject && footerFunctionsArgumentsAsObject.sayfaSayisi==undefined){
                    footerFunctionsArgumentsAsObject['pageCount']=incomingData.pageCount;
                    footerFunctionsArgumentsAsObject['recordCount']=incomingData.recordCount;
                }

                // sayfalama
                oPage = whichPage?whichPage:1;
                pagingArguments = {
                    currentPage: oPage,
                    alignment:'right',
                    useBootstrapTooltip:true,
                    size:'small',
                    totalPages: pageCount,
                    onPageClicked: function(e,originalEvent,type,page){
                        e.stopImmediatePropagation();
                        currentTarget = $(e.currentTarget);
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
                if(!$('#'+tableID+'_pagingCarier').length){
                    switch(pagingPosition){
                        case "A":
                            // Sayfalama yukarıda olsun isteniyorsa
                            $('#'+tableID).before('<div id="' + tableID + '_pagingCarier">...</div>');
                            break;
                        case "B":
                            // Sayfalama aşağıda olsun isteniyorsa
                            $('#'+tableID+' > tbody').append('<tr><td colspan="'+totalColumnCount+'"><div id="' + tableID + '_pagingCarier">...</div></td></tr>');
                            break;

                    }
                    $('#'+tableID+'_pagingCarier').css({marginTop:'0px', marginBottom:'0px'});
                }
                $('#'+tableID+'_pagingCarier').bootstrapPaginator(pagingArguments);


            }
            //------------
            if(typeof footerFunctionName == 'function'){footerFunctionName(footerFunctionsArgumentsAsObject);}
            rowCounter=0;
        }
    );

}



$('.dnb_OrderType').on('click',function(){
            //console.log('orderType class clicked!');
            let that = $(this);
            that = that[0];
            dnb_Refresh_List(1,{sortField:that.dataset.sortField, sortOrder:that.dataset.sortOrder});
            that.dataset.sortOrder=(that.dataset.sortOrder=='asc')?'desc':'asc';
        });

$('#dnb_searchButton').on('click',function(){
    let that = $(this);
    that = that[0];
    let searchString = $('#dnb_searchString').val();
    let searchOnlyIn = that.dataset.searchFields;
    if(searchString.length>2){
        if(searchOnlyIn!=='' && searchOnlyIn!==null){
            dnb_Refresh_List(1,{searchString:searchString, searchOnlyIn:searchOnlyIn})
        }else{
            alert('Search field restrictions was not set! Please check them out.');
            return false;

        }

    }else{
        alert('Search string must be at least 3 chars long!');
        $('#dnb_searchString').focus();
    }

});

$('#dnb_FilterSelection').on('change',function(){
    let selectedFilter =  $('#dnb_FilterSelection').val();
    dnb_Refresh_List(1,{filterBy:selectedFilter});
    console.log(selectedFilter);
});


        // More code using $ as alias to $
    });
})(jQuery);

