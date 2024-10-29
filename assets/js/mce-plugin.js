(function() {
    
    tinymce.PluginManager.requireLangPack('apiki_wp_faq');

    tinymce.create('tinymce.plugins.apiki_wp_faq', {
		
        init : function(ed, url) {
            
            ed.addCommand('apiki_wp_faq', function() {
                ed.windowManager.open({
                    file    : url + '../../../views/mce-window.php',
                    width   : 400,
                    height  : 160,
                    inline  : 1
                }, {
                    plugin_url : url
                });
            });

            ed.addButton('apiki_wp_faq', {
                title   : 'FAQ',
                cmd     : 'apiki_wp_faq',
                image   : url + '../../images/apiki-wp-faq-20.png'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add( function( ed, cm, n ) {
                cm.setActive('apiki_wp_faq', n.nodeName == 'IMG');
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : 'apiki_wp_faq',
                author 	  : 'Apiki Apps',
                authorurl : 'http://apps.apiki.com',
                infourl   : 'http://apps.apiki.com',
                version   : "1.0"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add( 'apiki_wp_faq', tinymce.plugins.apiki_wp_faq );
})();