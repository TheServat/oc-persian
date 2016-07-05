/*
 * Date time converter.
 * See moment.js for format options.
 * http://momentjs.com/docs/#/displaying/format/
 *
 * Usage:
 *
 * <time
 *      data-datetime-control
 *      datetime="2014-11-19 01:21:57"
 *      data-format="dddd Do [o]f MMMM YYYY hh:mm:ss A"
 *      data-timezone="Australia/Sydney"
 *      data-locale="en-au">This text will be replaced</time>
 *
 * Alias options:
 *
 * time             -> 6:28 AM
 * timeLong         -> 6:28:01 AM
 * date             -> 04/23/2016
 * dateMin          -> 4/23/2016
 * dateLong         -> April 23, 2016
 * dateLongMin      -> Apr 23, 2016
 * dateTime         -> April 23, 2016 6:28 AM
 * dateTimeMin      -> Apr 23, 2016 6:28 AM
 * dateTimeLong     -> Saturday, April 23, 2016 6:28 AM
 * dateTimeLongMin  -> Sat, Apr 23, 2016 6:29 AM
 *
 */
moment.loadPersian()
+function ($) { "use strict";
    var DateTimeConverter = $.fn.dateTimeConverter.Constructor
    var toEnglishDigits = function (str) {
        var charCodeZero = '۰'.charCodeAt(0);
        return (str.replace(/[۰-۹]/g, function (w) {
            return w.charCodeAt(0) - charCodeZero
        }))
    }
    DateTimeConverter.prototype.getDateTimeValue = function() {
        this.datetime = this.$el.attr('datetime')
        var momentObj = moment(moment.tz(moment(this.datetime , 'jYYYY-jMM-jDD HH:mm:ss'), this.appTimezone)),
            result
console.log(momentObj)
console.log(moment(momentObj))
        if (this.options.locale) {
            momentObj = momentObj.locale(this.options.locale)
        }

        if (this.options.timezone) {
            momentObj = momentObj.tz(this.options.timezone)
        }

        if (this.options.timeSince) {
            result = momentObj.fromNow()
        }
        else if (this.options.timeTense) {
            result = momentObj.calendar()
        }
        else {
            result = momentObj.format(this.options.format)
        }

        return result
    }
}(window.jQuery);