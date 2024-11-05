// JavaScript Document
$(document).ready(function() {
    $("form").submit(function(event){
        checkSubmitFlag++;
        if(checkSubmitFlag > 1){
            event.preventDefault();
            toastr.info('请勿重复提交，请稍等一会儿~');
        }else if(checkSubmitFlag === 1){
            setTimeout(function (){
                checkSubmitFlag = 0;
            }, 5000);
        }
    });

    $("[data-click|='once']").click(function (event){
        $(this).attr('disabled', true);
    });

    $("[data-click|='repeat']").click(function (event){
        checkRepeatFlag++;
        if(checkRepeatFlag > 1){
            event.preventDefault();
            toastr.info('请勿重复点击，稍等一会儿~');
        }else if(checkRepeatFlag === 1){
            setTimeout(function (){
                checkRepeatFlag = 0;
            }, 3000);
        }
    });

    $("[data-toggle|='adminConfirm']").click(function (event){
        //console.log(event);
        adminConfirmOptStyle();
        if(event.target.dataset.yes){
            //$(this).removeAttr('data-yes');
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

    //$("[data-display|='decimal-small']").numberDecimalSmall();
    $("[data-toggle|='compare-different-highlights']").compareDifferentHighlights();
    $('[data-toggle="tooltip"]').tooltip();
});

$.fn.numberDecimalSmall = function (){
    $(this).each(function (){
        let number = $(this)[0].textContent;
        let parts = number.split('.');
        let integer = parts[0];
        let decimal = parts[1] ? parts[1] : '00';
        $(this).html(`${integer}.<small>${decimal}</small>`);
    });
}

$.fn.compareDifferentHighlights = function (){
    $(this).each(function (){
        let od = $(this).attr('title');
        let ocd = $(this).attr('data-compare');
        if(ocd !== undefined){
            od = ocd;
        }
        let nd = $(this).html();
        if(od !== nd){
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

function adminCommFunc(token) {
    this.token = token === undefined ? '' : token;

    this.post = function (url, d, successFunc) {
        d['apiToken'] = this.token;
        $.ajax({
            url: url,
            data: JSON.stringify(d),
            type: 'POST',
            processData: false,
            contentType: 'application/json',
            success: function (data, status) {
                successFunc(data, status);
            }
        });
    }

    this.formPost = function (url, formData = {}, fileData =[], successFunc) {
        let postData = new FormData();
        postData.append("apiToken", this.token);

        $.each(formData, function (key, value){
            postData.append(`${key}`, value);
        });
        if(Array.isArray(fileData)){
            fileData.forEach(el=>{
                let l = el.file.length;
                let field = el.name;
                if(l === 0){
                    postData.append(field, null);
                    return;
                }
                if(l > 1){
                    field = field + '[]';
                }
                for(let i = 0; i < l; i++){
                    postData.append(field, el.file[i]);
                }
            })
        }

        $.ajax({
            url: url,
            type: 'POST',
            async: true,
            data: postData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (data, status) {
                successFunc(data, status);
            }
        });
    }

    this.awaitPost = function (url, d) {
        d['apiToken'] = this.token;
        return $.ajax({
            url: url,
            data: JSON.stringify(d),
            type: 'POST',
            async: false,
            contentType: 'application/json'
        });
    }

    this.apiPost = function (url, d, successFunc) {
        let p = {};
        p['head'] = {"unique_id":this.randString(24),"apiToken":this.token};
        p['body'] = d;
        p['signType'] = "NONE";
        $.ajax({
            url: url,
            data: JSON.stringify(p),
            type: 'POST',
            processData: false,
            contentType: 'application/json',
            success: function (data, status) {
                successFunc(data, status);
            }
        });
    }

    this.formatCurrency = function (num) {
        num = num.toString().replace(/\$|\,/g, '');
        if (isNaN(num))
            num = "0";
        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num * 100 + 0.50000000001);
        cents = num % 100;
        num = Math.floor(num / 100).toString();
        if (cents < 10)
            cents = "0" + cents;
        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
            num = num.substring(0, num.length - (4 * i + 3)) + ','
                + num.substring(num.length - (4 * i + 3));
        return (((sign) ? '' : '-') + num + '.' + cents);
    }

    /**
     * Function generates a random string for use in unique IDs, etc
     *
     * @param <int> n - The length of the string
     */
    this.randString = function(n){
        if(!n){
            n = 5;
        }
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        for(var i=0; i < n; i++){
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return text;
    }

    this.LoadingOpen = function (txt = 'Loading...'){
        $("#admin-pop-background").show();
        $('#adminActivityContent').append(`
    <div class="text-center" id="admin-loading">
        <button class="btn btn-primary btn-lg" type="button" disabled>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
        ${txt}
        </button>
    </div>
    `);
    }

    this.LoadingClose = function (){
        $('#admin-pop-background').hide();
        $("#admin-loading").hide().remove();
    }
}
