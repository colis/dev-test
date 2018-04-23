/*
 * Bootstrap popovers and tooltips
 */


var $ = require('jquery')


module.exports = function () {

    // initialise all popovers
    $('body').popover({
        selector: '[data-toggle="popover"]',
        container: 'body',
        viewport: { selector: 'body', padding: 20 }
    })

    // Destroy an existing popover
    $('[data-toggle="popover"]').click(function(e) {
      $('.popover').popover('destroy')
    })
}
