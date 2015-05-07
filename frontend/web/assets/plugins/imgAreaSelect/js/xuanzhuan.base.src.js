var 
    userAgent = navigator.userAgent,
    isIE = /msie/i.test(userAgent) && !window.opera,
    isWebKit = /webkit/i.test(userAgent),
    isFirefox = /firefox/i.test(userAgent);
function rotate(target, degree) {
    if (isWebKit) {
        target.style.webkitTransform = "rotate(" + degree + "deg)";
    } else if (isFirefox) {
        target.style.MozTransform = "rotate(" + degree + "deg)";
    } else if (isIE) {
        //chessDiv.style.filter = "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + degree + ")";
        degree = degree / 180 * Math.PI;
        var sinDeg = Math.sin(degree);
        var cosDeg = Math.cos(degree);
         
        target.style.filter = "progid:DXImageTransform.Microsoft.Matrix(" +
                "M11=" + cosDeg + ",M12=" + (-sinDeg) + ",M21=" + sinDeg + ",M22=" + cosDeg + ",SizingMethod='auto expand')";
    } else {
        target.style.transform = "rotate(" + degree + "deg)";
    }
}