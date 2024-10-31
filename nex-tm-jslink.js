( function( tinymce ) {
    tinymce.PluginManager.add( 'nex_jslink', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('jslink', {
            title: 'Insert JSLink',
            image: url + '/jslink.png',
            cmd: 'jslink',
        });
        editor.addButton('jslink_blank', {
            title: 'Insert JSLink (open in new tab)',
            image: url + '/jslink_blank.png',
            cmd: 'jslink_blank',
        });
        editor.addButton('jslink_remove', {
            title: 'Remove JSLink',
            image: url + '/jslink_remove.png',
            cmd: 'jslink_remove',
        });


        editor.addCommand('jslink', function() {
            // Check we have selected some text that we want to link
            var text = editor.selection.getContent({
                'format': 'html'
            });
            if ( text.length === 0 ) {
                alert( 'Please select some text to link.' );
                return;
            }

            // Ask the user to enter a URL
            var link = prompt('Enter the link');
            if ( !link ) {
                // User cancelled - exit
                return;
            }
            if (link.length === 0) {
                // User didn't enter a URL - exit
                return;
            }
            if(link.indexOf('://') === -1){
                // No protocol is defined, set it to default http
                link = 'http://' + link;
            }

            // Insert selected text back into editor, wrapping it in an anchor tag
            editor.execCommand('mceReplaceContent', false, '<span class="jslink" style="cursor: pointer;" data-url="' + link + '">' + text + '</span>');

        });

        editor.addCommand('jslink_blank', function() {
            // Check we have selected some text that we want to link
            var text = editor.selection.getContent({
                'format': 'html'
            });
            if ( text.length === 0 ) {
                alert( 'Please select some text to link.' );
                return;
            }

            // Ask the user to enter a URL
            var link = prompt('Enter the link');
            if ( !link ) {
                // User cancelled - exit
                return;
            }
            if (link.length === 0) {
                // User didn't enter a URL - exit
                return;
            }
            if(link.indexOf('://') === -1){
                // No protocol is defined, set it to default http
                link = 'http://' + link;
            }

            // Insert selected text back into editor, wrapping it in an anchor tag
            editor.execCommand('mceReplaceContent', false, '<span class="jslink" data-url="' + link + '" style="cursor: pointer;" data-target="_blank">' + text + '</span>');
        });


        editor.addCommand('jslink_remove', function() {
            // Check we have selected some text that we want to link
            var text = editor.selection.getContent({
                'format': 'html'
            });

            if ( text.length === 0 ) {
                alert( 'Please select some text to link.' );
                return;
            }

            match = /<span.*>(.*)<\/span>/.exec(text);

            if(!match || match.length < 2){
                return;
            }
            plaintext = match[1];

            // Insert selected text back into editor, wrapping it in an anchor tag
            editor.execCommand('mceReplaceContent', false, plaintext);

        });

    });
})( window.tinymce );

