+function ($) {
    var removeList = [
        "a", "an", "as", "at", "before", "but", "by", "for", "from", "is",
        "in", "into", "like", "of", "off", "on", "onto", "per", "since",
        "than", "the", "this", "that", "to", "up", "via", "with",'از','به',
        'در','با','یا','یک','قبل','است','بالا','پایین','این','آن'
    ]
    var InputPreset = $.fn.inputPreset.Constructor

    function slugify(slug, numChars) {
        var regex = new RegExp('\\b(' + removeList.join('|') + ')\\b', 'gi')
        slug = slug.replace(regex, '')
        slug = slug.replace(/[^-\w\s۰-۹آا-ی]/g, '')
        slug = slug.replace(/^\s+|\s+$/g, '')
        slug = slug.replace(/[-\s]+/g, '-')
        slug = slug.toLowerCase()
        return slug.substring(0, numChars)
    }

    var oldFormatValue = InputPreset.prototype.formatValue;
    InputPreset.prototype.formatValue = function () {
        if (this.options.inputPresetType == 'namespace' || this.options.inputPresetType == 'camel'
            || this.options.inputPresetType == 'file' ) {
            return oldFormatValue.call(this);
        }
        var value = slugify(this.$src.val())

        if (this.options.inputPresetType == 'url') {
            value = '/' + value
        }

        return value.replace(/\s/gi, "-")
    }
}(jQuery);