_.namespace('doctyl.cookie', function(ns) {
    ns.set = function(name, value, expiredays, cross_subdomain) {
        var expiration = new Date(),
            domain = ((cross_subdomain) ? this._parse_domain(document.location.hostname) : ""),
            cookiestring = name + "=" + escape(value);
        
        expiration.setDate(expiration.getDate()+expiredays);
        cookiestring += ((expiredays===null) ? "" : ";expires=" + expiration.toGMTString());
        cookiestring += "; path=/";
        cookiestring += ((domain) ? ";domain=." + domain : ""); 
        document.cookie = cookiestring;
    };

    ns.remove = function(name) {
        ns.set(name, "", -1);
    };

    ns.get = function(name) {
        if (document.cookie.length>0) {
            var start = document.cookie.indexOf(name + "=");
            if (start != -1) {
                start = start + name.length + 1;
                var end = document.cookie.indexOf(";", start);
                if (end == -1) { end=document.cookie.length; }
                return unescape(document.cookie.substring(start,end));
            }
        }
        return "";
    };

    ns.exists = function(name) {
        if (document.cookie.length > 0) {
            var start = document.cookie.indexOf(name + "=");
            if (start != -1) {
                return true;
            }
        }
        return false;
    };

    ns._parse_domain = function(url) {
        var matches = url.match(/[a-z0-9][a-z0-9\-]+\.[a-z\.]{2,6}$/i);
        return matches ? matches[0] : '';
    };
});
