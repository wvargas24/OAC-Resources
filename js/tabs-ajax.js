let clear_search;
let search_pre_content;
jQuery(function($){
    let maincontent = $('#container-tab-resource');
    let link = $('ul.resource-filter li a');
    let aditional_link = $('#aditional-categories .dropdown-menu a');
    let cat_list = $('#cat_list').val();
    let orderby_link = $('#orderby .dropdown-menu a')

    link.click(function (e) {
        e.preventDefault();
        var id = $(this).attr("id");
        link.removeClass('active');
        link.parent().removeClass('resource-active-filtr');
        $(this).addClass('active');
        $(this).parent().addClass('resource-active-filtr');
        var name = $(this).text();
        var p = $('#paged-'+name.toLowerCase()).val();
        var paged =  parseInt(p)+parseInt(1);
        $("input[type=checkbox]").each(function(){
            console.log('ID: '+$(this).attr('id'));
            $(this).prop('checked',false);
        });
        //console.log("Category: "+name+" Id: "+id+" ajax_object.ajaxurl: "+ajax_object.ajaxurl);
        if( id != 'all-items' && id != 'video-items'){
            var data = {
                action: 'tab_resource',
                term_id: id,
                name: name,
                paged: paged
            };
        }else if(id == 'video-items'){
            var data = {
                action: 'tab_video',
                term_id: id,
                name: 'Videos',
                paged: paged
            };
        }else{
            var data = {
                action: 'tab_all_resource',
                term_id: id,
                name: name,
                paged: paged,
                cat_list:cat_list
            };
        }

        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                maincontent.html(result);   
                $('#close-banner').trigger( "click" );
            },
            beforeSend: function (xhr) {
                document.querySelector('#container-tab-resource').scrollIntoView();
                loadIconLoader(maincontent);
            }
        });
    });

    aditional_link.click(function (e) {
        e.preventDefault();
        var id = $(this).attr("id");
        aditional_link.removeClass('active');
        $(this).addClass('active');
        var name = $(this).text();
        var p = $('#paged-'+name.toLowerCase()).val();
        var paged =  parseInt(p)+parseInt(1);
        $("input[type=checkbox]").each(function(){
            console.log('ID: '+$(this).attr('id'));
            $(this).prop('checked',false);
        });
        var data = {
            action: 'tab_resource',
            term_id: id,
            name: name,
            paged: paged
        };

        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                maincontent.html(result);    
                $('#close-banner').trigger( "click" );           
            },
            beforeSend: function (xhr) {
                loadIconLoader(maincontent);
            }
        });
    });

    orderby_link.click(function (e) {
        e.preventDefault();
        var id = $('ul.resource-filter li a.active').attr("id");
        orderby_link.removeClass('active');
        $(this).addClass('active');
        var name = $('ul.resource-filter li a.active').text();
        var p = $('#paged-'+name.toLowerCase()).val();
        var paged =  parseInt(p)+parseInt(1);
        var order = $(this).data('order');
        var orderby = $(this).data('orderby');
        $("input[type=checkbox]").each(function(){
            console.log('ID: '+$(this).attr('id'));
            $(this).prop('checked',false);
        });
        
        if( id != 'all-items'){
            var data = {
                action: 'tab_resource',
                term_id: id,
                name: name,
                paged: paged,
                order:order,
                orderby:orderby
            };
        }else{
            var data = {
                action: 'tab_all_resource',
                term_id: id,
                name: name,
                paged: paged,
                cat_list:cat_list,
                order:order,
                orderby:orderby
            };
        }

        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                maincontent.html(result); 
                $('#close-banner').trigger( "click" );              
            },
            beforeSend: function (xhr) {
                loadIconLoader(maincontent);
            }
        });
    });

    $("#container-tab-resource").on("click",".btn-morepost", function(e){
        e.preventDefault();
        var loadmore = $(this);                 
        loadPostAjax(loadmore);
    });

    function loadPostAjax(loadmore) {
        var name = loadmore.data('type');
        var id = loadmore.data('id');
        console.log('Name: '+name+' ID: '+id);
        var p = $('#paged-'+name).val();
        var paged =  parseInt(p)+parseInt(1);
        var data = {
                action: 'load_more_resource',
                paged: paged,
                name: name,
                term_id: id
            };
        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                $('#box-'+name).append(result); 
                $('#paged-'+name).val(paged); 
                loadmore.html('Load More '+name);   
                $('#close-banner').trigger( "click" );           
            },
            beforeSend: function (xhr) {
                //loadIconLoader(maincontent);
                loadmore.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
            }
        });
    }

    $("#container-tab-resource").on("click","#showmorepost-video", function(e){
        e.preventDefault();
        var loadmore = $(this);                 
        loadPostAjax_Video(loadmore);
    });

    function loadPostAjax_Video(loadmore) {
        var name = loadmore.data('type');
        var id = loadmore.data('id');
        console.log('Name: '+name+' ID: '+id);
        var p = $('#paged-'+name).val();
        var paged =  parseInt(p)+parseInt(1);
        var data = {
                action: 'load_more_video',
                paged: paged,
                name: name,
                term_id: id
            };
        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                $('#box-'+name).append(result); 
                $('#paged-'+name).val(paged); 
                loadmore.html('Load More '+name);   
                $('#close-banner').trigger( "click" );           
            },
            beforeSend: function (xhr) {
                //loadIconLoader(maincontent);
                loadmore.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
            }
        });
    }

    function loadIconLoader(element) {
        var iconbox = document.createElement("div");
        iconbox.className = "boxloader";
        var imgicon = document.createElement("img");
        imgicon.className = "loadericon";
        imgicon.src = "/wp-content/uploads/ajax-loader.gif";
        iconbox.append(imgicon);
        $(element).html(iconbox);
    }

    function loadArrayCatlist(name) {
        var advocasy_array = [];
        $("input:checkbox[name="+name+"]:checked").each(function(){
            console.log('ID: '+$(this).attr('id'));
            advocasy_array.push($(this).attr('id'));
        });
        return advocasy_array;
    }

    function callBackPost(taxonomy, term_id) {
        var id = $('ul.resource-filter li a.active').attr("id");
        if( id != 'all-items'){
            var data = {
                action: 'checkTaxonomy',
                taxonomy: taxonomy,
                term_id:term_id,
                cat_list:id
            };
        }else{
            var data = {
                action: 'checkTaxonomy',
                taxonomy: taxonomy,
                term_id:term_id,
                cat_list:cat_list
            };
        }
        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                maincontent.html(result);
                $('#close-banner').trigger( "click" );    
            },
            beforeSend: function (xhr) {
                loadIconLoader(maincontent);
            }
        }); 
    }

    $("#resource-box").on("click",".input-aiovg", function(e){
        var input = $(this);
        var taxonomy = input.data('taxonomy');
        var catarray = loadArrayCatlist(input.attr('name'));
        var term_id =  catarray.toString();
        callBackPost(taxonomy, term_id);     
    });

    $("#resource-box").on("click",".input-advocasy", function(e){
        var input = $(this);
        var taxonomy = input.data('taxonomy');
        var catarray = loadArrayCatlist(input.attr('name'));
        var term_id =  catarray.toString();
        callBackPost(taxonomy, term_id);     
    });

    $("#resource-box").on("click",".input-language", function(e){
        var input = $(this);
        var taxonomy = input.data('taxonomy');
        var catarray = loadArrayCatlist(input.attr('name'));
        var term_id =  catarray.toString();
        callBackPost(taxonomy, term_id);     
    });  

    $('#close-banner').on("click", function (event) {
        event.preventDefault();           
        // $(this).parent().find('#img-banner').addClass('hidden');
        // $(this).addClass('hidden');
        // $(this).parent().css('margin-bottom','0');
        $(this).parent().addClass('hidden');
        document.cookie = "oac_banner_close=yes"; 
        
    });

    /*$(document).ready(function(){
      $("#acordion > li > a").on("click", function(e){
        if($(this).parent().has("ul")) {
          e.preventDefault();
        }
        
        if(!$(this).hasClass("open")) {
          // hide any open menus and remove all other classes
          $("#acordion li ul").slideUp(350);
          $("#acordion li a").removeClass("open");
          
          // open our new menu and add the open class
          
          $("#acordion li a .fa-chevron-up").addClass('fa-chevron-down').removeClass('fa-chevron-up');

          if ($(this).find('.fa-chevron-down').length) {              
              $(this).find( ".fa-chevron-down" ).addClass('fa-chevron-up').removeClass('fa-chevron-down');
          }else {
              $(this).find( ".fa-stack-1x" ).addClass('fa-chevron-down').removeClass('fa-chevron-up');
          }
          
          $(this).next("ul").slideDown(350);
          $(this).addClass("open");
          
        }
        
        else if($(this).hasClass("open")) {
          $(this).removeClass("open");
          $(this).next("ul").slideUp(350);
        }
      });
    });*/

    $(document).on( 'submit', '#search-container form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $input = $form.find('input[name="s"]');
        var query = $input.val();
        var data = {
                action: 'load_search_results',
                query : query
            };
        
        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success : function( response ) {
                $input.prop('disabled', false);
                maincontent.html( response );
                $('#close-banner').trigger( "click" );
            },
            beforeSend: function() {
                $input.prop('disabled', true);
                if(!search_pre_content) search_pre_content = maincontent.html();
                loadIconLoader(maincontent);
            }
        });
    });

    clear_search = function(){
      maincontent.html( search_pre_content );
    }

    $("#container-tab-resource").on("click","#showmorepost-search", function(e){
        e.preventDefault();
        var loadmore = $(this);                 
        var p = $('#paged-results').val();
        var paged =  parseInt(p)+parseInt(1);
        var form = $('#search-container form');
        var input = form.find('input[name="s"]');
        var query = input.val();
        var data = {
                action: 'load_more_search_resource',
                query : query,
                paged: paged,
            };
        return $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: data,
            success: function (result) {
                $('#box-resources').append(result); 
                $('#paged-results').val(paged); 
                loadmore.html('Load More');           
            },
            beforeSend: function (xhr) {
                //loadIconLoader(maincontent);
                loadmore.html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
            }
        });
    });

    function accordionMe(selector, initalOpeningClass) {

        var speedo = 300;
        var $this = selector;
        var accordionStyle = true; // fancy or not as fancy.. (just set it to true)

        // disable all links with # as href, or the page will jump like a chicken on coce
        $this.find("li").each(function(){
            // Find all these links
            if ($(this).find("ul").size() != 0) {
                // and disable them if needed
                if ($(this).find("a:first").attr('href') == '#') {
                    $(this).find("a:first").click(function(){ return false; });
                }
            }
        });

        // Hide every ul first
        // $("#advocasy-resources li>ul").hide();
        // $("#languages li>ul").hide();

        // Open all items (depending on the class you chose)
        // $this.find("li."+initalOpeningClass).each(function(){
        //     $(this).parents("ul").slideDown(speedo);
        // });

        // and now.. time for magic
        $this.find("li a").click(function(){
            if ($(this).parent().find("ul").size() != 0) {
                if (accordionStyle) {
                    if(!$(this).parent().find("ul").is(':visible')){

                        // get all parents
                        parents = $(this).parent().parents("ul");

                        // get all visible ul's'
                        visible = $this.find("ul:visible");

                        // Loop through
                        visible.each(function(visibleIndex){

                            // check if parent was closed
                            var close = true;
                            parents.each(function(parentIndex){
                                if(parents[parentIndex] == visible[visibleIndex]){
                                    close = false;
                                    return false;
                                }
                            });

                            // if closed, close parent
                            if(close)
                                if($(this).parent().find("ul") != visible[visibleIndex])
                                    $(visible[visibleIndex]).slideUp(speedo);              
                        });
                    }
                }

                // if the parent was shown at first, hide him and vica versa
                if($(this).parent().find("ul:first").is(":visible"))
                    $(this).parent().find("ul:first").slideUp(speedo);

                else
                    $(this).parent().find("ul:first").slideDown(speedo);


            }
        }); // einde klik event
    }
    accordionMe($("#video-resources"), "selected");
    accordionMe($("#advocasy-resources"), "selected");
    accordionMe($("#languages"), "selected");
    

    $('.has-child').on('click', function(e){
        e.preventDefault();
        i = $(this).find('i');
        d = $(this).next('div');
        d.toggle();
        i.toggleClass('child-closed child-opened');
    });

    $('.btn-clear').on('click', function(e){
        e.preventDefault();
        $('#all-items').trigger('click');
    });

    //Search autocomplete
    $( ".search-field" ).autocomplete({
      source: ajax_object.ajaxurl + "?action=auto_search",
      minLength: 2,
      select: function( event, ui ) {
        //console.log( "Selected: " + ui.item.value + ", " + ui.item.id );
        window.location.href = ui.item.id;
      }
    });

});