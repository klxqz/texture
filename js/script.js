$(document).ready(function () {
    var _csrf = $('input[name=_csrf]').val();

    $('.fileupload').fileupload({
        url: '?plugin=texture&action=saveImage',
        dataType: 'json',
        done: function (e, data) {
            $(this).parent().find('.slide-result').html('');
            $('.loading').remove();

            if (data.result.status == 'ok') {
                $(this).parent().find('.value_feature').val(data.result.data.preview);
                $(this).parent().parent().find('.preview').html('<img src="' + data.result.data.image_url + data.result.data.preview + '" />');
                $(this).parent().parent().find(".deleteButton").show();
                var f = $("#plugins-settings-form");
                $.post(f.attr('action'), f.serialize());
            } else {
                $(this).parent().parent().find(".response").text(data.result.errors.join(' '));
            }
        },
        fail: function (e, data) {
            $(this).parent().parent().find('.loading').remove();
            $(this).parent().parent().find(".response").text(data.result.errors.join(' '));
        },
        start: function (e, data) {
            $(this).parent().append('<span class="loading"><i class="icon16 loading"></i>Loading...</span>');
        },
    });

    $('a.deleteButton').click(function () {

        $(this).parent().append('<span class="loading"><i class="icon16 loading"></i>Loading...</span>');
        var feature_id = $(this).parent().find('a .value_feature').data('feature-id');
        var feature_value = $(this).parent().find('a .value_feature').data('feature-value');
        $.ajax({
            url: "?plugin=texture&action=deleteImage",
            dataType: 'json',
            type: 'POST',
            data: {_csrf: _csrf, feature_id: feature_id, feature_value: feature_value}
        }).done(function (response) {
            var element = $('.feature_' + response.data.feature_id + '_' + response.data.feature_value);
            element.find('.loading').remove();
            element.find(".preview").html('');
            element.find(".deleteButton").hide();
            element.find('.value_feature').val('');
        });
    });
});
