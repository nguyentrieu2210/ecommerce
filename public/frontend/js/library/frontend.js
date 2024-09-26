(function ($) {
    "use strict";
    var HT = {}
    var _token = $('meta[name="csrf-token"]').attr('content');

    HT.setupOwlCarouselProduct = () => {
        if ($('.owl-carousel-product').length) {
            $('.owl-carousel-product').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 2,
                        nav: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 5,
                        nav: true,
                        loop: false
                    }
                }
            })
        }
    }

    HT.setupOwlCarouselNews = () => {
        if ($('.owl-carousel-news').length) {
            $('.owl-carousel-news').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                        loop: true,
                        autoplay: true, // Tự động chạy carousel
                        autoplayTimeout: 3000, // Thay đổi item mỗi 3 giây
                        autoplayHoverPause: true // Dừng autoplay khi hover chuột
                    },
                    600: {
                        items: 2,
                        nav: false,
                        autoplay: true, 
                        autoplayTimeout: 3000,
                        autoplayHoverPause: true 
                    },
                    1000: {
                        items: 3,
                        nav: true,
                        loop: true
                    }
                },
                
            })
        }
    }

    HT.setupOwlCarouselMultiple = () => {
        if ($('.owl-carousel-multiple').length) {
            $('.owl-carousel-multiple').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                        nav: true,
                        loop: true
                    },
                    600: {
                        items: 3,
                        nav: true
                    },
                    1000: {
                        items: 5,
                        nav: true,
                        loop: false
                    }
                },
                autoplay: true, // Tự động chạy carousel
                autoplayTimeout: 3000, // Thay đổi item mỗi 3 giây
                autoplayHoverPause: true // Dừng autoplay khi hover chuột
            })
        }
    }

    HT.setupSwiper = () => {
        $.each($('.swiper-container'), function (index, elem) {
            let _elem = $(elem);
            let numberSlide = _elem.data('number');
            let setting = JSON.parse(_elem.find('.settingSlide').val());
            let swiper = new Swiper(elem, {
                slidesPerView: numberSlide,
                spaceBetween: 10,
                loop: true,
                effect: setting.animation,
                speed: setting.speed,
                //Tự động chạy
                autoplay: setting.autoplay == 'on' ? {
                    delay: setting.delay, // Thời gian chờ giữa các slide (3000ms)
                }: false,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                // scrollbar: {
                //     el: '.swiper-scrollbar',
                // },
                // on: {
                //     resize: function() {
                //         this.el.style.width = setting.width == 0 ? (numberSlide == 2 ? '50%' : '100%') : setting.width;
                //         this.el.style.height = setting.height == 0 ? 'auto' : setting.height;
                //     }
                // }
            });   
            //Dừng khi hơ chuột
            if (setting.pauseOnMouseEnter == 'on') {
                _elem.on('mouseenter', function () {
                    swiper.autoplay.stop();
                });
                _elem.on('mouseleave', function () {
                    swiper.autoplay.start();
                });
            }
        })
    }

    HT.setupMultipleProduct = () => {
        $(document).on('click', '.nav-link', function () {
            let target = $(this).data('target');
            $.each($('.nav-link'), function (index, elem) {
                $(elem).removeClass('active');
            })
            $(this).addClass('active');
            $.each($('#pills-multiple-product').find('.tab-pane'), function (index, elem) {
                $(elem).removeClass('show active');
                if (!$(elem).hasClass('fade')) {
                    $(elem).addClass('fade');
                }
            })
            $('#pills-multiple-product').find('#' + target).removeClass('fade').addClass('show active');
        })
    }

    HT.viewMore = () => {
        $(document).on('click', '.viewmore', function (e) {
            e.preventDefault();
            let quantityAddition = 3;
            let currentQuantity = $('.quantityPerpage').val();
            $('.quantityPerpage').val(parseInt(currentQuantity) + quantityAddition);
            let currentPage = $('.currentPage').val();
            $('.currentPage').val(parseInt(currentPage) + 1);
            let currentUrl = window.location.href;
            //Tìm vị trí của dấu #
            let hashIndex = currentUrl.indexOf('#');
            //Lấy phần trước của dấu #
            let baseUrl = hashIndex !== -1 ? currentUrl.substring(0, hashIndex) : currentUrl;
            history.replaceState(null, null, baseUrl + '#' + (parseInt(currentPage) + 1));
            let option = {
                'id': $('.modelId').val(),
                'relation': $('.modelId').data('relation'),
                'model': $('.modelId').data('model'),
                'orderBy': ['id', 'DESC'],
                'paginate': parseInt(currentQuantity) + quantityAddition,
                'subRelation': [$('.modelId').attr('data-subRelation')],
                '_token': _token,
                'isToast': false
            }
            HT.callAjax(option, '/ajax/view/more', 'POST', function(response) {
                let lisNews = response.posts.slice(parseInt(currentQuantity));
                if(lisNews.length > 0) {
                    HT.setupListNews(lisNews);
                }
                if(lisNews.length < quantityAddition){
                    $('.viewmore').addClass('hidden');
                }
            });
        })
    }

    HT.setupListNews = (lisNews) => {
        let html = '';
        $.each(lisNews, function(index, item) {
            html += HT.renderNewsItem(item);
        })
        $('#mainlist').append(html);
    }

    HT.renderNewsItem = (item) => {
        return `<li data-id=""><a href="/${item.canonical}" previewlistener="true">
            <div class="tempvideo">
                <img width="100" height="70"
                    src="${item.image}">
            </div>
            <h3 class="titlecom">
                ${item.name}
            </h3>
            <div class="timepost margin">
                <div class="user-info"><span>${item.users.name}</span></div>
                <span>${HT.formatTimestamp(item.created_at)}</span>
            </div>
        </a></li>`;
    }

    HT.callAjax = (option = [], url, method, callback) => {
        $.ajax({
            url: url,
            method: method,
            data: option,
            dataType: 'json',
            success: function (response) {
                if(option.isToast) {
                    toastr.clear();
                    toastr.success(response.message);
                }
                if(typeof callback == 'function') {
                    callback(response);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    HT.formatTimestamp = (timestamp) => {
        moment.locale('vi');
        return moment(timestamp).fromNow();
    }

    HT.setupRateStar = () => {
        if($('.rateYo').length) {
            let starRating = parseFloat($('.starRatingInfo').text());
            $.each($('.rateYo'), function(index, elem) {
                $(elem).rateYo({
                    rating: starRating,               // Giá trị mặc định
                    starWidth: "25px",         // Kích thước ngôi sao
                    ratedFill: "#FF9F01",      // Màu cho các ngôi sao đã đánh giá (active)
                    normalFill: "#C0C0C0",     // Màu cho các ngôi sao chưa được đánh giá (inactive)
                    backgroundColor: "#FFFFFF", // Màu nền trắng
                    fullStar: false,            // Các ngôi sao đầy đủ (không cho phép nửa ngôi sao)
                    readOnly: true,             //true là không được đánh giá
                    onSet: function (rating, rateYoInstance) {
                      alert("Bạn đã đánh giá: " + rating + " sao!");
                    }
                });
            })
        }
    }

    HT.openMenuMobile = () => {
        $(document).on('click', '#menu-mobile-open', function() {
            $('body').addClass('no-scroll');
        })
    }

    HT.closeMenuMobile = () => {
        $(document).on('click', '#menu-mobile-close', function() {
            $('body').removeClass('no-scroll');
        })
    }

    
    HT.setupSelect2 = () => {
        if($('.setupSelect2').length) {
            $('.setupSelect2').select2();
        }
    }

    HT.setupDatePicker = () => {
        if ($('.setupDatePicker').length) {
            $('.setupDatePicker').each(function() {
                const $this = $(this);
                const defaultDate = $this.val() !== '' ? $this.val() : null; 
                
                $this.datetimepicker({
                    format: 'd/m/Y',
                    defaultDate: defaultDate 
                });
            });
        }
    };

    $(document).ready(function () {
        HT.setupOwlCarouselProduct();
        HT.setupSwiper();
        HT.setupMultipleProduct();
        HT.setupOwlCarouselNews();
        HT.setupOwlCarouselMultiple();
        HT.viewMore();
        HT.setupRateStar();
        HT.openMenuMobile();
        HT.closeMenuMobile();
        HT.setupSelect2();
        HT.setupDatePicker();
    });
})(jQuery);