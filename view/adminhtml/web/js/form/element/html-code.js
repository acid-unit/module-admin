// noinspection JSUnusedGlobalSymbols,JSPotentiallyInvalidConstructorUsage,JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable no-undef */

define([
    'Magento_Ui/js/form/element/textarea',
    'jquery',
    'mage/adminhtml/wysiwyg/widget'
], function (
    Textarea,
    $
) {
    'use strict';

    const HTML_ID_PLACEHOLDER = 'HTML_ID_PLACEHOLDER';

    return Textarea.extend({
        defaults: {
            elementTmpl: 'AcidUnit_Admin/form/element/html-code'
        },

        adminConfig: window.acidAdminConfig ? window.acidAdminConfig : {},
        wysiwygInstance: null,
        showHideEditorButtonVisible: null,

        initialize: function () {
            this.showHideEditorButtonVisible = this.adminConfig
                && this.adminConfig['wysiwyg_editor']
                && this.adminConfig['wysiwyg_editor']['enabled']
                && this.adminConfig['wysiwyg_editor']['enabled_for_pagebuilder_html_element'];

            this._super();
            return this;
        },

        /**
         * Click event for Insert Widget Button
         */
        clickInsertWidget: function () {
            return widgetTools.openDialog(
                this.widgetUrl.replace(HTML_ID_PLACEHOLDER, this.uid)
            );
        },

        /**
         * Click event for Insert Image Button
         */
        clickInsertImage: function () {
            // noinspection JSCheckFunctionSignatures
            return MediabrowserUtility.openDialog(
                this.imageUrl.replace(HTML_ID_PLACEHOLDER, this.uid)
            );
        },

        /**
         * Click event for Insert Variable Button
         */
        clickInsertVariable: function () {
            return MagentovariablePlugin.loadChooser(
                this.variableUrl,
                this.uid
            );
        },

        /**
         * Click event to show / hide WYSIWYG editor
         *
         * @param {Object} component
         * @param {Object} event
         */
        clickShowHideEditor: function (component, event) {
            if (this.wysiwygInstance) {
                this.wysiwygInstance.toggle();
            } else {
                window.tinyMCE_GZ = window.tinyMCE_GZ || {};
                window.tinyMCE_GZ.loaded = true;

                require([
                    'jquery',
                    'mage/translate',
                    'mage/adminhtml/events',
                    'mage/adminhtml/wysiwyg/tiny_mce/setup',
                    'mage/adminhtml/wysiwyg/widget'
                ], function (jQuery) {
                    (function (jquery) {
                        jquery.mage.translate.add({
                            'Show / Hide Editor': 'Show / Hide Editor',
                            'Insert Image...': 'Insert Image...',
                            'Insert Media...': 'Insert Media...',
                            'Insert File...': 'Insert File...'
                        });
                    })(jQuery);

                    const id = $(event.currentTarget)
                        .closest('.admin__control-wysiwig')
                        .siblings('.admin__control-textarea')
                        .attr('id');

                    // noinspection JSPotentiallyInvalidConstructorUsage
                    this.wysiwygInstance = new wysiwygSetup(id);
                    this.wysiwygInstance.setup('exact');
                }.bind(this));
            }

            $(event.currentTarget).siblings('button').toggle();
        }
    });
});
