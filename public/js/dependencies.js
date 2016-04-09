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
        format: "dd/mm/yyyy",
        startDate: 'today',
        language: 'en'      // TODO ::: Change according to client Lang
    });
});
  
$(document).ready(function() {

    // Dropzone
    Dropzone.options.addPhotosForm = {
        paramName: 'photo',                             // name of the input, in controller: $request->file('photo')
        maxFileSize: 3,                                 // File size in Mb
        acceptedFiles: '.jpg, .jpeg, .png, .bmp',       // file formats accepted
    };
});
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
$(document).ready(function () {
    $.noty.themes.customTheme = {
        name    : 'customTheme',
        helpers : {
            borderFix: function() {
                if(this.options.dismissQueue) {
                    var selector = this.options.layout.container.selector + ' ' + this.options.layout.parent.selector;
                    switch(this.options.layout.name) {
                        case 'top':
                        case 'topCenter':
                        case 'topLeft':
                        case 'topRight':
                        case 'bottomCenter':
                            $(selector).css({
                                borderRadius: '0',
                                width: '100%'
                            });
                            $(selector).first().css({
                                'border-top-left-radius': '0',
                                'border-top-right-radius': '0',
                                width: '100%'
                            });
                            $(selector).last().css({'border-bottom-left-radius': '0', 'border-bottom-right-radius': '0'});
                            break;
                        case 'bottomLeft':
                        case 'bottomRight':
                        case 'center':
                        case 'centerLeft':
                        case 'centerRight':
                        case 'inline':
                        case 'bottom':
                        default:
                            break;
                    }
                }
            }
        },
        modal   : {
            css: {
                position       : 'fixed',
                width          : '100%',
                height         : '100%',
                backgroundColor: '#000',
                zIndex         : 10000,
                opacity        : 0.6,
                display        : 'none',
                left           : 0,
                top            : 0
            }
        },
        style   : function() {

            this.$bar.css({
                overflow  : 'hidden'
            });

            this.$message.css({
                fontSize  : '16px',
                lineHeight: '16px',
                textAlign : 'center',
                padding   : '8px 10px 9px',
                width     : 'auto',
                position  : 'relative',
                height: '60px',
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center'
            });

            this.$closeButton.css({
                position  : 'absolute',
                top       : 4, right: 4,
                width     : 10, height: 10,
                background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",
                display   : 'none',
                cursor    : 'pointer'
            });

            this.$buttons.css({
                padding        : 5,
                textAlign      : 'right',
                borderTop      : '1px solid #ccc',
                backgroundColor: '#fff'
            });

            this.$buttons.find('button').css({
                marginLeft: 5
            });

            this.$buttons.find('button:first').css({
                marginLeft: 0
            });

            this.$bar.on({
                mouseenter: function() {
                    $(this).find('.noty_close').stop().fadeTo('normal', 1);
                },
                mouseleave: function() {
                    $(this).find('.noty_close').stop().fadeTo('normal', 0);
                }
            });

            switch(this.options.layout.name) {
                case 'top':
                case 'topCenter':
                case 'center':
                case 'bottomCenter':
                    this.$bar.css({
                        borderRadius: '0',
                        borderTop      : '2px solid #eee',
                        boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                    break;
                case 'inline':
                case 'topLeft':
                case 'topRight':
                case 'bottomLeft':
                case 'bottomRight':
                case 'centerLeft':
                case 'centerRight':
                case 'bottom':
                default:
                    this.$bar.css({
                        border   : '2px solid #eee',
                        boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                    break;
            }

            switch(this.options.type) {
                case 'alert':
                case 'notification':
                    this.$bar.css({backgroundColor: '#A1A4AA', borderColor: '#989898', color: '#FFF'});
                    break;
                case 'warning':
                    this.$bar.css({backgroundColor: '#F1C40F', borderColor: '#F39C12', color: '#FFF'});
                    this.$buttons.css({borderTop: '1px solid #FFC237'});
                    break;
                case 'error':
                    this.$bar.css({
                        backgroundColor: '#E74C3C', borderColor: '#C0392B', color: '#FFFFFF'
                    });
                    this.$buttons.css({borderTop: '1px solid darkred'});
                    break;
                case 'information':
                    this.$bar.css({backgroundColor: '#3498DB', borderColor: '#2980B9', color: '#FFFFFF'});
                    this.$buttons.css({borderTop: '1px solid #0B90C4'});
                    break;
                case 'success':
                    this.$bar.css({backgroundColor: '#2ECC71', borderColor: '#27AE60', color: '#FFF'});
                    this.$buttons.css({borderTop: '1px solid #50C24E'});
                    break;
                default:
                    this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'});
                    break;
            }
        },
        callback: {
            onShow : function() {
                $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
            },
            onClose: function() {
                $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
            }
        }
    };
});


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
Vue.transition('fade', {
    enterClass: 'fadeIn',
    leaveClass: 'fadeOut'
});

Vue.transition('slide', {
    enterClass: 'slideInLeft',
    leaveClass: 'slideOutLeft'
});

Vue.transition('fade-slide', {
    enterClass: 'fadeInDown',
    leaveClass: 'fadeOutUp'
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

        $(window).on('resize', _.debounce(optimizeTabWidths, 50, {
            leading: true
        }));

    }
});
Vue.directive('dropdown-toggle', {
    twoWay: true,
    bind: function () {

        this.className = $(this.el).attr('class').replace(' ', '.');

        var selector = '.' + this.className + ' button';

        $(document).on('click.' + this.className, selector, function (e) {
            e.stopPropagation();
            this.set(true);
            $(document).on('click.checkHideDropdown', function (event) {
                if (!$(event.target).closest('.dropdown-container').length && !$(event.target).is('.dropdown-container')) {
                    this.set(false);
                    $(document).off('click.checkHideDropdown');
                }
            }.bind(this))
        }.bind(this));
    },
    unbind: function () {
        $(document).off('click.' + this.className)
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
Vue.filter('capitalize', function (str) {
    if(str) return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
});
Vue.filter('chunk', function (array, length) {
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
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").fromNow();
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
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD MMM YYYY');
    }
    return value;
});

Vue.filter('easyDateModel', {
    // model -> view
    // formats the value when updating the input element.
    read: function (value) {
        if (value) {
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
    if (val) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" "))) + '...';
        return trimmedString
    }

    return val;
});
Vue.filter('numberFormat', function (val) {
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
        return parseInt(val.replace(/[^0-9.]/g, ""))
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
Vue.component('add-item-modal', {
    name: 'addItemModal',
    template: '<div class="modal-item-add" v-show="visible">' +
    '<form class="form-item-add">' +
    '   <div class="form-group">' +
    '       <label for="">SKU</label>' +
    '       <input class="form-control" type="text" placeholder="SKU" v-model="sku">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="">Brand</label>' +
    '       <input class="form-control" type="text" placeholder="Brand" v-model="brand">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="" class="required">Name</label>' +
    '       <input class="form-control" type="text" placeholder="Name" v-model="name">' +
    '   </div>' +
    '   <div class="form-group">' +
    '       <label for="" class="required">Specification</label>' +
    '       <textarea class="autosize form-control" v-model="specification"></textarea>' +
    '   </div>' +
        '<input class="item-photos-upload" type="file" name="item_photos[]" multiple="multiple">' +
    '   <button type="button"' +
    '           class="btn btn-outline-green"' +
    '           @click.prevent="submitAddItemForm"' +
    '           :disabled="! canSubmitForm"' +
    '   >' +
    '       Save Item' +
    '   </button>' +
    '</form>' +
    '</div>',
    data: function() {
        return {
            existingBrands: [],
            existingNames: [],
            sku: '',
            brand: '',
            name: '',
            specification: '',
            uploadedfiles: []
        };
    },
    props: ['visible'],
    computed: {
        canSubmitForm: function() {
            return this.name && this.specification
        }
    },
    methods: {
        submitAddItemForm: function() {

        }
    },
    events: {

    },
    ready: function() {

        

        // File Upload
        $('.item-photos-upload').fileupload({
            autoUpload: false
        }).on('fileuploadadd', function (e, data) {
            _.forEach(data.files, function(file) {
                self.uploadedFiles.push(file);
            });
        });
    }
});
Vue.component('form-errors', {
    template: '<div class="validation-errors" v-show="errors.length > 0">' +
    '<h5 class="errors-heading">{{ errorHeading }}</h5>' +
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
    computed: {
        errorHeading: function() {
            if(this.errors.length > 1) {
                return 'There were '  + errors.length + ' errors'
            } else if (this.errors.length == 1){
                return 'There was 1 error'
            }
        }
    },
    events: {
        'new-errors': function(errors) {
            var self = this;
            var newErrors = [];
            _.forEach(errors, function (error) {
                newErrors.push(error);
            });
            self.errors = newErrors;
        },
        'clear-errors': function() {
            this.errors = [];
        }
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
                        url: '/api/user/email/' + self.email + '/check',
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
                url: '/api/company',
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
Vue.component('select-picker', {
    template:'<select v-model="name" class="themed-select" @change="function">' +
    '<option value="{{ option.value }}" v-for="option in options">{{ option.label }}</option>' +
    '</select>',
    name: 'selectpicker',
    props: ['options', 'name', 'function'],
    ready: function() {

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
})
Vue.component('select-type', {
    name: 'selectType',
    template: '<select class="select-type" v-model="name">' +
    '               <option value="{{ option.value }}" v-for="option in options">{{ option.label }}</option>' +'' +
    '          </select>',
    data: function() {
        return {
            'selectize': {}
        };
    },
    props: [
        'options',
        'name',
        'create',
        'unique',
        'placeholder'
    ],
    ready: function() {

        var unique = this.unique || true,
            create = this.create || true,
            placeholder = this.placeholder | 'Type to select...';

        this.selectize = $(this.$el).selectize({
            create: create,
            sortField: 'text',
            placeholder: placeholder,
            createFilter: function(input) {
                input = input.toLowerCase();
                var optionsArray = $.map(unique.options, function(value) {
                    return [value];
                });
                var unmatched = true;
                _.forEach(optionsArray, function (option) {
                    if((option.text).toLowerCase() === input) {
                        unmatched = false;
                    }
                });
                return unmatched;   // true if unmatched (ie. new) value
            }
        })[0].selectize;

        // TODO :: Add ability to re-render when options changes
        //      - Maybe define options on selectize and render options / item through plugin (instead of Vue)
        //      - Call clearOption()?
        //      - Clear Cache? Some bug, unknown if fixed
    },
    beforeDestroy: function() {
        this.selectize.destroy();   // TODO :: Check if valid & necessary
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
            finishedCompiling: false
        };
    },
    props: ['user'],
    computed: {},
    methods: {
        toggleUserPopup: function() {
            this.userPopup = !this.userPopup;
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
//# sourceMappingURL=dependencies.js.map
