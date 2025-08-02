document.querySelectorAll('.rating[data-rating]').forEach(function (el) {
    const rating = parseFloat(el.getAttribute('data-rating'));
    const percent = (rating / 5) * 100;
    el.querySelector('.stars').style.setProperty('--rating-percent', percent);
});

(function ($) {
    "use strict";
    $(document).ready(function () {

        const $featured = $('#featured');
        const $thumbs = $('.thumb');
        const $dots = $('.dot');
        let currentIndex = 0;

        function changeImage(element) {
            const largeSrc = $(element).data('large') || $(element).attr('src');
            $featured.attr('src', largeSrc);
            $thumbs.removeClass('active');
            $(element).addClass('active');
            currentIndex = $thumbs.index(element);
            activateDot(currentIndex);
        }

        function activateDot(index) {
            $dots.removeClass('active');
            if ($dots.eq(index).length) {
                $dots.eq(index).addClass('active');
            }
        }

        // Thumbnail click
        $thumbs.each(function (idx, thumb) {
            $(thumb).on('click', function () {
                changeImage(thumb);
            });
        });

        // Dot click
        $dots.each(function (idx, dot) {
            $(dot).on('click', function () {
                changeImage($thumbs.get(idx));
            });
        });

        // Prev button
        const $prevBtn = $('.prev');
        if ($prevBtn.length) {
            $prevBtn.on('click', function () {
                currentIndex = (currentIndex - 1 + $thumbs.length) % $thumbs.length;
                changeImage($thumbs.get(currentIndex));
            });
        }

        // Next button
        const $nextBtn = $('.next');
        if ($nextBtn.length) {
            $nextBtn.on('click', function () {
                currentIndex = (currentIndex + 1) % $thumbs.length;
                changeImage($thumbs.get(currentIndex));
            });
        }

        // Initialize first dot as active
        activateDot(currentIndex);

        function setPlan(plan) {
            $('#singleDrinkSections').css('display', plan === 'single' ? 'block' : 'none');
            $('#doubleDrinkSections').css('display', plan === 'double' ? 'block' : 'none');
            $('#singlePlan').prop('checked', plan === 'single');
            $('#doublePlan').prop('checked', plan === 'double');
        }

        const $singlePlan = $('#singlePlan');
        const $doublePlan = $('#doublePlan');

        $singlePlan.on('change', function () {
            setPlan('single');
        });
        $doublePlan.on('change', function () {
            setPlan('double');
        });

        // Initialize subscription sections
        $('#singleDrinkSections').show();
        $('#doubleDrinkSections').hide();
        $singlePlan.prop('checked', true);
        $doublePlan.prop('checked', false);

        $('input[type="radio"][name="flavor-single"]').on('change', function () {
            // Find the closest .subscription block
            var $subscription = $(this).closest('.subscription');

            // Find the plan radio inside that block
            var $planRadio = $subscription.find('input[type="radio"][name="plan"]');

            // If not already checked, check it and trigger the onclick
            if (!$planRadio.prop('checked')) {
                $planRadio.prop('checked', true);

                // Trigger the onclick manually
                if (typeof setPlan === 'function') {
                    setPlan('single');
                }
            }
        });

        // When a plan radio button is selected
        $('input[name="plan"]').on('change', function () {
            var groupedId = $('input[name="plan"]:checked').data('grouped-id');
            console.log('Selected Grouped Product ID:', groupedId);

            // You can now use groupedId for further logic (e.g., AJAX, UI updates, etc.)
        });


        $('.add-to-cart').click(function (e) {
            e.preventDefault();
            var variation = $('input[name="flavor-single"]:checked').data('variation-id');
            var grouped_product_id = $('input[name="plan"]:checked').data('grouped-id');

            //Ajax
            $.ajax({
                url: wc_custom_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'wc_add_to_cart_ajax',
                    variation: variation,
                    product_id: grouped_product_id,
                    nonce: wc_custom_params.nonce
                },
                beforeSend: function () {
                    $('.add-to-cart').text('Please wait...');
                },
                success: function (res) {
                    if (res.success) {
                        $('.add-to-cart').text('Added!').delay(1000).fadeOut(600, function () {
                            $(this).text('Add to Cart').fadeIn(200);
                        });
                        // Optional: update cart icon/fragment
                    } else {
                        alert(res.data.message || 'Failed to add to cart');
                        $('.add-to-cart').text('Add to Cart');
                    }
                }

            });

        });
    })
})(jQuery);