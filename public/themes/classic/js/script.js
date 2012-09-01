yiiapi = function () {
    var elements = {};

    var values = {
        searchHeight:null,
        selected:'selected',
        category:'category',
        open:'open',
        catSelected:'cat-selected',
        sub:'sub',
        hasFocus:true,
        loader:'<div id="loader"></div>',
        title:'Alternative Yii API Documentation - '
    };

    var keys = {
        enter:13,
        escape:27,
        up:38,
        down:40,
        array:[13, 27, 38, 40]
    };


    function initialize() {
        elements = {
            search:$('#search-field'),
            searchWrapper:$('#search'),
            content:$('#content'),
            list:$('#static-list'),
            window:$(window),
            results:null,
            category:null
        };

        elements.results = jQuery('<ul>', { id:'results' }).insertBefore(elements.list);
        elements.category = $('.category', elements.list);
        values.searchHeight = elements.searchWrapper.innerHeight();

        elements.window.resize(function () {
            var winH = elements.window.height() - $("#header").height();
            var listH = elements.window.height() - values.searchHeight;

            elements.list.height(listH);
            elements.results.height(listH);
            elements.content.height(winH);
            elements.search.width(elements.searchWrapper.width() - 8);
        })
            .mousemove(function (event) {
                if (event.pageX < elements.list.width()) searchFocus();
            })
            .keydown(function (event) {
                if (event.keyCode == keys.escape) {
                    elements.search.val('').focus();
                    elements.results.hide();
                    elements.list.show();
                }
            })
            .trigger('resize'); //trigger resize event to initially set sizes

        elements.search.keyup(function (event) {
            if ($.inArray(event.keyCode, keys.array) != -1) { //it is an event key
                handleKey(event.keyCode);
            } else { //it is a character
                startSearch();
            }
        })
            .focus(function () {
                values.hasFocus = true;
            })
            .blur(function () {
                values.hasFocus = false;
            })
            .focus();

        $('.' + values.category + ' > span', elements.list).toggle(function () {
            clearSelected();
            searchFocus();
            $(this).parent().addClass(values.open).children('ul').show();
        }, function () {
            clearSelected();
            searchFocus();
            $(this).parent().removeClass(values.open).children('ul').hide();
        });

        $('.sub a').live('click', function () {
            var el = $(this);

            clearSelected();
            searchFocus();
            el.parent().addClass(values.selected);
            $.bbq.pushState({ p:urlMethodName(el) });

            return false;
        });

        $(window).bind('hashchange',function (event) {
            var state = event.getState(),
                hasMarkup = /(<([^>]+)>)/ig.test(state.p);

            //defeat html xss insertion like #p=<img src%3D/%20onerror%3Dalert(1)>
            //see https://twitter.com/#!/bulkneets/status/156620076160786432

            if (state.p && !hasMarkup) {
                //console.log($('.sub a[href*="' + state.p + '"]:first'), state.p);
                loadPage($('.sub a[href*="' + state.p + '"]:first'));
            }
        }).trigger('hashchange');

        zebraItems(elements.list); //zebra the items in the static list
    } //-initialize


    function searchFocus() {
        elements.search.focus();
    } //-searchFocus


    function zebraItems(list) {
        $('.sub:odd', list).addClass('odd');
    } //-zebraItems


    function clearSelected() {
        $('.' + values.selected).removeClass(values.selected);
    } //-clearSelected


    function handleKey(key) {
        if (values.hasFocus) {
            var selVis = $('.' + values.selected + ':visible');

            if (selVis.length) {
                if (key == keys.up && selVis.prev().length)    selVis.removeClass(values.selected).prev().addClass(values.selected);
                if (key == keys.down && selVis.next().length)  selVis.removeClass(values.selected).next().addClass(values.selected);
                if (key == keys.enter)                         $.bbq.pushState({ p:urlMethodName(selVis.children('a')) });
            } else { //no visible selected item
                var catSel = $('.' + values.catSelected, elements.list);

                if (catSel.length) { //a category is selected
                    if (key == keys.up)    catSel.removeClass(values.catSelected).prev().addClass(values.catSelected);
                    if (key == keys.down)  catSel.removeClass(values.catSelected).next().addClass(values.catSelected);
                    if (key == keys.enter) catSel.removeClass(values.catSelected).children('span').trigger('click');
                } else { //no category selected
                    var subVis = $('.' + values.sub + ':visible', elements.list);

                    if (subVis.length) { //there are visible subs in the static list
                        if (key == keys.up)    subVis.filter(':last').addClass(values.selected);
                        if (key == keys.down)  subVis.filter(':first').addClass(values.selected);
                    } else { //only categories are shown
                        if (key == keys.up)    elements.category.last().addClass(values.catSelected);
                        if (key == keys.down)  elements.category.first().addClass(values.catSelected);
                    }
                }
            }
        }
    } //-handleKey


    function urlMethodName(link) {
        var href = link.attr('href');
        return href;
        //return href.substr(5, href.length - 16);
    } //-urlMethodName


    function loadPage(link) {
        elements.content.html(values.loader).load(link.attr('href'), function () {
            document.title = values.title + link.children('span:first').text();
            //pageTracker._trackPageview(urlMethodName(link));
            formatArticle();
        });
    } //-loadPage


    function formatArticle() {
        var pDesc = $('p.desc', elements.content);

        $('.arguement:odd', elements.content).addClass('arguement-odd');
        if (pDesc.text().length <= 13) pDesc.remove();
        $('img', elements.content).attr('src', function () {
            return $(this).attr('src').substr(1);
        });

        $('.signatures', elements.content).each(function () {
            var winner = 0;
            var arg = $(this).find('.arguement');

            arg.children('strong').each(function () {
                var width = $(this).width();
                if (width > winner) winner = width;
            });

            arg.css('padding-left', winner + 50);
        });
    } //-formatArticle



    function startSearch() {
        elements.search.doTimeout('text-type', 300, function () {
            var term = elements.search.val();

            if (term.length) {
                elements.results.html('').show();
                elements.list.hide();

                var lastPos = 100;
                var winner = $;

                $('.searchable', elements.list).each(function () {
                    var el = $(this);
                    var name = el.text();
                    var pos = name.toLowerCase().indexOf(term.toLowerCase());

                    if (pos != -1 && elements.results.text().indexOf(name) == -1) {
                        var lastLi = jQuery('<li>', {
                            'class':'sub',
                            html:el.parent().parent().html()
                        }).appendTo(elements.results);

                        if (pos < lastPos) {
                            lastPos = pos;
                            winner = lastLi;
                        }
                    }
                });

                console.log(elements.results.prepend(winner));
                elements.results.prepend(winner).highlight(term, true, 'highlight').children('li:first').addClass(values.selected);
                zebraItems(elements.results);
            } else {
                elements.results.hide();
                elements.list.show();
            }
        });
    } //-startSearch


    return {
        initialize:initialize
    }
}();

$(document).ready(function () {
    var navigation_el = $('#navigation').load('/doc/navigation', function () {yiiapi.initialize();});
});