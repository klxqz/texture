(function ($) {
    $.texture_plugin_settings = {
        options: {
            categories: {}
        },
        init: function (options) {
            this.options = options;
            this.initButtons();
            this.initTemplates();
            this.initScroll();
        },
        initScroll: function () {
            $(window).scroll(function () {
                var item = $('.field-group.submit');
                var form_bottom_position = $('#plugins-settings-form').offset().top + $('#plugins-settings-form').height();
                var scroll_bottom = $(this).scrollTop() + $(window).height();
                if (form_bottom_position - scroll_bottom > 120 && !item.hasClass("fixed")) {
                    item.hide();
                    item.addClass("fixed").slideToggle(200);
                } else if (form_bottom_position - scroll_bottom < 100 && item.hasClass("fixed")) {
                    item.removeClass("fixed");
                }
            }).scroll();
        },
        initButtons: function () {
            var self = this;
            $('#ibutton-status').iButton({labelOn: "", labelOff: "", className: 'mini'}).change(function () {
                var self = $(this);
                var enabled = self.is(':checked');
                if (enabled) {
                    self.closest('.field-group').siblings().show(200);
                } else {
                    self.closest('.field-group').siblings().hide(200);
                }
                var f = $("#plugins-settings-form");
                $.post(f.attr('action'), f.serialize());
            });
            $('.ibutton').iButton({labelOn: "", labelOff: "", className: 'mini'}).change(function () {
                var f = $("#plugins-settings-form");
                $.post(f.attr('action'), f.serialize());
            });
            $('[name="shop_texture[feature_id]"]').change(function () {
                var $feature_id_selector = $(this);
                var loading = $('<i class="icon16 loading"></i>');
                $(this).attr('disabled', true);
                $(this).after(loading);
                $('#feature-values').slideUp('slow');
                $.get('?plugin=texture&module=settings&action=featureValues', {feature_id: $(this).val()}, function (html) {
                    loading.remove();
                    $feature_id_selector.removeAttr('disabled');
                    $('#feature-values').html(html);
                    $('#feature-values').slideDown('slow');
                    self.initFeatureValues();
                });
            }).change();

            $(document).keydown(function (e) {
                // ctrl + s
                if (e.ctrlKey && e.keyCode == 83) {
                    $('#plugins-settings-form').submit();
                    return false;
                }
            });
        },
        initFeatureValues: function () {
            $('.fileupload').fileupload({
                url: '?plugin=texture&module=settings&action=saveImage',
                dataType: 'json',
                done: function (e, data) {
                    console.log(data);
                    $(this).closest('.value').find('> .loading').remove();
                    if (data.result.status == 'ok') {
                        for (var value_id in data.result.data) {
                            var res = data.result.data[value_id];
                            $('.value[data-value-id=' + value_id + ']').find('.preview').html('<img src="' + res.image_url + '?' + Math.random() + '" />');
                            $('.value[data-value-id=' + value_id + ']').find('.deleteButton').removeClass('hidden');
                        }
                    } else {
                        alert(data.result.errors.join(' '))
                    }
                },
                fail: function (e, data) {
                    console.log(data);
                    $(this).closest('.value').find('> .loading').remove();
                    alert(data.jqXHR.responseText);
                },
                start: function (e, data) {
                    $(this).closest('.value').append('<span class="loading"><i class="icon16 loading"></i>Loading...</span>');
                }
            });
            $('.deleteButton').click(function () {
                var $delete_button = $(this);
                $.ajax({
                    url: '?plugin=texture&module=settings&action=deleteImage',
                    type: 'POST',
                    data: {
                        feature_id: $('[name="shop_texture[feature_id]"]').val(),
                        value_id: $delete_button.closest('.value').data('value-id')
                    },
                    success: function (data, textStatus) {
                        if (data.status == 'ok') {
                            $delete_button.hide();
                            $delete_button.closest('.value').find('.preview').empty();
                        } else {
                            alert(data.errors.join(', '));
                        }
                    }
                });
                return false;
            });
        },
        initTemplates: function () {
            var templates = this.options.templates;
            for (var i = 0; i < templates.length; i++) {
                CodeMirror.fromTextArea(document.getElementById(templates[i].id), {
                    mode: "text/" + templates[i].mode,
                    tabMode: "indent",
                    height: "dynamic",
                    lineWrapping: true
                });
            }

            $('.template-block').hide();
            $('.edit-template').click(function () {
                $(this).closest('.field').find('.template-block').slideToggle('slow');
                return false;
            });
            $('.templates-block').hide();
            $('.edit-templates').click(function () {
                $(this).closest('.field-group').find('.templates-block').slideToggle('slow');
                return false;
            });
        }
    };
})(jQuery);