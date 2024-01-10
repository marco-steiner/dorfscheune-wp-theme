
jQuery(document).ready(function() {
    jQuery('nav [title]').removeAttr('title');

    jQuery( '.navbar-toggler' ).click(function() {
        jQuery( '.hamburger' ).toggleClass( 'is-active' );
    });

});

// Tabellen wenn gefüllt nicht mehr bearbeitbar machen
jQuery(document).ready(function() {
    setTimeout(function() { 
        customTableEdits();
        if (typeof wpDataTables != 'undefined') {
            wpDataTables.table_1.addOnDrawCallback(function() {
                customTableEdits();
            });
        }
    }, 1000);
});

function customTableEdits(){
    if(typeof swpmUserName != 'undefined') {
        if(swpmUserName != 'claudiaott' && swpmUserName != 'marcosteiner') {
            jQuery('td').each(function() {
                var value = jQuery(this).text();
                if (value !== '') {
                    jQuery(this).addClass('disabledCell');
                }       
            });
        }
    }
}