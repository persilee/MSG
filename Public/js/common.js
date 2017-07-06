$(function(){
    var baseLang = 'en';
    if (navigator.language) {
        baseLang = navigator.language.substring(0,5).toLowerCase();
    } else {
        baseLang = navigator.userLanguage.substring(0,5).toLowerCase();
    }
    if('zh-cn' != baseLang){
        confirmMsg = "Are you sure?";
        alertMsg = ", The page will automatically jump.";
    }else{
        confirmMsg = "确认要执行该操作吗?";
        alertMsg = ", 页面即将自动跳转。";
    }
    // ajax 跳转
    // $('.ajax-forward').on('click',function(){
    $("body").on("click",".ajax-forward", function () {
        $.blockUI();
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==0) {
                    updateAlert(data.info,'alert-error');
                    setTimeout(function(){
                        if (data.url) {
                            // location.href=data.url;
                            $.ajax({
                                url: data.url,
                                cache: false,
                                success: function (html) {
                                    $('#content').html(html);
                                    $('#top-alert').find('button').click();
                                    $.unblockUI();
                                }
                            });
                        }else{
                            $('#top-alert').find('button').click();
                            $.unblockUI();
                        }
                    },3000);
                }else{
                    $.unblockUI();
                    $('#content').html(data);
                }
            });
        }
        return false;
    });
    //ajax get请求
    // $('.ajax-get').on('click',function(){
    $("body").on("click",".ajax-get", function () {
        $.blockUI();
        var target;
        var that = this;
        // var baseLang = 'en';
        // if (navigator.userLanguage) {
        //     baseLang = navigator.userLanguage.substring(0,2).toLowerCase();
        // } else {
        //     baseLang = navigator.language.substring(0,2).toLowerCase();
        // }
        if ( $(this).hasClass('confirm') ) {
            // if('en' == baseLang){
            //     if(!confirm('Are you sure?')){
            //         $.unblockUI();
            //         return false;
            //     }
            // }else{
            //     if(!confirm('确认要执行该操作吗?')){
            //         $.unblockUI();
            //         return false;
            //     }
            // }
            if(!confirm(confirmMsg)){
                $.unblockUI();
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        // if('en' == baseLang){
                        //     updateAlert(data.info + ', The page will automatically jump.','alert-success');
                        // }else{
                        //     updateAlert(data.info + ', 页面即将自动跳转。','alert-success');
                        // }
                        updateAlert(data.info + alertMsg,'alert-success');
                    }else{
                        updateAlert(data.info,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            // location.href=data.url;
                            $.ajax({
                                url: data.url,
                                cache: false,
                                success: function (html) {
                                    $('#content').html(html);
                                    $('#top-alert').find('button').click();
                                    $.unblockUI();
                                }
                            });
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                            $.unblockUI();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info,'alert-error');
                    setTimeout(function(){
                        if (data.url) {
                            // location.href=data.url;
                            $.ajax({
                                url: data.url,
                                cache: false,
                                success: function (html) {
                                    $('#content').html(html);
                                    $('#top-alert').find('button').click();
                                    $.unblockUI();
                                }
                            });
                        }else{
                            $('#top-alert').find('button').click();
                            $.unblockUI();
                        }
                    },3000);
                }
            });
        } 
        return false;
    });

    //ajax get请求-全屏跳转
    // $('.ajax-get-full').on('click',function(){
    $("body").on("click",".ajax-get-full", function () {
        $.blockUI();
        var target;
        var that = this;
        // var baseLang = 'en';
        // if (navigator.userLanguage) {
        //     baseLang = navigator.userLanguage.substring(0,2).toLowerCase();
        // } else {
        //     baseLang = navigator.language.substring(0,2).toLowerCase();
        // }
        if ( $(this).hasClass('confirm') ) {
            // if('en' == baseLang){
            //     if(!confirm('Are you sure?')){
            //         $.unblockUI();
            //         return false;
            //     }
            // }else{
            //     if(!confirm('确认要执行该操作吗?')){
            //         $.unblockUI();
            //         return false;
            //     }
            // }
            if(!confirm(confirmMsg)){
                $.unblockUI();
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        // if('en' == baseLang){
                        //     updateAlert(data.info + ', The page will automatically jump.','alert-success');
                        // }else{
                        //     updateAlert(data.info + ', 页面即将自动跳转。','alert-success');
                        // }
                        updateAlert(data.info + alertMsg,'alert-success');
                    }else{
                        updateAlert(data.info,'alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $('#top-alert').find('button').click();
                            $.unblockUI();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info,'alert-error');
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                            $.unblockUI();
                        }
                    },3000);
                }
            });

        } 
        return false;
    });

    //ajax post submit请求
    // $('.ajax-post').on('click',function(){
    $("body").on("click",".ajax-post", function () {
        $.blockUI();
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        // var baseLang = 'en';
        // if (navigator.userLanguage) {
        //     baseLang = navigator.userLanguage.substring(0,2).toLowerCase();
        // } else {
        //     baseLang = navigator.language.substring(0,2).toLowerCase();
        // }
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);
            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    // if('en' == baseLang){
                    //     if(!confirm('Are you sure?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }else{
                    //     if(!confirm('确认要执行该操作吗?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }
                    if(!confirm(confirmMsg)){
                        $.unblockUI();
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                    target = $(this).attr('url');
                }else{
                    target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    // if('en' == baseLang){
                    //     if(!confirm('Are you sure?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }else{
                    //     if(!confirm('确认要执行该操作吗?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }
                    if(!confirm(confirmMsg)){
                        $.unblockUI();
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    // if('en' == baseLang){
                    //     if(!confirm('Are you sure?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }else{
                    //     if(!confirm('确认要执行该操作吗?')){
                    //         $.unblockUI();
                    //         return false;
                    //     }
                    // }
                    if(!confirm(confirmMsg)){
                        $.unblockUI();
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        if ( $(that).hasClass('jump') ) {
                            if($(that).hasClass('full')){
                                location.href=data.url;
                            }else{
                                $('*[data-dismiss]').click();//用于关闭弹出窗口
                                // $("#dialog-div").html("");
                                $.ajax({
                                    url: data.url,
                                    cache: false,
                                    success: function (html) {
                                        $('#content').html(html);
                                        $.unblockUI();
                                    }
                                });
                                return false;
                            }
                        }else{
                            // if('en' == baseLang){
                            //     updateAlert(data.info + ', The page will automatically jump.','alert-success');
                            // }else{
                            //     updateAlert(data.info + ', 页面即将自动跳转。','alert-success');
                            // }
                            updateAlert(data.info + alertMsg,'alert-success');
                        }
                    }else{
                        updateAlert(data.info ,'alert-success');
                    } 
                    setTimeout(function(){
                        if (data.url) {
                            if($(that).hasClass('full')){
                                location.href=data.url;
                            }else{
                                $.ajax({
                                    url: data.url,
                                    cache: false,
                                    success: function (html) {
                                        $('*[data-dismiss]').click();//用于关闭弹出窗口
                                        // $("#dialog-div").html("");
                                        $('#top-alert').find('button').click();
                                        $('#content').html(html);
                                        $.unblockUI();
                                    }
                                });
                            }
                        }else if( $(that).hasClass('no-refresh')){
                            $(that).removeClass('disabled').prop('disabled',false);
                            $.unblockUI();
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    updateAlert(data.info,'alert-error');
                    setTimeout(function(){
                        if (data.url) {
                            // location.href=data.url;
                            $.ajax({
                                url: data.url,
                                cache: false,
                                success: function (html) {
                                    $('#content').html(html);
                                    $('#top-alert').find('button').click();
                                    $.unblockUI();
                                }
                            });
                        }else{
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled',false);
                            $.unblockUI();
                        }
                    },3000);
                }
            });
        }
        return false;
    });

    /**顶部警告栏*/
    // var content = $('#main');
    var top_alert = $('#top-alert');
    top_alert.find('.close').on('click', function () {
        top_alert.removeClass('block').slideUp(200);
        // content.animate({paddingTop:'-=55'},200);
    });

    window.updateAlert = function (text,c) {
        text = text||'default';
        c = c||false;
        if ( text!='default' ) {
            top_alert.find('.alert-content').text(text);
            if (top_alert.hasClass('block')) {
            } else {
                top_alert.addClass('block').slideDown(200);
                // content.animate({paddingTop:'+=55'},200);
            }
        } else {
            if (top_alert.hasClass('block')) {
                top_alert.removeClass('block').slideUp(200);
                // content.animate({paddingTop:'-=55'},200);
            }
        }
        if ( c!=false ) {
            top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
        }
    };
    /**文件上传返回处理*/
    function uploadCallback(message){
        var data = eval(message); 
        // var baseLang = 'en';
        // if (navigator.userLanguage) {
        //     baseLang = navigator.userLanguage.substring(0,2).toLowerCase();
        // } else {
        //     baseLang = navigator.language.substring(0,2).toLowerCase();
        // }
        if (data.status==1) {
            if (data.url) {
                // if('en' == baseLang){
                //     updateAlert(data.info + ', The page will automatically jump.','alert-success');
                // }else{
                //     updateAlert(data.info + ', 页面即将自动跳转。','alert-success');
                // }
                updateAlert(data.info + alertMsg,'alert-success');
            }else{
                updateAlert(data.info ,'alert-success');
            } 
            setTimeout(function(){
                if (data.url) {
                    location.href=data.url;
                }else if( $(that).hasClass('no-refresh')){
                    $('#top-alert').find('button').click();
                    $(that).removeClass('disabled').prop('disabled',false);
                }else{
                    location.reload();
                }
            },1500);
        }else{
            updateAlert(data.info,'alert-error');
            setTimeout(function(){
                if (data.url) {
                    location.href=data.url;
                }else{
                    $('#top-alert').find('button').click();
                }
            },3000);
        }
    }

    // 对话框弹出
    // $(".func-dialog").bind("click",function(){
    $("body").on("click",".func-dialog", function () {
        url = $(this).attr('url');
        modalID = $(this).attr('data-target');
        $(modalID).find(".dialog-div").html("");
        // $(modalID).find(".dialog-div").html('<div style="text-align:center;margin-right:auto;margin-left:auto;"><img src="../../Public/img/loaders/11.gif"/></div>');
        $.ajax({
            url : url,
            type : "get",
            success: function (data) {
                if(data.status==0){
                    updateAlert(data.info,'alert-error');
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },3000);
                    $(modalID).modal('hide'); 
                }else{
                    $(modalID).find(".dialog-div").html(data);
                }
            }
        });
    });
});
    