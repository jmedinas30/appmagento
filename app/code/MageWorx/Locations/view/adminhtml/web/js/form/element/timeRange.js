/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'ko',
    'underscore',
    'mageUtils',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract',
    'uiLayout'
], function (ko, _, utils, registry, Abstract, layout) {
    'use strict';

    var inputNode = {
        template: '${ $.$data.template }',
        provider: '${ $.$data.provider }',
        parent: '${ $.$data.parentName }',
        component: 'Magento_Ui/js/form/element/abstract',
        name: '${ $.$data.index }',
        sortOrder: {
            after: '${ $.$data.name }'
        },
        dataScope: '${ $.$data.customEntry }',
        customScope: '${ $.$data.customScope }',
        displayArea: 'body',
        label: '${ $.$data.label }'
    };

    return Abstract.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }',
            elementTmpl: 'MageWorx_Locations/timeRange',
            caption: '',
            options: []
        },

        initInput: function () {
            layout([utils.template(inputNode, this)]);

            return this;
        },

        /**
         * init observable
         * @returns {exports}
         */
        initObservable: function () {
            this._super();

            this.nameFromHour = this.inputName  + '[from][hour]';
            this.nameFromMinute = this.inputName  + '[from][minute]';
            this.nameFromDayPart = this.inputName  + '[from][day_part]';
            this.nameToHour = this.inputName  + '[to][hour]';
            this.nameToMinute = this.inputName  + '[to][minute]';
            this.nameToDayPart = this.inputName  + '[to][day_part]';

            this.nameLunchFromHour = this.inputName  + '[lunch_from][hour]';
            this.nameLunchFromMinute = this.inputName  + '[lunch_from][minute]';
            this.nameLunchFromDayPart = this.inputName  + '[lunch_from][day_part]';
            this.nameLunchToHour = this.inputName  + '[lunch_to][hour]';
            this.nameLunchToMinute = this.inputName  + '[lunch_to][minute]';
            this.nameLunchToDayPart = this.inputName  + '[lunch_to][day_part]';

            this.observe(
                'fromHour fromMinute fromDayPart toHour toMinute toDayPart '
                + 'lunchFromHour lunchFromMinute lunchFromDayPart lunchToHour lunchToMinute lunchToDayPart'
            );

            var self = this;

            this.fromHour.subscribe(function (value) {
                if (typeof self.value().from === 'undefined') {
                    self.value({
                        'from': {'hour': '1', 'minute':'00', 'day_part':'am'},
                        'to': {'hour': '1', 'minute':'00', 'day_part':'am'},
                        'off': '0',
                        'lunch_from': {'hour': '1', 'minute':'00', 'day_part':'am'},
                        'lunch_to': {'hour': '1', 'minute':'00', 'day_part':'am'},
                        'has_lunch_time': '0'
                    });
                }

                self.value().from.hour = value;
                self.source.set(self.dataScope, self.value());
            });

            this.fromMinute.subscribe(function (value) {
                self.value().from.minute = value;
                self.source.set(self.dataScope, self.value());
            });

            this.fromDayPart.subscribe(function (value) {
                self.value().from.day_part = value;
                self.source.set(self.dataScope, self.value());
            });

            this.toHour.subscribe(function (value) {
                self.value().to.hour = value;
                self.source.set(self.dataScope, self.value());
            });

            this.toMinute.subscribe(function (value) {
                self.value().to.minute = value;
                self.source.set(self.dataScope, self.value());
            });

            this.toDayPart.subscribe(function (value) {
                self.value().to.day_part = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchFromHour.subscribe(function (value) {
                self.value().lunch_from.hour = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchFromMinute.subscribe(function (value) {
                self.value().lunch_from.minute = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchFromDayPart.subscribe(function (value) {
                self.value().lunch_from.day_part = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchToHour.subscribe(function (value) {
                self.value().lunch_to.hour = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchToMinute.subscribe(function (value) {
                self.value().lunch_to.minute = value;
                self.source.set(self.dataScope, self.value());
            });

            this.lunchToDayPart.subscribe(function (value) {
                self.value().lunch_to.day_part = value;
                self.source.set(self.dataScope, self.value());
            });

            return this;
        },

        setInitialValue: function () {
            this._super();

            if (typeof this.value().from !== 'undefined') {
                this.fromHour(this.value().from.hour);
                this.fromMinute(this.value().from.minute);
                this.fromDayPart(this.value().from.day_part);
            } else {
                this.fromHour('1');
                this.fromMinute('00');
                this.fromDayPart('am');
            }

            if (typeof this.value().to !== 'undefined') {
                this.toHour(this.value().to.hour);
                this.toMinute(this.value().to.minute);
                this.toDayPart(this.value().to.day_part);
            } else {
                this.toHour('1');
                this.toMinute('00');
                this.toDayPart('am');
            }

            if (typeof this.value().lunch_from !== 'undefined') {
                this.lunchFromHour(this.value().lunch_from.hour);
                this.lunchFromMinute(this.value().lunch_from.minute);
                this.lunchFromDayPart(this.value().lunch_from.day_part);
            } else {
                this.lunchFromHour('1');
                this.lunchFromMinute('00');
                this.lunchFromDayPart('am');
            }

            if (typeof this.value().lunch_to !== 'undefined') {
                this.lunchToHour(this.value().lunch_to.hour);
                this.lunchToMinute(this.value().lunch_to.minute);
                this.lunchToDayPart(this.value().lunch_to.day_part);
            } else {
                this.lunchToHour('1');
                this.lunchToMinute('00');
                this.lunchToDayPart('am');
            }

            return this;
        }
    });
});
