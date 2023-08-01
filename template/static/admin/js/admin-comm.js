// JavaScript Document

function adminCommFunc(token) {
    this.token = token === undefined ? '' : token;

    this.post = function (url, d, successFunc) {
        d['token'] = this.token;
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
        if(!n)
        {
            n = 5;
        }

        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for(var i=0; i < n; i++)
        {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }
}
