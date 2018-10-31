$(function() {
    // snippets init functions
    altair_snippets.init();
});

altair_snippets = {
    init: function () {

        var $snippets = $('#snippets'),
            $snippet_modal = $('#snippet_new'),
            $html_editor = $('#snippet_content');

        // show code on content click
        $snippets
            .find('.md-card-content')
            .css({ 'cursor': 'pointer' })
            .on('click',function() {
                UIkit.modal.blockUI(
                    // snippet title
                    '<div class="uk-modal-header">'
                    +   '<h3 class="uk-modal-title">' + $(this).parent('.md-card').attr('data-snippet-title') + '</h3>'
                    + '</div>'

                    // snipped code
                    + $(this).html()

                    // hide modal
                    + '<div class="uk-modal-footer uk-text-right">'
                    + '<button type="button" class="md-btn md-btn-flat md-btn-flat-primary uk-modal-close">Close</button>'
                    + '</div>'
                );
            });

    }
};