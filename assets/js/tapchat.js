(function($){
  $(function(){
    var $fab = $('.tapchat-fab[data-append-page="1"]');
    if ($fab.length) {
      try {
        var href = new URL($fab.attr('href'));
        var text = href.searchParams.get('text') || '';
        var extra = '\n\n' + document.title + '\n' + window.location.href;
        href.searchParams.set('text', (text ? text + ' ' : '') + extra);
        $fab.attr('href', href.toString());
      } catch(e) {}
    }
  });
  $(document).on('click', '.tapchat-fab, .tapchat-inline', function(){
    if (window.wp && wp.hooks) { wp.hooks.doAction('tapchat_click'); }
  });
})(jQuery);
