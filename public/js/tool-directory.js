/* ============================================================================
   Tool directory search.

   Filters the tool cards on /tools and the homepage without a page reload.
   Pure DOM work - no request is made and nothing is sent anywhere.
   ========================================================================= */
(function () {
    'use strict';

    function cards() {
        return document.querySelectorAll('[data-tool-card]');
    }

    window.tdFilter = function (term) {
        var query = String(term || '').trim().toLowerCase();
        var all = cards();
        var shown = 0;

        all.forEach(function (card) {
            var haystack = card.getAttribute('data-search') || card.textContent.toLowerCase();
            var match = query === '' || haystack.indexOf(query) !== -1;
            card.style.display = match ? '' : 'none';
            if (match) shown++;
        });

        /* Hide a category heading when none of its cards survived the filter. */
        document.querySelectorAll('[data-category-block]').forEach(function (block) {
            var visible = block.querySelectorAll('[data-tool-card]:not([style*="display: none"])');
            block.hidden = visible.length === 0;
        });

        var status = document.getElementById('toolSearchStatus');
        if (status) {
            status.textContent = query === ''
                ? ''
                : shown + (shown === 1 ? ' tool matches' : ' tools match') + ' “' + term + '”';
        }

        var empty = document.getElementById('toolSearchEmpty');
        if (empty) empty.hidden = !(query !== '' && shown === 0);
    };

    /* The homepage search box sends the visitor to the directory, which is
       where the full list lives. */
    window.tdFilterSubmit = function (event) {
        event.preventDefault();
        var input = document.getElementById('homeSearch');
        var term = input ? input.value.trim() : '';
        window.location.href = '/tools' + (term ? '#' + encodeURIComponent(term) : '');
        return false;
    };

    /* Allow /tools#merge to land pre-filtered. */
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.location.hash) return;
        var term = decodeURIComponent(window.location.hash.slice(1));
        var input = document.getElementById('toolSearch');
        if (input && term) {
            input.value = term;
            window.tdFilter(term);
        }
    });
})();
