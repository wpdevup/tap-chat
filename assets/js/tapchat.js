(function($){
  // --- GA4 / GTM event helper -------------------------------------------
  var tapchatLastEvent = { name: '', time: 0 };

  function tapchatClickEventName() {
    return (window.TapChatData && window.TapChatData.analytics && window.TapChatData.analytics.eventName) || 'tapchat_click';
  }

  function tapchatTrack(eventName, extra) {
    var cfg = (window.TapChatData && window.TapChatData.analytics) || {};
    if (!cfg.enabled) { return; }

    // De-dupe identical events fired within 600ms (e.g. bubble click also triggers the button)
    var now = (window.Date && Date.now) ? Date.now() : new Date().getTime();
    if (eventName === tapchatLastEvent.name && (now - tapchatLastEvent.time) < 600) { return; }
    tapchatLastEvent = { name: eventName, time: now };

    var method = cfg.method || 'auto';
    extra = extra || {};
    var channel = cfg.channel || 'chat';

    // GA4 gtag.js
    if ((method === 'auto' || method === 'gtag') && typeof window.gtag === 'function') {
      var gparams = {
        event_category: 'Engagement',
        event_label: extra.label || channel,
        page_location: window.location.href,
        chat_channel: channel
      };
      if (extra.source) { gparams.source = extra.source; }
      if (extra.trigger_type) { gparams.trigger_type = extra.trigger_type; }
      try { window.gtag('event', eventName, gparams); } catch(e) {}
    }

    // Google Tag Manager dataLayer
    if ((method === 'auto' || method === 'datalayer') && window.dataLayer && typeof window.dataLayer.push === 'function') {
      var dobj = { event: eventName, chat_channel: channel };
      if (extra.source) { dobj.source = extra.source; }
      if (extra.trigger_type) { dobj.trigger_type = extra.trigger_type; }
      try { window.dataLayer.push(dobj); } catch(e) {}
    }
  }
  // ----------------------------------------------------------------------

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
      
      function showBubble(triggerType) {
        if (!bubbleShown) {
          bubbleShown = true;
          $bubble.addClass('visible');
          var acfg = (window.TapChatData && window.TapChatData.analytics) || {};
          if (acfg.enabled && acfg.trackTriggers) {
            tapchatTrack('tapchat_bubble_shown', { trigger_type: triggerType || 'unknown', label: triggerType || 'bubble' });
          }
        }
      }
      
      var triggersEnabled = triggers.scrollEnabled || triggers.exitEnabled || 
                           triggers.timeEnabled || triggers.idleEnabled;
      
      if (!triggersEnabled) {
        return;
      }
      
      if (triggers.timeEnabled) {
        var timeDelay = (triggers.timeDelay || 3) * 1000;
        setTimeout(function(){ showBubble('time'); }, timeDelay);
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
            showBubble('scroll');
          }
        });
      }
      
      if (triggers.exitEnabled) {
        var exitTriggered = false;
        
        $(document).on('mouseleave', function(e) {
          if (exitTriggered) return;
          
          if (e.clientY <= 0) {
            exitTriggered = true;
            showBubble('exit');
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
            showBubble('idle');
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
          tapchatTrack(tapchatClickEventName(), { source: 'bubble' });
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
    tapchatTrack(tapchatClickEventName(), { source: 'button' });
  });
})(jQuery);