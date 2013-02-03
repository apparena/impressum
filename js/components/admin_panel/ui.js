'use strict';

define(
    [
        'components/flight/lib/component'
    ],

    function(defineComponent) {

        return defineComponent(admin_panel);

        function admin_panel() {

            this.defaultAttrs({
                selectedClass: 'selected',
                selectionChangedEvent: 'uiFolderSelectionChanged',
                adminPanelButton: '#admin_panel_new'
            });

            // Opens up the admin panel
            this.openPanel = function(ev, data) {
                //this.trigger('uiMailItemsRequested', {folder: data.selectedIds[0]});
                console.log('Open my admin panel now');
                this.trigger('adminOpenEvent');
            }

            this.after('initialize', function() {
                this.on('click', { 'adminPanelButton': this.openPanel });
            });
        }
    }
);