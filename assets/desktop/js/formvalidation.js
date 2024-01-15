var isNumber = function (evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey &&
        // numbers   
        key >= 48 && key <= 57 ||
        // Numeric keypad
        key >= 96 && key <= 105 ||
        // Backspace and Tab and Enter
        key == 8 || key == 9 || key == 13 ||
        // Home and End
        key == 35 || key == 36 ||
        // left and right arrows
        key == 37 || key == 39 ||
        // Del and Ins
        key == 46 || key == 45 ||
        // ctrl+a and ctrl+x and ctrl+z
        (e.ctrlKey && (e.keyCode == 65 || e.keyCode == 88 || e.keyCode == 90))) {
        // input is VALID
    } else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
};


var numberWithCommas = function (n) {
    //var parts = n.toString().split(".");
    //return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");

    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}