// JavaScript Document

$(document).ready(function() {
    $("[data-toggle|='adminPopForm']").click(function (){
        //toastr.info($(this).attr('data-target'));
        let srcdivid = $(this).attr('data-target');
        adminPopWrapper();
        $('#admin-pop-background').show();
        $("#admin-pop-wrapper").show();
        $("#admin-pop-body").html($(srcdivid).html());
        $("#admin-pop-wrapper .admin-pop-header .header-text").html($(this).attr('title'));
        let _dataopt = $(this).attr('data-option');
        if(_dataopt == null || _dataopt != undefined || _dataopt == ""){

        }else {
            let dataOption = JSON.parse(_dataopt);
            //console.log(dataConfig);
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

});


function adminPopWrapper(){
    $('body').append(`
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

function treeviewopen(id){
    //$(".sidebar-menu-sub").hide();
    $("#sidebar-submenu-"+id).toggle();
}

function ajaxviewopen(id){

}

function userInfoMenu(){
    $("#userinfomenu").fadeToggle();
}

function rightSidebarBox(){
    $("#admin-right-sidebar").fadeToggle();
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


