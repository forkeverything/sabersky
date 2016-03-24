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
        language: 'id'
    });
});
$(document).ready(function() {
    // Dropzone
    Dropzone.options.addPhotosForm = {
        paramName: 'photo',                             // name of the input, in controller: $request->file('photo')
        maxFileSize: 3,                                 // File size in Mb
        acceptedFiles: '.jpg, .jpeg, .png, .bmp',       // file formats accepted
    }
});
$(document).ready(function() {
    $(".fancybox").fancybox();
});
(function () {
    $('#purchase-requests-add .input-item-photos').fileinput({
        'showUpload': false,
        'allowedFileExtensions': ['jpg', 'gif', 'png'],
        'showRemove': false,
        'showCaption': false,
        'previewSettings': {
            image: {width: "120px", height: "120px"}
        },
        'browseLabel': 'Photo',
        'browseIcon': '<i class="fa fa-plus"></i> &nbsp;',
        'browseClass': 'btn btn-outline-grey',
        'layoutTemplates': {
            preview: '<div class="file-preview {class}">\n' +
            '    <div class="close fileinput-remove">Clear</div>\n' +
            '    <div class="{dropClass}">\n' +
            '    <div class="file-preview-thumbnails">\n' +
            '    </div>\n' +
            '    <div class="clearfix"></div>' +
            '    <div class="file-preview-status text-center text-success"></div>\n' +
            '    <div class="kv-fileinput-error"></div>\n' +
            '    </div>\n' +
            '</div>'
        }
    });
})();

(function () {
    var uploadUrl = 'http:' + $('#form-item-photo').attr('action');
    var $input = $('#purchase-request-single .input-item-photos');
    $input.fileinput({
        uploadUrl: uploadUrl,
        uploadAsync: true,
        allowedFileExtensions: ['jpg', 'gif', 'png'],
        showRemove: false,
        showCaption: false,
        showPreview: false,
        showCancel: false,
        showUpload: false,
        browseIcon: '<i class="fa fa-plus"></i> &nbsp;',
        browseClass: 'btn btn-outline-grey',
        browseLabel: 'Photo'
    }).on("filebatchselected", function (event, files) {
        $input.fileinput("upload");
    }).on('filebatchuploadcomplete', function (event, files, extra) {
        location.reload();
    });
})();

$(document).ready(function () {
    // Moment JS
    moment.locale('id'); // 'en';
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

Vue.directive('collapse-tabs', {
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

            var pickedChild = $(children[count - 1]);
            if (pickedChild.hasClass('active')) pickedChild = $(children[count - 2]);   // We don't pick active kids
            pickedChild.prependTo(collapsedBox);

            bindClick($(children[count - 1]));
            tabsHeight = $tabs.innerHeight();
            if (tabsHeight >= 50) popChild();
        }

        // Click Handler
        function clickHandler() {
            popChild();
            $(this).insertBefore($tabs.children('li:last-child'));
            unbindClick($(this));

            // Remove all active list items (uncollapsed and collapsed)
            $tabs.children('li').removeClass('active');
            $tabs.children('li').children('ul').children('li').removeClass('active');

            $(this).children('a').tab('show');
        }

        // Bind click Event
        function bindClick($el) {
            $el.on('click', clickHandler);
        }

        function unbindClick($el) {
            $el.off('click', clickHandler);
        }


        // Function that auto-collapse and uncollapses based on tab nav height
        function autocollapse() {

            var tabsHeight = $tabs.innerHeight();

            // Stacks on stack?
            if (tabsHeight >= 50) {
                popChild();
                setTimeout(autocollapse, 150);  // Sometimes things happen too quick - let's recalibrate at the end
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
                                unbindClick($(collapsedItems[0]));
                                collapsedItemWidths.shift();
                                console.log(collapsedItemWidths);

                                adjustor();
                            } else {
                                console.log('element not removed');
                            }

                            setTimeout(autocollapse, 100);
                        }
                    })();
                }

            }

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
        $(window).on('resize' + self.eventName, autocollapse);
    },
    unbind: function () {
        // unbind to from window resize
        var self = this;
        $(window).off(self.eventName);
    }
});


Vue.filter('date', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD/MM/YYYY');
    }
    return value;
});

Vue.filter('easyDate', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD MMMM YYYY');
    }
    return value;
});

Vue.filter('diffHuman', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").fromNow();
    }
    return value;
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

Vue.filter('limitString', function (val, limit) {
    if (val) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" "))) + '...';
        return trimmedString
    }

    return val;
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

Vue.filter('capitalize', function (str) {
    if(str) return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
});

Vue.component('form-errors', {
    data: function () {
        return {
            errors: []
        }
    },
    template: '<ul ' +
    'class="alert alert-danger list-unstyled"' +
    'v-show="errors.length > 0"' +
    '>' +
    '<li v-for="error in errors">{{ error }}</li>' +
    '</ul>',
    events: {
        'new-errors': function(errors) {
            var self = this;
            var newErrors = [];
            _.forEach(errors, function (error) {
                newErrors.push(error);
            });
            self.errors = newErrors;
            setTimeout(function () {
                self.errors = [];
            }, 3500);
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
//# sourceMappingURL=setup.js.map
