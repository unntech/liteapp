// JavaScript Document

$(document).ready(function() {
    $("#navMenuStyleSetting [name=navStyle]").click(function (){
        toastr.options.positionClass = "toast-top-center";
        let d = {
            "nav": $(this).val(),
            "action": "SETNAV"
        };
        adminComm.post('/admin/profile.php', d, function (dataBody, status) {
            if (status == 'success') {
                let ret = JSON.parse(dataBody);
                //console.log(ret);
                if(ret.errcode == 0) {
                    let data = ret.data;
                    toastr.success('设置成功：'+ data.navigation);
                }else{
                    toastr.warning(ret.errcode + ": "+ret.msg);
                }
            }
        });
    });

    $("[data-toggle|='previewImage']").click(function (){
        let img = $(this).attr('src');
        adminPreviewImage(img);
    });

    $("[data-toggle|='adminPopForm']").click(function (){
        //toastr.info($(this).attr('data-target'));
        let srcdivid = $(this).attr('data-target');
        adminPopWrapper();
        $('#admin-pop-background').show();
        $("#admin-pop-wrapper").show();
        $("#admin-pop-body").html($(srcdivid).html());
        $("#admin-pop-wrapper .admin-pop-header .header-text").html($(this).attr('title'));
        let _dataopt = $(this).attr('data-option');
        if(_dataopt == null || _dataopt == undefined || _dataopt == ""){

        }else {
            let dataOption = JSON.parse(_dataopt);
            //console.log(dataOption);
            if (dataOption.hasOwnProperty('pop')) {
                if (dataOption.pop == 'full') {
                    $("#admin-pop-wrapper").removeClass('admin-pop-wrapper-modal');
                    if ($("#admin-main-sidebar").length > 0) {
                        $("#admin-pop-wrapper").addClass('admin-pop-wrapper-full');
                    } else {
                        $("#admin-pop-wrapper").addClass('admin-pop-wrapper-full1');
                    }
                    $(".admin-pop-header [data-target|='fullscreen']").remove();
                }
            }
            if (dataOption.hasOwnProperty('width')) {
                $(".admin-pop-wrapper-modal").css('width', dataOption.width + 'px');
                $(".admin-pop-wrapper-modal").css('margin-left', dataOption.width / 2 * -1 + 'px');
                $(".admin-pop-header [data-target|='fullscreen']").remove();
            }
            if (dataOption.hasOwnProperty('height')) {
                $("#admin-pop-wrapper .admin-pop-body").css('min-height', dataOption.height + 'px');
            }
        }
    });

    $(".admin-pop-header [data-target|='close']").click(function (){
        adminPopWraClose();
    });

    $(".admin-pop-header [data-target|='fullscreen']").click(function (){
        adminPopWraFullsreen();
    });

    $("#presentation-top-bar-close").click(function (){
       $("#presentation-top-bar").slideUp();
    });

    $("#navtop-appname").hover(function (){
        $("#presentation-top-bar").slideDown();
    })

    $("#clear-cache").click(function (){
        toastr.options.positionClass = "toast-top-center";
        let d = {
            "action": "clearCache"
        };
        adminComm.post('/admin/profile.php', d, function (dataBody, status) {
            if (status == 'success') {
                let ret = JSON.parse(dataBody);
                //console.log(ret);
                if(ret.errcode == 0) {
                    let data = ret.data;
                    toastr.success('清除缓存成功！');
                }else{
                    toastr.warning(ret.errcode + ": "+ret.msg);
                }
            }
        });
    });

});

function removePresentation(k){
    let d = {
        "id": k,
        "action": "removePresentation"
    };
    adminComm.post('/admin/profile.php', d, function (dataBody, status) {
        if (status == 'success') {
            let ret = JSON.parse(dataBody);
            //console.log(ret);
            if(ret.errcode == 0) {
                let data = ret.data;
                $("#presentation-"+data.id).remove();
            }else{
                toastr.warning(ret.errcode + ": "+ret.msg);
            }
        }
    });
}

function adminPopWrapper(){
    $('#adminActivityContent').append(`
    <div class="admin-pop-wrapper admin-pop-wrapper-modal" id="admin-pop-wrapper">
    <div class="admin-pop-header">
        <span class="header-text">弹窗</span>
        <a href="javascript:adminPopWraClose()" data-target="close"><i class="bi bi-x-lg"></i></a>
        <a href="javascript:adminPopWraFullsreen()" data-target="fullscreen"><i class="bi bi-fullscreen-exit"></i></a>
    </div>
    <div class="admin-pop-body" id="admin-pop-body">
    </div>
    </div>
    `);
}

function adminPopWraClose(){
    $('#admin-pop-background').hide();
    $("#admin-pop-wrapper").hide().remove();
}

function adminPopWraFullsreen(){
    //let width =$('.admin-content-body').css('width');
    if($("#admin-pop-wrapper").hasClass('admin-pop-wrapper-modal')){
        $("#admin-pop-wrapper").removeClass('admin-pop-wrapper-modal');
        if($("#admin-main-sidebar").length > 0){
            $("#admin-pop-wrapper").addClass('admin-pop-wrapper-full');
        }else{
            $("#admin-pop-wrapper").addClass('admin-pop-wrapper-full1');
        }
    }else{
        $("#admin-pop-wrapper").removeClass('admin-pop-wrapper-full');
        $("#admin-pop-wrapper").removeClass('admin-pop-wrapper-full1');
        $("#admin-pop-wrapper").addClass('admin-pop-wrapper-modal');
    }
}

function adminPreviewImage(img){
    let ntop = getScrollTop();
    $("#admin-pop-background").show();
    $('#adminActivityContent').append(`
    <div id="admin-preview-image">
        <i class="bi bi-x-circle-fill pi-close" onclick="closePreviewImage();"></i>
        <i class="bi bi-arrow-clockwise pi-close" onclick="rotatePreviewImage();"></i>
        <div class="image"><img src="${img}" alt="" data-rotate="0"></div>
    </div>
    `);
    $("#admin-preview-image").css("top", ntop + 'px');
    $("body").css('overflow', 'auto');
}

function closePreviewImage(){
    $('#admin-pop-background').hide();
    $("#admin-preview-image").hide().remove();
    $("body").css('overflow', '');
}

function rotatePreviewImage(){
    let img = $("#admin-preview-image img");
    let rd = Number(img[0].dataset.rotate);
    rd += 90;
    img.css('transform','rotate('+ rd + 'deg)');
    img.attr('data-rotate', rd);
}

function adminLoadingOpen(){
    $("#admin-pop-background").show();
    $('#adminActivityContent').append(`
    <div class="text-center" id="admin-loading">
        <button class="btn btn-primary btn-lg" type="button" disabled>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
        Loading...
        </button>
    </div>
    `);
}
function adminLoadingClose(){
    $('#admin-pop-background').hide();
    $("#admin-loading").hide().remove();
}

function treeviewopen(id){
    //$(".sidebar-menu-sub").hide();
    $("#sidebar-submenu-"+id).toggle();
}

//获取当前滚动条的位置
function getScrollTop(){
    var scrollTop=0
    if(document.documentElement && document.documentElement.scrollTop){
        scrollTop = document.documentElement.scrollTop
    }else if(document.body){
        scrollTop = document.body.scrollTop
    }
    return scrollTop
}

function ajaxviewopen(id){

}

function userInfoMenu(){
    $("#userinfomenu").fadeToggle();
}

function rightSidebarBox(){
    $("#admin-right-sidebar").fadeToggle();
}

function navigatorSiderToggle(){
    if(navigatorSiderFlag == 0){
        $("#admin-main-sidebar").addClass('main-sidebar-sm');
        $(".admin-content-wrapper").css('padding-left', '68px');
        $(".main-header").addClass('main-header-sm')
        $("#admin-main-sidebar .menu-text").hide();
        navigatorSiderFlag = 1;
        $.cookie('navigatorSiderFlag', 1, {path: '/'});
    }else{
        $("#admin-main-sidebar").removeClass('main-sidebar-sm');
        $(".admin-content-wrapper").css('padding-left', '245px');
        $(".main-header").removeClass('main-header-sm');
        $("#admin-main-sidebar .menu-text").fadeIn("slow");
        navigatorSiderFlag = 0;
        $.cookie('navigatorSiderFlag', 0, {path: '/'});
    }
}

function LiadminFullScreen(){
    if (
        document.fullscreen ||
        document.mozFullScreen ||
        document.webkitIsFullScreen ||
        document.webkitFullScreen ||
        document.msFullScreen
    ){
        if(document.exitFullScreen) {
            document.exitFullScreen();
        } else if(document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if(document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if(document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }else{
        let ele = document.documentElement;
        if (ele.requestFullscreen) {
            ele.requestFullscreen();
        } else if (ele.mozRequestFullScreen) {
            ele.mozRequestFullScreen();
        } else if (ele.webkitRequestFullscreen) {
            ele.webkitRequestFullscreen();
        } else if (ele.msRequestFullscreen) {
            ele.msRequestFullscreen();
        }
    }
}


