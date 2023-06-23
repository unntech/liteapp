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

    $("form").submit(function(event){
        checkSubmitFlag++;
        if(checkSubmitFlag > 1){
            event.preventDefault();
        }
    });

    $("[data-toggle|='adminConfirm']").click(function (event){
        //console.log(event);
        adminConfirmOptStyle();
        if(event.target.dataset.yes){
            $(this).removeAttr('data-yes');
        }else{
            event.preventDefault();
            $("#adminConfirmOpt").modal('show');
            $("#adminConfirmOptLabel").html(event.target.attributes.title.value)
            $("#adminConfirmOpt .modal-body").html(event.target.dataset.msg);
            $("#adminConfirmOptYes").attr("data-nodename", event.target.nodeName);
            $("#adminConfirmOptYes").attr("data-targetid", '#'+event.target.id);
        }
    });

    $("#adminActivityContent").on('click', '#adminConfirmOptYes', function (event){
        //console.log(event);
        if(event.target.dataset.nodename == 'A'){
            window.location.href = $(event.target.dataset.targetid).attr('href');
        }else{
            $(event.target.dataset.targetid).attr('data-yes', true);
            $(event.target.dataset.targetid).click();
        }
        $("#adminConfirmOpt").modal('hide');
    });

    $('#adminActivityContent').on('hidden.bs.modal', '#adminConfirmOpt', function (event) {
        //console.log('hidden');
        $("#adminActivityContent #adminConfirmOpt").remove();
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

    $("[data-toggle|='compare-different-highlights']").compareDifferentHighlights();
    $('[data-toggle="tooltip"]').tooltip()
});


$.fn.compareDifferentHighlights = function (){
    $("[data-toggle|='compare-different-highlights']").each(function (){
        let od = $(this).attr('title');
        let nd = $(this).html();
        if(od != nd){
            $(this).addClass('text-danger');
            $(this).attr('data-toggle', 'tooltip');
            $(this).attr('data-placement', 'bottom');
        }
    });
}

function adminConfirmOptStyle(){
    $('#adminActivityContent').append(`
    <div class="modal fade" id="adminConfirmOpt" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="adminConfirmOptLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminConfirmOptLabel">操作提示</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-danger" id="adminConfirmOptYes">确定</button>
            </div>
        </div>
    </div>
    </div>
    `);
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


