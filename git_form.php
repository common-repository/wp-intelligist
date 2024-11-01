<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<link rel='stylesheet' href='git_styles.css' type='text/css' />
<script type="text/javascript">
          
            
    var GitCodesWindow = {
        local_ed : 'ed',
        init : function(ed) {
            GitCodesWindow.local_ed = ed;
            tinyMCEPopup.resizeToInnerSize();
        },
        insert : function insertButton(ed) {
	 
            // Try and remove existing style / blockquote
            tinyMCEPopup.execCommand('mceRemoveNode', false, null);
                
            var codes = '';
            var decs  = '';
            var text  = '';
            
            if($('.pr_gits').length == '0'){
                $('#form_error').html("No Codes Available");
                $('#form_error').show();                
            }else{
                $('#form_error').hide();  
                $('.pr_gits').each(function(){
                    codes += $(this).find('.pr_code').html()+",";
                    decs += $(this).find('.pr_desc').html()+",";
                });

                codes = codes.substring(0,(codes.length-1));
                decs = decs.substring(0,(decs.length-1));
 
		 
                var output = '';
		
                // setup the output of our shortcode
                output = '[wp_intelligist ';
                output += 'codes="' + codes + '" ';
                output += 'desc="' + decs + '" ';

			
                output += ']'+ text + '[/wp_intelligist]';

                tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
                // Return
                tinyMCEPopup.close();
            }
            
            
        }
    };
    tinyMCEPopup.onInit.add(GitCodesWindow.init, GitCodesWindow);
    
    var addGits = function(){ 
        
        $('#form_error').hide();
        
        if($('#icode').val() == '' || $('#idesc').val() == ''){
            $('#form_error').html("Please Fill the Required Fields");
            $('#form_error').show();
        }else{
            $('#git_panel').append("<li class='pr_gits'><div class='label pr_code'>"+$('#icode').val()+"</div><div class='label pr_desc'>"+$('#idesc').val()+"</div></li>");
            
           
            $('#icode').val('');
            $('#idesc').val('');
    
        }
    }
</script>

<h2>Add Git Codes</h2>

<ul class="git_panel">
    <li id="form_error" style="display: none;"></li>
    <li><div class="label">Git Code <span>*</span></div><div class='field'><input type="text" id="icode" /></div></li>
    <li><div class="label">Git Title <span>*</span></div><div class='field'><input type="text" id="idesc" />
            <input type="button" id="" onclick="addGits();" value="Add Code"/></div></li>

    <li>
        <div id='git_panel'>
            <ul class="git_code_panel">
                <li><div class="label">Git Code</div><div class='label'>Title</div></li>
            </ul>
        </div>
    </li>
    <li style="margin-top: 10px;text-align: center;">
        <input type='button' onclick="javascript:GitCodesWindow.insert(GitCodesWindow.local_ed)" value="Add Codes To Editor" ></li>
</ul>

