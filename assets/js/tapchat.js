(function($){
  $(function(){
    // Append page context
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
    
    // Welcome Bubble
    var $bubble = $('.tapchat-welcome-bubble');
    if ($bubble.length) {
      var delay = parseInt($bubble.attr('data-delay') || 3) * 1000;
      var bubbleId = 'tapchat_bubble_closed_' + window.location.pathname;
      
      // Check if user already closed the bubble on this page
      if (sessionStorage.getItem(bubbleId) === 'true') {
        return;
      }
      
      // Show bubble after delay
      setTimeout(function() {
        $bubble.addClass('visible');
      }, delay);
      
      // Close button
      $('.tapchat-bubble-close').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $bubble.removeClass('visible');
        setTimeout(function() {
          $bubble.addClass('hidden');
        }, 400);
        
        // Remember that user closed it
        sessionStorage.setItem(bubbleId, 'true');
      });
      
      // Click on bubble = click on FAB
      $bubble.on('click', function(e) {
        if (!$(e.target).closest('.tapchat-bubble-close').length) {
          $fab.trigger('click');
          
          // Hide bubble after click
          $bubble.removeClass('visible');
          setTimeout(function() {
            $bubble.addClass('hidden');
          }, 400);
          
          sessionStorage.setItem(bubbleId, 'true');
        }
      });
    }
  });
  
  // Track clicks
  $(document).on('click', '.tapchat-fab, .tapchat-inline', function(){
    if (window.wp && wp.hooks) { 
      wp.hooks.doAction('tapchat_click'); 
    }
  });
})(jQuery);