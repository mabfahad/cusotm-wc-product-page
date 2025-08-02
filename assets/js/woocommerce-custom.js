(function($){
    "use strict";

    $(function () {
        const $featured = $('#featured');
        const $thumbs = $('.thumb');
        const $dots = $('.dot');
        let currentIndex = 0;

        function changeImage($el) {
            const src = $el.data('large') || $el.attr('src');
            $featured.attr('src', src);
            $thumbs.removeClass('active');
            $el.addClass('active');
            currentIndex = $thumbs.index($el);
            activateDot(currentIndex);
        }

        function activateDot(index) {
            $dots.removeClass('active').eq(index).addClass('active');
        }

        $thumbs.on('click', function() {
            changeImage($(this));
        });

        $dots.on('click', function() {
            changeImage($thumbs.eq($(this).index()));
        });

        $('.prev').on('click', function() {
            currentIndex = (currentIndex - 1 + $thumbs.length) % $thumbs.length;
            changeImage($thumbs.eq(currentIndex));
        });

        $('.next').on('click', function() {
            currentIndex = (currentIndex + 1) % $thumbs.length;
            changeImage($thumbs.eq(currentIndex));
        });

        activateDot(currentIndex);

        function setPlan(plan) {
            $('#singleDrinkSections').toggle(plan === 'single');
            $('#doubleDrinkSections').toggle(plan === 'double');
            $('#singlePlan').prop('checked', plan === 'single');
            $('#doublePlan').prop('checked', plan === 'double');
        }

        $('#singlePlan').on('change', () => setPlan('single'));
        $('#doublePlan').on('change', () => setPlan('double'));

        // Initialize subscription sections
        setPlan('single');

        // Update rating stars fill
        $('.rating[data-rating]').each(function() {
            const rating = parseFloat($(this).data('rating'));
            const percent = (rating / 5) * 100;
            $(this).find('.stars').css('--rating-percent', percent + '%');
        });

        // When flavor changes, check related plan radio & call setPlan
        $('input[name="flavor-single"]').on('change', function () {
            const $subscription = $(this).closest('.subscription');
            const $planRadio = $subscription.find('input[name="plan"]');
            if (!$planRadio.prop('checked')) {
                $planRadio.prop('checked', true);
                setPlan('single');
            }
        });

        // Log grouped product ID on plan change
        $('input[name="plan"]').on('change', function () {
            console.log('Selected Grouped Product ID:', $('input[name="plan"]:checked').data('grouped-id'));
        });

        // AJAX add to cart
        $('.add-to-cart').on('click', function (e) {
            e.preventDefault();

            const variation = $('input[name="flavor-single"]:checked').data('variation-id');
            const grouped_product_id = $('input[name="plan"]:checked').data('grouped-id');

            $.ajax({
                url: wc_custom_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'wc_add_to_cart_ajax',
                    variation: variation,
                    product_id: grouped_product_id,
                    nonce: wc_custom_params.nonce
                },
                beforeSend() {
                    $('.add-to-cart').text('Please wait...');
                },
                success(res) {
                    if (res.success) {
                        $('.add-to-cart').text('Added!').delay(1000).fadeOut(600, function() {
                            $(this).text('Add to Cart').fadeIn(200);
                        });
                        $(document.body).trigger('added_to_cart');
                    } else {
                        alert(res.data?.message || 'Failed to add to cart');
                        $('.add-to-cart').text('Add to Cart');
                    }
                },
                error() {
                    alert('Server error.');
                    $('.add-to-cart').text('Add to Cart');
                }
            });
        });

    });
})(jQuery);
