/**
 * Handles behavior on the global search page.
 */
jQuery(function($) {
    var $globalSearchResults = $('#globalSearchResults');
    if ($globalSearchResults.length === 0) {
        return;
    }

    // Constants
    var NEXT = 'next';
    var PREV = 'prev';
    var nextSetIncrement = 10;

    var $searchEntries = $globalSearchResults.children('.search-entries');
    var $searchMenu = $globalSearchResults.children('.search-menu');
    var itemMin = 0;
    var itemMax = nextSetIncrement - 1;
    var oldResultSet;
    var resultSet;

    // Retrieve and parse the results
    var searchResults = JSON.parse($globalSearchResults.attr('data-global-search-results'));

    // listen for click events on the menu-item class. When clicked, display the content for the associated item.
    $searchMenu.delegate('.menu-item', 'click', function() {
        resultSet = $(this).attr('data-result-type');

        // Only update the content if they click a result set that is different than what is currently displayed.
        if (resultSet !== oldResultSet) {
            oldResultSet = resultSet;
            itemMin = 0;
            itemMax = nextSetIncrement - 1;
            displayContentFor(resultSet, searchResults);
        }
    });

    $searchEntries.delegate('#searchPagination .change-direction', 'click', function () {
        updateSet($(this).attr('data-direction'));
        displayContentFor(resultSet, searchResults);
    });

    // build the menu.
    buildMenu(searchResults);

    // Select the first item initially
    $($searchMenu.children('.menu-item')[0]).trigger('click');

    /**
     * Returns the current page based on the current value of itemMin and itemMax
     * @returns {number}
     */
    function getCurrentPage() {
        return Math.ceil(itemMin / nextSetIncrement) + 1;
    }

    /**
     * Returns the total number of pages for a given entries array
     * @param {Array} entries
     * @returns {Number}
     */
    function getTotalPages(entries) {
        return Math.ceil(entries.length / nextSetIncrement);
    }

    /**
     * Updates the min and max values
     * @param direction
     */
    function updateSet(direction) {
        if (direction === NEXT) {
            itemMin += nextSetIncrement;
            itemMax += nextSetIncrement;
        } else if (direction === PREV) {
            itemMin -= nextSetIncrement;
            itemMax -= nextSetIncrement;
        }
    }

    /**
     * Builds the menu given an object that contains keys for each type
     * @param {Object} searchResults
     */
    function buildMenu(searchResults) {
        var menuItems = Object.keys(searchResults);
        menuItems.forEach(function addMenuItem(menuItem) {
            if (searchResults[menuItem] && searchResults[menuItem].length > 0) {
                var clickableMenuItem = '<li class="menu-item" data-result-type="' + menuItem + '"><a>' + menuItem + '</a></li>';
                $searchMenu.append(clickableMenuItem);
            }
        });
    }

    /**
     * Display's the content for a specified type given the type and the content
     * @param type
     * @param content
     */
    function displayContentFor(type, searchResults) {
        if (searchResults[type] && searchResults[type].length > 0) {

            var itemsToDisplay = searchResults[type].filter(function filterItemsToDisplay(item, index) {
                return index >= itemMin && index <= itemMax;
            });

            if (itemsToDisplay.length > 0) {
                clearResultsContent();
            }

            itemsToDisplay.forEach(function displayItem(item) {
                $searchEntries.append(item);
            });

            // Should the pagination controls be displayed?
            addPagination(searchResults[type]);

            // Add the page number if necessary
            addPageNumber(searchResults[type]);
        }
    }

    /**
     * Adds the current page number to the view.
     * @param entries
     */
    function addPageNumber(entries) {
        if (Array.isArray(entries) && entries.length > nextSetIncrement) {
            var pageNumber = getCurrentPage();
            var totalPages = getTotalPages(entries);
            $searchEntries.prepend('<p class="page-number">Page: ' + pageNumber + ' of ' + totalPages + '</p>');
        }
    }

    /**
     * Clears the content from the search results container.
     */
    function clearResultsContent() {
        // clear the contents initially
        $searchEntries.empty();
    }

    /**
     * Adds pagination to the search results.
     * @param allContent
     */
    function addPagination(allContent) {
        var paginationDivs = '<div id="searchPagination">';
        var usePrev = false;
        var useNext = false;

        if (itemMin > 0) {
            paginationDivs += '<a class="change-direction" data-direction="prev"><- prev</a>';
            usePrev = true;
        }

        if (itemMax < allContent.length - 1) {
            paginationDivs += '<a class="change-direction" data-direction="next">next -></a></div>';
            useNext = true;
        }

        paginationDivs += '</div>';

        // Only add it if we have a next, previous, or both.
        if (usePrev || useNext) {
            $searchEntries.append(paginationDivs);
            $searchEntries.prepend(paginationDivs);
        }
    }
});

// Polyfill. Production steps of ECMA-262, Edition 5, 15.4.4.18
// Reference: http://es5.github.com/#x15.4.4.18
if (!Array.prototype.forEach) {

    Array.prototype.forEach = function (callback, thisArg) {

        var T, k;

        if (this == null) {
            throw new TypeError(" this is null or not defined");
        }

        // 1. Let O be the result of calling ToObject passing the |this| value as the argument.
        var O = Object(this);

        // 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".
        // 3. Let len be ToUint32(lenValue).
        var len = O.length >>> 0;

        // 4. If IsCallable(callback) is false, throw a TypeError exception.
        // See: http://es5.github.com/#x9.11
        if (typeof callback !== "function") {
            throw new TypeError(callback + " is not a function");
        }

        // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
        if (arguments.length > 1) {
            T = thisArg;
        }

        // 6. Let k be 0
        k = 0;

        // 7. Repeat, while k < len
        while (k < len) {

            var kValue;

            // a. Let Pk be ToString(k).
            //   This is implicit for LHS operands of the in operator
            // b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.
            //   This step can be combined with c
            // c. If kPresent is true, then
            if (k in O) {

                // i. Let kValue be the result of calling the Get internal method of O with argument Pk.
                kValue = O[k];

                // ii. Call the Call internal method of callback with T as the this value and
                // argument list containing kValue, k, and O.
                callback.call(T, kValue, k, O);
            }
            // d. Increase k by 1.
            k++;
        }
        // 8. return undefined
    };
}

// Filter polyfill
if (!Array.prototype.filter)
{
    Array.prototype.filter = function(fun /*, thisArg */)
    {
        "use strict";

        if (this === void 0 || this === null)
            throw new TypeError();

        var t = Object(this);
        var len = t.length >>> 0;
        if (typeof fun !== "function")
            throw new TypeError();

        var res = [];
        var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
        for (var i = 0; i < len; i++)
        {
            if (i in t)
            {
                var val = t[i];

                // NOTE: Technically this should Object.defineProperty at
                //       the next index, as push can be affected by
                //       properties on Object.prototype and Array.prototype.
                //       But that method's new, and collisions should be
                //       rare, so use the more-compatible alternative.
                if (fun.call(thisArg, val, i, t))
                    res.push(val);
            }
        }

        return res;
    };
}

// From https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Object/keys
if (!Object.keys) {
    Object.keys = (function () {
        'use strict';
        var hasOwnProperty = Object.prototype.hasOwnProperty,
            hasDontEnumBug = !({toString: null}).propertyIsEnumerable('toString'),
            dontEnums = [
                'toString',
                'toLocaleString',
                'valueOf',
                'hasOwnProperty',
                'isPrototypeOf',
                'propertyIsEnumerable',
                'constructor'
            ],
            dontEnumsLength = dontEnums.length;

        return function (obj) {
            if (typeof obj !== 'object' && (typeof obj !== 'function' || obj === null)) {
                throw new TypeError('Object.keys called on non-object');
            }

            var result = [], prop, i;

            for (prop in obj) {
                if (hasOwnProperty.call(obj, prop)) {
                    result.push(prop);
                }
            }

            if (hasDontEnumBug) {
                for (i = 0; i < dontEnumsLength; i++) {
                    if (hasOwnProperty.call(obj, dontEnums[i])) {
                        result.push(dontEnums[i]);
                    }
                }
            }
            return result;
        };
    }());
}