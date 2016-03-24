Vue.directive('selectize', {
    twoWay: true,
    bind: function () {
        $(this.el).selectize({
                sortField: 'text',
                placeholder: 'Type to select...'
            })
            .on("change", function (e) {
                this.set($(this.el).val());
            }.bind(this));
    },
    update: function (newValue, oldValue) {
//            $(this.el).trigger("change");
        $(this.el)[0].selectize.clear();
    },
    unbind: function () {
        $(this.el)[0].selectize.destroy(); // remove bindings
    }
});

Vue.directive('selectpicker', {
    twoWay: true,
    bind: function () {
        $(this.el).addClass('bootstrap-select-el');

        $(this.el).selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        }).on("change", function (e) {
            this.set($(this.el).val());
        }.bind(this));

        $(this.el).on('option:loaded', function () {
            $(this.el).selectpicker('refresh')
        }.bind(this));
    },
    update: function (newVal, oldVal) {
        $(this.el).selectpicker('refresh').trigger("change");
    },
    unbind: function () {
        $(this.el).off().selectpicker('destroy');
    }
});

Vue.directive('selectoption', {
    twoWay: true,
    bind: function () {
        Vue.nextTick(function () {
            $('.bootstrap-select-el').trigger("option:loaded");
        });
    }
});


Vue.directive('rule-property-select', {
    twoWay: true,
    bind: function () {
        $(this.el).addClass('bootstrap-select-el');

        $(this.el).selectpicker().on("change", function (e) {
            this.set($(this.el).val());
            $('.rule-trigger-selector').trigger('property:selected');
        }.bind(this));

        $(this.el).on('option:loaded', function () {
            $(this.el).selectpicker('refresh')
        }.bind(this));
    },
    update: function (newVal, oldVal) {
        $(this.el).selectpicker('refresh').trigger("change");
    },
    unbind: function () {
        $(this.el).off().selectpicker('destroy');
    }
});

Vue.directive('rule-trigger-select', {
    twoWay: true,
    bind: function () {
        var self = this;
        $(self.el).addClass('rule-trigger-selector');

        $(self.el).on('property:selected', function () {
            Vue.nextTick(function () {
                $(self.el).selectpicker('refresh').selectpicker('deselectAll');
            });
        });

        $(self.el).selectpicker().on("change", function (e) {
            self.set($(this.el).val());
            self.vm.ruleLimit = ''
        });

    },
    unbind: function () {
        $(this.el).off().selectpicker('destroy');
    }
});

Vue.directive('tabs', {
    bind: function () {
        var self = this;
        var $tabs = $(self.el);

        // Add collapse container
        $tabs.append('<li role="presentation" class="collapse-box dropdown">' +
            '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">' +
            'More <span class="caret"></span>' +
            '</a>' +
            '<ul class="dropdown-menu"></ul>' +
            '</li>');

        var collapsedBox = $tabs.children('li:last-child').children('ul');
        var collapsedItemWidths = [];

        // Pops an item
        function popChild() {
            var children = $tabs.children('li:not(:last-child)');
            var count = children.size();
            // Grab the one before last - because last is the dropdown
            collapsedItemWidths.unshift($(children[count - 1]).width());
            $(children[count - 1]).prependTo(collapsedBox);
            tabsHeight = $tabs.innerHeight();
            if (tabsHeight >= 50) popChild();
            setTimeout(autocollapse, 100);
        }


        // UnpopChild
        function unPopChild(collapsedItems) {
            $(collapsedItems[0]).insertBefore($tabs.children('li:last-child'));
        }

        var ready = true;


        // Function that auto-collapse and uncollapses based on tab nav height
        function autocollapse() {

            var tabsHeight = $tabs.innerHeight();

            // Stacks on stack?
            if (tabsHeight >= 50) {
                popChild();
            } else {
                // Not stacking - put items back in line?

                // How many to pop out? As many as it would take just before it would make the height over 50

                var collapsedItems = $tabs.children('li:last-child').children('ul').children('li');
                if (($tabs.children('li').size() > 0) && collapsedItems.size() > 0) {

                    (function adjustor() {
                        console.log('called adjustor!');
                        var containerWidth = $tabs.width();
                        var cumulativeItemWidths = 0;
                        $tabs.children('li').each(function () {
                            cumulativeItemWidths += $(this).width();
                        });

                        if( (containerWidth - cumulativeItemWidths) >  collapsedItemWidths[0]) {
                            var numItemsBeforeInsert = $tabs.children('li').length;
                            $(collapsedItems[0]).insertBefore($tabs.children('li:last-child'));
                            if($tabs.children('li').length == (numItemsBeforeInsert + 1 )) {
                                // element removed
                                console.log('unpopped one');
                                collapsedItemWidths.shift();
                                console.log(collapsedItemWidths);

                                adjustor();
                            } else {
                                console.log('element not removed');
                                setTimeout(autocollapse, 100);
                            }

                        }
                    })();

                }

                // // If we are a single line, have tabs, and have tabs that are collapsed
                // while (tabsHeight < 50 && ($tabs.children('li').size() > 0) && collapsedCount > 0) {
                //
                //     // Grab the first and un-collapse it
                //     $(collapsedItems[0]).insertBefore($tabs.children('li:last-child'));
                //     // Manually decrease collapse count
                //     collapsedCount--;
                //
                //     // Update height
                //     tabsHeight = $tabs.innerHeight();
                // }

            }

            // All collapsed / uncollapsed

            if (collapsedBox.children('li').size() == 0) {
                $tabs.children('.collapse-box').addClass('empty');
            } else {
                $tabs.children('.collapse-box').removeClass('empty');
            }

        }

        // Run it once on load
        $(window).load(autocollapse);

        // Bind to window resize
        self.eventName = '.autocollapse' + Math.floor((Math.random() * 100000000000) + 1);
        // $(window).on('resize' + self.eventName, autocollapse);

        $(window).resize(autocollapse);

        // var resizeTimer;

        // $(window).on('resize', function (e) {
        //     clearTimeout(resizeTimer);
        //     resizeTimer = setTimeout(autocollapse, 250);
        // });

    },
    unbind: function () {
        // unbind to from window resize
        var self = this;
        $(window).off(self.eventName);
    }
});

