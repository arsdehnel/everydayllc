(function($) {
    jQuery.fn.spinner = function(options) {

        var settings = $.extend({
            'loaderSymbols': ['0', '1', '2', '3', '4', '5', '6', '7'],
            'loaderRate': 150,
            'loadingText': 'loading'
        }, options);

        return this.each(function() {
            var str_opts = (settings.loadingText).split('');
            var id = $(this).attr('id');
            $(this).html('<span class="text">' + settings.loadingText + '</span>').append('<span class="icon"></span>');
            var loaderSymbols = settings.loaderSymbols,
                loaderRate = settings.loaderRate,
                loaderIndex = 0,
                textIndex = 0,
                loader = function() {
                    str_base = str_opts[textIndex];
                    str_opts[textIndex] = '<em>' + str_opts[textIndex] + '</em>';
                    $('#' + id + ' span.icon').html(loaderSymbols[loaderIndex]);
                    if ( str_opts.length > 1 ){
/*
                    	console.log(str_opts);
                    }else{
                    	console.log(str_opts.length);
                    	*/
	                    $('#' + id + ' span.text').html(str_opts.join(''));
	                }
                    str_opts[textIndex] = str_base;
                    loaderIndex = loaderIndex < loaderSymbols.length - 1 ? loaderIndex + 1 : 0;
                    textIndex = textIndex < str_opts.length - 1 ? textIndex + 1 : 0;
                    spinTimer = setTimeout(loader, loaderRate);
                };
            loader();
        });
    }
})(jQuery);