Vue.directive('autofit-tabs', {
    bind: function () {
        var self = this;
        var $tabs = $(self.el);

        // Dynamically create a hidden container to hold collapsed tabs
        $tabs.append('<li role="presentation" class="collapse-box dropdown">' +
            '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">' +
            '<i class="fa fa-angle-down"></i>' +
            '</a>' +
            '<ul class="dropdown-menu"></ul>' +
            '</li>');

        // Initialize instance vars
        self._dropDownButtonWidth = 60; // in px
        self._hasHeadings = ($tabs.children('li').size() > 0); // Do we even have headings?
        self._$hiddenContainer = $tabs.children('li:last-child').children('ul'); // the $el that we want to put our collapsed tabs in
        self._collapsedTabs = [];
        self._collapsedTabWidths = []; // holds the widths of hidden tabs if they weren't hidden
        self._combinedTabWidth = 0; // Width of all visisble tabs

        // Checks to see if any collapsed tabs exist - adds class to show dropdown button if one does
        function setDropDownClass() {
            if (self._$hiddenContainer.children('li').size() == 0) {
                $tabs.children('.collapse-box').addClass('empty');
            } else {
                $tabs.children('.collapse-box').removeClass('empty');
            }
        }

        // Click Handler
        function hiddenTabClickedEventHandler() {
            var hiddenIndex = $(this).index();

            hideTabsToFit(); // Hide a single tab
            unhideTabsToFit($(this));

            // Remove all active list items (uncollapsed and collapsed)
            $tabs.children('li').removeClass('active');
            $tabs.children('li').children('ul').children('li').removeClass('active');

            // Re-add class programatically
            $(this).children('a').tab('show');
        }

        // Bind click Event
        function bindClick($el) {
            $el.on('click', hiddenTabClickedEventHandler);
        }

        function unbindClick($el) {
            $el.off('click', hiddenTabClickedEventHandler);
        }

        // Sort func
        function sortHeadings() {
            var sorted = $tabs.children('li').sort(function (a, b) {
                return $(a).children('a')[0].originalIndex > $(b).children('a')[0].originalIndex;
            });
            $tabs.html(sorted);
            // rebind click
            _.forEach($tabs.children('li:last-child').children('ul').children('li'), function (nestedItem) {
                bindClick($(nestedItem));
            });
        }

        // Fits tab headings to available widthds
        function hideTabsToFit() {

            // Hide a tab
            var children = $tabs.children('li:not(:last-child)');
            var count = children.size();
            var pickedChild = $(children[count - 1]);
            if (pickedChild.hasClass('active')) pickedChild = $(children[count - 2]);   // Repick - We don't pick (hide) active kids
            var childIndex = (pickedChild.hasClass('active')) ? count - 2 : count - 1;  // store child index to change widths later
            bindClick(pickedChild);
            pickedChild.prependTo(self._$hiddenContainer);
            self._collapsedTabs.push(pickedChild);

            // Update values
            var pickedTabWidth = self._visibleTabWidths[childIndex];
            self._collapsedTabWidths.unshift(pickedTabWidth);
            self._visibleTabWidths.splice(childIndex, 1);
            self._combinedTabWidth -= pickedTabWidth;

            if (getSpace() < 0) hideTabsToFit();

            setDropDownClass();
        }

        function unhideTabsToFit($tab) {
            // Physically move the tab out
            $tab.insertBefore($tabs.children('li:last-child'));
            _.remove(self._collapsedTabs, $tab);

            unbindClick($tab);

            var unhideTabWidth = self._collapsedTabWidths[0];

            self._combinedTabWidth += unhideTabWidth;
            self._visibleTabWidths.push(unhideTabWidth);
            self._collapsedTabWidths.shift();

            sortHeadings();

            if (getSpace() > self._collapsedTabWidths[0]) unhideTabsToFit($(self._collapsedTabs[0]));

            setDropDownClass();
        }

        function getSpace() {
            self._containerWidth = $tabs.width();
            return self._containerWidth - self._combinedTabWidth - self._dropDownButtonWidth;
        }


        function optimizeTabWidths() {
            // This is the function that just checks if there is space
            if (!self._hasHeadings) return;
            // If thers is not enough space
            if (getSpace() < 0) {
                // Hide all the tabs to fit
                hideTabsToFit();
            } else {
                // If there is enough space for a collapsed item and we actually have collapsed items
                if (getSpace() > self._collapsedTabWidths[0] && self._collapsedTabs.length > 0) {
                    unhideTabsToFit($(self._collapsedTabs[0])); // unhide the first one
                }
            }


        }


        // When doucment is all loaded and good
        $(document).on('ready page:load', function () {
            var tabHeadings = $tabs.children('li:not(.collapse-box)').children('a').toArray();
            // Make an array of tab widths
            self._visibleTabWidths = tabHeadings.map(function (c) {
                self._combinedTabWidth += c.offsetWidth;    // get the combined width of all tabs
                c.originalIndex = $(c).parent().index();    // bind the index for sorting later
                return c.offsetWidth;                       // return tab width
            });
            setDropDownClass();
            optimizeTabWidths();
        });

        $(window).on('resize', _.debounce(optimizeTabWidths, 50, {
            leading: true
        }));

    }
});