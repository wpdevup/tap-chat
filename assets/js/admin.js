jQuery(document).ready(function($) {
    
    $('.tap-chat-color-picker').wpColorPicker();
    
    var selectedCode = $('input[name="tap_chat_settings[country_code]"]').val();
    
    function updateSelectedDisplay() {
        var code = $('input[name="tap_chat_settings[country_code]"]').val();
        var option = $('.tap-chat-country-option[data-code="' + code + '"]');
        if (option.length) {
            var flag = option.find('.tap-chat-country-flag').text();
            var name = option.find('.tap-chat-country-name').text();
            var codeText = option.find('.tap-chat-country-code').text();
            $('.tap-chat-selected-country').html(
                '<span class="tap-chat-country-flag">' + flag + '</span>' +
                '<span>' + name + ' ' + codeText + '</span>'
            );
        }
    }
    
    updateSelectedDisplay();
    
    $('.tap-chat-selected-country').on('click', function() {
        $('.tap-chat-country-search').toggle().focus();
        $('.tap-chat-country-select').toggle();
    });
    
    $('.tap-chat-country-search').on('input', function() {
        var search = $(this).val().toLowerCase();
        $('.tap-chat-country-option').each(function() {
            var text = $(this).text().toLowerCase();
            if (text.indexOf(search) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    $('.tap-chat-country-option').on('click', function() {
        var code = $(this).data('code');
        $('input[name="tap_chat_settings[country_code]"]').val(code);
        $('.tap-chat-country-option').removeClass('selected');
        $(this).addClass('selected');
        $('.tap-chat-country-search').hide().val('');
        $('.tap-chat-country-select').hide();
        $('.tap-chat-country-option').show();
        updateSelectedDisplay();
    });
    
    $('.tap-chat-country-option[data-code="' + selectedCode + '"]').addClass('selected');
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.tap-chat-country-wrapper').length) {
            $('.tap-chat-country-search').hide();
            $('.tap-chat-country-select').hide();
        }
    });
    
    $('.tap-chat-search-pages').on('input', function() {
        var search = $(this).val().toLowerCase();
        $('.tap-chat-page-item').each(function() {
            var title = $(this).find('.tap-chat-page-title').text().toLowerCase();
            if (title.indexOf(search) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    
    function updateSelectedCount(list) {
        var count = $(list).find('input[type="checkbox"]:checked').length;
        $(list).prev('.tap-chat-selected-count').text(count + ' selected');
    }
    
    $('.tap-chat-pages-list input[type="checkbox"]').on('change', function() {
        updateSelectedCount($(this).closest('.tap-chat-pages-list'));
    });
    
    $('.tap-chat-pages-list').each(function() {
        updateSelectedCount(this);
    });
    
    function toggleVisibilitySections() {
        var showOnChecked = $('#enable_show_on').is(':checked');
        var hideOnChecked = $('#enable_hide_on').is(':checked');
        
        $('#tap-chat-show-on-section').toggle(showOnChecked);
        $('#tap-chat-hide-on-section').toggle(hideOnChecked);
    }
    
    $('#enable_show_on, #enable_hide_on').on('change', function() {
        var section = $(this).attr('id') === 'enable_show_on' ? '#tap-chat-show-on-section' : '#tap-chat-hide-on-section';
        $(section).slideToggle(200);
    });
    
    toggleVisibilitySections();
    
    function toggleWorkingHoursSection() {
        $('#tap-chat-working-hours-section').toggle($('#enable_working_hours').is(':checked'));
    }
    
    $('#enable_working_hours').on('change', function() {
        $('#tap-chat-working-hours-section').slideToggle(200);
    });
    
    toggleWorkingHoursSection();
    
    $('.tap-chat-day-enabled').on('change', function() {
        var row = $(this).closest('tr');
        var timeInputs = row.find('input[type="time"]');
        
        if ($(this).is(':checked')) {
            row.removeClass('disabled');
            timeInputs.prop('disabled', false);
        } else {
            row.addClass('disabled');
            timeInputs.prop('disabled', true);
        }
    });
    
    $('.tap-chat-day-enabled').each(function() {
        $(this).trigger('change');
    });
    
    function toggleWelcomeBubbleSection() {
        if ($('#enable_welcome_bubble').is(':checked')) {
            $('#tap-chat-welcome-bubble-section').slideDown(200);
        } else {
            $('#tap-chat-welcome-bubble-section').slideUp(200);
        }
    }
    
    $('#enable_welcome_bubble').on('change', function() {
        $('#tap-chat-welcome-bubble-section').slideToggle(200);
    });
    
    toggleWelcomeBubbleSection();
    
    function toggleBubbleStyleFields() {
        var selectedStyle = $('input[name="tap_chat_settings[bubble_style]"]:checked').val();
        
        $('#bubble-name-field, #bubble-avatar-field').toggle(selectedStyle === 'modern');
        $('#bubble-position-field').toggle(selectedStyle === 'simple');
    }
    
    $('.tap-chat-bubble-style-option').on('click', function() {
        $(this).find('input[type="radio"]').prop('checked', true);
        $('.tap-chat-bubble-style-option').removeClass('selected');
        $(this).addClass('selected');
        
        var selectedStyle = $(this).find('input[type="radio"]').val();
        
        if (selectedStyle === 'modern') {
            $('#bubble-position-field').slideUp(200);
            $('#bubble-name-field, #bubble-avatar-field').slideDown(200);
        } else {
            $('#bubble-name-field, #bubble-avatar-field').slideUp(200);
            $('#bubble-position-field').slideDown(200);
        }
    });
    
    $('input[name="tap_chat_settings[bubble_style]"]').on('change', function() {
        var selectedStyle = $(this).val();
        $('.tap-chat-bubble-style-option').removeClass('selected');
        $(this).closest('.tap-chat-bubble-style-option').addClass('selected');
        
        if (selectedStyle === 'modern') {
            $('#bubble-position-field').slideUp(200);
            $('#bubble-name-field, #bubble-avatar-field').slideDown(200);
        } else {
            $('#bubble-name-field, #bubble-avatar-field').slideUp(200);
            $('#bubble-position-field').slideDown(200);
        }
    });
    
    var selectedStyle = $('input[name="tap_chat_settings[bubble_style]"]:checked').closest('.tap-chat-bubble-style-option');
    if (selectedStyle.length) {
        selectedStyle.addClass('selected');
    }
    
    toggleBubbleStyleFields();
    
    var mediaUploader;
    
    $('#tap-chat-upload-avatar').on('click', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media({
            title: 'Choose Avatar Image',
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#tap-chat-avatar-url').val(attachment.url);
            $('#tap-chat-avatar-preview').attr('src', attachment.url);
            $('.tap-chat-avatar-preview').addClass('has-image');
        });
        
        mediaUploader.open();
    });
    
    $('#tap-chat-remove-avatar').on('click', function(e) {
        e.preventDefault();
        $('#tap-chat-avatar-url').val('');
        $('#tap-chat-avatar-preview').attr('src', '');
        $('.tap-chat-avatar-preview').removeClass('has-image');
    });
});