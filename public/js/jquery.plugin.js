/**
 * jquery plugin template
 *
 * @author Ben Chung oommgg@gmail.com
 */
(function($) {
    /**
     * plugin description
     */
    $.fn.PLUGIN_NAME = function(options) {
        //extend the default setting from imported options
        var settings = $.extend({}, $.fn.PLUGIN_NAME.defaults, options);

        //iterate the match elements
        return this.each(function() {
            var $this = $(this);

            //do something
            $this.html(settings.aaa + settings.hello);
        });
    };

    /**
     * default setting for plugin
     */
    $.fn.PLUGIN_NAME.defaults = {
        hello: 'Hello World!!'
    };

    /**
     * public method
     *
     * @param mixed param
     * @return mixed
     */
    $.fn.PLUGIN_NAME.METHOD = function(param) {
        return param;
    };

    /**
     * private function for debugging
     *
     * @param mixed obj
     * @return void
     */
    function debug(obj) {
        if (window.console && window.console.log) {
            window.console.log(obj);
        }
    };
})(jQuery);
