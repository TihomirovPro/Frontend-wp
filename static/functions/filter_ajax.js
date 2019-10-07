(function($) {

  var filters = $('.activities__activity');

  filters.on('click', function (e) {
    e.preventDefault();
    var category = $(this).attr('data-activity');

    $.ajax( {
      type : "POST",
      url : filter_ajax_params.ajaxurl,
      data : {
        action : 'ajax_filter',
        cat: category
      },
      success : function( response ) {
        if ( response ) {
          $( '.specialists' ).html( response );

        }
      }
    } );

  });
  
})(jQuery);