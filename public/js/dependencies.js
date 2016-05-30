$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Authorization': 'Bearer ' + localStorage.getItem('token')
    }
});
$(document).ready(function () {
    autosize($('.autosize'));
});


$(document).ready(function () {
    $('.datepicker').datepicker({
        dateFormat: "dd/mm/yy",
        minDate: 0
    });

    $('.filter-datepicker').datepicker({
        dateFormat: "dd/mm/yy"
    });
});
Dropzone.autoDiscover = false;


//
// $(document).ready(function() {
//
//     // Dropzone
//     Dropzone.options.addPhotosForm = {
//         paramName: 'photo',                             // name of the input, in controller: $request->file('photo')
//         maxFileSize: 3,                                 // File size in Mb
//         acceptedFiles: '.jpg, .jpeg, .png, .bmp',       // file formats accepted
//     };
// });
$(document).ready(function() {
    $(".fancybox").fancybox();
});
// $('.bs-file-input').fileinput();
//
// (function () {
//     $('.bs-file-input').fileinput({
//         'showUpload': false,
//         'allowedFileExtensions': ['jpg', 'gif', 'png'],
//         'showRemove': false,
//         'showCaption': false,
//         'previewSettings': {
//             image: {width: "120px", height: "120px"}
//         },
//         'browseLabel': 'Photo',
//         'browseIcon': '<i class="fa fa-plus"></i> &nbsp;',
//         'browseClass': 'btn btn-outline-grey',
//         'layoutTemplates': {
//             preview: '<div class="file-preview {class}">\n' +
//             '    <div class="close fileinput-remove">Clear</div>\n' +
//             '    <div class="{dropClass}">\n' +
//             '    <div class="file-preview-thumbnails">\n' +
//             '    </div>\n' +
//             '    <div class="clearfix"></div>' +
//             '    <div class="file-preview-status text-center text-success"></div>\n' +
//             '    <div class="kv-fileinput-error"></div>\n' +
//             '    </div>\n' +
//             '</div>'
//         }
//     });
// })();
//
// (function () {
//     var uploadUrl = 'http:' + $('#form-item-photo').attr('action');
//     var $input = $('#purchase-request-single .input-item-photos');
//     $input.fileinput({
//         uploadUrl: uploadUrl,
//         uploadAsync: true,
//         allowedFileExtensions: ['jpg', 'gif', 'png'],
//         showRemove: false,
//         showCaption: false,
//         showPreview: false,
//         showCancel: false,
//         showUpload: false,
//         browseIcon: '<i class="fa fa-plus"></i> &nbsp;',
//         browseClass: 'btn btn-outline-grey',
//         browseLabel: 'Photo'
//     }).on("filebatchselected", function (event, files) {
//         $input.fileinput("upload");
//     }).on('filebatchuploadcomplete', function (event, files, extra) {
//         location.reload();
//     });
// })();

$(document).ready(function () {
    // Moment JS
    moment.locale('en'); // 'en';
});
// $(document).ready(function () {
//     $.noty.themes.customTheme = {
//         name    : 'customTheme',
//         helpers : {
//             borderFix: function() {
//                 if(this.options.dismissQueue) {
//                     var selector = this.options.layout.container.selector + ' ' + this.options.layout.parent.selector;
//                     switch(this.options.layout.name) {
//                         case 'top':
//                         case 'topCenter':
//                         case 'topLeft':
//                         case 'topRight':
//                         case 'bottomCenter':
//                             $(selector).css({
//                                 borderRadius: '0',
//                                 width: '100%'
//                             });
//                             $(selector).first().css({
//                                 'border-top-left-radius': '0',
//                                 'border-top-right-radius': '0',
//                                 width: '100%'
//                             });
//                             $(selector).last().css({'border-bottom-left-radius': '0', 'border-bottom-right-radius': '0'});
//                             break;
//                         case 'bottomLeft':
//                         case 'bottomRight':
//                         case 'center':
//                         case 'centerLeft':
//                         case 'centerRight':
//                         case 'inline':
//                         case 'bottom':
//                         default:
//                             break;
//                     }
//                 }
//             }
//         },
//         modal   : {
//             css: {
//                 position       : 'fixed',
//                 width          : '100%',
//                 height         : '100%',
//                 backgroundColor: '#000',
//                 zIndex         : 10000,
//                 opacity        : 0.6,
//                 display        : 'none',
//                 left           : 0,
//                 top            : 0
//             }
//         },
//         style   : function() {
//
//             this.$bar.css({
//                 overflow  : 'hidden'
//             });
//
//             this.$message.css({
//                 fontSize  : '16px',
//                 lineHeight: '16px',
//                 textAlign : 'center',
//                 padding   : '8px 10px 9px',
//                 width     : 'auto',
//                 position  : 'relative',
//                 height: '60px',
//                 display: 'flex',
//                 justifyContent: 'center',
//                 alignItems: 'center'
//             });
//
//             this.$closeButton.css({
//                 position  : 'absolute',
//                 top       : 4, right: 4,
//                 width     : 10, height: 10,
//                 background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",
//                 display   : 'none',
//                 cursor    : 'pointer'
//             });
//
//             this.$buttons.css({
//                 padding        : 5,
//                 textAlign      : 'right',
//                 borderTop      : '1px solid #ccc',
//                 backgroundColor: '#fff'
//             });
//
//             this.$buttons.find('button').css({
//                 marginLeft: 5
//             });
//
//             this.$buttons.find('button:first').css({
//                 marginLeft: 0
//             });
//
//             this.$bar.on({
//                 mouseenter: function() {
//                     $(this).find('.noty_close').stop().fadeTo('normal', 1);
//                 },
//                 mouseleave: function() {
//                     $(this).find('.noty_close').stop().fadeTo('normal', 0);
//                 }
//             });
//
//             switch(this.options.layout.name) {
//                 case 'top':
//                 case 'topCenter':
//                 case 'center':
//                 case 'bottomCenter':
//                     this.$bar.css({
//                         borderRadius: '0',
//                         borderTop      : '2px solid #eee',
//                         boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
//                     });
//                     break;
//                 case 'inline':
//                 case 'topLeft':
//                 case 'topRight':
//                 case 'bottomLeft':
//                 case 'bottomRight':
//                 case 'centerLeft':
//                 case 'centerRight':
//                 case 'bottom':
//                 default:
//                     this.$bar.css({
//                         border   : '2px solid #eee',
//                         boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
//                     });
//                     break;
//             }
//
//             switch(this.options.type) {
//                 case 'alert':
//                 case 'notification':
//                     this.$bar.css({backgroundColor: '#A1A4AA', borderColor: '#989898', color: '#FFF'});
//                     break;
//                 case 'warning':
//                     this.$bar.css({backgroundColor: '#F1C40F', borderColor: '#F39C12', color: '#FFF'});
//                     this.$buttons.css({borderTop: '1px solid #FFC237'});
//                     break;
//                 case 'error':
//                     this.$bar.css({
//                         backgroundColor: '#E74C3C', borderColor: '#C0392B', color: '#FFFFFF'
//                     });
//                     this.$buttons.css({borderTop: '1px solid darkred'});
//                     break;
//                 case 'information':
//                     this.$bar.css({backgroundColor: '#3498DB', borderColor: '#2980B9', color: '#FFFFFF'});
//                     this.$buttons.css({borderTop: '1px solid #0B90C4'});
//                     break;
//                 case 'success':
//                     this.$bar.css({backgroundColor: '#2ECC71', borderColor: '#27AE60', color: '#FFF'});
//                     this.$buttons.css({borderTop: '1px solid #50C24E'});
//                     break;
//                 default:
//                     this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'});
//                     break;
//             }
//         },
//         callback: {
//             onShow : function() {
//                 $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
//             },
//             onClose: function() {
//                 $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
//             }
//         }
//     };
// });
//

/**
 * Selectize Instantiator
 *
 * Calls the selectize plugin with an added filter that won't let
 * you add new values that are duplicates. Ignores the case of
 * the value and sorts the dropdown selects using the text.
 */

function uniqueSelectize(el, placeholder) {
    var unique = $(el).selectize({
        create: true,
        sortField: 'text',
        placeholder: placeholder,
        createFilter: function(input) {
            input = input.toLowerCase();
            var array = $.map(unique.options, function(value) {
                return [value];
            });
            var unmatched = true;
            _.forEach(array, function (option) {
                if((option.text).toLowerCase() === input) {
                    unmatched = false;
                }
            });
            return unmatched;
        }
    })[0].selectize;

    return unique;
}
toastr.options = {
    "closeButton": true,
    "closeHtml": '<button type="button" class="btn-close"><i class="fa fa-close"></i></button>',
    "debug": false,
    "newestOnTop": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
Vue.transition('fade', {
    enterClass: 'fadeIn',
    leaveClass: 'fadeOut'
});

Vue.transition('slide', {
    enterClass: 'slideInLeft',
    leaveClass: 'slideOutLeft'
});

Vue.transition('slide-right', {
    enterClass: 'slideInRight',
    leaveClass: 'slideOutRight'
});

Vue.transition('fade-slide', {
    enterClass: 'fadeInDown',
    leaveClass: 'fadeOutUp'
});

Vue.transition('slide-down', {
    enterClass: 'slideInDown',
    leaveClass: 'slideOutUp'
});
Vue.directive('autofit-tabs', {
    bind: function () {
        var self = this;
        var $tabs = $(self.el);

        // Dynamically create a hidden container to hold collapsed tabs
        $tabs.append('<li role="presentation" class="collapse-box dropdown">' +
            '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">' +
            '<i class="fa fa-angle-down"></i>' +
            '</a>' +
            '<ul class="dropdown-menu animated fadeInDownSmall"></ul>' +
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

        $(window).on('resize.setAutoTabSizes', _.debounce(optimizeTabWidths, 50, {
            leading: true
        }));

    }
});
Vue.directive('datepicker', {
    params: ['button-only'],
    bind: function() {
        if(this.params.buttonOnly) {
            $(this.el).datepicker({
                dateFormat: "dd/mm/yy",
                minDate: 0,
                buttonImage: '/images/icons/calendar.png',
                buttonImageOnly: true,
                showOn: 'both'
            });
        } else {
            $(this.el).datepicker({
                dateFormat: "dd/mm/yy",
                minDate: 0
            });
        }
    }
});
Vue.directive('dropdown-toggle', {
    twoWay: true,
    bind: function () {

        var self = this;

        self.className = $(self.el).attr('class').replace(' ', '.');

        var selector = '.' + self.className + ' button';

        $(document).on('click.' + self.className, selector, function (e) {
            e.stopPropagation();
            $(document).trigger('hideAllDropdowns');

            self.set(true);
            $(document).on('click.checkHideDropdown', function (event) {
                if (!$(event.target).closest('.dropdown-container').length && !$(event.target).is('.dropdown-container')) {
                    self.set(false);
                    $(document).off('click.checkHideDropdown');
                }
            })
        });

        $(document).on('hideAllDropdowns', function () {
            self.set(false);
        });
    },
    unbind: function () {
        $(document).off('click.' + this.className);
        $(document).off('hideAllDropdowns');
    }
});

Vue.directive('fancybox', function() {
    // Init fancy box on elements that may be loaded dynamically using Vue
    $(this.el).fancybox();
});
/**
 * Bootstrap popover
 */
Vue.directive('popover', function () {
    var self = this;
    $(self.el).popover({
        html: true,
        content: function() {
            return $(this).siblings('.popover-content').html();
        }
    });
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
Vue.directive('table-bulk-actions', function () {

    var $this = $(this.el),
        $parent = $($(this.el).parents('tr'));


    function setWidthToParent() {

        var parentWidth = $parent.width();

        // Do we have a width? (els might not have loaded)
        if (parentWidth > 0) {
            $this.width(parentWidth);
        } else {
            setTimeout(function () {
                setWidthToParent();
            }, 200);
        }
    }

    $(window).on("resize.resizeBulkActionsDiv", _.debounce(setWidthToParent, 50, {
        leading: true
    }));

    setWidthToParent();
});
Vue.directive('tooltip', function() {
    $(this.el).tooltip();
});
Vue.filter('capitalize', function (str) {
    if(str && str.length > 0) return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
});
Vue.filter('chunk', function (array, length) {
    if(! array) return;
    var totalChunks = [];
    var chunkLength = parseInt(length, 10);

    if (chunkLength <= 0) {
        return array;
    }

    for (var i = 0; i < array.length; i += chunkLength) {
        totalChunks.push(array.slice(i, i + chunkLength));
    }


    return totalChunks;
});
Vue.filter('diffHuman', function (value) {
    if(! value || value == '') return;
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").fromNow();
    }
    return value;
});
Vue.filter('properDateModel', {
    // model -> view
    // formats the value when updating the input element.
    read: function (value) {
        if (value.replace(/\s/g, "").length > 0) {
            return moment(value, "YYYY-MM-DD").format('DD/MM/YYYY');
        }
        return value;
    },
    // view -> model
    // formats the value when writing to the data.
    write: function (val, oldVal) {
        if(val.replace(/\s/g, "").length > 0) {
            return moment(val, "DD/MM/YYYY").format("YYYY-MM-DD");
        }
        return val;
    }
});
Vue.filter('dateTime', function (value) {
    if(! value || value == '') return;
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD MMM YYYY, h:mm a');
    }
    return value;
});

Vue.filter('date', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD/MM/YYYY');
    }
    return value;
});
Vue.filter('easyDate', function (value) {
    if(!value) return;
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD MMM YYYY');
    }
    return value;
});

Vue.filter('easyDateModel', {
    // model -> view
    // formats the value when updating the input element.
    read: function (value) {
        console.log(value);
        var date = moment(value, "DD-MM-YYYY");
        if (value && date) {
            return moment(value, "DD-MM-YYYY").format('DD MMM YYYY');
        }
        return value;
    },
    // view -> model
    // formats the value when writing to the data.
    write: function (val, oldVal) {
        return val;
    }
});
Vue.filter('limitString', function (val, limit) {
    if (val && val.length > limit) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" ")));
        return trimmedString
    }

    return val;
});
Vue.filter('numberFormat', function (val) {
    if(isNaN(parseFloat(val))) return val;
    //Seperates the components of the number
    var n = val.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
});
Vue.filter('numberModel', {
    read: function (val) {
        if(val) {
            //Seperates the components of the number
            var n = val.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return n.join(".");
        }
    },
    write: function (val, oldVal, limit) {
        val = val.replace(/\s/g, ''); // remove spaces
        limit = limit || 0; // is there a limit?
        if(limit) {
            val = val.substring(0, limit); // if there is a limit, trim the value
        }
        //val = val.replace(/[^0-9.]/g, ""); // remove characters
        // Trim invalid characters, and round to 2 decimal places
        return Math.round(val.replace(/[^0-9\.]/g, "") * 100) / 100;
    }
});
Vue.filter('percentage', {
    read: function(val) {
        return (val * 100);
    },
    write: function(val, oldVal){
        val = val.replace(/[^0-9.]/g, "");
        return val / 100;
    }
});
var modalSinglePR = {
    created: function () {
    },
    methods: {
        showSinglePR: function (purchaseRequest) {
            vueEventBus.$emit('modal-single-pr-show', purchaseRequest);
        }
    }
};
var numberFormatter = {
    created: function () {
    },
    methods: {
        formatNumber: function (number, decimalPoints, currencySymbol) {

            // Default decimal points
            if(decimalPoints === null || decimalPoints === '') decimalPoints = 2;

            // If we gave a currency symbol - format it as money
            if(currencySymbol) return accounting.formatMoney(number, currencySymbol, decimalPoints, ',');

            // otherwise just a norma lnumber format will do
            return accounting.formatNumber(number, decimalPoints, ',');
        }
    }
};
var userCompany = {
    props: ['user'],
    computed: {
        company: function () {
            return this.user.company;
        },
        availableCurrencies: function() {
            if(! this.user.id) return [];
            return this.user.company.settings.currencies;
        },
        companyCurrencies: function() {
            if(! this.user.id) return [];
            return this.user.company.currencies;
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        },
        companyAddress: function () {
            if (_.isEmpty(this.user.company.address)) return false;
            return this.user.company.address;
        },
        PORequiresAddress: function () {
            return this.user.company.settings.po_requires_address;
        },
        PORequiresBankAccount: function () {
            return this.user.company.settings.po_requires_bank_account;
        }
    }
};
Vue.component('form-errors', {
    template: '<div class="validation-errors" v-show="errors.length > 0">' +
    '<h5 class="errors-heading"><i class="fa fa-warning"></i>Could not process request due to</h5>' +
    '<ul class="errors-list list-unstyled"' +
    'v-show="errors.length > 0"' +
    '>' +
    '<li v-for="error in errors">{{ error }}</li>' +
    '</ul>' +
    '</div>',
    data: function () {
        return {
            errors: []
        }
    },
    events: {
        'new-errors': function(errors) {
            var self = this;
            var newErrors = [];
            _.forEach(errors, function (error) {
                if(newErrors.indexOf(error[0]) == -1) newErrors.push(error[0]);
            });
            self.errors = newErrors;
        },
        'clear-errors': function() {
            this.errors = [];
        }
    }
});
Vue.component('paginator', {
    name: 'paginator',
    template: '<div class="api-paginator">' +
    '<ul class="list-unstyled list-inline">' +
    '   <li class="paginate-nav to-first"' +
    '       :class="{' +
    "           'disabled': currentPage < 3  || currentPage > lastPage" +
    '       }"' +
    '       @click="goToPage(1)"' +
    '   >'+
    '       <i class="fa fa-angle-double-left"></i>' +
    '   </li>'+
    '   <li class="paginate-nav prev"' +
    '       :class="{'+
    "           'disabled': (currentPage - 1) < 1 || currentPage > lastPage" +
    '       }"'+
    '       @click="goToPage(currentPage - 1)"'+
    '   >'+
    '       <i class="fa fa-angle-left"></i>'+
    '   </li>'+
    '   <li class="paginate-link"'+
    '       v-for="page in paginatedPages"'+
    '       :class="{' +
                "'current_page': currentPage === page,"+
                "'disabled': page > lastPage"+
    '       }"'+
    '       @click="goToPage(page)"'+
    '   >'+
    '       {{ page }}'+
    '   </li>'+
    '   <li class="paginate-nav next"'+
    '       :class="{'+
                "'disabled': currentPage >= lastPage"+
    '       }"'+
    '       @click="goToPage(currentPage + 1)"'+
    '    >'+
    '       <i class="fa fa-angle-right"></i>'+
    '   </li>'+
    '   <li class="paginate-nav to-last"'+
    '       :class="{'+
    "           'disabled': currentPage > (lastPage - 2)"+
    '       }"'+
    '       @click="goToPage(lastPage)"'+
    '   >'+
    '       <i class="fa fa-angle-double-right"></i>'+
    '   </li>'+
    '</ul>'+
    '</div>',
    data: function() {
        return {

        };
    },
    props: ['response', 'reqFunction', 'event-name'],
    computed: {
        currentPage: function() {
            return this.response.current_page;
        },
        lastPage: function() {
            return this.response.last_page
        },
        paginatedPages: function () {
            var startPage;
            var endPage;
            switch (this.currentPage) {
                case 1:
                case 2:
                    // First 2 pages - always return first 5 pages
                    return this.makePagesArray(1, 5);
                    break;
                case this.lastPage:
                case this.lastPage - 1:
                    // Last 2 pages - return last 5 pages
                        // If we have more than 5 pages count back 4 pages. Else start at page 1
                        startPage = (this.lastPage > 5) ? this.lastPage - 4 : 1;
                        endPage = (this.lastPage > 5 ) ? this.lastPage : 5;
                    return this.makePagesArray(startPage, endPage);
                    break;
                default:
                    startPage = this.currentPage - 2;
                    endPage = this.currentPage + 2;
                    return this.makePagesArray(startPage, endPage);
            }
        }
    },
    methods: {
        makePagesArray: function (startPage, endPage) {
            var pagesArray = [];
            for (var i = startPage; i <= endPage; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        },
        goToPage: function (page) {
            // if we get a custom event name - fire it
            if(this.eventName) vueEventBus.$emit(this.eventName, page);
            vueEventBus.$emit('go-to-page', page);
            this.$dispatch('go-to-page', page);         // TODO ::: REMOVE WILL BE DEPRACATED Vue 2.0 <
            if (0 < page && page <= this.lastPage && typeof(this.reqFunction) == 'function') this.reqFunction(updateQueryString('page', page));
        }
    },
    events: {

    },
    ready: function() {

    }
});
Vue.component('per-page-picker', {
    name: 'itemsPerPagePicker',
    template: '<div class="per-page-picker">' +
    '<span>Results Per Page</span>' +
    '<select-picker :name.sync="newItemsPerPage" :options.sync="itemsPerPageOptions" :function="changeItemsPerPage"></select-picker>' +
    '</div>',
    el: function() {
        return ''
    },
    data: function() {
        return {
            newItemsPerPage: '',
            itemsPerPageOptions: [
                {
                    value: 8,
                    label: 8
                }, {
                    value: 16,
                    label: 16
                },
                {
                    value: 32,
                    label: 32
                }
            ]
        };
    },
    props: ['response', 'reqFunction'],
    computed: {
        itemsPerPage: function() {
            return this.response.per_page;
        }
    },
    methods: {
        changeItemsPerPage: function() {
            var self = this;
            if(self.newItemsPerPage !== self.itemsPerPage) {
                self.reqFunction(updateQueryString({
                    page: 1, // Reset to page 1
                    per_page: self.newItemsPerPage // Update items per page
                }));
            }
        }
    }
});
Vue.component('power-table', {
    name: 'powerTable',
    template: '<div class="table-responsive">' +
    '<table class="table power-table"' +
    '       :class="{' +
    "           'table-hover': hover" +
    '       }"' +
    '>' +
    '<thead>' +
    '<tr>' +
    '<template v-for="header in headers">' +
    '<th v-if="header.sort"' +
    '    @click="changeSort(header.sort)"' +
    '    :class="{' +
    "       'active': sortField === header.sort," +
    "       'asc'   : sortAsc === 1," +
    "       'desc'  : sortAsc === -1," +
    "       'clickable'  : sort" +
    '    }"' +
    '>' +
    '{{ header.label }}' +
    '</th>' +
    '<th v-else>' +
    '{{ header.label }}' +
    '</th>' +
    '</template>' +
    '</tr>' +
    '</thead>' +
    '<tbody>' +
    '<template' +
    '   v-for="item in data | orderBy sortField sortAsc"' +
    '>' +
    '<tr>' +
    '<td v-for="header in headers" ' +
    '    @click="clickEvent(item, field, parseItemValue(header, item))"' +
    '    :class="{' +
    "       'clickable': header.click === true" +
    '    }"' +
    '> {{{ parseItemValue(header, item) }}}</td>' +
    '</tr>' +
    '' +
    '</template>' +
    '</tbody>' +
    '' +
    '</table>' +
    '</div>',
    data: function() {
        return {
            sortField: '',
            sortAsc: 1
        };
    },
    props: [
        'headers',
        'data',
        'filter',    // TO DO ::: Hook up way to filter data
        'sort',
        'hover'     // Set table-hover class
    ],
    computed: {

    },
    methods: {
        parseItemValue: function(header, item) {
            var value;
            _.forEach(header.path, function (path, key) {
                value = (key === 0) ? item[path] : value[path];
            });
            return value;
        },
        changeSort: function(field) {
            if(! this.sort) return;

            if(this.sortField === field) {
                this.sortAsc = (this.sortAsc === 1) ? -1 : 1;
            } else {
                this.sortField = field;
                this.sortAsc = 1;
            }
        },
        clickEvent: function(item, field, value) {
            this.$dispatch('click-table-cell', {
                item: item,
                field: field,
                value: value
            });
        }
    },
    events: {

    },
    ready: function() {

    }
});
Vue.component('select-picker', {
    template: '<select v-model="name" class="themed-select" @change="callChangeFunction">' +
    '<option v-if="placeholder" value="" selected disabled>{{ placeholder }}</option>' +
    '<option v-if="option && option.value" value="{{ option.value }}" v-for="option in options">{{ option.label }}</option>' +
    '</select>',
    name: 'selectpicker',
    props: ['options', 'name', 'function', 'placeholder'],
    methods: {
        callChangeFunction: function () {
            if (this.function && typeof this.function === 'function') {
                this.function();
            }
        }
    },
    ready: function () {

        // Init our picker
        $(this.$el).selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        });

        this.$watch('name', function (val) {
            $(this.$el).val(val);
            $(this.$el).selectpicker('render');
        });

        // Update whenever options change
        this.$watch('options', function (val) {
            // Refresh our picker UI
            $(this.$el).selectpicker('refresh');
            // Update manually because v-model won't catch
            this.name = $(this.$el).selectpicker('val');
        }.bind(this))
    }
});
Vue.component('select-type', {
    name: 'selectType',
    template: '<select class="select-type" v-show="receivedOptions">' +
    '<option></option>' +
    '               <option value="{{ option }}" v-for="option in options">{{ option }}</option>' + '' +
    '          </select>',
    data: function () {
        return {
            receivedOptions: false,
            selectize: {}
        };
    },
    props: [
        'options',
        'name',
        'create',
        'unique',
        'placeholder'
    ],
    ready: function () {


        var self = this;

        var unique = this.unique || true,
            create = this.create || true,
            placeholder = this.placeholder || 'Type to select...';

        this.$watch('name', function (value) {
            if(! value)this.selectize.clear();
        });

        this.$watch('options', function () {
            this.receivedOptions = true;
            if (!_.isEmpty(this.selectize)) this.selectize.destroy();
            this.selectize = $(this.$el).selectize({
                create: create,
                sortField: 'text',
                placeholder: placeholder,
                createFilter: function (input) {
                    input = input.toLowerCase();
                    var optionsArray = $.map(unique.options, function (value) {
                        return [value];
                    });
                    var unmatched = true;
                    _.forEach(optionsArray, function (option) {
                        if ((option.text).toLowerCase() === input) {
                            unmatched = false;
                        }
                    });
                    return unmatched;   // true if unmatched (ie. new) value
                },
                onChange: function (value) {
                    // When we select / enter a new value - enter it into our data
                    self.name = value;
                }
            })[0].selectize;
            // Let parent component know select is loaded
            this.$dispatch('select-loaded');
        });


        // TODO :: Add ability to re-render when options changes
        //      - Maybe define options on selectize and render options / item through plugin (instead of Vue)
        //      - Call clearOption()?
        //      - Clear Cache? Some bug, unknown if fixed

    },
    beforeDestroy: function () {
        this.selectize.destroy();   // TODO :: Check if valid & necessary
    }
});
Vue.component('text-clipper', {
    name: 'textClipper',
    template: '<div class="text-clipper"' +
    '               :class="{' +
    "                   'expanded': !clip" +
    '               }"' +
    '           >' +
    '               <div v-if="isClipped" class="clipped">' +
    '                   {{ text | limitString limit }}' +
    '                   <a class="btn-show-more-text" @click.prevent.stop="unclip">' +
    '                       <span class="clickable">...</span>' +
    '                   </a>' +
    '               </div>' +
    '               <div v-else class="unclipped">' +
    '                   {{ text }}' +
    '               </div>' +
    '            </div>',
    data: function() {
        return {
            limit: 150,
            clip: true
        };
    },
    props: ['text'],
    computed: {
        isClipped: function() {
            return this.text.length > this.limit && this.clip;
        }
    },
    methods: {
        unclip: function() {
            // Set max-height dynamically - depending on amount of text
            $(this.$el).css('max-height', $(this.$el).height());
            // Playing it safe
            setTimeout(function() {
                this.clip = false;
            }.bind(this), 150);
        }
    },
    ready: function() {
        // If the data changes but we're still using the same Component instance
        this.$watch('text', function () {
            // Reset it - ie. clip text
            this.clip = true;
        });
    }
});
Vue.component('toast-alert', {
    name: 'toaster',
    template: '<div id="toast-plate">' +
    '               <div class="toast animated"' +
    '                    v-for="(index, alert) in alerts"' +
    '                    transition="fade"' +
    '                    :class="alert.type">' +
    '<button type="button" class="btn-close" @click="dismiss(alert) "><i class="fa fa-close"></i></button>' +
    '{{{ alert.content }}}' +
    '</div>' +
    '</div>',
    data: function() {
        return {
            alerts: []
        };
    },
    methods: {
        addToQueue: function(alert) {
            // Attach a timeout ID and use it as unique id
            alert.timerID = setTimeout(function () {
                // dismiss (hide) the alert after 3 secs...
                this.dismiss(alert);
            }.bind(this), 3000);
            // finally push alert
            this.alerts.push(alert);
        },
        dismiss: function(alert) {
            // if we prematurely cleared it.. clear the timeout
            clearTimeout(alert.timerID);
            // Remove it from array (will work because of unique timerID)
            this.alerts = _.reject(this.alerts, alert);
        }
    },
    events: {
        'serve-toast': function(alert) {
            this.addToQueue(alert);
        }
    },
    ready: function() {
        /*
        TODO ::: Implement this component to handle alerts if/when we
        make the jump to Vue for handling all client-side. Which
        includes routing, auth etc.
         */
    }
});
Vue.component('date-range-field', {
    name: 'dateRangeField',
    template: '<div class="date-range-field">' +
    '<div class="starting">' +
    '<label>starting</label>'+
    '<input type="text" class="filter-datepicker" v-model="min | properDateModel" placeholder="date">'+
    '</div>' +
    '<span class="dash">-</span>' +
    '<div class="ending">' +
    '<label>Ending</label>' +
    '<input type="text" class="filter-datepicker" v-model="max | properDateModel" placeholder="date">' +
    '</div>'+
    '</div>',
    props: ['min', 'max']
});
Vue.component('integer-range-field', {
    name: 'integerRangeField',
    template: '<div class="integer-range-field">'+
    '<input type="number" class="form-control" v-model="min" min="0">'+
    '<span class="dash">-</span>'+
    '<input type="number" class="form-control" v-model="max" min="0">'+
    '</div>',
    props: ['min', 'max']
});
Vue.component('number-input', {
    name: 'numberInput',
    template: '<input type="text" :class="class" v-model="inputVal" :placeholder="placeholder" :disabled="disabled">',
    props: ['model', 'placeholder', 'decimal', 'currency', 'class', 'disabled', 'on-change-event-name', 'on-change-event-data'],
    computed: {
        precision: function() {
            return this.decimal || 0;
        },
        inputVal: {
            get: function() {
                if(this.model === 0) return 0;
                if(! this.model) return;
                if(this.currency) return accounting.formatMoney(this.model, this.currency + ' ', this.precision);
                return accounting.formatNumber(this.model, this.precision, ",");
            },
            set: function(newVal) {
                // Acts like a 2 way filter
                var decimal = this.decimal || 0;
                this.model = accounting.toFixed(newVal, this.precision);

                if(this.onChangeEventName) {
                    var data = this.onChangeEventData || null;
                    vueEventBus.$emit(this.onChangeEventName, {
                        newVal: newVal,
                        attached: data
                    });
                }
            }
        }
    },
    ready: function() {
    }
});
Vue.component('add-address-modal', {
    name: 'addAddressModal',
    template: '<button type="button"' +
    '                  class="btn btn-add-address btn-outline-green"' +
    '                  @click="showModal"' +
    '                  >' +
    '                  <i class="fa fa-plus"></i> New Address' +
    '          </button>' +
    '          <div class="modal-overlay modal-address-add modal-form" v-show="visible" @click="hideModal">' +
    '               <form class="modal-body form-address-add main-form" v-show="loaded" @click.stop="" @submit.prevent="addAddress">' +
    '                   <button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '                   <form-errors></form-errors>' +
    '                   <h3>Add Address</h3>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input">' +
    '                               <input type="text" ' +
    '                                      class="not-required" ' +
    '                                      v-model="contactPerson" ' +
    '                                      :class="{' +
    "                                           'filled': contactPerson }" +
    '                               ">' +
    '                               <label placeholder="Contact Person"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input">' +
    '                               <input type="text" required v-model="phone">' +
    '                               <label placeholder="Phone" class="required"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="shift-label-input no-validate">' +
    '                       <input type="text" v-model="address1" required>' +
    '                       <label class="required" placeholder="Address"></label>' +
    '                   </div>' +
    '                   <div class="shift-label-input no-validate">' +
    '                       <input class="not-required"' +
    '                              type="text"' +
    '                              v-model="address2"' +
    '                              :class="{' +
    "                                  'filled': address2.length > 0" +
    '                              }"' +
    '                       >' +
    '                       <label placeholder="Address 2"></label>' +
    '                   </div>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input no-validate">' +
    '                               <input type="text" v-model="city" required>' +
    '                               <label class="required" placeholder="City"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input no-validate">' +
    '                               <input type="text" v-model="zip" required>' +
    '                               <label class="required" placeholder="Zip"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="form-group shift-select">' +
    '                               <label class="required">Country</label>' +
    '                               <country-selecter :name.sync="countryID"></country-selecter>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="form-group shift-select">' +
    '                               <label class="required">State</label>' +
    '                               <state-selecter :name.sync="state""></state-selecter>'+
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="form-group align-end">' +
    '                       <button type="submit" class="btn btn-solid-green" :disabled="! canSaveAddress">Save Address</button>' +
    '                   </div>' +
    '               </form>' +
    '          </div>',
    data: function () {
        return {
            ajaxReady: true,
            ajaxObject: {},
            visible: false,
            loaded: false,
            contactPerson: '',
            phone: '',
            address1: '',
            address2: '',
            city: '',
            zip: '',
            countryID: '',
            state: ''
        };
    },
    props: ['owner-id', 'owner-type'],
    computed: {
        canSaveAddress: function () {
            return this.address1.length > 0 && this.city.length > 0 && this.countryID.length > 0 && this.zip.length > 0 && this.phone.length > 0;
        }
    },
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        addAddress: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address',
                method: 'POST',
                data: {
                    "owner_id": self.ownerId,
                    "owner_type": self.ownerType,
                    "contact_person": self.contactPerson,
                    "phone": self.phone,
                    "address_1": self.address1,
                    "address_2": self.address2,
                    "city": self.city,
                    "zip": self.zip,
                    "country_id": self.countryID,
                    "state": self.state
                },
                success: function (data) {
                    // success
                    self.visible = false;
                    flashNotify('success', 'Added a new address');
                    self.$dispatch('address-added', data);
                    self.ajaxReady = true;

                    // reset fields
                    self.contactPerson = '';
                    self.phone = '';
                    self.address1 = '';
                    self.address2 = '';
                    self.city = '';
                    self.zip = '';
                    self.countryID = '';
                    self.state = '';

                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
        var self = this;
        self.loaded = true;
    }
});
Vue.component('add-item-modal', {
    name: 'addItemModal',
    template: '<button type="button"' +
    '               class="btn button-add-item"' +
    '               :class="{' +
    "                   'btn-outline-blue': this.buttonType === 'blue'," +
    "                   'btn-solid-green': ! this.buttonType"+
    '}"' +
    '               @click="showModal"' +
    '               >' +
    '               Add New Item' +
    '</button>'+
    '<div class="modal-item-add modal-form modal-overlay" v-show="visible" @click="hideModal">' +
    '<form class="form-item-add main-form modal-body" v-show="loaded" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<form-errors></form-errors>' +
    '<h2>Add New Item</h2>' +
    '   <div class="form-group">' +
    '       <label>SKU</label>' +
    '       <input class="form-control" type="text" v-model="sku">' +
    '   </div>' +
    '<div class="form-group brand-name-wrap">' +
    '<div class="brand-selection"><label>Brand</label><select class="item-add-brand-select"><option></option></select></div>' +
    '<div class="enter-name"><label  class="required">Name</label><input class="form-control" type="text" v-model="name"></div>' +
    '</div>' +
    '   <div class="form-group">' +
    '       <label  class="required">Specification</label>' +
    '       <textarea class="form-control" v-model="specification" rows="5"></textarea>' +
    '   </div>' +
    '<div class="form-group">' +
    '<div class="item-photo-uploader">' +
    '<label>Photos</label>' +
    '<div class="dropzone-errors" v-show="fileErrors.length > 0">' +
    '<span class="error-heading">Could not add the following files</span>' +
    '<span class="button-clear" @click="clearErrors">clear</span>' +
    '<ul class="file-upload-errors">' +
    '<li v-for="error in fileErrors" track-by="$index">{{ error }}</li>' +
    '</ul>' +
    '</div>' +
    '<div class="item-photo-dropzone dropzone">' +
    '<div class="dz-message"><i class="fa fa-image"></i>' +
    'Click or drop images to upload' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '<div class="bottom align-end">' +
    '   <button type="button"' +
    '           class="btn btn-solid-green"' +
    '           @click.prevent="submitAddItemForm"' +
    '           :disabled="! canSubmitForm"' +
    '   >' +
    '       Save Item' +
    '   </button>' +
    '</div>' +
    '</form>' +
    '</div>',
    data: function () {
        return {
            visible: false,
            ajaxReady: true,
            loaded: false,
            existingBrands: null,
            sku: '',
            brand: '',
            name: '',
            specification: '',
            uploadedFiles: [],
            fileErrors: [],
            dropzone: {}
        };
    },
    props: ['buttonType'],
    computed: {
        canSubmitForm: function () {
            return this.name.length > 0 && this.specification.length > 0;
        }
    },
    methods: {
        showModal: function() {
            this.visible = true;
        },
        hideModal: function() {
            this.visible = false;
        },
        clearErrors: function () {
            this.fileErrors = []
        },
        submitAddItemForm: function () {
            var self = this;

            // Create new FormData Instance
            var fd = new FormData();

            // Attach our previously uploaded files to data
            _.forEach(self.uploadedFiles, function (file) {
                fd.append('item_photos[]', file);
            });

            // Append our other data
            fd.append('sku', self.sku);
            fd.append('brand', self.brand);
            fd.append('name', self.name);
            fd.append('specification', self.specification);

            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/items',
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function (data) {
                    // success
                    console.log('success!');
                    console.log(data);
                    self.ajaxReady = true;
                    self.clearFields(); // Clear selected fields
                    self.$dispatch('added-new-item', data);   // Send out event for parent component
                    self.visible = false;
                    flashNotify('success', 'Added new Item');
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        clearFields: function () {
            this.sku = '';
            this.brand = '';
            this.name = '';
            this.specification = '';
            this.uploadedFiles = '';
            this.fileErrors = [];
            this.dropzone.removeAllFiles();
        }
    },
    events: {},
    ready: function () {
        var self = this;

        // Brand selectize init
        $('.item-add-brand-select').selectize({
            valueField: 'brand',
            searchField: 'brand',
            create: true,
            placeholder: 'Find or enter a new brand',
            render: {
                option: function(item, escape) {
                    return '<div class="single-brand-option">' + escape(item.brand) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-brand">' + escape(item.brand) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/brands/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.brand = value;
            }
        });

        // File Upload
        var dzMaxFileSize = 5 * (1000000);
        self.dropzone = new Dropzone("div.item-photo-dropzone", {
            autoProcessQueue: false,
            url: "#",
            acceptedFiles: 'image/*',
            accept: function (file, done) {
                if (self.uploadedFiles.length > 12) {
                    self.fileErrors.push("Maximum of 12 Photos reached");
                    this.removeFile(file);
                } else if (file.type !== 'image/jpeg' && file.type !== 'image/png' && file.type !== 'image/gif') {
                    self.fileErrors.push('"' + file.name + '" not a valid image type (.jpeg, .png, .gif)');
                    this.removeFile(file);
                } else if (file.size > dzMaxFileSize) {
                    self.fileErrors.push('"' + file.name + '" file size over 5MB');
                    this.removeFile(file);
                }
                else {
                    done();
                }
            },
            previewTemplate: '<div class="dz-image-row"><div class="dz-image"><img data-dz-thumbnail></div><div class="dz-file-details"><span data-dz-name class="file-name"></span><span class="file-size" data-dz-size></span></div><div class="link-remove"><i class="fa fa-close" data-dz-remove></i></div></div>',
            init: function () {
                this.on("addedfile", function (file) {
                    self.uploadedFiles.push(file);
                });
                this.on("removedfile", function (file) {
                    self.uploadedFiles = _.reject(self.uploadedFiles, file);
                })
            }
        });

        self.loaded = true;
    }
});
Vue.component('modal', {
    data: function () {
        return {
            title: '',
            body: '',
            buttonText: '',
            buttonClass: '',
            callbackEventName: ''
        }
    },
    template: '<div class="modal-roles modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
    '<div class="vertical-alignment-helper">' +
    '<div class="modal-dialog vertical-align-center">' +
    '<div class="modal-content">' +
    '<div class="modal-header">' +
    '<h5 class="text-center">{{ title }}</h5>' +
    '</div>' +
    '<div class="modal-body">' +
    '<p>{{ body }}</p>' +
    '</div>' +
    '<div class="modal-footer">' +
    '<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>' +
    '<a class="btn btn-ok btn-confirm {{ buttonClass }}"' +
    '   @click="fireEvent" data-dismiss="modal"' +
    '>' +
    '{{ buttonText }}' +
    '</a>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>',
    methods: {
        fireEvent: function() {
            this.$dispatch(this.callbackEventName);
        }
    },
    events: {
        'new-modal': function (settings) {
            var self = this;
            self.title = settings.title;
            self.body = settings.body;
            self.buttonClass = settings.buttonClass;
            self.buttonText = settings.buttonText;
            self.callbackEventName = settings.callbackEventName;

            // show the modal
            $(this.$el).modal('show');

        }
    }
});
Vue.component('single-pr-modal', {
    name: 'singlePurchaseRequestModal',
    template: '<div class="modal-overlay single-pr" tabindex="-1" role="dialog" aria-labelledby="singlePRModal" aria-hidden="true" v-show="visible" @click="hideModal">' +
    '               <div class="modal-body" @click.stop="">'+
    '                   <modal-close-button></modal-close-button>' +
    '                       <h3>Purchase Request #{{ purchaseRequest.number }} <span'+
    '                           class="badge-state {{ purchaseRequest.state }}">{{ purchaseRequest.state }}</span>' +
    '                       </h3>' +
    '                       <div class="pr">' +
    '                           <div class="request-info">' +
    '                               <span class="requested">Requested {{ purchaseRequest.created_at | diffHuman }}</span>' +
    '                               <span class="requester">{{ purchaseRequest.user.name }}</span>' +
    '                           </div>' +
    '                           <div class="due">' +
    '                               <h5>Due Date</h5>' +
    '                               <span class="date">{{ purchaseRequest.due | easyDate }}</span>' +
    '                           </div>' +
    '                           <div class="quantity">' +
    '                               <h5>Quantity</h5>' +
    '                               <span class="number">{{ purchaseRequest.quantity }}</span>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="item">' +
    '                           <h5>Item</h5>' +
    '                           <div class="main-photo">' +
    '                               <a v-if="purchaseRequest.item.photos.length > 0" :href="purchaseRequest.item.photos[0].path" class="fancybox image-item-main" rel="group">' +
    '                                   <img :src="purchaseRequest.item.photos[0].thumbnail_path" alt="Item Main Photo">' +
    '                               </a>' +
    '                               <div class="placeholder" v-else>'+
    '                                   <i class="fa fa-image"></i>'+
    '                               </div>' +
    '                           </div>' +
    '                           <div class="details">' +
    '                               <span class="sku display-block" v-if="purchaseRequest.item.sku">{{ purchaseRequest.item.sku }}</span>' +
    '                               <span class="brand" v-if="purchaseRequest.item.brand">{{ purchaseRequest.item.brand }} - </span>' +
    '                               <span class="name">{{ purchaseRequest.item.name }}</span>' +
    '                               <p class="specification">{{ purchaseRequest.item.specification }} </p>' +
    '                               <div class="item-images" v-if="purchaseRequest.item.photos.length > 0">' +
    '                                   <ul class="image-gallery list-unstyled list-inline">' +
    '                                       <li class="single-item-image" v-for="photo in purchaseRequest.item.photos">' +
    '                                           <a :href="photo.path" v-fancybox rel="group">' +
    '                                               <img :src="photo.thumbnail_path" alt="item image">' +
    '                                           </a>' +
    '                                       </li>' +
    '                                   </ul>' +
    '                               </div>' +
    '                          </div>' +
    '                      </div>'+
    '             </div>' +
    '       </div>',
    data: function() {
        return {
            purchaseRequest: {
                user: {},
                item: {
                    photos: []
                }
            },
            visible: false
        }
    },
    props: [],
    computed: {

    },
    methods: {
        hideModal: function() {
            this.visible = false;
        }
    },
    events: {
        'click-close-modal': function() {
            this.hideModal();
        }
    },
    ready: function() {
        var self = this;
        vueEventBus.$on('modal-single-pr-show', function(purchaseRequest) {
            self.purchaseRequest = purchaseRequest;
            self.$nextTick(function () {
                self.visible = true;
            });
        });
        vueEventBus.$on('modal-close', function() {
            self.hideModal();
        });
    }
});


var apiRequestAllBaseComponent = Vue.extend({
    name: 'APIRequestall',
    data: function () {
        return {
            ajaxReady: true,
            request: {},
            response: {},
            params: {},
            showFiltersDropdown: false,
            filter: '',
            filterValue: '',
            minFilterValue: '',
            maxFilterValue: ''
        };
    },
    props: [],
    computed: {},
    methods: {
        checkSetup: function() {
            if(!this.requestUrl) throw new Error("No Request URL set as 'requestUrl' ");
            if(this.hasFilter && _.isEmpty(this.filterOptions)) throw new Error("Need filterOptions[] defined to use filters");
        },
        makeRequest: function (query) {
            var self = this,
                url = this.requestUrl;

            // If we got a new query parameter, use it in our request - otherwise, try get query form address bar
            query = query || window.location.href.split('?')[1];
            // If we had a query (arg or parsed) - attach it to our url
            if (query) url = url + '?' + query;

            // self.finishLoading = false;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.request = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    // Attach filters
                    // Reset obj
                    self.params = {};
                    // Loop through and attach everything (Only pre-defined keys in data obj above will be accessible with Vue)
                    _.forEach(response.data.query_parameters, function (value, key) {
                        self.params[key] = value;
                    });


                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);

                    document.getElementById('body-content').scrollTop = 0;

                    self.ajaxReady = true;
                },
                error: function (res, status, req) {
                    console.log(status);
                    self.ajaxReady = true;
                }
            });
        },
        changeSort: function (sort) {
            if (this.params.sort === sort) {
                var order = (this.params.order === 'asc') ? 'desc' : 'asc';
                this.makeRequest(updateQueryString('order', order));
            } else {
                this.makeRequest(updateQueryString({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        },
        searchTerm: _.debounce(function () {
            if (this.request && this.request.readyState != 4) this.request.abort();
            var term = this.params.search || null;
            this.makeRequest(updateQueryString({
                search: term,
                page: 1
            }))
        }, 200),
        clearSearch: function () {
            this.params.search = '';
            this.searchTerm();
        },
        resetFilterInput: function() {
            this.filter = '';
            this.filterValue = '';
            this.minFilterValue = '';
            this.maxFilterValue = '';
        },
        addFilter: function () {
            var queryObj = {
                page: 1
            };
            queryObj[this.filter] = this.filterValue || [this.minFilterValue, this.maxFilterValue];
            this.makeRequest(updateQueryString(queryObj));
            this.resetFilterInput();
            this.showFiltersDropdown = false;
        },
        removeFilter: function(filter) {
            var queryObj = {
                page: 1
            };
            queryObj[filter] = null;
            this.makeRequest(updateQueryString(queryObj));
        },
        removeAllFilters: function() {
            var self = this;
            var queryObj = {};
            _.forEach(self.filterOptions, function (option) {
                queryObj[option.value] = null;
            });
            this.makeRequest(updateQueryString(queryObj));
        }
    },
    events: {},
    ready: function () {
        this.checkSetup();
        this.makeRequest();
        onPopCallFunction(this.makeRequest);
    }
});
var baseChart = Vue.extend({
    name: 'BaseChart',
    template: '<canvas v-el:canvas class="canvas-chart"></canvas>',
    data: function () {
        return {
            mode: 'url',
            chartLabel: '',
            showZeroValues: false,
            chartType: 'bar',
            chart: '',
            theme: 'red'
        }
    },
    props: [],
    computed: {
        colors: function() {
            switch(this.theme) {
                case 'red':
                    return {
                        backgroundColor: "rgba(255,99,132,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)"
                    };
                    break;
                case 'blue':
                    return {
                        backgroundColor: "rgba(52,152,219,0.2)",
                        borderColor: "rgba(52,152,219,1)",
                        hoverBackgroundColor: "rgba(52,152,219,0.4)",
                        hoverBorderColor: "rgba(52,152,219,1)"
                    };
                    break;
                case 'green':
                    return {
                        backgroundColor: "rgba(46,204,113,0.2)",
                        borderColor: "rgba(46,204,113,1)",
                        hoverBackgroundColor: "rgba(46,204,113,0.4)",
                        hoverBorderColor: "rgba(46,204,113,1)"
                    };
                    break;
                default:
                    break;
            }

        },
        backgroundColor: function() {
            return this.colors.backgroundColor;
        },
        borderColor: function(){
            return this.colors.borderColor;
        },
        hoverBackgroundColor: function() {
            return this.colors.hoverBackgroundColor;
        },
        hoverBorderColor: function() {
            return this.colors.hoverBorderColor;
        }
    },
    methods: {
        load: function () {
            var self = this;

            if(this.mode === 'url') {
                this.fetchData().done(function (data) {
                    self.render(data);
                });
            }
            self.render(this.chartData);
        },
        fetchData: function () {
            return $.get(this.chartURL);
        },
        render: function (data) {

            // Remove 0 values from our data
            if (!this.showZeroValues) data = this.removeZeroValues(data);

            this.chart = new Chart(this.$els.canvas.getContext('2d'), {
                type: this.chartType,
                data: {
                    labels: Object.keys(data),
                    datasets: [
                        {
                            data: _.map(data, function (val) {
                                return val;
                            }),
                            label: this.chartLabel,
                            backgroundColor: this.backgroundColor,
                            borderColor: this.borderColor,
                            borderWidth: 1,
                            hoverBackgroundColor: this.hoverBackgroundColor,
                            hoverBorderColor: this.hoverBorderColor
                        }
                    ]
                }
            });
        },
        removeZeroValues: function(data) {
            return _.pickBy(data, function (value) {
                return value > 0
            });
        },
        reload: function () {
            if (!_.isEmpty(this.chart)) this.chart.destroy();
            this.load();
        }
    },
    events: {},
    ready: function () {
        if(this.mode === 'url' && !this.chartURL) throw new Error("Chart Mode: url - no URL to retrieve chart data");

        var watchVariable = this.mode === 'url' ? 'chartURL' : 'chartData';

        this.$watch(watchVariable, function () {
            this.reload();
        }.bind(this));

        this.load();


    }
});
Vue.component('address', {
    name: 'singleAddress',
    template: '<div class="address" v-if="address">' +
    '<span v-if="address.contact_person" class="contact_person display-block">{{ address.contact_person }}</span>' +
    '<span v-if="company" class="company_name display-block">{{ company.name }}</span>' +
    '<span class="address_1 display-block">{{ address.address_1 }}</span>' +
    '<span v-if="address.address_2" class="address_2 display-block">{{ address.address_2 }}</span>' +
    '<span class="city">{{ address.city }}</span>,' +
    '<span class="zip">{{ address.zip }}</span>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ address.state }}</span>,' +
    '<span class="country">{{ address.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ address.phone }}</span>' +
    '</div>' +
    '</div>',
    props: ['address', 'company']
});
Vue.component('bank-account', {
    name: 'singleBankAccount',
    template: '<div class="bank_account" v-if="bankAccount">' +
    '<div class="info bank_name">' +
    '<label>Bank</label>' +
    '<span>{{ bankAccount.bank_name }}</span>' +
    '</div>' +
    '<div class="name-number">' +
    '<div class="account_name info">' +
    '<label>Account Name</label>' +
    '<span>{{ bankAccount.account_name }}</span>' +
    '</div>' +
    '<div class="number info">' +
    '<label>Account Number</label>' +
    '<span class="account_number">{{ bankAccount.account_number }}</span>' +
    '</div>' +
    '</div>' +
    '<div class="phone_swift">' +
    '<div class="bank_phone info">' +
    '<label>Phone</label>' +
    '<span v-if="bankAccount.bank_phone">{{ bankAccount.bank_phone }}</span><span v-else>-</span>' +
    '</div>' +
    '<div class="info swift">' +
    '<label>SWIFT / IBAN</label>' +
    '<span>' +
    '<span v-if="bankAccount.swift">{{ bankAccount.swift }}</span><span v-else>-</span>' +
    '</span>' +
    '</div>' +
    '</div>' +
    '<div class="info bank_address">' +
    '<label>Address</label>' +
    '<span>' +
    '<span v-if="bankAccount.bank_address">{{ bankAccount.bank_address }}</span><span v-else>-</span>' +
    '</span>' +
    '</div>' +
    '</div>',
    props: ['bank-account']
});
Vue.component('company-currency-selecter', {
    template: '<select v-model="id" class="themed-select" v-el:select>' +
    '<option :value="currency.id" v-for="currency in currencies">{{ currency.code }} - {{ currency.symbol }}</option>' +
    '</select>',
    name: 'selectpicker',
    props: ['currencies', 'currency-object', 'id'],
    methods: {
        getCurrency: function(currencyID) {
            return _.find(this.currencies, function(currency) {
                return currency.id == currencyID;
            });
        },
        refresh: function() {
            this.$nextTick(function() {
                // Refresh our picker UI
                $(this.$els.select).selectpicker('refresh');
                // Update manually because v-model won't catch
                var currencyID = this.id = $(this.$els.select).selectpicker('val');
                this.currencyObject = this.getCurrency(currencyID);
            });
        }
    },
    ready: function () {
        var self = this;
        $(self.$els.select).selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check'
        }).on('changed.bs.select', function () {
            self.currencyObject = self.getCurrency($(self.$els.select).selectpicker('val'));
        });

        self.$watch('currencies', this.refresh);

        vueEventBus.$on('updated-company-currency', this.refresh);
    }
});
Vue.component('company-search-selecter', {
    name: 'companySearchSelecter',
    template: '<select class="company-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.company-search-selecter').selectize({
            valueField: 'id',
            searchField: ['name'],
            create: false,
            placeholder: 'Search by Company Name',
            render: {
                option: function (item, escape) {

                    var optionClass = 'class="option company-single-option ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection pending">pending</span>';
                            break;
                        case 'verified':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection verified">verified</span>';
                            break;
                        default:
                            optionClass += '"';
                            connectionSpan = '';
                    }


                    return '<div ' + optionClass +'>' +
                        '       <span class="name">' + escape(item.name) + '</span>' +
                        connectionSpan +
                        '   </div>'
                },
                item: function (item, escape) {

                    var selectedClass = 'class="company-selected ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection pending">pending</span>';
                            break;
                        case 'verified':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection verified">verified</span>';
                            break;
                        default:
                            selectedClass += '"';
                            connectionSpan = '';
                    }

                    return '<div ' + selectedClass + '>' +
                        '           <label>Selected Company</label>' +
                        '           <div class="name">' + escape(item.name) +
                        connectionSpan +
                        '           </div>' +
                        '           <span class="description">' + escape(item.description) + '</span>' +
                        '       </div>' +
                        '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/company/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                self.name = value;
            }
        });
    }
});
Vue.component('company-employee-search-selecter', {
    name: 'companyEmployeeSearchSelecter',
    template: '<select class="company-employee-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.company-employee-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Search for Company Employee',
            render: {
                option: function(item, escape) {
                    return '<div class="single-name-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-name">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/users/company/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
Vue.component('country-selecter', {
    name: 'countrySelecter',
    template: '<select class="country-selecter"><option></option></select>',
    data: function() {
        return {

        };
    },
    props: ['name', 'event', 'default'],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {
        var self = this,
            select_country;
        $select_country = $(self.$el).selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Country',
            render: {
                option: function (item, escape) {
                    return '<div class="single-country-option">' + escape(item.name) + '</div>'
                },
                item: function (item, escape) {
                    return '<div class="selected-country">' + escape(item.name) + '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/countries/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                // Update the name prop to pass data onto parent component
                self.name = value;
                var eventName = self.event || 'selected-country';
                // Fire event
                vueEventBus.$emit(eventName, value);
            }
        });

        select_country = $select_country[0].selectize;
        // IF we got a default country ID
        self.$watch('default', function (countryID) {
            // fetch associated country
            $.get('/countries/' + countryID, function(data) {
                // Add option
                select_country.addOption(data);

                // Select the option - we set to silent because there may be other
                // selecters watching this one for changes, and they may have
                // their own default values: ie. state-selecter
                select_country.setValue(countryID, true);

                // Update the name value
                self.name = countryID;
            });
        });
    }
});
Vue.component('currency-selecter', {
    name: 'currencySelecter',
    template: '<select class="currency-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name', 'default', 'id'],
    ready: function() {
        var self = this;
        var selecter = $('.currency-selecter').selectize({
            valueField: 'id',
            searchField: ['country_name', 'name', 'code', 'symbol'],
            create: false,
            placeholder: 'Search for a currency',
            maxItems: 1,
            render: {
                option: function(item, escape) {
                    return '<div class="option-currency">' + escape(item.country_name) + ' - ' + escape(item.symbol) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-currency">' + escape(item.country_name) + ' - ' + escape(item.symbol)  + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/countries/currency/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.id = value;

                if(! value) {
                    self.name = '';
                    return;
                }

                $.get('/countries/' + value, function (data) {
                    self.name = data;
                });
            }
        });

        // Setting the default (company's saved) currency
        var _selecter = selecter[0].selectize;
        var defaultCurrency;

        self.$watch('default', function (value) {
            // if we've already added it, return
            if(defaultCurrency && defaultCurrency.id === value.id) return;
            defaultCurrency = value;
            _selecter.addOption(value);
            _selecter.setValue(value.id);
        });
    }
});
Vue.component('item-brand-selecter', {
    name: 'itemBrandSelecter',
    template: '<select class="item-brand-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.item-brand-search-selecter').selectize({
            valueField: 'brand',
            searchField: 'brand',
            create: false,
            placeholder: 'Search for a brand',
            render: {
                option: function(item, escape) {
                    return '<div class="single-brand-option">' + escape(item.brand) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-brand">' + escape(item.brand) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/brands/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
Vue.component('item-name-selecter', {
    name: 'itemNameSelecter',
    template: '<select class="item-name-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.item-name-search-selecter').selectize({
            valueField: 'name',
            searchField: 'name',
            create: false,
            placeholder: 'Search for a name',
            render: {
                option: function(item, escape) {
                    return '<div class="single-name-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-name">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/names/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
Vue.component('item-sku-selecter', {
    name: 'itemSKUSelecter',
    template: '<select class="item-sku-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.item-sku-search-selecter').selectize({
            valueField: 'sku',
            searchField: 'sku',
            create: false,
            placeholder: 'Search for SKU',
            render: {
                option: function(item, escape) {
                    return '<div class="single-sku-option">' + escape(item.sku) + ' - ' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-sku">' + escape(item.sku) + ' - ' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/sku/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
Vue.component('line-item-price-input', {
    name: 'lineItemPriceInput',
    template: '<input type="text" class="input-price form-control" v-model="inputVal" placeholder="price" @change="updateOtherLineItemPrices()">',
    props: ['model', 'line-items', 'current-line-item', 'decimal'],
    computed: {
        precision: function() {
            return this.decimal || 0;
        },
        inputVal: {
            get: function() {
                if(this.model === 0) return 0;
                if(! this.model) return;
                return accounting.formatNumber(this.model, this.precision, ",");
            },
            set: function(newVal) {
                // Acts like a 2 way filter
                var decimal = this.decimal || 0;
                this.model = accounting.toFixed(newVal, this.precision);
            }
        }
    },
    methods: {
        updateOtherLineItemPrices: function () {
            console.log('changed!');

            var self = this;

            var otherLineItemsWithSameItem = _.filter(self.lineItems, function (lineItem) {
                return lineItem.item.id === self.currentLineItem.item.id;
            });

            console.log(otherLineItemsWithSameItem);

            _.forEach(otherLineItemsWithSameItem, function (lineItem) {

                if(lineItem.id === self.currentLineItem.id) return;

                var index = _.indexOf(self.lineItems, lineItem);
                console.log('index is: ' + index);

                var updatedLineItem = lineItem;
                updatedLineItem.order_price = self.currentLineItem.order_price;
                console.log('updated price is: ' + updatedLineItem.order_price);

                self.lineItems.splice(index, 1, updatedLineItem);

            });
        }
    },
    ready: function() {
    }
});
Vue.component('modal-select-address', {
    name: 'modalSelectAddress',
    template: '<button type="button" v-show="! selected" class="btn btn-small button-select-address btn-outline-blue" @click="showModal">Select Address</button>' +
    '<div class="modal-select-address modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h3>Select an Address</h3>' +
    '<ul class="list-unstyled list-address" v-if="addresses.length > 0">' +
    '<li class="single-address clickable" v-for="address in addresses" @click="select(address)">' +
    '<span class="contact_person display-block" v-if="address.contact_person">{{ address.contact_person }}</span>' +
    '<span class="address_1 display-block">{{ address.address_1 }}</span>' +
    '<span class="address_2 display-block" v-if="address.address_2">{{ address.address_2 }}</span>' +
    '<span class="city">{{ address.city }}</span>,' +
    '<div class="zip">{{ address.zip }}</div>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ address.state }}</span>,' +
    '<span class="country">{{ address.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ address.phone }}</span>' +
    '</div>' +
    '</li>' +
    '</ul>' +
    '<em v-else>No Addresses found, add an address to a Vendor to select it here.</em>' +
    '</div>' +
    '</div>' +
    '<div class="single-address clickable selected" v-show="selected">' +
    '<div class="change-overlay" @click="remove">' +
    '<i class="fa fa-close"></i>' +
    '<h3>Remove</h3>' +
    '</div>' +
    '<span class="contact_person display-block" v-if="selected.contact_person">{{ selected.contact_person }}</span>' +
    '<span class="address_1 display-block">{{ selected.address_1 }}</span>' +
    '<span class="address_2 display-block" v-if="selected.address_2">{{ selected.address_2 }}</span>' +
    '<span class="city">{{ selected.city }}</span>,' +
    '<span class="zip">{{ selected.zip }}</span>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ selected.state }}</span>,' +
    '<span class="country">{{ selected.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ selected.phone }}</span>' +
    '</div>' +
    '</div>',
    data: function () {
        return {
            visible: false
        };
    },
    props: ['selected', 'addresses'],
    computed: {},
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        select: function (address) {
            this.selected = address;
            this.hideModal();
        },
        remove: function () {
            this.selected = '';
        }
    },
    events: {},
    ready: function () {

    }
});
Vue.component('modal-select-bank-account', {
    name: 'modalSelectBankAccount',
    template: '<button type="button" v-show="! selected" class="btn btn-small button-select-account btn-outline-blue" @click="showModal">Select Bank Account</button>' +
    '<div class="modal-select-account modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h3>Select a Bank Account</h3>' +
    '<ul class="list-unstyled list-accounts" v-if="accounts.length > 0">' +
    '<li class="single-account clickable" v-for="account in accounts" @click="select(account)">' +
    '<span class="account-name">{{ account.account_name }}</span>' +
    '<span class="account-number">{{ account.account_number }}</span>' +
    '<span class="bank-name">{{ account.bank_name }}</span>' +
    '<span class="bank-phone"><abbr title="Phone">P:</abbr> {{ account.bank_phone }}</span>' +
    '<span class="bank-address" v-if="account.bank_address">{{ account.bank_address }}</span>' +
    '<span class="swift" v-if="account.swift">SWIFT / IBAN: {{ account.swift }}</span>' +
    '</li>' +
    '</ul>' +
    '<em v-else>No Bank Accounts found. Add one to Vendor before selecting it here.</em>' +
    '</div>' +
    '</div>' +
    '<div class="single-account clickable selected" v-show="selected">' +
    '<div class="change-overlay" @click="remove">' +
    '<i class="fa fa-close"></i>' +
    '<h3>Remove</h3>' +
    '</div>' +
    '<span class="account-name">{{ selected.account_name }}</span>' +
    '<span class="account-number">{{ selected.account_number }}</span>' +
    '<span class="bank-name">{{ selected.bank_name }}</span>' +
    '<span class="bank-phone"><abbr title="Phone">P:</abbr> {{ selected.bank_phone }}</span>' +
    '<span class="bank-address" v-if="selected.bank_address">{{ selected.bank_address }}</span>' +
    '<span class="swift" v-if="selected.swift">SWIFT / IBAN: {{ selected.swift }}</span>' +
    '</div>',
    data: function () {
        return {
            visible: false
        };
    },
    props: ['selected', 'accounts'],
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        select: function (account) {
            this.selected = account;
            this.hideModal();
        },
        remove: function () {
            this.selected = '';
        }
    },
    events: {},
    ready: function () {

    }
});
Vue.component('notes', {
    name: 'Notes',
    template: '<div class="notes">' +
    '<form-errors></form-errors>' +
    '<form class="form-post-note" @submit.prevent="add">' +
    '<div class="form-group">' +
    '<textarea class="form-control autosize" placeholder="Add a new note..." v-model="content"></textarea>' +
    '</div>' +
    '<div class="form-group align-end">' +
    '<button type="submit" class="btn btn-solid-blue btn-small" :disabled="! content">Post</button>' +
    '</div>' +
    '</form>' +
    '<div class="existing-notes" v-show="notes.length > 0">' +
    '<ul class="list-unstyled list-notes">' +
    '<li v-for="note in notes" class="single-note">' +
    '<a v-if="canDelete(note)" @click="deleteNote(note)" class="btn-close small"><i class="fa fa-close"></i></a>' +
    '<div class="notes-meta">'+
    '<span class="poster">{{ note.poster.name }}</span><span class="posted">{{ note.created_at | diffHuman }}</span>' +
    '</div>' +
    '<p class="content">{{ note.content }}</p>' +
    '</li>' +
    '</ul>' +
    '</div>' +
    '</div>',
    data: function () {
        return {
            ajaxReady: true,
            notes: [],
            content: ''
        };
    },
    props: ['subject', 'subject_id'],
    computed: {
        url: function () {
            return '/notes/' + this.subject + '/' + this.subject_id;
        }
    },
    methods: {
        fetch: function () {
            $.get(this.url, function (data) {
                this.notes = data;
            }.bind(this));
        },
        add: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: this.url,
                method: 'POST',
                data: {
                    "content": self.content
                },
                success: function (data) {
                    // success
                    self.content = '';
                    self.notes.unshift(data);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
        canDelete: function(note) {
          if(this.user.role.position === 'admin') return true;
            return this.user.id === note.user_id;
        },
        deleteNote: function (note) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: self.url + '/' + note.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.notes = _.reject(self.notes, note);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    mixins: [userCompany],
    events: {},
    ready: function () {
        this.fetch();
    }
});
Vue.component('po-mark-received-popover', {
    name: 'purchaseOrderMarkReceivedPopover',
    template: '<div class="popover-container">' +
    '<button type="button" class="btn-popup btn-mark-received btn btn-small btn-solid-green" :disabled="! (purchaseOrder.status === ' + "'approved'" + ')" @click="togglePopover">Received</button>'+
    '<div class="popover-content popover right" v-show="showPopover">' +
    '<div class="arrow"></div>'+
    '<button type="button" class="btn btn-accept btn-outline-green btn-small" @click="markReceived(lineItem, ' + "'accepted'" + ')">Accept</button>'+
    '<button type="button" class="btn btn-return btn-outline-red btn-small"  @click="markReceived(lineItem, ' + "'returned'" + ')">Return</button>'+
    '</div>' +
    '</div>',
    data: function() {
        return {
            showPopover: false
        };
    },
    props: ['purchase-order', 'line-item'],
    computed: {
        
    },
    methods: {
        togglePopover: function() {
            this.showPopover = !this.showPopover;
        },
        markReceived: function(lineItem, status) {
            if(status !== 'accepted' && status !== 'returned') return;
            $.get('/purchase_orders/' + this.purchaseOrder.id + '/line_item/' + lineItem.id + '/received/' + status, function(data) {
                lineItem.status = data.status;
                lineItem.received = data.received;
                lineItem.accepted = data.accepted;
                lineItem.returned = data.returned;
            });
        }
    },
    events: {
        
    },
    ready: function() {
        var self = this;
        $(document).on('click.hidePopovers', function (event) {
            if (!$(event.target).closest('.btn-popup').length && !$(event.target).closest('.popover-container').length && !$(event.target).is('.popover-container')) {
                self.showPopover = false;
            }
        })
    }
});
Vue.component('po-single-rule', {
    template: '<tr>' +
    '<td class="col-description">' +
    '{{ rule.property.label }} - {{ rule.trigger.label }} <span ' +
    'v-if="rule.trigger.has_limit">{{ formatRuleLimit(rule) }}</span>' +
    '</td>' +
    '<td class="col-approve col-controls">' +
    '<i v-if="approved" class="fa fa-check icon-check"></i>' +
    '<button type="button" class="btn btn-approve" v-if="! set && allowedUser"  @click="processRule(' + "'approve'" + ', rule)"><i class="fa fa-check"></i></button>' +
    '<i v-if="! set && allowedUser" class="fa fa-check placeholder"></i></button>' +
    '<i v-if="! approved && ! allowedUser" class="fa fa-warning"></i>' +
    '</td>' +
    '<td class="col-reject col-controls">' +
    '<i v-if="rejected" class="fa fa-close icon-close"></i>' +
    '<button type="button" class="btn btn-reject" v-if="!set && allowedUser"  @click="processRule(' + "'reject'" + ', rule)"><i class="fa fa-close"></i></button>' +
    '<i v-if="!set && allowedUser" class="fa fa-close placeholder"></i></button>' +
    '<i v-if="! rejected && ! allowedUser" class="fa fa-warning"></i>' +
    '</td>' +
    '</tr>',
    name: 'purchaseOrderSingleRule',
    data: function () {
        return {};
    },
    props: ['purchase-order', 'rule'],
    computed: {
        set: function() {
            return this.rule.pivot.approved !== null;
        },
        approved: function() {
            return this.rule.pivot.approved;
        },
        rejected: function() {
            return this.rule.pivot.approved === 0;
        },
        allowedUser: function() {
            var self = this;
            return _.findIndex(this.rule.roles, function(role) { return role.id == self.user.role_id; }) !== -1;
        }
    },
    methods: {
        formatRuleLimit: function (rule) {
            var currencySymbol = rule.trigger.has_currency ? rule.currency.symbol : null;
            return this.formatNumber(rule.limit, this.currencyDecimalPoints, currencySymbol);
        },
        processRule: function (action, rule) {
            var self = this;

            function updatePOStatus(data) {
                self.purchaseOrder.status = data.status;
                self.purchaseOrder.pending = data.pending;
                self.purchaseOrder.approved = data.approved;
                self.purchaseOrder.rejected = data.rejected;
            }

            if (action === 'approve') {
                $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/approve', function (data) {
                    rule.pivot.approved = 1;
                    updatePOStatus(data);
                })
            } else {
                $.get('/purchase_orders/' + self.purchaseOrder.id + '/rule/' + rule.id + '/reject', function (data) {
                    rule.pivot.approved = 0;
                    updatePOStatus(data);
                })
            }
        }
    },
    mixins: [numberFormatter, userCompany],
    events: {},
    ready: function () {

    }
});
Vue.component('registration-popup', {
    name: 'registration-popup',
    el: function () {
        return '#registration-popup'
    },
    data: function () {
        return {
            showRegisterPopup: false,
            companyName: '',
            validCompanyName: 'unfilled',
            companyNameError: '',
            email: '',
            validEmail: 'unfilled',
            emailError: '',
            password: '',
            validPassword: 'unfilled',
            name: '',
            validName: 'unfilled',
            ajaxReady: true
        };
    },
    props: [],
    computed: {},
    methods: {
        toggleShowRegistrationPopup: function () {
            this.showRegisterPopup = !this.showRegisterPopup;
        },
        checkCompanyName: function () {
            var self = this;
            self.validCompanyName = 'unfilled';
            if (self.companyName.length > 0) {
                // No symbols in name
                if (!alphaNumeric(self.companyName)) {
                    self.validCompanyName = false;
                    self.companyNameError = 'Company name cannot contain symbols';
                    return;
                }
                self.validCompanyName = 'loading';
                if (!self.ajaxReady) return;
                self.ajaxReady = false;
                $.ajax({
                    url: '/api/company/profile/' + encodeURI(self.companyName),
                    method: '',
                    success: function (data) {
                        // success
                        if (!_.isEmpty(data)) {
                            self.validCompanyName = false;
                            self.companyNameError = 'That Company name is already taken'
                        } else {
                            self.validCompanyName = true;
                            self.companyNameError = '';
                        }
                        self.ajaxReady = true;
                    },
                    error: function (response) {
                        console.log(response);
                        self.ajaxReady = true;
                    }
                });
            }
        },
        checkEmail: function () {
            var self = this;
            self.validEmail = 'unfilled';
            if (self.email.length > 0) {
                if (validateEmail(self.email)) {
                    self.validEmail = 'loading';
                    if (!self.ajaxReady) return;
                    self.ajaxReady = false;
                    $.ajax({
                        url: '/user/email/' + self.email + '/check',
                        method: 'GET',
                        success: function (data) {
                            // success
                            if (data) {
                                self.validEmail = true;
                                self.emailError = '';
                            }
                            self.ajaxReady = true;
                        },
                        error: function (response) {
                            console.log(response);
                            self.ajaxReady = true;
                            self.validEmail = false;
                            self.emailError = 'Account already exists for that email';
                        }
                    });
                } else {
                    self.validEmail = false;
                    self.emailError = 'Invalid email format - you@example.com';
                }
            }
        },
        checkPassword: function () {
            this.validPassword = 'unfilled';
            if (this.password.length > 0) {
                this.validPassword = (this.password.length >= 6);
            }
        },
        checkName: function () {
            this.validName = this.name.length > 0 ? true : 'unfilled';
        },
        registerNewCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company',
                method: 'POST',
                data: {
                    company_name: self.companyName,
                    name: self.name,
                    email: self.email,
                    password: self.password
                },
                success: function (data) {
                    // success
                    window.location.href = "/dashboard";
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
    }
});
Vue.component('side-menu', {
    name: 'sideMenu',
    el: function () {
        return '#side-menu'
    },
    data: function () {
        return {
            show: false,
            userPopup: false,
            userInitials: '',
            companyName: '',
            finishedCompiling: false,
            expandedSection: ''
        };
    },
    props: ['user'],
    computed: {},
    methods: {
        toggleUserPopup: function() {
            this.userPopup = !this.userPopup;
        },
        expand: function(section) {
            this.expandedSection = (this.expandedSection ===  section) ? '' : section;
        }
    },
    events: {
        'toggle-side-menu': function() {
            this.show = !this.show;
        },
        'hide-side-menu': function() {
            this.show = false;
        }
    },
    ready: function () {
        var self = this;
        $(window).on('resize', _.debounce(function() {
            if($(window).width() > 1670) self.show = false;
        }, 50));

        // To hide popup
        $(document).click(function (event) {
            if (!$(event.target).closest('.user-popup').length && !$(event.target).is('.user-popup')) {
                self.userPopup = false;
            }
        });

        // When user prop is loaded
        self.$watch('user', function (user) {
            // set initials
            var names = user.name.split(' ');
            self.userInitials = names.map(function (name, index) {
                if(index === 0 || index === names.length - 1) return name.charAt(0);
            }).join('');
            // set name
            self.companyName = user.company.name;
            // set flag
            self.finishedCompiling = true;
        });
    }
});
Vue.component('state-selecter', {
    name: 'stateSelecter',
    template: '<select class="state-selecter"><option></option></select>',
    data: function () {
        return {};
    },
    props: ['name', 'listen', 'event', 'default'],
    computed: {},
    methods: {},
    ready: function () {
        var xhr,
            select_state,
            self = this,
            listenEvent = self.listen || 'selected-country';

        $select_state = $(self.$el).selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            placeholder: 'State',
            create: true,
            onChange: function (value) {
                self.name = value;
                var selectedEventName = self.event || 'selected-state';
                vueEventBus.$emit(selectedEventName);
            }
        });

        select_state = $select_state[0].selectize;

        window.select_state = $select_state[0].selectize;


        vueEventBus.$on(listenEvent, function (value) {
            select_state.disable();
            select_state.clearOptions();
            select_state.load(function (callback) {
                // Jump queue
                if (!_.isEmpty(xhr) && xhr.readyState != 4) xhr.abort();
                // Fire req
                xhr = $.ajax({
                    url: '/countries/' + value + '/states',
                    success: function (results) {
                        select_state.enable();
                        callback(results);
                    },
                    error: function () {
                        callback();
                    }
                })
            });
        });

        self.$watch('default', function (state) {
            select_state.createItem(state);
        });
    }
});
Vue.component('team-member-selecter', {
    name: 'teamMemberSelecter',
    template: '<select class="team-member-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.team-member-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Search for Team Member',
            render: {
                option: function(item, escape) {
                    return '<div class="single-name-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-name">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/users/team/members/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
Vue.component('user-projects-selecter', {
    name: 'userProjectsSelecter',
    template: '<select-picker :options="projects" ' +
    '               :name.sync="name"'+
    '               :placeholder="' + "'Pick a Project...'" + '">' +
    '           </select-picker>',
    data: function() {
        return {
            projects: []
        };
    },
    props: ['name'],
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/user/projects',
            method: 'GET',
            success: function (data) {
                // success
                self.projects = _.map(data, function (project) {
                    if (project.name) {
                        project.value = project.id;
                        project.label = strCapitalize(project.name);
                        return project;
                    }
                });
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
});
Vue.component('vendor-connection', {
    name: 'vendorConnection',
    template: '<span v-if="vendor.linked_company" class="vendor-connection {{ vendor.linked_company.connection }}">{{ vendor.linked_company.connection }}</span>',
    props: ['vendor']
});
Vue.component('vendor-selecter', {
    name: 'vendorSelecter',
    template: '<select class="vendor-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['vendor'],
    methods: {
        clearVendor: function () {
            this.vendor = {
                linked_company: {},
                addresses: [],
                bank_accounts: []
            };
        },
        fetchVendor: function(vendorID) {
            $.get('/api/vendors/' + vendorID, function (data) {
                this.vendor = data;
            }.bind(this));
        }
    },
    ready: function () {
        var self = this;
        $('.vendor-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            maxItems: 1,
            create: false,
            placeholder: 'Search for vendor',
            render: {
                option: function (item, escape) {
                    return '<div class="single-vendor-option">' + escape(item.name) + '</div>'
                },
                item: function (item, escape) {
                    return '<div class="selected-vendor">' + escape(item.name) + '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/vendors/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                vueEventBus.$emit('po-submit-selected-vendor');
                value ? self.fetchVendor(value) : self.clearVendor();
            }
        });
    }
});
Vue.component('modal-close-button', {
    name: 'modalClose',
    template: '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>',
    methods: {
        hideModal: function() {
            vueEventBus.$emit('modal-close');
        }
    }
});
var spendingsReport = Vue.extend({
    name: 'SpendingsReport',
    template: '',
    data: function() {
        return {
            currency: '',
            currencyId: 840,
            dateMin: '',
            dateMax:'',
            spendingsData: ''
        };
    },
    props: [],
    computed: {

    },
    methods: {
        fetchSpendingsData: function() {
            $.get(this.dataURL).then(function (data) {
                this.spendingsData = data;
            }.bind(this));
        },
        clearDateRange: function() {
            this.dateMin = '';
            this.dateMax = '';
        }
    },
    events: {},
    mixins: [userCompany],
    ready: function () {
        this.fetchSpendingsData();
        // Use direct watcher because the inputs are in separate, shared
        // components so we can't bind events directly on them
        this.$watch('dataURL', this.fetchSpendingsData);
    }
});

//# sourceMappingURL=dependencies.js.map
