$(document).ready(function () {
    try {
        $(".texture-selector").fancybox({
            width: 350,
            height: 'auto',
            fitToView: false,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none'
        });
        $('.inline-select a').on("click", function () {
            $(this).siblings('a').removeClass('active');
            $(this).addClass('active');
            var texture_img = $(this).find('.texture_view img');
            $(this).closest('.texture-select-box').find('.features_texture_img').html(texture_img.clone());
            return false;
        });
        $('.inline-select a.active').click();
        $('.button_texture').on("click", function () {
            var a = $(this).closest('.texture-select-box').find('.inline-select a.active');
            var feature_id = $(this).data('feature-id');
            var feature_value_id = a.data('value-id');
            $("[name='features[" + feature_id + "]'] ").val(feature_value_id).change();

            $('.feature-' + feature_id).find('.texture-selector .texture_view').html(a.find('.texture_view img').clone());
            $('.feature-' + feature_id).find('.texture-selector .name_texture').text(a.find('.name_texture').text());

            $.fancybox.close();
            return false;
        });
        if (texture_features) {
            for (var i in texture_features) {
                var feature_id = texture_features[i];
                $('[name="features[' + feature_id + ']"],[data-feature-id="' + feature_id + '"]').hide();
                $('[name="features[' + feature_id + ']"],[data-feature-id="' + feature_id + '"]').parent().hide();
            }
        }
    } catch (e) {
        console.log(e);
    }
});