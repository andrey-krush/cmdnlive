
let map;

async function initMap(lat, lng, text) {
    const { Map } = await google.maps.importLibrary("maps");
    map = new Map(document.getElementById("map"), {
        center: { lat: lat, lng: lng },
        zoom: 8,
    });
    const marker = new google.maps.Marker({
        position:  { lat: lat, lng: lng },
        map: map,
        icon: mapIcon,
    });
        const info = new google.maps.InfoWindow({
        content: text
    });
        google.maps.event.addListener(marker, "mouseover", () => {
        info.open(map, marker);
    });
        google.maps.event.addListener(marker, "mouseout", () => {
        info.close(map, marker);
    });
}

function showMap() {
    $('.shows__map').click(function () {
        const lat = parseFloat($(this).attr('data-lat'));
        const lng = parseFloat($(this).attr('data-lng'));
        const text = $(this).text();
        initMap(lat, lng, text);
        // $('.modal__map .modal__overflow').append(map)
    });
}

const openMenu = () => {
    $('.header__burger').toggleClass("header__burger-open");
    $('.header__menu').toggleClass('header__menu-show');
    $('body').toggleClass('hidden');
};

function changeToDifferentWidth() {
    if (window.innerWidth <= 666) {
        $('.article__back button').text('');
        $('.header__menu .header__logo').closest('li').remove();
        // $('.music__more').text('Go to the all posts')

        let breadcrumbs = $('.breadcrumbs');
        breadcrumbs.css('background', '#EAE1CF');
        if (window.location.href.includes('shows') || window.location.href.includes('tickets')) {
            $('.delivery').prepend(breadcrumbs);
        } else {
            $('.delivery').append(breadcrumbs);
        }


        $('.oneproduct__gallery').prepend($('.article__social-wrap'));
        $('.article__social-wrap').prepend("<div class='article__social-button'></div>");
        showSocial();
        const recommended = new Swiper('.article__recommended-slider', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            centeredSlides: false,
            loop: true,
            navigation: {
                nextEl: ".main__next-act",
                prevEl: ".main__prev-act"
            }

        });
        const showRelated = new Swiper('.show .upcoming__block', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            centeredSlides: false,

            loop: true,
            navigation: {
                nextEl: ".main__next-upcoming",
                prevEl: ".main__prev-upcoming"
            }

        });
        const errorRelated = new Swiper('.error__wrap .upcoming__block', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            centeredSlides: false,

            loop: true,
            navigation: {
                nextEl: ".main__next-upcoming",
                prevEl: ".main__prev-upcoming"
            }

        });

        const bandUpcoming = new Swiper('.band .upcoming__block', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            centeredSlides: false,

            loop: true,
            navigation: {
                nextEl: ".main__next",
                prevEl: ".main__prev"
            }
        });
        const related = new Swiper('.related-slider', {
            slidesPerView: 1.5,
            spaceBetween: 10,
            centeredSlides: false,

            loop: true,
            navigation: {
                nextEl: ".main__next",
                prevEl: ".main__prev"
            }

        });
    } else{
        $('.modal__social-content').hide()
        $('.modal__link').show()
        toogleModal($('.article__copy'), $('.modal__social'))
    }
}

function showSocial() {
    $('.modal__social-content .modal__overflow').prepend($('.article__social'))
    toogleModal($('.article__social-wrap'), $('.modal__social'))
    changeContent($('.article__copy'), $('.modal__link'));
}

function filterActive() {
    $('.tickets__categories > a').click(function () {
        $(this).toggleClass('active');
        let activeTextArray = [];
        $(".active .tickets__categories-name").each(function () {
            let text = $(this).closest('a').data('category_slug');
            activeTextArray.push(text);
        });
        $('.categories__input').val(activeTextArray);

        let filterForm = $('.filter__form');
        page = 1;
        $('.page').val(page);
        sendForm(filterForm, '/wp-admin/admin-ajax.php', filterSuccess)
    });
};

function goBack() {
    const previousPageValue = getCookie("previousPage");
    $(document).on('click', '.article__back',function () {
        if( window.history.length == 0){
            window.location.href = '/'
        }
        window.location.href = `${previousPageValue}`
    })
}

function getPrevPage(){
    let previousPage = document.referrer;
    document.cookie = "previousPage=" + previousPage + "; path=/";
}

function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].trim();

        if (cookie.startsWith(name + '=')) {
            return cookie.substring(name.length + 1);
        }
    }
    return null;
}





function removeNews() {
    $('.news__block .news__close').click(function () {
        $(this).closest('.news').hide();
    });
}

function search() {

    let searchButton = $('.header__search');
    searchButton.click(function () {
        let searchForm = $(this).closest('.header__mob').find('.header__search-form');
        searchForm.show();
        $(document).on("click", function (event) {
            if (!searchForm.is(event.target) && searchForm.has(event.target).length === 0 && !searchButton.is(event.target) && searchButton.has(event.target).length === 0) {
                searchForm.hide();
            }
        });
    });
}

let mySwiper;
function initSlider(prevArr, nextArr, slider) {
        mySwiper = new Swiper(slider,{
            slidesPerView: 3,
            spaceBetween: 30,
            centeredSlides: false,
            loop: true,
            navigation: {
                nextEl: nextArr,
                prevEl: prevArr
            },
            breakpoints: {
                0: {
                    slidesPerView: 1.25,
                    spaceBetween: 10,
                    centeredSlides: false,
                },
                666: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                }
            }
        });
}

function showLabel() {

    $('input').each(function (){
        setTimeout(() => {
            if ($(this).is(':-internal-autofill-selected')) {
                $(this).closest('.modal__form-content').find('.label').addClass('active');
            }
            if ($(this).is(':-internal-autofill-selected')) {
                $(this).closest('.form-row').find('.label').addClass('active');
            }
        }, 500);
    })
    // $('input').change(function (){
    //     $(this).closest('.modal__form-content').find('.label').addClass('active');
    //     $(this).closest('.form-row').find('.label').addClass('active');
    // })

    $('.modal__form-content input').each(function () {
        if($(this).val() !== ''){
            $(this).closest('.modal__form-content').find('.label').addClass('active');
        }
        $(this).click(function () {
            $(this).closest('.modal__form-content').find('.label').addClass('active');
        });
        // $(document).on(this, 'keydown', function(event) {
        //     // Перевіряємо, чи натиснута клавіша "Tab" (код 9)
        //     // if (event.keyCode === 9) {
        //         $(this).closest('.modal__form-content').find('.label').addClass('active');
        //         $(this).closest('.form-row').find('.label').addClass('active');
        //         console.log('Tab key pressed on the current input.');
        //     // }
        //
        // });
    });
    $(document).on('keydown', function (e) {
            $('input').each(function () {
                if ($(this).is(':focus')) {
                    $(this).closest('.modal__form-content').find('.label').addClass('active');
                    $(this).closest('.form-row').find('.label').addClass('active');
                    console.log('Input з фокусом:', $(this).attr('name'));
                }
            });
    });
}

const validateForm = (form, func) => {
    form.on("submit", function (e) {
        e.preventDefault();
    });
    $.validator.addMethod("goodName", function (value, element) {
        return this.optional(element) || /^[\sаА-яЯіІєЄїЇґҐa-zA-Z0-9._-]{2,30}$/i.test(value);
    }, "Please enter correct");

    $.validator.addMethod("goodEmail", function (value, element) {
        return this.optional(element) || /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,62}$/i.test(value);
    }, "Please enter correct email");

    $.validator.addMethod("goodPhone", function (value, element) {
        // return this.optional(element) || /^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/i.test(value);
        return this.optional(element) || /^[+]*[0-9]{10,20}$/g.test(value);
    }, "Please enter correct phone number");

    form.validate({
        rules: {
            name: {
                required: true,
                goodName: true,
                // minlength:2,
                // maxLength: 30
            },

            lastname: {
                required: true,
                goodName: true,
                // minlength:2,
                // maxLength: 30
            },
            phone: {
                required: true,
                goodPhone: true

            },
            phoneAccount:{
                goodPhone: true
            },
            email: {
                required: true,
                goodEmail: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirm: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            passwordNew: {
                required: true,
                minlength: 8
            },
            passwordNewRepeat: {
                required: true,
                minlength: 8,
                equalTo: "#passwordNew"
            },
            band_name: {
                required: true,
                goodName: true
                // minlength:2,
                // maxLength: 25
            },
            contact_name: {
                required: true,
                goodName: true
                // minlength:2,
                // maxLength: 25
            },
        },
        messages: {
            name: {
                required: "This field is required",
                minlength: "First name can't be shorter than 2 characters",
                maxLength: "First name can't be longer than 30 characters "
            },
            lastname: {
                required: "This field is required",
                minlength: "Last name can't be shorter than 2 characters",
                maxLength: "Last name can't be longer than 30 characters "
            },
            phone: {
                required: "This field is required",
                phone: "Please enter correct phone number"
            },
            phoneAccount:{
                phone: "Please enter correct phone number"
            },
            email: {
                required: "This field is required",
                email: "Please enter correct email"
            },
            password: {
                required: "This field is required",
                minlength: "Password can't be shorter than 8 characters"
            },
            password_confirm: {
                required: "Це поле є обов’язкове",
                equalTo: "Passwords not equal",
                minlength: "Password can't be shorter than 8 characters"
            },
            passwordNew: {
                required: "This field is required",
                minlength: "Last name can't be shorter than 8 characters"
            },
            passwordNewRepeat: {
                required: "This field is required",
                equalTo: "Passwords do not match"
            },
            band_name: {
                required: "This field is required",
                minlength: "Band name can't be shorter than 2 characters",
                maxLength: "Band name can't be longer than 30 characters "
            },
            contact_name: {
                required: "This field is required",
                minlength: "Contact name can't be shorter than 2 characters",
                maxLength: "Contact name can't be longer than 30 characters "
            },

        },
        submitHandler: function () {
            func();
            if (!window.location.href.includes('my-account')){
                form[0].reset();
            }

        }
    });
};

// create ajax
function ajaxSend(date, url, funcSuccess, funcError) {
    $.ajax({
        url: url,
        data: date,
        method: 'POST',
        success: function (res) {
            console.log('success ajax');
            funcSuccess(res);
        },
        error: function (error) {
            funcError(error);
        },
        complete: function (){

        }
    });
}

// send form
function sendForm(form, url, funcSuccess, funcError) {
    form = form.serialize();
    ajaxSend(form, url, funcSuccess, funcError);
}

const main = new Swiper('.main__slider', {
    slidesPerView: 1,
    spaceBetween: 60,
    centeredSlides: true,
    loop: true,
    navigation: {
        nextEl: ".main__next",
        prevEl: ".main__prev"
    }
});

// show&hide password
function showPassword() {
    $('.modal__form-eye').click(function (e) {
        $(this).toggleClass('active');
        $(this).hasClass('active') ? $(this).closest('.modal__form-password').find('input').attr('type', 'text') : $(this).closest('.modal__form-password').find('input').attr('type', 'password');
    });
}

// to default after close modal
function resetForm() {
    $('.modal__forget').hide();
    $('.modal__forget-wrap').show();
    $('.modal__forget-mail').hide();
    $('.modal__reg-success').hide();
    $('.modal__join-success').hide();
    $('.modal__reg').hide();
    $('.modal__enter').show();
    $('.label').removeClass('active');
    $('.modal__join .modal__content-full').show();

    if(window.innerWidth <= 666){
        $('.modal__social-content').show()
        $('.modal__link').hide()
    }
}

// change content in modal window
function changeContent(btn, content) {
    btn.click(function () {
        $(this).closest('.modal__content').hide();
        content.show();
        if ($(".modal__social").css("display") === "block") {
            setTimeout(function (){
                $('.modal__social').animate({
                    opacity: 0
                },1000, function() {
                    $(this).closest('.modal__social').hide();
                    resetForm();
                    $('body').css('overflow', 'visible');

                });
            },1500)
        }
    });
}

// open modal with click
function toogleModal(btn, modal) {
    btn.click(function () {
        modal.show();
        $('body').css('overflow', 'hidden');
        $('.modal__social').css('opacity', '1');
        showLabel();
        if ($(".modal__social").css("display") === "block" && window.innerWidth > 666) {
            setTimeout(function (){
                $('.modal__social').animate({
                    opacity: 0
                },2000, function() {
                    $(this).closest(modal).hide();
                    resetForm();
                    $('body').css('overflow', 'visible');

                });
            },2000)
        }
        return false;
    });
    $('.modal__close').click(function () {
        $(this).closest(modal).hide();
        resetForm();
        $('body').css('overflow', 'visible');
        addressLabel()
        showLabel()
        return false;
    });
    $('.modal__ok').click(function () {
        $(this).closest(modal).hide();
        $('body').css('overflow', 'visible');
        addressLabel()
        showLabel()
        return false;
    });
    $(document).keydown(function (e) {
        if (e.keyCode === 27) {
            e.stopPropagation();
            modal.hide();
            resetForm();
            addressLabel()
            showLabel()
            $('body').css('overflow', 'visible');
        }
    });
    modal.click(function (e) {
        if ($(e.target).closest('.modal__content').length == 0) {
            $(this).hide();
            resetForm();
            addressLabel()
            showLabel()
            $('body').css('overflow', 'visible');
        }
    });
}

function toogleModalWithoutClick(modal, func) {
    modal.show();
    $('body').css('overflow', 'hidden');

    $('.modal__close').click(function () {
        $(this).closest(modal).hide();
        $('body').css('overflow', 'visible');
        func();
        return false;
    });
    $('.modal__ok').click(function () {
        $(this).closest(modal).hide();
        $('body').css('overflow', 'visible');

    });
    $(document).keydown(function (e) {
        if (e.keyCode === 27) {
            e.stopPropagation();
            modal.hide();
            $('body').css('overflow', 'visible');
            func();
        }
    });
    modal.click(function (e) {
        if ($(e.target).closest('.modal__content').length == 0) {
            $(this).hide();
            $('body').css('overflow', 'visible');
            func();
        }
    });
    $(document).on('click', '.modal__cart-close', function () {
        $(this).closest(modal).hide();
        $('body').css('overflow', 'visible');
        return false;
    });
}

function tabs(tab, tabItem) {
    tab.click(function () {
        tab.removeClass("active").eq($(this).index()).addClass("active");
        tabItem.hide().eq($(this).index()).fadeIn();

        if($('.posts__slider').length > 0){
            $('.posts__slider').each(function(){
                this.swiper.destroy();
            })
            let tabEq = $(this).index()
            let slider ='.' + $('.posts__tab-item').eq(tabEq).find('.posts__slider').attr("class").split(" ")[0]
            initSlider('.posts__prev', '.posts__next', slider );
        }
        removePayment()

        $('#billing_country').select2({})
        showPayment()
        //
        //     .on('select2:opening', function (e) {
        //     $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder', 'Search');
        //     console.log(222)
        // });
    }).eq(0).addClass("active");
}

function changeFilter() {
    $('.tickets__filter .select2-selection__rendered').each(function () {
        let placeholder = $(this).closest('.filter__item').find('.filter__header').text();
        $(this).text(placeholder);
    });
}

function filterActiveOne() {
    $('.blog__display-item').click(function () {
        $(this).addClass('active');
        $(this).prevAll('.blog__display-item').removeClass('active');
        $(this).nextAll('.blog__display-item').removeClass('active');
        let list = $('.blog__list');
        if ($('.blog__display-menu').hasClass('active')) {
            list.addClass('blog__list-menu');
            list.removeClass('blog__list-list');
        }
        if ($('.blog__display-list').hasClass('active')) {
            list.addClass('blog__list-list');
            list.removeClass('blog__list-menu');
        }
    });
};

function calendar() {
    $(".filter__datepicker").datepicker({
        dateFormat: 'dd/mm/yy',
    });
    $(".birthdate__input").datepicker({
        dateFormat: 'dd/mm/yy'
    });
}

function drop(drop, inner) {
    $(document).on('click', drop, function () {
        $(this).find(inner).toggle();
    });
    $(document).on('click', function (e) {
        let targetElement = drop;
        let clickedElement = $(e.target);
        if (!clickedElement.is(targetElement) && !targetElement.has(clickedElement).length) {
            inner.hide();
        }
    });
}

function dropDown() {
    if (window.innerWidth > 666) {
        drop($('.filter .filter__dropdown'), $('.filter .filter__inner'));
    }
    drop($('.modal .filter__dropdown'), $('.modal .filter__inner'));
    $('.filter__inner-item').each(function () {
        $(this).click(function () {
            $(this).addClass('active');
            $(this).prevAll('.filter__inner-item').removeClass('active');
            $(this).nextAll('.filter__inner-item').removeClass('active');
            let current = $(this).text();
            $(this).closest('.filter__item').find('input').val(current);

            $('.modal__dropdown .filter__header').css('color', '#212121');
            $(this).closest('.modal .filter__item ').find('.filter__header').text(current);
            if (window.innerWidth > 666) {
                $(this).closest('.filter .filter__item ').find('.filter__header').text(current);
            }
            page = 1;
            $('.page').val(page);
            sendForm($('.filter__form'), '/wp-admin/admin-ajax.php', filterSuccess);
        });
    });
}

function filterMob() {
    $('.filter__mob').click(function () {
        $('.filter__mob-wrap').addClass('active');
        $('.hidden').addClass('hidden');
    });
    $(document).on('click', '.filter__mob-close', function () {
        $('.filter__mob-wrap').removeClass('active');
    });
}

function filterSuccess(res) {
    $('.shop__block').html(res);
    $('.shows__block').html(res);
    $('.recommended').remove();
    $('.upcoming ').remove();
    if($('.band__wrap').length>0){
       $('.bands__sort').html(res)
    } else{
        $('.tickets').after(res);
    }

    //
    // $('.upcoming .upcoming__block').remove();
    // $('.upcoming .container').append(res);

    toogleModal($('.shows__map'), $('.modal__map'));
    getCount()
    showMap();
}


function hideMessage(){
    if($('.band__wrap').length > 0 || $('.error__wrap').length > 0 ){
        $('.main__message').hide()
    }
}
function getCount(){
    let count= 0
    $('section').each(function (){
        if($(this).data('found_posts')){
            count += $(this).data('found_posts')
        }

        if($(this).find('.shop__list').data('posts_count')){
            count += $(this).find('.shop__list').data('posts_count')
        }
    })
    $('.filter__mob-show').text(`Show (${count})`)
        let countShop = $('.shop__item').length


}

let page = 1;
function loadMore() {
    $(document).on('click', '.section__load', function () {
        page++;
        let wrapBlog = $(this).closest('.blog__block')
        if($('.blog').length>0){
            page = +wrapBlog.attr('data-page')
            page ++;
        }
        $('.page').val(page);

        if($('.blog__list-list').length > 0){
            $('.blog__list-ajax').val('1')
        }
        let category = $(this).closest('.blog__block').data('category')
        $('.category__input').val(category)
        let form = $('.filter__form');
        const formData = new FormData(form[0]);
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: formData,
            processData: false,
            contentType: false,
            method: 'POST',
            success: function (res) {
                loadMoreSuccess(res)

                wrapBlog.find('.blog__load').remove();
                wrapBlog.append(res);
                wrapBlog.attr('data-page', page)

            },
            error: function (error) {
                console.log('error', error);
            },

        });
    });
}
function showArchivedPost(){
    $(document).on('click', '.blog__display-archived', function (){

        let btn = $(this)
        btn.toggleClass('active')
        if(btn.hasClass('active')){
            $('.filter__form input[name="action"]').val('load_more_blog')
            $('.filter__form input[name="category"]').val('archived_posts')
            $('.blog__block').attr('data-category','archived_posts')
        } else {
            $('.filter__form input[name="action"]').val('not-archived-posts')
        }
        if($('.blog__list-list').length > 0){
            $('.blog__list-ajax').val('1')
        }else{
            $('.blog__list-ajax').val('0')
        }
        $('.page').val(1);
        let form = $('.filter__form');
        const formData = new FormData(form[0]);

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: formData,
            processData: false,
            contentType: false,
            method: 'POST',
            success: function (res) {
                if(btn.hasClass('active')){
                    console.log(3333)
                    $('.blog__block:not(:first-child)').hide()
                    $('.blog__block  > * ').remove();
                    $('.blog__block').append(' <h3 class="blog__block-title">Archived posts</h3>')
                    $('.blog__block ').append(res);
                }else{
                    $('.blog__block-wrap').html(res);
                    $('.filter__form input[name="action"]').val('load_more_blog')
                }

            },
            error: function (error) {
                console.log('error', error);
            },

        });
    })
}

function loadMoreShow() {
    $(document).on('click', '.shows__load', function () {
        let showsDate = $(this).data('date')
        let obj = {action:"filter__shows-by-day", date: showsDate}
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: obj,
            method: 'POST',
            success: function (res) {
                loadMoreSuccess(res)
                // $('.preloader__wrap').hide()
            },
            error: function (error) {
                console.log('error', error);
            },

        });
    });
}

function loadMoreSuccess(res){
    //front page
    $('.music__more').remove();
    $('.music__block').append(res);

    //shows page
    $('.shows__load').remove();
    $('.shows__block').append(res);

    //shop page
    $('.shop__load').remove();
    $('.shop__block').append(res);

    // blog page



    // venues page
    $('.venues__load').remove();
    $('.venues__container').append(res);

    // blog page
    // $('.tickets__load').remove();
    // $('.upcoming__block').append(res);

    $('.upcoming__load').remove();
    $('.upcoming > .container').append(res);

    $('.bands__load').remove();
    $('.bands__sort').append(res);
}

function dropDesc() {
    $('.oneproduct__description-drop').click(function () {
        $('.oneproduct__description-inner').toggleClass('active');
    });
}

function changeOrderStatus(){
    $('.order__status').each(function (){
        if($(this).find('p').text() =='Processing'){
            $(this).find('h4').addClass('processing')
        }
        if($(this).find('p').text() =='Failed'){
            $(this).find('h4').addClass('failed')
        }
        if($(this).find('p').text() =='Cancelled'){
            $(this).find('h4').addClass('cancelled')
        }
        if($(this).find('p').text() =='Completed'){
            $(this).find('h4').addClass('completed')
        }
        if($(this).find('p').text() =='Pending'){
            $(this).find('h4').addClass('pending')
        }
    })
}

function copyUrlHandler () {

    let temp = $("<input>");
    let url = $(location).attr('href');

    $('.article__copy').on('click', function() {
        $("body").append(temp);
        temp.val(url).select();
        document.execCommand("copy");
        temp.remove();
    })
}

function showTickets() {
    let filterForm = $('.filter__form');

    $(document).on('change', '.filter__datepicker', function () {
        page = 1;
        $('.page').val(page);
        sendForm(filterForm, '/wp-admin/admin-ajax.php', function (res) {
            //shows page
            $('.shows__block > *').remove();
            $('.shows__block').html(res);

            filterSuccess(res)
        });
    });
    $(document).on('change', '.filter__select', function () {
        page = 1;
        $('.page').val(page);
        sendForm(filterForm, '/wp-admin/admin-ajax.php', filterSuccess);
    });
    filterForm.on("submit", function (e) {
        e.preventDefault();
        sendForm(filterForm, '/wp-admin/admin-ajax.php'), function () {
            page = 1;
            $('.page').val(page);
            $('.filter__mob-wrap').removeClass('active');
        };
        $('.filter__mob-wrap').removeClass('active');
    });
    filterForm.on("reset", function (e) {
        e.preventDefault();
        page = 1;
        $('.page').val(page);
        $('.filter__sort-input').val('');
        $('.filter__datepicker').val('');
        $('.filter__datepicker').attr('placeholder', 'Date');
        $('.select2-selection__rendered').attr('placeholder', 'Date');
        $('.tickets__categories > a').each(function () {
            $(this).removeClass('active');
        });
        $('.filter__select option').each(function () {
            $(this).removeAttr('selected');
        });
        $('.categories__input').val('');

        changeFilter();

        sendForm(filterForm, '/wp-admin/admin-ajax.php', function (res) {
            page = 1;
            $('.page').val(page);
            filterSuccess(res);
            $('.filter__mob-wrap').removeClass('active');
        });
        $('.filter__inner-item').removeClass('active');
        $('.filter__mob-wrap').removeClass('active');
    });
}

function removePayment(){
    if($('.checkout__tickets-item').length > 0 && $('.checkout__clothes-list li').length > 0 ){
        $('.checkout__info-tickets .checkout__info-tickets').remove();
        $('.checkout__info-tickets #payment').remove();
        $('.checkout__info-tickets .form-create_account').remove();
        $('.checkout__info-tickets').remove();
    }
}

function showPayment(){
    if($(".checkout__clothes-list > li").length == 0 || $(".checkout__tickets-list  > li").length > 0  ){
        $('li.payment_method_cod').hide()
        $('.payment_method_stripe_cc > label').hide()
    }
    $('#payment_method_stripe_cc').prop('checked','checked')
    $('#payment_method_cod').removeAttr('checked')

    $('div.payment_method_cod').hide()
    $('div.payment_method_stripe_cc').show()
    $(document).on('change','li.wc_payment_method input', function (){
        $('li.wc_payment_method input').removeAttr('checked')
        $(this).prop('checked','checked')

        if($('#payment_method_cod').prop('checked')){
            $('div.payment_method_stripe_cc').hide(200)
            $('div.payment_method_cod').show(200)
        } else{
            $('div.payment_method_cod').hide(300)
            $('div.payment_method_stripe_cc').show(200)
        }
    })
}

function resetDataShows() {

    let filterForm = $('.filter__form');
    let today = $('.shows').data('today')


    $(document).on('click','.shows .filter__reset',function () {
        $('.filter__datepicker').val('')
        $('.filter__datepicker').attr('placeholder','Date')

        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: {action:'filter__shows-by-day', date: today },
            method: 'POST',
            success: function (res) {
                console.log('success ajax');
                filterSuccess(res);
            },
            error: function (error) {},
            complete: function (){}
        });
    })

}

function resetFilter() {
    let filterForm = $('.filter__form');

    $(document).on('click','.tickets .filter__reset',function (){
        page = 1;
        $('.page').val(page);
        $('.filter__sort-input').val('');
        $('.filter__datepicker').val('');
        $('.filter__datepicker').attr('placeholder', 'Date');
        $('.select2-selection__rendered').attr('placeholder', 'Date');
        $('.tickets__categories > a').each(function () {
            $(this).removeClass('active');
        });
        $('.filter__select option').each(function () {
            $(this).removeAttr('selected');
        });
        $('.categories__input').val('');

        changeFilter();

        sendForm(filterForm, '/wp-admin/admin-ajax.php', function (res) {
            page = 1;
            $('.page').val(page);
            filterSuccess(res);
        });

        $('.filter__inner-item').removeClass('active');
        $('.filter__mob-wrap').removeClass('active');
    });
}

function enterError(error) {
    if (error.responseJSON.data === "user_not_exists") {
        $('.modal__overflow').prepend('<div class="error-mail">User with this email does not exits</div>');
    }

    if (error.responseJSON.data === "user_mail_exists") {
        $('.modal__overflow').prepend('<div class="error-mail">User with this email already exits</div>');
    }

    if (error.responseJSON.data === "incorrect_password") {
        $('.modal__overflow').prepend('<div class="error-mail">Password is incorrect</div>');
    }
    if (error.responseJSON.data === "user_exists") {
        $('.modal__overflow').prepend('<div class="error-mail">User with this email exists</div>');
    }
    setTimeout(function () {
        $('.error-mail').hide();
    }, 4000);
}



function registerSuccess(res) {
    $('.modal__enter-wrap').hide();
    $('.modal__reg').hide();
    toogleModalWithoutClick($('.modal__reg-success'));
}

function enterSuccess(res) {
    window.location.href = `${res.data.redirect_url}`;
}

function changeColorDropdown() {
    $('.modal__dropdown .filter__header').addClass('modal__dropdown-header');
}

let currentInput;
let currentInputValue;
function changeProfileInfo() {
    $('.profile__info-edit').click(function () {
        currentInput = $(this).closest('.profile__info-input').find('input');
        currentInputValue = currentInput.val();
        $(this).hide();
        currentInput.prop('disabled', false);
        $(this).next('.profile__info-btn').css('display', 'flex');
    });

    $('.profile__info-cancel').click(function () {
        currentInput.prop('disabled', true);
        currentInput.val(currentInputValue);
        $(this).closest('.profile__info-input').find('.profile__info-edit').show();
        $(this).closest('.profile__info-btn').hide();
    });

    $('.profile__info-change').click( function () {
        let profileForm = $('.profile__info');
        let btnShow = $('.profile__info-edit');
        let btnHide = $('.profile__info-btn');

        $('.profile__info-input').find('input').prop('disabled', false);
        // $('.preloader__wrap').show();
        console.log(2222)
        validateForm(profileForm, function () {
            console.log(123)
            btnHide.hide();
            btnShow.show();
            sendForm(profileForm, '/wp-admin/admin-ajax.php', function () {
                console.log(444)
                currentInput.val(currentInput.val());
                $('.profile__info-input').find('input').prop('disabled', true);

                // $('.preloader__wrap').hide();
            });
        });
    });
}

function logout(){
    $('.profile__info-logout').click(function (e){
        e.preventDefault()
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: {action:'logout'},
            method: 'POST',
            success: function () {
                console.log('success ajax');
                window.location.href = '/';
            },
            error: function (error) {},
            complete: function (){}
        });
    })
}

function addLabel(){
    $('.woocommerce-billing-fields__field-wrapper .form-row').each(function (){
        const label = $(this).find('label')
        const span = $(this).find('span')
        const wrap = $(this).find('.woocommerce-input-wrapper')
        const input = $(this).find('input')
        label.addClass('label')
        wrap.addClass('modal__form-content')
        input.attr("placeholder", "")
        input.attr("data-placeholder", "")
        span.append(label)
    })
}

function hideLine(){
    if($('.upcoming').length<1){
        $('.recommended').addClass('recommended__noupcoming')
    }
    if($('.article__recommended').length<1){
        $('.article__content').addClass('article__content-one')
    }
}

function resetSuccess(){
    let currentURL ='/';
    let newURL = currentURL;
    window.history.pushState({path: newURL}, '', newURL);
    $('.modal__reset-password').hide();
    $('.modal__auth').show();
}

function initFilterSelect(){
    $('.filter__select').select2({
    }).on('select2:opening', function (e) {
        $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder', 'Search');
    });
}

$(document).ready(function () {
    hideMessage()
    showArchivedPost()
    removePayment();
    changeOrderStatus();
    initSlider('.posts__prev', '.posts__next','.posts__slider');
    resetFilter()
    addLabel()
    hideLine()
    $('.header__burger').on('click', openMenu);
    changeToDifferentWidth();
    tabs($('.tab'), $('.tab__item'));
    tabs($('.tab__order'), $('.tab__item-order'));
    filterMob();
    initFilterSelect();
    showPayment();
    getPrevPage();
    showMap();
    dropDesc();
    changeFilter();
    filterActive();
    removeNews();
    search();
    filterActiveOne();
    calendar();
    dropDown();
    goBack();
    getCount();
    showTickets();
    resetDataShows();
    showPassword();
    changeColorDropdown();
    changeProfileInfo();
    showLabel();
    changeContent($('.modal__enter-reg'), $('.modal__reg'));
    changeContent($('.modal__reg-enter'), $('.modal__enter'));
    changeContent($('.modal__enter-forget'), $('.modal__forget'));
    toogleModal($('.header__auth'), $('.modal__auth'));
    toogleModal($('.join__button'), $('.modal__join'));
    toogleModal($('.shows__map'), $('.modal__map'));
    loadMore();
    loadMoreShow();
    copyUrlHandler();
    logout();


    // form subs
    let subsForm = $('.subs__form');
    let subsModal = $('.modal__subs');
    validateForm(subsForm, function () {
        sendForm(subsForm, '/wp-admin/admin-ajax.php', function () {
            toogleModalWithoutClick(subsModal);
        });
    });

    //change password
    let passwordForm = $('.password__form');
    let passwordModal = $('.modal__password');
    validateForm(passwordForm, function () {
        sendForm(passwordForm, '/wp-admin/admin-ajax.php', function () {
            toogleModalWithoutClick(passwordModal);
        });
    });

    // form join
    let joinForm = $('.modal__form-join');
    let joinModal = $('.modal__join-success');
    validateForm(joinForm, function () {
        sendForm(joinForm, '/wp-admin/admin-ajax.php', function () {
            $('.modal__join .modal__content-full').hide();
            toogleModalWithoutClick(joinModal);
        });
    });

    // let searchForm = $('.header__search-form');
    // validateForm(searchForm, function () {
    //     sendForm(searchForm, '/wp-admin/admin-ajax.php'), function () {
    //         $('.header__search-form').hide();
    //     };
    // });

    // sign in
    let formEnter = $('.modal__form-enter');
    validateForm(formEnter, function () {
        sendForm(formEnter, '/wp-admin/admin-ajax.php', enterSuccess, enterError);
    });

    // form checkhout filter
    // THIS LOGIC TRIGGER ERRORS WHEN WE HAVE TICKETS FIELDS
    // let formCheckoutTicket = $('.checkout__tickets-form');
    // validateForm(formCheckoutTicket, function () {
    //     // sendForm(formCheckoutTicket, '/wp-admin/admin-ajax.php',function (){
    //         $('.checkout .tab').removeClass('active')
    //         $('.checkout .tab:last-child').addClass('active')
    //         $('.checkout .tab__item:first-child').hide()
    //         $('.checkout .tab__item:last-child').show()
    //     // });
    // });

    // register form
    let formRegister = $('.modal__form-reg');
    validateForm(formRegister, function () {
        sendForm(formRegister, '/wp-admin/admin-ajax.php', registerSuccess, enterError);
    });


    // forgot password form
    let formForgot = $('.modal__form-forget');
    validateForm(formForgot, function () {
        sendForm(formForgot, '/wp-admin/admin-ajax.php'), function (){
            $('.modal__forget').hide();
            $('.modal__forget-mail').show();
        };
    });


    //reset passpord
    let formReset = $('.modal__reset-password-form');
    validateForm(formReset, function () {
        sendForm(formReset, '/wp-admin/admin-ajax.php', resetSuccess);
    });

});

$(window).load(function () {


});

$(window).resize(function () {});
//# sourceMappingURL=index.js.map
