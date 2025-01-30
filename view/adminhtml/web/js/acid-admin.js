// noinspection DuplicatedCode

/**
 * Copyright © Acid Unit (https://acid.7prism.com). All rights reserved.
 * See LICENSE file for license details.
 */

/* eslint-disable max-depth, max-nested-callbacks */

require([
    'jquery',
    'Magento_Ui/js/lib/view/utils/dom-observer',
    'domReady!'
], function (
    $,
    domObserver
) {
    'use strict';

    const scrollPositionForButtonToHide = 300,
        highlightTime = 2000,
        scrollToTime = 400,
        map = {
            gtm: {
                href: 'google_google_tag_manager-head',
                path: 'admin/system_config/edit/section/google',
                text: 'Google Tag Manager'
            },
            wysiwygEditor: {
                href: 'row_cms_wysiwyg_enabled_for_pagebuilder_html_element',
                path: 'admin/system_config/edit/section/cms',
                text: 'WYSIWYG Editor'
            },
            layeredNavigation: {
                href: 'catalog_layered_navigation_acid_unit-head',
                path: 'admin/system_config/edit/section/catalog',
                text: 'Layered Navigation'
            }
        };

    /**
     * @param {HTMLElement} element
     * @return {boolean}
     */
    function isElementInViewport(element) {
        const rect = element.getBoundingClientRect(),
            viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight),
            viewWidth = Math.max(document.documentElement.clientWidth, window.innerWidth);

        return !(
            rect.bottom <= 0 ||
            rect.top - viewHeight >= 0 ||
            rect.left + rect.width <= 0 ||
            rect.right - rect.width >= viewWidth
        );
    }

    /**
     * @param {HTMLElement} element
     */
    function highlight(element) {
        element.classList.add('acid-highlight');

        setTimeout(() => {
            element.classList.remove('acid-highlight');
        }, highlightTime);
    }

    /**
     * @param {HTMLElement} element
     * @param {string} id
     */
    function createButton(element, id) {
        const button = document.createElement('button'),
            buttonWrapper = document.createElement('div');

        let buttonText = '';

        Object.keys(map).forEach(key => {
            if (map[key].href === id) {
                buttonText = map[key].text;
            }
        });

        buttonWrapper.appendChild(button);
        buttonWrapper.classList.add('acid-button-wrapper');
        button.classList.add('acid-button');
        button.classList.add('acid-button-visible');
        button.innerHTML = '🧪&nbsp;' + buttonText + '👇';

        document.body.appendChild(buttonWrapper);

        // scroll to highlighted element when the button is clicked
        button.addEventListener('click', () => {
            if ($(element).is(':hidden')) {
                element.closest('fieldset.admin__collapsible-block').style.display = 'block';
            }

            element.scrollIntoView({behavior: 'smooth', block: 'center'});
            button.classList.replace('acid-button-visible', 'acid-button-hidden');

            setTimeout(() => {
                highlight(element);
                button.remove();
            }, scrollToTime);
        });

        // hide scroll to button when page is scrolled for more than 300px
        $(document).on('scroll.button', () => {
            const scroll = document.body.getBoundingClientRect().top * -1;

            if (scroll >= scrollPositionForButtonToHide) {
                button.classList.replace('acid-button-visible', 'acid-button-hidden');

                setTimeout(() => {
                    button.remove();
                }, scrollToTime);

                $(document).off('scroll.button');
            }
        });

        // hide scroll to button when admin collapsible block is clicked
        document.querySelectorAll('.admin__collapsible-block').forEach(collapsible => {
            collapsible.addEventListener('click', () => {
                button.classList.replace('acid-button-visible', 'acid-button-hidden');

                setTimeout(() => {
                    button.remove();
                }, scrollToTime);
            });
        });
    }

    /**
     * @param {HTMLElement} element
     * @param {string} id
     */
    function expandCollapsible(element, id) {
        element.closest('fieldset.admin__collapsible-block').style.display = 'block';

        if (element.style.display === 'none') {
            return;
        }

        if (isElementInViewport(element)) {
            highlight(element);
        } else {
            createButton(element, id);
        }
    }

    /**
     * @param {HTMLElement} element
     * @param {string} id
     */
    function handleElement(element, id) {
        if (isElementInViewport(element)) {
            highlight(element);
        } else if ($(element).is(':hidden')) {
            expandCollapsible(element, id);
        }
    }

    function initHighlighter() {
        const acidLinks = document.querySelectorAll('.admin__menu li.item-acid-menu [class*=item-acid-menu] a'),
            urlParams = new URLSearchParams(document.location.search),
            url = new URL(window.location.href),
            id = urlParams.get('h');

        url.searchParams.delete('h');
        window.history.pushState({}, document.title, url);

        document.querySelectorAll('a').forEach(link => {
            link.setAttribute('href', link.getAttribute('href').split('?h=')[0]);
        });

        if (id) {
            const element = document.querySelector('#' + id);

            if (element) {
                handleElement(element, id);
            } else {
                domObserver.get('#' + id, () => {
                    domObserver.off('#' + id);
                    handleElement(element, id);
                });
            }
        }

        acidLinks.forEach(link => {
            const href = link.getAttribute('href');

            Object.keys(map).forEach(key => {
                if (href.includes(map[key].path)) {
                    link.setAttribute('href', href + '?h=' + map[key].href);
                }
            });
        });
    }

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

    window.addEventListener('load', function () {
        initHighlighter();
        addExternalUrl();
    });
});
