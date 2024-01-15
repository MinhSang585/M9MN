function checkClientos() {
    var clientosType = null;
    var md = new MobileDetect(window.navigator.userAgent);
    if (md.os() == null) {
        clientosType = "pc";
    } else {
        clientosType = md.os();
    }
    return clientosType;
}

function checkBrowser() {
    var ua = navigator.userAgent.toLocaleLowerCase();
    var browserType = null;
    if (ua.match(/msie/) != null || ua.match(/trident/) != null) {
        browserType = "IE";
        browserVersion = ua.match(/msie ([\d.]+)/) != null ? ua.match(/msie ([\d.]+)/)[1] : ua.match(/rv:([\d.]+)/)[1];
    } else if (ua.match(/firefox/) != null) {
        browserType = "firefox";
    } else if (ua.match(/ubrowser/) != null) {
        browserType = "UC";
    } else if (ua.match(/opera/) != null) {
        browserType = "opera";
    } else if (ua.match(/bidubrowser/) != null) {
        browserType = "bidu";
    } else if (ua.match(/metasr/) != null) {
        browserType = "sougou";
    } else if (ua.match(/tencenttraveler/) != null || ua.match(/qqbrowse/) != null) {
        browserType = "qq";
    } else if (ua.match(/chrome/) != null) {
        var is360 = _mime("type", "application/vnd.chromium.remoting-viewer");

        function _mime(option, value) {
            var mimeTypes = navigator.mimeTypes;
            for (var mt in mimeTypes) {
                if (mimeTypes[mt][option] == value) {
                    return true;
                }
            }
            return false;
        }

        if (is360) {
            browserType = '360';
        } else {
            browserType = "chrome";
        }

    } else if (ua.match(/safari/) != null) {
        browserType = "Safari";
    }
    return browserType;
}