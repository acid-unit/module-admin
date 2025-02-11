/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

require([
    'jquery',
    'domReady!'
], function (
    $
) {
    'use strict';

    function addExternalUrl() {
        const menu = document.querySelector('.admin__menu li.item-acid-menu > .submenu > ul[role="menu"]'),
            li = document.createElement('li'),
            link = document.createElement('a');

        li.appendChild(link);
        li.classList.add('level-1');
        li.classList.add('acid-website-url');
        link.innerText = 'https://acid.7prism.com';
        link.href = 'https://acid.7prism.com';
        link.target = '_blank';

        menu.appendChild(li);
    }

    function toggleRootItemVisibility() {
        const item = document.querySelector('#menu-acidunit-core-acid-menu'),
            isHidden = window.acidAdminConfig ? window.acidAdminConfig['root_menu_item_hidden'] : false;

        if (!isHidden) {
            item.style.display = 'block';
        }
    }

    window.addEventListener('load', function () {
        toggleRootItemVisibility();
        addExternalUrl();
    });
});
