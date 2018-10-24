/******************
 ＊动画监听
 ＊  x.addEventListener(animationEvent, function() {
            $('.nicescroll').getNiceScroll().resize();
        });
 ******************/

//探测浏览器种类，并增加动画监听
function whichAnimationEvent() {
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
        'WebkitTransition': 'webkitAnimationEnd',
        'OTransition': 'oAnimationEnd',
        'transition': 'animationend',
        'MozTransition': 'animationend'
    }

    for (t in transitions) {
        if (el.style[t] !== undefined) {
            return transitions[t];
        }
    }
}
function whichTransitionEvent() {
    var t;
    var el = document.createElement('fakeelement');
    var transitions = {
        'WebkitTransition': 'webkitTransitionEnd',
        'OTransition': 'oTransitionEnd',
        'transition': 'transitionend',
        'MozTransition': 'transitionend'
    };
    for (t in transitions) {
        if (el.style[t] !== undefined) {
            return transitions[t];
        }
    }
}
var animationEvent = whichAnimationEvent();
var transitionEvent = whichTransitionEvent();
