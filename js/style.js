/** Requires prototype and behaviour */
var ruleset = {
    'input.validDate' : function(el) {
        el.onchange = function() {
            checkDate(this);
        }
    },
    '#qShow' : function(el) {
        el.onclick = function() {
            fixQuestions();
        }
    },
    '#iShow' : function(el) {
        el.onclick = function() {
            fixInitials();
        }
    },
    '#questionTable' : function(el) {
        setDisplayCheckboxes(checkCookies());
        fixInitials();
        fixQuestions();
    },
    'a.helpLink' : function (el) {
        el.onclick = function() {
            showHelp(el.href);       
            return false;
        }
    }
};

Behaviour.register(ruleset);