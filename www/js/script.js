yiiapi = function () {
	var elements = {};

	var values = {
		searchHeight: null,
		scrollSpeed: 500,
		selected: 'selected',
		category: 'category',
		open: 'open',
		catSelected: 'cat-selected',
		sub: 'sub',
		hasFocus: true,
		loader: '<div id="loader"></div>',
		title: ''
	};

	var keys = {
		enter: 13,
		escape: 27,
		up: 38,
		down: 40,
		array: [13, 27, 38, 40]
	};

	function initialize() {
		elements = {
			search: $('#search-field'),
			searchWrapper: $('#search'),
			content: $('#content'),
			list: $('#static-list'),
			window: $(window),
			results: null,
			category: null
		};

		elements.results = jQuery('<ul>', { id: 'results' }).insertBefore(elements.list);
		elements.category = $('.category', elements.list);
		values.searchHeight = elements.searchWrapper.innerHeight();
		values.title = siteName;

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

		$(elements.list).on("click", '.' + values.category + ' > span', function () {
			clearSelected();
			searchFocus();

			if ($(this).parent().hasClass(values.open)) {
				$(this).parent().removeClass(values.open);
			} else {
				$(this).parent().addClass(values.open);
			}

			$(this).parent().children('ul').toggle();
		});

		$(document).on('click', '#inner_content a.toggle', function () {
			if ($(this).parents(".summary").find(".inherited").is(':visible')) {
				$(this).text($(this).text().replace(/Hide/, "Show"));
				$(this).parents(".summary").find(".inherited").fadeOut();
			} else {
				$(this).text($(this).text().replace(/Show/, "Hide"));
				$(this).parents(".summary").find(".inherited").fadeIn();
			}
		});

		$("#inner_content a.sourceLink").attr("target", "_blank");

		$(document).on('click', '#inner_content div.sourceCode a.show', function () {
			if ($(this).parents(".sourceCode").find("div.code").is(":visible")) {
				$(this).text($(this).text().replace(/hide/, "show"));
				$(this).parents(".sourceCode").find("div.code").slideUp();
			} else {
				$(this).text($(this).text().replace(/show/, "hide"));
				$(this).parents(".sourceCode").find("div.code").slideDown();
			}

			return false;
		});


		// Checking pathname and hash parts
		makeSelected();
		hashScroll();

		// On changing history page
		// History.Adapter.bind(window,'statechange',function(){
		History.Adapter.bind(window, 'popstate', process);

		$(document).on('click', '.sub a, #inner_content a[href^="/"]', function () {
			var el = $(this);

			if (location.pathname != el.attr('href')) {
				History.pushState(null, "", el.attr('href'));
			}

			return false;
		});

		$(document).on('click', '#inner_content a[href^="#"][class!="show"]', function () {
			History.pushState(null, "", location.pathname + $(this).attr('href'));
			//hashScroll();
			return false;
		});

		zebraItems(elements.list); //zebra the items in the static list
	} //-initialize

	function process() {
		var hash = location.hash;
		var pathname = location.pathname;
		var hasMarkup = /(<([^>]+)>)/ig.test(pathname);

		//defeat html xss insertion like #p=<img src%3D/%20onerror%3Dalert(1)>
		//see https://twitter.com/#!/bulkneets/status/156620076160786432
		if (hasMarkup) {
			return;
		}

		var loadUrl = "/index";
		var link = null;

		if (pathname != "/") {
			link = $('.sub a[href="' + pathname + '"]:first');
			if (link) {
				loadUrl = link.attr('href');
			}
		}

		elements.content.html(values.loader).load(loadUrl, function () {
			if (link) {
				document.title = link.children('span:first').text() + " - " + values.title;
			} else {
				document.title = values.title;
			}
			hashScroll();
			makeSelected();
		});
		return false;
	}

	function searchFocus() {
		elements.search.focus();
	} //-searchFocus

	function zebraItems(list) {
		$('.sub:odd', list).addClass('odd');
	} //-zebraItems

	function clearSelected() {
		$('.' + values.selected).removeClass(values.selected);
	} //-clearSelected

	function makeSelected() {
		clearSelected();

		if (location.pathname != "/") {
			var selectedLink = $('.sub a[href="' + location.pathname + '"]:first');

			// Expand category spoiler
			var categoryLink = selectedLink.parents('li.category').find('span:first');
			if (!selectedLink.parents('li.category').hasClass(values.open)) {
				categoryLink.click();
			}

			selectedLink.parent().addClass(values.selected);
			$('#static-list').scrollTo("#" + selectedLink.parents('li.sub').attr('id'), values.scrollSpeed);
		}
	}

	function hashScroll() {
		if (location.hash) {
			if ($("#content").find(location.hash).length) {
				$('#content').scrollTo(location.hash, values.scrollSpeed);
			} else {
				$('#content').scrollTo("*[name=\"" + location.hash.substr(1, location.hash.length - 1) + "\"]", values.scrollSpeed);
			}
		}
	}

	function handleKey(key) {
		if (values.hasFocus) {
			var selVis = $('.' + values.selected + ':visible');

			if (selVis.length) {
				if (key == keys.up && selVis.prev().length)    selVis.removeClass(values.selected).prev().addClass(values.selected);
				if (key == keys.down && selVis.next().length)  selVis.removeClass(values.selected).next().addClass(values.selected);
				if (key == keys.enter)                         History.pushState({}, "", selVis.children('a').attr('href'));
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
							'class': 'sub',
							html: el.parent().parent().html()
						}).appendTo(elements.results);

						if (pos < lastPos) {
							lastPos = pos;
							winner = lastLi;
						}
					}
				});

				//console.log(elements.results.prepend(winner));
				elements.results.prepend(winner).highlight(term, true, 'highlight').children('li:first').addClass(values.selected);
				zebraItems(elements.results);
			} else {
				elements.results.hide();
				elements.list.show();
			}
		});
	} //-startSearch

	return {
		initialize: initialize
	}
}();

$(document).ready(function () {
	$('#navigation').load('/doc/navigation', function () {
		yiiapi.initialize();
	});
});