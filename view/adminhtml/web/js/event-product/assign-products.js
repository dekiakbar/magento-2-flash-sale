/**
 * Copyright © (Deki) nooby.dev All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            eventProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;
        $('in_event_products').value = Object.toJSON(eventProducts);

        /**
         * Register Event Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerEventProduct(grid, element, checked) {
            if (checked) {
                if (element.qtyElement) {
                    element.qtyElement.disabled = false;
                    eventProducts.set(element.value, [element.salePriceElement.value, element.qtyElement.value]);
                }
                if (element.salePriceElement) {
                    element.salePriceElement.disabled = false;
                    eventProducts.set(element.value, [element.salePriceElement.value, element.qtyElement.value]);
                }
            } else {
                if (element.qtyElement) {
                    element.qtyElement.disabled = true;
                }
                if (element.salePriceElement) {
                    element.salePriceElement.disabled = true;
                }
                eventProducts.unset(element.value);
            }
            $('in_event_products').value = Object.toJSON(eventProducts);
            grid.reloadParams = {
                'selected_products[]': eventProducts.keys()
            };
        }

        /**
         * Click on product row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function eventProductRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                eventElement = Event.element(event),
                isInputCheckbox = eventElement.tagName === 'INPUT' && eventElement.type === 'checkbox',
                isInputQty = grid.targetElement &&
                    grid.targetElement.tagName === 'INPUT' &&
                    grid.targetElement.name === 'qty',
                isInputSalePrice = grid.targetElement &&
                    grid.targetElement.tagName === 'INPUT' &&
                    grid.targetElement.name === 'flash_sale_price',
                checked = false,
                checkbox = null;

            if (eventElement.tagName === 'LABEL' &&
                trElement.querySelector('#' + eventElement.htmlFor) &&
                trElement.querySelector('#' + eventElement.htmlFor).type === 'checkbox'
            ) {
                event.stopPropagation();
                trElement.querySelector('#' + eventElement.htmlFor).trigger('click');

                return;
            }

            if (trElement && !isInputQty && !isInputSalePrice) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInputCheckbox ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change product qty or price
         *
         * @param {String} event
         */
        function inputChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                eventProducts.set(
                    element.checkboxElement.value,
                    [
                        element.checkboxElement.salePriceElement.value,
                        element.checkboxElement.qtyElement.value
                    ]
                );
                $('in_event_products').value = Object.toJSON(eventProducts);
            }
        }
        /**
         * Initialize event product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function eventProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0],
                inputQty = $(row).querySelector('[name="qty"]'),
                inputSalePrice = $(row).querySelector('[name="flash_sale_price"]');

            if (checkbox && inputQty && inputSalePrice) {
                checkbox.qtyElement = inputQty;
                checkbox.salePriceElement = inputSalePrice;

                inputQty.checkboxElement = checkbox;
                inputQty.disabled = !checkbox.checked;
                Event.observe(inputQty, 'keyup', inputChange);

                inputSalePrice.checkboxElement = checkbox;
                inputSalePrice.disabled = !checkbox.checked;
                inputSalePrice.tabIndex = tabIndex++;
                Event.observe(inputSalePrice, 'keyup', inputChange);
            }
        }

        gridJsObject.rowClickCallback = eventProductRowClick;
        gridJsObject.initRowCallback = eventProductRowInit;
        gridJsObject.checkboxCheckCallback = registerEventProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                eventProductRowInit(gridJsObject, row);
            });
        }
    };
});
