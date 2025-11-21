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
    
    var $bubble = $('.tapchat-welcome-bubble');
    if ($bubble.length) {
      var bubbleId = 'tapchat_bubble_closed_' + window.location.pathname;
      
      if (sessionStorage.getItem(bubbleId) === 'true') {
        return;
      }
      
      var triggers = window.TapChatData && window.TapChatData.triggers ? window.TapChatData.triggers : {};
      var bubbleShown = false;
      
      function showBubble() {
        if (!bubbleShown) {
          bubbleShown = true;
          $bubble.addClass('visible');
        }
      }
      
      var triggersEnabled = triggers.scrollEnabled || triggers.exitEnabled || 
                           triggers.timeEnabled || triggers.idleEnabled;
      
      if (!triggersEnabled) {
        return;
      }
      
      if (triggers.timeEnabled) {
        var timeDelay = (triggers.timeDelay || 3) * 1000;
        setTimeout(showBubble, timeDelay);
      }
      
      if (triggers.scrollEnabled) {
        var scrollDepth = triggers.scrollDepth || 50;
        var scrollTriggered = false;
        
        $(window).on('scroll', function() {
          if (scrollTriggered) return;
          
          var scrollTop = $(window).scrollTop();
          var docHeight = $(document).height();
          var winHeight = $(window).height();
          var scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
          
          if (scrollPercent >= scrollDepth) {
            scrollTriggered = true;
            showBubble();
          }
        });
      }
      
      if (triggers.exitEnabled) {
        var exitTriggered = false;
        
        $(document).on('mouseleave', function(e) {
          if (exitTriggered) return;
          
          if (e.clientY <= 0) {
            exitTriggered = true;
            showBubble();
          }
        });
      }
      
      if (triggers.idleEnabled) {
        var idleTime = (triggers.idleTime || 60) * 1000;
        var idleTimer = null;
        var idleTriggered = false;
        
        function resetIdleTimer() {
          if (idleTriggered) return;
          
          clearTimeout(idleTimer);
          idleTimer = setTimeout(function() {
            idleTriggered = true;
            showBubble();
          }, idleTime);
        }
        
        $(document).on('mousemove keypress scroll touchstart', resetIdleTimer);
        resetIdleTimer();
      }
      
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