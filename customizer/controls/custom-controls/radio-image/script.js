jQuery( document ).ready( function() {

    // Use buttonset() for radio images.
    jQuery( '.customize-control-radio-image .buttonset' ).buttonset();

    jQuery.hestiaTab = {

        'init': function () {
            var firstTabControlName = this.initTabDefault();
            this.handleClick(firstTabControlName);
        },

        'handleClick':function (parentTabName) {
            var self = this;
            jQuery( '.customize-control-radio-image .buttonset.customizer-tab input:radio' ).click( function() {

                var controlsToShow = jQuery(this).data('controls');

                var controlsToShowArray = controlsToShow.split(',');
                var activeTabControlName = jQuery(this).parent().parent().attr('id').replace('customize-control-','');
                var currentSection = jQuery(this).parent().parent().parent();
                var allControlsToShow = controlsToShowArray;


                controlsToShowArray.forEach(function(controlId){
                    if( typeof wp.customize.control(controlId) !== 'undefined' ){
                        if( typeof wp.customize.control(controlId).params.is_tab !== 'undefined'){
                            var grandChildToShow = self.getControlsToShow(controlId);
                            allControlsToShow = allControlsToShow.concat(grandChildToShow);
                        }
                    }
                });

                allControlsToShow.push(activeTabControlName);
                allControlsToShow.push(parentTabName);

                self.hideControlExcept(currentSection, allControlsToShow);
            } );
        },

        'initTabDefault': function () {

            var section = jQuery('ul.accordion-section');

            //First tab control in section
            var firstTabControl = section.find('.customizer-tab').first();
            var currentSection = firstTabControl.parent().parent();
            var firstTabControlName = firstTabControl.parent().attr('id').replace('customize-control-','');
            var firstTab = firstTabControl.children('input').first();
            firstTabControl.children('label').removeClass('ui-state-active');
            firstTabControl.children('label').first().addClass('ui-state-active');
            var controlsToShow = firstTab.data('controls');
            var controlsToShowArray = controlsToShow.split(',');
            var allControlsToShow = controlsToShowArray;

            /**
             * Beside the controls that are defined in tab, we must check if there is another tab in this tab and
             * to show its controls.
             */
            var self = this;
            controlsToShowArray.forEach(function(controlId){
                if ( typeof wp.customize.control( controlId ) !== 'undefined' ) {
                    var is_tab = wp.customize.control(controlId).params.is_tab;
                    if( typeof is_tab !== 'undefined' && is_tab === true ){
                        var grandChildToShow = self.getControlsToShow(controlId);
                        allControlsToShow = allControlsToShow.concat(grandChildToShow);
                    }
                }
            });
            allControlsToShow.push(firstTabControlName);


            this.hideControlExcept(currentSection, allControlsToShow);
            return firstTabControlName;

        },
        'getControlsToShow': function (controlId) {
            var firstTabControl = jQuery('#customize-control-'+controlId).find('.customizer-tab').first();
            var firstTab = firstTabControl.children('input:checked');
            var controlsToShow = firstTab.data('controls');
            if( typeof controlsToShow !== 'undefined' ){
                return controlsToShow.split(',');
            }
            return [];
        },
        'hideControlExcept':function (section, controls) {
            jQuery(section).find('.customize-control').hide();
            for( var i in controls ){
                if( controls[i] === 'widgets' ){
                    jQuery( section ).children( 'li[class*="widget"]' ).css( 'display', 'list-item' );
                } else {
                    jQuery(section).find('#customize-control-'+controls[i]).show();
                }
            }
        }
    };
    // jQuery.hestiaTab.init();

} );