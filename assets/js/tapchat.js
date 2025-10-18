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
    
    var $bubble = $('.tapchat-welcome-bubble, .tapchat-welcome-bubble-simple');
    if ($bubble.length) {
      var delay = parseInt($bubble.attr('data-delay') || 3) * 1000;
      var bubbleId = 'tapchat_bubble_closed_' + window.location.pathname;
      
      if (sessionStorage.getItem(bubbleId) === 'true') {
        return;
      }
      
      setTimeout(function() {
        $bubble.addClass('visible');
      }, delay);
      
      $('.tapchat-bubble-close').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $bubble.removeClass('visible');
        setTimeout(function() {
          $bubble.addClass('hidden');
        }, 400);
        
        sessionStorage.setItem(bubbleId, 'true');
      });
      
      $bubble.on('click', function(e) {
        if (!$(e.target).closest('.tapchat-bubble-close').length) {
          $fab.trigger('click');
          
          $bubble.removeClass('visible');
          setTimeout(function() {
            $bubble.addClass('hidden');
          }, 400);
          
          sessionStorage.setItem(bubbleId, 'true');
        }
      });
    }
  });
  
  $(document).on('click', '.tapchat-fab, .tapchat-inline', function(){
    if (window.wp && wp.hooks) { 
      wp.hooks.doAction('tapchat_click'); 
    }
  });
})(jQuery);