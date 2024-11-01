(function() {
    tinymce.create('tinymce.plugins.GitCodePlugin', {
        init : function(ed, url) {

            ed.addCommand('git_code', function() {
                ed.windowManager.open({
                    file : url + '/git_form.php',
                    width : 620 + parseInt(ed.getLang('button.delta_width', 0)), 

                    inline : 1
                }, {
                    plugin_url : url
                });
            });

            ed.addButton('git_button', {
                title : 'Git Codes', 
                cmd : 'git_code', 
                image: url + '/gist.jpg'
            });
          
            
            
        }
    });

    tinymce.PluginManager.add('git_button', tinymce.plugins.GitCodePlugin);
    
    


})();



    

