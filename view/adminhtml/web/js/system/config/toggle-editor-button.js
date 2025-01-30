// noinspection JSUnresolvedReference

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable no-undef */

define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        $(element).on('click', function () {
            window.tinyMCE_GZ = window.tinyMCE_GZ || {};
            window.tinyMCE_GZ.loaded = true;

            require([
                'jquery',
                'mage/adminhtml/events',
                'mage/adminhtml/wysiwyg/tiny_mce/setup',
                'mage/adminhtml/wysiwyg/widget'
            ], function (jquery) {
                const targetElement = jquery(`#${config.targetField}`);

                if (!targetElement.data('editor-instance')) {
                    // noinspection JSPotentiallyInvalidConstructorUsage
                    targetElement.data('editor-instance', new wysiwygSetup(
                        config.targetField,
                        config.editorConfig
                    ));
                    targetElement.data('editor-instance').setup('exact');
                } else {
                    targetElement.data('editor-instance').toggle();
                }
            });
        });
    };
});
