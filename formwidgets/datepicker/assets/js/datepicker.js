/*
 * DatePicker plugin
 * 
 * Data attributes:
 * - data-control="datepicker" - enables the plugin on an element
 * - data-min-date="value" - minimum date to allow
 * - data-max-date="value" - maximum date to allow
 * - data-year-range="value" - range of years to display
 *
 * JavaScript API:
 * $('a#someElement').datePicker({ option: 'value' })
 *
 * Dependences:
 * - Pikaday plugin (pikaday.js)
 * - Pikaday jQuery addon (pikaday.jquery.js)
 */

+function ($) {
    "use strict";

    // DATEPICKER CLASS DEFINITION
    // ============================

    var DatePicker = function (element, options) {
        var self = this
        this.options = options
        this.$el = $(element)
        this.$input = this.$el.find('input.date-input:first')
        this.$value = this.$el.find('input.date-value:first')

        // Init

        var $form = this.$el.closest('form'),
            changeMonitor = $form.data('oc.changeMonitor')

        if (changeMonitor !== undefined)
            changeMonitor.pause()

        var that = this
        var persianToEnglish = function(value) {
            var newValue = "";
            for (var i = 0; i < value.length; i++) {
                var ch = value.charCodeAt(i);
                if (ch >= 1776 && ch <= 1785) // For Persian digits.
                {
                    var newChar = ch - 1728;
                    newValue = newValue + String.fromCharCode(newChar);
                }
                else if (ch >= 1632 && ch <= 1641) // For Arabic & Unix digits.
                {
                    var newChar = ch - 1584;
                    newValue = newValue + String.fromCharCode(newChar);
                }
                else
                    newValue = newValue + String.fromCharCode(ch);
            }
            return newValue;
        }
        var calendar = $.calendars.instance('persian', 'fa')
        this.$input.val(persianToEnglish(this.$input.val()))
        var pDate = this.$input.val().split('-')
        var date = new Date();
        if(calendar.isValid(parseInt(pDate[0]),parseInt(pDate[1]),parseInt(pDate[2]))){
            date = calendar.newDate(parseInt(pDate[0]),parseInt(pDate[1]),parseInt(pDate[2])).toJSDate()
        }
        that.$value.val($.calendars.instance().fromJSDate(date).formatDate('yyyy-mm-dd'))
        this.$input.calendarsPicker({
            calendar: calendar,
            minDate: calendar.fromJSDate(new Date(options.minDate)),
            maxDate: calendar.fromJSDate(new Date(options.maxDate)),
            defaultDate: calendar.fromJSDate(date),
            dateFormat: 'yyyy-mm-dd',
            onSelect: function(dates) {
                var date = $.calendars.instance().fromJSDate(dates[0].toJSDate())
                that.$value.val(date.formatDate('yyyy-mm-dd'))
            },
            selectDefaultDate: true
        })
        //this.$input.pikaday({
        //    minDate: new Date(options.minDate),
        //    maxDate: new Date(options.maxDate),
        //    yearRange: options.yearRange,
        //    setDefaultDate: moment(this.$input.val()).toDate(),
        //    onOpen: function() {
        //        var $field = $(this._o.trigger)
        //
        //        $(this.el).css({
        //            left: 'auto',
        //            right: $(window).width() - $field.offset().left - $field.outerWidth()
        //        })
        //    }
        //})

        if (changeMonitor !== undefined)
            changeMonitor.resume()
    }

    DatePicker.DEFAULTS = {
        minDate: '2000-01-01',
        maxDate: '2020-12-31',
        yearRange: 10
    }

    // DATEPICKER PLUGIN DEFINITION
    // ============================

    var old = $.fn.datePicker

    $.fn.datePicker = function (option) {
        var args = Array.prototype.slice.call(arguments, 1)
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('oc.datepicker')
            var options = $.extend({}, DatePicker.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.datepicker', (data = new DatePicker(this, options)))
            else if (typeof option == 'string') data[option].apply(data, args)
        })
    }

    $.fn.datePicker.Constructor = DatePicker

    // DATEPICKER NO CONFLICT
    // =================

    $.fn.datePicker.noConflict = function () {
        $.fn.datePicker = old
        return this
    }

    // DATEPICKER DATA-API
    // ===============

    $(document).on('render', function () {
        $('[data-control="datepicker"]').datePicker()
    });

}(window.jQuery);