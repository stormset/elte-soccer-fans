class encapuslator {
    constructor(jQuery) {
        (function ($) {
    "use strict";

    /**
     * @type {number}
     */
    var actualPage = 1;
    /**
     * @type {number}
     */
    var pageCount = 0;
    /**
     * @type {number}
     */
    var pageRowCount = 0;
    /**
     * @type {string}
     */
    var pages = '';
    /**
     * @type {object}
     */
    var obj = null;
    /**
     * @type {boolean}
     */
    var activeSearch = false;
    /**
     * @type {string}
     */
    var arrowUp = '';
    /**
     * @type {string}
     */
    var arrowDown = '';
    /**
     * @type {string}
     */
    var searchFormClass = '';
    /**
     * @type {string}
     */
    var pageFieldText = '';
    /**
     * @type {string}
     */
    var searchFieldText = '';

    $.fn.bdt = function (options, callback) {

        var settings = $.extend({
            pageRowCount: 10,
            arrowDown: 'fa-angle-down',
            arrowUp: 'fa-angle-up',
            searchFormClass: '',
            pageFieldText: 'Elemek egy lapon:',
            searchFieldText: 'Keresés',
            showSearchForm: 1,
            nextText: 'Következő',
            previousText: 'Előző',
        }, options);

        /**
         * @type {object}
         */
        var tableBody = null;

        return this.each(function () {
            obj = $(this).addClass('bdt');
            tableBody = obj.find("tbody");
            pageRowCount = settings.pageRowCount;
            arrowDown = settings.arrowDown;
            arrowUp = settings.arrowUp;
            searchFormClass = settings.searchFormClass;
            pageFieldText = settings.pageFieldText;
            searchFieldText = settings.searchFieldText;
            
            var searchForm;

            /**
             * search input field
             */
            if(settings.showSearchForm === 1) {
                searchForm = $('<form/>')
                    .addClass(searchFormClass)
                    .attr('role', 'form')
                    .append(
                        $('<div/>')
                            .addClass('form-group')
                            .append(
                                $('<input/>')
                                    .addClass('form-control search')
                                    .attr('placeholder', searchFieldText)
                            )
                    );
            }
            
            obj.before(
                $('<div/>')
                    .addClass('table-header')
                    .append(
                        searchForm
                    )
            );

            /**
             * select field for changing row per page
             */
            obj.after(
                $('<div/>')
                    .addClass('table-footer')
                    .append(
                        $('<div/>')
                    )
                    
            );

            if (tableBody.children('tr').length > pageRowCount) {
                setPageCount(tableBody);
                addPages();
                paginate(tableBody, actualPage);
            }

            searchTable(tableBody);

            sortColumn(obj);

            obj.parent().find('.table-footer').on('click', '.pagination li', function (event) {
                var listItem;

                if ($(event.target).is("a")) {
                    listItem = $(event.target).parent();
                } else {
                    listItem = $(event.target).parent().parent();
                }

                var page = listItem.data('page');

                if (!listItem.hasClass("disabled") && !listItem.hasClass("active")) {
                    paginate(tableBody, page, $(event.target).parent().parent().parent());
                }

                event.preventDefault();
            });

        });

        /**
         * the main part of this function is out of this thread http://stackoverflow.com/questions/3160277/jquery-table-sort
         * @author James Padolsey http://james.padolsey.com
         * @link http://jsfiddle.net/spetnik/gFzCk/1953/
         * @param obj
         */
        function sortColumn(obj, byIndex = undefined) {
            var table = obj;
            var oldIndex = 0;

            obj
                .find('thead th')
                .wrapInner('<span class="sort-element"/>')
                .append(
                    $('<span/>')
                        .addClass('sort-icon fa')
                )
                .each(function () {

                    var th = $(this);
                    var thIndex = th.index();
                    var inverse = false;
                    var addOrRemove = true;

                    const doSorting = function (inv = false) {
                        if(!$(this).hasClass('disable-sorting')) {
                            if($(this).find('.sort-icon').hasClass(arrowDown)) {
                                $(this)
                                    .find('.sort-icon')
                                    .removeClass( arrowDown )
                                    .addClass(arrowUp);

                            } else {
                                $(this)
                                    .find('.sort-icon')
                                    .removeClass( inverse ? arrowUp : arrowDown )
                                    .addClass(inverse ? arrowDown : arrowUp);
                            }

                            if(oldIndex != thIndex) {
                                obj.find('.sort-icon').removeClass(arrowDown);
                                obj.find('.sort-icon').removeClass(arrowUp);

                                $(this)
                                    .find('.sort-icon')
                                    .toggleClass( inverse ? arrowDown : arrowUp, addOrRemove );
                            }

                            table.find('td').filter(function () {

                                return $(this).index() === thIndex;

                            }).sortElements(function (a, b) {
                                const childOf_a = $(a).children(), childOf_b = $(b).children();

                                if (childOf_a.length >= 1
                                    && $(childOf_a.first()).attr('type') !== 'undefined'
                                    && $(childOf_a.first()).attr('type') !== false
                                    && $(childOf_a.first()).attr('type') === "checkbox") {
                                    return $(childOf_a.first()).is(":checked") < $(childOf_b.first()).is(":checked") ?
                                        inverse ? -1 : 1
                                        : inverse ? 1 : -1;
                                } else {
                                    return $.text([a]) > $.text([b]) ?
                                        inverse ? -1 : 1
                                        : inverse ? 1 : -1;
                                }

                            }, function () {
                                // parentNode is the element we want to move
                                return this.parentNode;

                            });

                            inverse = !inverse;
                            oldIndex = thIndex;
                            paginate(tableBody, actualPage);
                        }
                    };

                    th.click(doSorting);

                    if (th.hasClass("sortby")) {
                        doSorting.call(obj.find('thead th')[thIndex]);
                    } else if (th.hasClass("sortbyrev")) {
                        inverse = true;
                        doSorting.call(obj.find('thead th')[thIndex], true);
                    }

                });

        }

        /**
         * create li elements for pages
         */
        function addPages() {
            const footer = obj.parent().find('.table-footer');
            footer.find('.table-nav').remove();
            pages = $('<ul/>');

            $.each(new Array(pageCount), function (index) {
                var additonalClass = '';
                var page = $();

                if ((index + 1) == 1) {
                    additonalClass = 'active';
                }

                pages
                    .append($('<li/>')
                        .addClass(additonalClass)
                        .data('page', (index + 1))
                        .append(
                            $('<a/>')
                                .text(index + 1).addClass('page-link')
                        )
                    );
            });

            /**
             * pagination, with pages and previous and next link
             */
            footer
                .append(
                    $('<nav/>')
                        .addClass('table-nav')
                        .append(
                            pages
                                .addClass('pagination justify-content-center pagination-sm')
                                .prepend(
                                    $('<li/>')
                                        .addClass('disabled')
                                        .data('page', 'previous')
                                        .append(
                                            $('<a href="#" />')
                                                .append(
                                                    $('<span/>')
                                                        .attr('aria-hidden', 'true')
                                                        .html('&laquo;')
                                                ).addClass('page-link')
                                                .append(
                                                    $('<span/>')
                                                        .addClass('sr-only')
                                                        .text(settings.previousText)
                                                )
                                        )
                                )
                                .append(
                                    $('<li/>')
                                        .addClass('disabled')
                                        .data('page', 'next')
                                        .append(
                                            $('<a href="#" />')
                                                .append(
                                                    $('<span/>')
                                                        .attr('aria-hidden', 'true')
                                                        .html('&raquo;')
                                                ).addClass('page-link')
                                                .append(
                                                    $('<span/>')
                                                        .addClass('sr-only')
                                                        .text(settings.nextText)
                                                )
                                        )
                                )
                        )
                );

        }

        /**
         *
         * @param tableBody
         */
        function searchTable(tableBody) {
            obj.parent().find(".search").on("keyup", function (e) {
                $.each(tableBody.find("tr"), function () {

                    var text = $(this)
                        .text()
                        .replace(/ /g, '')
                        .replace(/(\r\n|\n|\r)/gm, "");

                    var searchTerm = $(e.target).val();

                    if (text.toLowerCase().indexOf(searchTerm.toLowerCase()) == -1) {
                        $(this)
                            .hide()
                            .removeClass('search-item');
                    } else {
                        $(this)
                            .show()
                            .addClass('search-item');
                    }

                    if (searchTerm != '') {
                        activeSearch = true;
                    } else {
                        activeSearch = false;
                    }
                });

                setPageCount(tableBody);
                addPages();
                paginate(tableBody, 1);
            });
        }

        /**
         *
         * @param tableBody
         */
        function setPageCount(tableBody) {
            if (activeSearch) {
                pageCount = Math.ceil(tableBody.children('.search-item').length / pageRowCount);
            } else {
                pageCount = Math.ceil(tableBody.children('tr').length / pageRowCount);
            }

            if (pageCount == 0) {
                pageCount = 1;
            }
        }

        /**
         *
         * @param tableBody
         * @param page
         */
        function paginate(tableBody, page, item) {
            const footerNav = obj.parent().find('.table-footer .table-nav');

            if (page == 'next') {
                page = actualPage + 1;
            } else if (page == 'previous') {
                page = actualPage - 1;
            }

            actualPage = page;

            var rows = (activeSearch ? tableBody.find(".search-item") : tableBody.find("tr"));
            var endRow = (pageRowCount * page);
            var startRow = (endRow - pageRowCount);
            var pagination = item === undefined ? footerNav.find('.pagination') :item;

            rows
                .hide();

            rows
                .slice(startRow, endRow)
                .show();

            pagination
                .find('li')
                .addClass('page-item')
                .removeClass('active disabled noclick');

            pagination
                .find('li:eq(' + page + ')')
                .addClass('page-item active');

            if (page == 1) {
                pagination
                    .find('li:first')
                    .addClass('disabled noclick');

            } else if (page == pageCount) {
                pagination
                    .find('li:last')
                    .addClass('disabled noclick');
            }
        }
    }
}(jQuery));
    }
  
  }

/**
 * jQuery.fn.sortElements
 * --------------
 * @author James Padolsey (http://james.padolsey.com)
 * @version 0.11
 * @updated 18-MAR-2010
 * --------------
 * @param Function comparator:
 *   Exactly the same behaviour as [1,2,3].sort(comparator)
 *
 * @param Function getSortable
 *   A function that should return the element that is
 *   to be sorted. The comparator will run on the
 *   current collection, but you may want the actual
 *   resulting sort to occur on a parent or another
 *   associated element.
 *
 *   E.g. $('td').sortElements(comparator, function(){
 *      return this.parentNode;
 *   })
 *
 *   The <td>'s parent (<tr>) will be sorted instead
 *   of the <td> itself.
 */
jQuery.fn.sortElements = (function(){

    var sort = [].sort;

    return function(comparator, getSortable) {

        getSortable = getSortable || function(){return this;};

        var placements = this.map(function(){

            var sortElement = getSortable.call(this),
                parentNode = sortElement.parentNode,

                // Since the element itself will change position, we have
                // to have some way of storing it's original position in
                // the DOM. The easiest way is to have a 'flag' node:
                nextSibling = parentNode.insertBefore(
                    document.createTextNode(''),
                    sortElement.nextSibling
                );

            return function() {

                if (parentNode === this) {
                    throw new Error(
                        "You can't sort elements if any one is a descendant of another."
                    );
                }

                // Insert before flag:
                parentNode.insertBefore(this, nextSibling);
                // Remove flag:
                parentNode.removeChild(nextSibling);

            };

        });

        return sort.call(this, comparator).each(function(i){
            placements[i].call(getSortable.call(this));
        });

    };

})();

$( window ).on( "load", function() {
    setTimeout(function () {
        $('.paged-table').each(function(i, obj) {
            const c = new encapuslator(jQuery);
            const rowCount =  (obj.dataset !== undefined && obj.dataset["rows"] !== undefined) ?
                Number(obj.dataset["rows"]) : 10;

            $(obj).bdt({
                showSearchForm: 1,
                pageRowCount: rowCount
            });
        });
    }, 100);
})