function getElementsByClassName(className, tag, elm){
    var testClass = new RegExp("(^|\\\\s)" + className + "(\\\\s|$)");
    var tag = tag || "*";
    var elm = elm || document;
    var elements = (tag == "*" && elm.all)? elm.all : elm.getElementsByTagName(tag);
    var returnElements = [];
    var current;
    var length = elements.length;
    for(var i=0; i<length; i++){
        current = elements[i];
        if(testClass.test(current.className)){
            returnElements.push(current);
        }
    }
    return returnElements;
}

// Takes an ISO time and returns a string representing how
// long ago the date represents.
// http://ejohn.org/blog/javascript-pretty-date/
function prettyDate(time){
    var date = new Date();
    date.setTime(time*1000); // milliseconds
    var diff = (((new Date()).getTime() - date.getTime()) / 1000);
    var day_diff = Math.floor(diff / 86400);
            
    if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
        return;
            
    return day_diff == 0 && (
            diff < 60 && "just now" ||
            diff < 120 && "1 minute ago" ||
            diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
            diff < 7200 && "1 hour ago" ||
            diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
        day_diff == 1 && "Yesterday" ||
        day_diff < 7 && day_diff + " days ago" ||
        day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
}

function showHideContent() {
    var sibling = this.nextSibling;
    
    if (-1 == sibling.className.indexOf('collapsed')) {
        this.className = 'expand';
        sibling.className += ' collapsed';
    } else {
        this.className = 'collapse';
        sibling.className = sibling.className.replace('collapsed','');
    }
}

window.onload = function() {
    //Dates to age
    var dates = getElementsByClassName('date');
    for (var i=0; i< dates.length; i++) {
        dates[i].title = dates[i].innerHTML;
        dates[i].innerHTML = prettyDate(dates[i].id.replace('post',''));

        if (dates[i].title && dates[i].title != '') {
            dates[i].style.cursor = "pointer";
            dates[i].onclick = function() {
                var tmp = this.innerHTML;
                this.innerHTML = this.title;
                this.title = tmp;
            }
        }
    }
    
    //Show/hide posts
    /*
    var postcontent = getElementsByClassName('post-content');
    for (var i=0; i<postcontent.length; i++){
        var p = postcontent[i].parentNode;
        
        var a = document.createElement('A');
        a.className = 'collapse';
        a.innerHTML = 'show/hide';
        a.onclick = showHideContent;
        
        p.insertBefore(a, postcontent[i]);
    }
    */
}