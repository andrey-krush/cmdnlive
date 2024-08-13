
function changeCount(){
    let quantity = 0
    let quantityCount = $('.quantity').length
    $('.quantity input').each(function (){
        quantity += +$(this).val()
    })
    let countItem = $('.cart__item').length
    let total = quantity - quantityCount + countItem
    if(total === 1){
        $('.cart__black .cart-subtotal th').text(`${total} Item`)
        $('.cart__header-count').text(`${total} good`)
    } else{
        $('.cart__black .cart-subtotal th').text(`${total} Items`)
        $('.cart__header-count').text(`${total} goods`)
    }
}


function checkoutTicketInfo(){
    if($('.tab').length == 1 && $(".checkout__clothes-list> li").length == 0  ){
        $('.checkout__tickets-button').hide()
        $('.checkout__info-tickets').show()
        $('.checkout__info-tickets #customer_details').hide()
        $('.form-create_account').hide()
        $('.checkout__password').hide()
        $('#create_account').removeAttr('checked')
    }
    if($('.tab').length == 1 && $(".checkout__clothes-list > li").length == 0 && $(".checkout__tickets-list > li").length % 2 !== 0 ){
        $('.checkout__info-tickets').addClass('half')
        $('.checkout__tickets-list').append($('.checkout__info-tickets'))
        $('.checkout__info-tickets #customer_details').hide()
        $('.form-create_account').hide()
    }
    if($(".checkout__clothes-list > li").length > 1){
        $('.checkout__info-tickets').remove()
    }
}




function accountError(error) {
    if (error.responseJSON.data === "user_mail_exists") {
        $('.checkout__info-wrap').prepend('<div class="error-mail">User with this email exists</div>');
    }

    setTimeout(function () {
        $('.error-mail').hide();
    }, 4000);
}



function deleteFromCheckout(){
    if ($(".checkout__clothes-list > li").length == 0 && $('.tab').length==1) {
        $('.tab__item:first-child .checkout__tickets-list').append($('#order_review'))
        $('#order_review').addClass('order__review-half')
        $('#create_account').removeAttr('checked')
    }
    $('.checkout .product-remove').click(function (){
        let item = $(this).closest('.checkout__item')
        let currency = $('.order-total .woocommerce-Price-amount').text().charAt(0)
        let price =  parseFloat($(this).closest('.checkout__item').find('.cart__price span').text().substring(1))
        let key =  $(this).closest('.checkout__item').data("item_key")

        let itemKey = $(".checkout__item[data-item_key='" + key + "']");
        let itemKeySubtotal = $("tr[data-item_key='" + key + "']");

        let quantity = $(this).closest('.checkout__item').data("item_quanity")
        let newQuantity;
        if ( quantity == 1 ){
             newQuantity =  0
        } else{
            newQuantity =  +$(this).closest('.checkout__item').data("item_quanity") - 1
        }
        itemKey.each(function() {
            $(this).attr("data-item_quanity",newQuantity)
        });

        itemKeySubtotal.each(function() {
            let subtotal = (newQuantity * price).toLocaleString('en-US', {minimumFractionDigits: 2});
            $(this).find('.product-total .woocommerce-Price-amount').text(`${currency}${subtotal}`)
        });

        $('.header__bag-counter span').each(function (){
            let headerCount = $(this).text()
            $(this).text(headerCount - 1)
        })
        item.remove()


        if ($(".checkout__clothes-list > li").length == 0 && $('.tab').length>1) {
            $('.tab__item:first-child .checkout__tickets-list').append($('#order_review'))
            $('.tab:last-child').remove()
            $('.tab__item:last-child').remove()
            $('.tab').addClass('active')
            $('.checkout__tickets-button').hide()
            $('.checkout__password').hide()
            $('.tab__item').show()
            $('li.payment_method_cod').hide()
            $('#order_review').addClass('order__review-half')
            $('#create_account').removeAttr('checked')
        }
        if($(".checkout__tickets-list > li").length == 0 && $('.tab').length>1) {
            $('.tab:first-child').remove()
            $('.tab__item:first-child').remove()
            $('.tab').addClass('active')
            $('.tab__item').show()
            $('li.payment_method_cod').show()
            addressLabel()
            $('.payment_method_stripe_cc > label').show()
            $('.tab').click(function (){
                $('.tab__item').show()
                $(this).addClass('active')
            })
        }
        $('#order_review .cart_item').each(function (){
            let reviewKey = $(this).data("item_key")
            if(key == reviewKey){
                $(this).find('.product-quantity').text(`× ${newQuantity}`)
                if( newQuantity == 0){
                    $(this).remove();
                }
            }
        })
        if($('.tab').length == 1 && $(".checkout__clothes-list > li").length == 0 && $(".checkout__tickets-list > li").length == 0  ){
            $('.preloader__wrap').show()
        }

        let date= {action:"change_checkout_quantity", key, quantity:newQuantity}
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function (res) {
                $('#billing_country').select2({

                }).on('select2:opening', function (e) {
                    $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder', 'Search');
                });
                changeTotal(res)
                if($('.tab').length == 1 && $(".checkout__clothes-list > li").length == 0 && $(".checkout__tickets-list > li").length == 0  ){
                    $('.checkout__wrap').addClass('checkout__wrap-empty')
                    // location.reload();

                    console.log(window.location.origin)

                    let newURL = window.location.origin + "/cart";
                    window.location.href = newURL;
                }
            },
            error: function (error) {

            },
            complete: function (){

            }
        });

    })
    console.log(window.location)
}


function priceScroll(){
    if( $('.product-type-simple').length > 0){
        $('p.scroll__info-inside').addClass('price')
        $('.scroll__info').addClass('scroll__info-single')
    }
}

function fixThank(){
    $('.thank__wrap').append($('.woocommerce-customer-details'))
}



function counter(){
    let counterProduct = $('.oneproduct .cart__counter')
    counterProduct.click(function () {
        let currentValue = parseInt($(this).closest('.quantity').find('input').val())
        if($(this).hasClass('cart__plus')){
            $('.quantity .input-text').each(function (){
                $(this).val(currentValue + 1);
            })
        }else{
            $('.quantity .input-text').each(function (){
                if ($(this).val() > 1 ) {
                    $(this).val( currentValue - 1)
                }
            })
        }
        changeCount()
    });
    let counterCart = $('.woocommerce-cart-form .cart__counter')
    counterCart.click(function (){
        let productCurrency = $('.order-total span').text().charAt(0)
        let currentValue = parseInt($(this).closest('.quantity').find('.input-text').val())
        let cartItem = $(this).closest('.cart__item').data('cart_item')
        let cartQuantity
        let productPrice = $(this).closest('.cart__item').find('.cart__price bdi')
        productPrice = parseFloat(productPrice.text().replace(/[^\d.]/g, ''))
        let total
        if($(this).hasClass('cart__plus')){
           $(this).closest('.quantity').find('input').val(currentValue + 1)
        }else{
            if ($(this).closest('.quantity').find('input').val() > 1 ) {
                $(this).closest('.quantity').find('input').val( currentValue - 1)
            }
        }
        total = ($(this).closest('.quantity').find('input').val() * productPrice).toFixed(2)
        total = parseFloat(total).toLocaleString('en-US', {minimumFractionDigits: 2});
        $(this).closest('.cart__item').find('.product-subtotal bdi').text(`${productCurrency}${total}`)

        cartQuantity = $(this).closest('.quantity').find('input').val()

        let date = {action: "change_item_quantity", cart_item: cartItem, quantity:cartQuantity}
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function (res) {
                console.log('success ajax');
                changeTotal(res)
                $('.header__bag-counter span').each(function (){
                    $(this).text(res.data.items_count)
                })
                changeCount()
            },
            error: function (error) {
                console.log('error ajax');
            },
            complete: function () {
            }
        });

    })

}

let del = 0
function changeTotal(res){
    let productCurrency = $('.order-total span').text().charAt(0)
    let orderTotal = parseFloat(res.data.total_price).toLocaleString('en-US', {minimumFractionDigits: 2});
    let subtotal
    if( $('.cart-discount').length > 0 && del == 0  ){
        let disc = +$('.cart-discount .woocommerce-Price-amount').text().slice(1,-3)

        let sub = parseFloat(res.data.total_price) + disc
        subtotal = parseFloat(sub).toLocaleString('en-US', {minimumFractionDigits: 2});
    }else {
        subtotal = orderTotal
    }
    $('.order-total .woocommerce-Price-amount').text(`${productCurrency}${orderTotal}`)
    $('.cart-subtotal .woocommerce-Price-amount').text(`${productCurrency}${subtotal}`)
}


function hideCash(){
    // setTimeout(function (){
    //     if($(".checkout__tickets-list > li").length > 0){
    //         console.log(123, $('li.payment_method_cod'))
    //         $('li.payment_method_cod').hide()
    //         $('li.payment_method_stripe_cc > label').hide()
    //
    //     }
    // },200)

   // hide Cash on delivery on tickets checkout 🩼
   const intervalId = setInterval(function () {
      if ($(".checkout__tickets-list > li").length > 0) {
         console.log(123, $('li.payment_method_cod'))
         $('li.payment_method_cod').hide()
         $('li.payment_method_stripe_cc > label').hide()

         // clear Interval id
         setTimeout(function () {
            clearInterval(intervalId);
         }, 10000);
      }
   }, 100);
}


function deleteCart(){


    $('.woocommerce-cart-form .product-remove').click(function (){
        let cartItem = $(this).closest('.cart__item').data('cart_item')
        let item = $(this).closest('.cart__item')
        let quantity = $(this).closest('.cart__item').find('.quantity input').val()
        let date = {action: "delete_item_from_cart", cart_item: cartItem}
        if($('.cart__item').length == 1){
            $('.preloader__wrap').show()
        }
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function (res) {
                console.log('success ajax');
                item.remove();
                changeCount()
                changeTotal(res)
                $('.header__bag-counter span' ).each(function (){
                    let countHeader = +$(this).text() - quantity
                    $(this).text(countHeader)
                    if($('.cart__item').length == 0){
                        location.reload();
                    }
                })
            },
            error: function (error) {
                console.log('error ajax');
            },
            complete: function () {
            }
        });


    })
}


function applyCoupon(){
    $('.coupon .button').click(function (){
        let coupon = $(this).closest('.coupon').find('.input-text').val()
        let date = {action: "apply_coupon", coupon}
        $('.coupon__mess').remove()
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function (res) {
                console.log('success ajax', res.success );
                if( res.success == true){
                    let productCurrency = $('.order-total span').text().charAt(0)
                    let couponDiscount = parseFloat(res.data.discount_amount).toLocaleString('en-US', {minimumFractionDigits: 2});
                    $('.cart-subtotal').after(`<tr class="cart-discount">
                            <th>Coupon: ${coupon}</th>
                            <td>-<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">${productCurrency}</span>${couponDiscount}</span> <a></a></td>
                        </tr>`);

                    $('.actions').append('<p class="coupon__mess">Coupon has been applied</p>')
                    changeTotal(res)

                    setTimeout(function (){
                        $('.coupon__mess').hide()
                    },5000)
                } else{
                    $('.actions').append('<p class="coupon__mess coupon__mess-error">Coupon already applied</p>')
                    setTimeout(function (){
                        $('.coupon__mess').hide()
                    },5000)
                }

            },
            error: function (error) {
                console.log('error ajax');
                $('.actions').append('<p class="coupon__mess coupon__mess-error">Coupon not found</p>')
                setTimeout(function (){
                    $('.coupon__mess').hide()
                },5000)

            },
            complete: function () {
            }
        });
    })
}


function removeCoupon(){
    $('.cart-discount a').text('')
    $('.cart-discount a').attr('href','')
    $('.cart-discount a').removeClass('woocommerce-remove-coupon')

    $(document).on('click','.cart-discount a', function (e){
        e.preventDefault()
        let date = {action: "remove_coupon" }
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function (res) {
                console.log('success ajax');
                del=1;
                changeTotal(res)
                del=0;
                $('.cart-discount').remove()
            },
            error: function (error) {
                console.log('error ajax');
            },
            complete: function () {
            }
        });
    })
}


function disButton(){
    console.log(555,$('.disabled'), $('.disabled').length)
    setTimeout(function (){
        console.log(666,$('.disabled'), $('.disabled').length)
        $('button').each(function (){
            if($(this).hasClass('disabled')){
                $(this).attr('disabled', 'true')
            } else{
                $(this).removeAttr('disabled')
            }
        })

    },500)

}


console.log(111111, $('#size').length)
console.log(111111444, $('select#size'))
console.log(111111333, $('select'))
console.log(222222, $('#size').val)
function addActiveLabel(){
    $('input').each(function (){
        $(this).prop('autocomplete','off')
    })
    $('.input-text').each(function (){

        // $(this).prop('autocomplete','off')

        if( $(this).val()!==''){
            $(this).closest('.modal__form-content').find('.label').addClass('active')
        }
        // if ($(this).attr('autocomplete') !== undefined && $(this).attr('autocomplete') !== '' ) {
        //     $(this).closest('.woocommerce-input-wrapper').find('.label').addClass('active')
        // }
    })

    $(document).on('change','.modal__form-content input', function (){
        $('.modal__form-content input').each(function (){
            if( $(this).val()!==''){
                $(this).closest('.modal__form-content').find('.label').addClass('active')
            }
        })

    })
}

function addValidationMessage(){
    $(document).on('click', '#payment .section__btn', function (){
            setTimeout(function () {
                let errorId
                let id
                $('.woocommerce-error li').each(function (){
                    errorId= $(this).attr('data-id')
                    $('.form-row').each(function (){
                        id = $(this).find('.input-text').attr('id')
                        if(errorId === id) {
                            $(this).find('.label').addClass('active')
                            $(this).find('.woocommerce-input-wrapper').prepend(`<label class="error" htmlFor=${id}>This field is required</label>`)
                        }
                    })

                })

            }, 2000);
        }
    )
    $(document).on('change focus','.input-text', function (){
        $(this).closest('.modal__form-content').find('.error').remove();
        $('.input-text').each(function (){
            if($(this).val() !== ''){
                $(this).closest('.modal__form-content').find('.label').addClass('active')
            }
        })
    })
}

let galleryThumbs = new Swiper('.gallery-thumbs', {
    spaceBetween: 10,
    slidesPerView: 6.4,
    freeMode: true,
    watchSlidesVisibility: true,
    watchSlidesProgress: true,
});


let galleryTop = new Swiper('.gallery-top', {
    spaceBetween: 10,
    mousewheel: true,
    thumbs: {
        swiper: galleryThumbs,
    },
    pagination: {
        el: '.swiper-pagination',
    },
});

function changePlacePrice(){
    let variationPrice = $('.woocommerce-variation-price')
    if(variationPrice){
        variationPrice.insertAfter(".product_title");
    }
    // $('.shop__variant').closest('.scroll__info').find('.price').hide()
}

function addToCartModal(){
    if(!$('.out-of-stock')){

        //
        // $(document).on('click', '.single_add_to_cart_button', function(e) {
        //     $('.modal__cart').show();
        // });
    }

}

function addWrap(){
     $('.scroll__info .product_title').addClass('scroll__info-inside');
     $('.scroll__info .price').addClass('scroll__info-inside');
    let item =$('.scroll__info-inside')
    let wrapper = $('<div class="scroll__info-item"></div>');
    item.wrapAll(wrapper);
    $('.scroll__info-item > .scroll__info-inside').removeClass('price')
}

function addressLabel(){
    $('#billing_country_field .label').addClass('active')
}

function getVariant(){
    let arrProduct = []
    $(".cart__list .product-name a").each(function () {
        arrProduct = $(this).text().split(" - ")
        $(this).text(arrProduct[0])
        if( arrProduct[1]){
            $(this).closest('.cart__item-info').find('.cart__size').text(`${arrProduct[1]}`)
        }
    });
}

function closeModal(modal) {
    $('body').css('overflow', 'hidden');
    $('.modal__close').click(function () {
        $(this).closest(modal).hide();
        $('body').css('overflow', 'visible');
        return false;
    });
    $(document).keydown(function (e) {
        if (e.keyCode === 27) {
            e.stopPropagation();
            modal.hide();
            $('body').css('overflow', 'visible');
        }
    });
    modal.click(function (e) {
        if ($(e.target).closest('.modal__content').length == 0) {
            $(this).hide();
            $('body').css('overflow', 'visible');
        }
    });
}

function hideSearch(){
    if(window.location.href.includes('product')){
        $(document).on('click','.select2', function (){
            $('.select2-search--dropdown').hide()
        })
    }
}

function openPopup(){
    if(window.location.href.includes('reset_password')){
        let modal = $('.modal__reset-password')
        modal.show()
        closeModal(modal)
    }
    if(window.location.href.includes('authorization')){
        let modal = $('.modal__auth')
        modal.show()
        closeModal(modal)
    }
}


let count = 0;
function submitCheckoutForm(){
    $('.checkout__tickets-button').click(function (){
        count = 0;
        validateTickets();
        if(count === $('.checkout__tickets-input input').length){
            $('.tab').removeClass('active')
            $('.tab:nth-child(2)').addClass('active')
            $('.tab__item:first-child').hide()
            $('.tab__item:nth-child(2)').show()
        }
        hideCash()
    })
    $('.checkout').on('checkout_place_order', function() {
        const isAccPasswordVisible = $('.checkout__password').is(':visible');
        const passwordAccInp = isAccPasswordVisible ? parseInt($('.checkout__password input').length) : 0;
        const allInputsCount = parseInt($('.checkout__tickets-input input').length) + passwordAccInp;
        count = 0;
        validateTickets();
        console.log(1111)
        setTimeout(function (){
            if($('.woocommerce-error').text() =='Error processing checkout. Please try again.'){
                console.log(123456)
                $('#billing_email_field').append('<div class="error-mail">User with this email exists</div>');
            }
        },1000)
        console.log($('.woocommerce-error').text() )
        if (count !== allInputsCount) {
            count = 0;
            return false;
        }



    })
}






function validateTickets(){
    const checkoutAccPassword = $('.checkout__password input[type="password"]');
    const checkoutAccPasswordVal = checkoutAccPassword.val();
    const isNeedToCreateAcc = $('#create_account').is(':checked');
    const isAccPasswordVisible = $('.checkout__password').is(':visible');
    const emailRegex = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,62}$/;

    function handleFocusClearErrors() {
        const currentField = $(this);
        currentField.closest('.modal__form-content').find('.error').remove();
    }

    function handleFieldsValidation(type = 'text', selector) {
        $(selector).each(function() {
            const currentField = $(this);
            const currentFieldVal = $(this).val();
            const currentFieldWrapper = currentField.closest('.modal__form-content');

            if (type === 'email' && currentFieldVal === '') return currentFieldWrapper.append(`<label class="error">This field is required</label>`);
            if (type === 'email' && !emailRegex.test(currentFieldVal)) return currentFieldWrapper.append(`<label class="error">Invalid email</label>`);
            if (type === 'text' &&  currentFieldVal === '') return currentFieldWrapper.append(`<label class="error">This field is required</label>`);
            if (type === 'text' && currentFieldVal.length < 3) return currentFieldWrapper.append(`<label class="error">Minimum 3 symbols</label>`);

            count++;
            return currentField.attr("data-valid", "true");
        });
    }

    $('.checkout__tickets-form input').each(function() {
        $(this).closest('.modal__form-content').find('.error').remove();
    });
    $('.checkout__password input').closest('.modal__form-content').find('.error').remove();

    handleFieldsValidation('email', '.checkout__tickets-form input[type="email"]');
    handleFieldsValidation('text', '.checkout__tickets-form input[type="text"]');

    if (checkoutAccPassword.length > 0) {
        if (checkoutAccPasswordVal === '') {
            checkoutAccPassword.closest('.modal__form-content').append(`<label class="error" >This field is required</label>`);
        }
        if (checkoutAccPasswordVal.length < 8 && checkoutAccPasswordVal.length > 1 ) {
            checkoutAccPassword.closest('.modal__form-content').append(`<label class="error" >Password must be at least 8 character long</label>`);
        }
        if (checkoutAccPasswordVal !== '' && checkoutAccPasswordVal.length > 7  && isAccPasswordVisible && isNeedToCreateAcc) {
            count++;
            checkoutAccPassword.attr("data-valid","true");
        }
    }
    $(document).on('focus', '.checkout__tickets-form input, .checkout__password input', handleFocusClearErrors);
}


function addToCart(){
    function changeCountHeader(){
        let count = $('.oneproduct__block .quantity input').val()
        $('.header__bag-counter span' ).each(function (){
            let countHeader = +$(this).text() + +count
            $(this).text(countHeader)
        })
    }
    function clickButton(e) {
        e.preventDefault()
        let id = $('section.oneproduct').data("id")
        let variable = $('.oneproduct__block .variations select').val()
        console.log('variable', variable)
        let quantity = $('.oneproduct__desc .quantity .input-text').val()
        let date = {action: "add-to-cart", id, quantity, variable}
        $.ajax({
            url: '/wp-admin/admin-ajax.php',
            data: date,
            method: 'POST',
            success: function () {
                console.log('success ajax');
                changeCountHeader()
                toogleModalWithoutClick($('.modal__cart'));
            },
            error: function (error) {
                console.log('error ajax');
            },
            complete: function () {

            }
        });
    }
    $('.oneproduct__desc button[name="add-to-cart"]').click( function (e){
        clickButton(e)
    })
    $('.variations_button .single_add_to_cart_button').click(function (e){
        clickButton(e)
    })
    // $('.scroll__info button[type="submit"]').click( function (e){
    //     clickButton(e)
    // })
}



function priceVariant(){
    // $('#ticket-type option:first-child').hide()
    // $('#ticket-type-scroll option:first-child').hide()
    $('.scroll__info .price__variant').remove()

    // $('option[value="Fan"]').each(function (){
    //     $(this).trigger('click')
    //     console.log(123)
    //     $(this).attr('selected','selected')
    // })
    setTimeout(function (){
        let startPrice = $('.oneproduct__desc .woocommerce-variation-price')

        let startPriceClone = startPrice.clone()

        if( $('.product-type-variable').length>0){
            $('.scroll__info-item p.scroll__info-inside').text('')
        }

        $('.oneproduct__desc .price__variant').append(startPrice)
        $('.scroll__info-item p.scroll__info-inside').append(startPriceClone)

        if('.oneproduct__desc .price__variant > *'.length>0){
            $('.oneproduct__desc .price__variant .woocommerce-variation-price').append($('.oneproduct__desc .woocommerce-variation-availability'))
        }
    },100)

    $(document).on('change', '.oneproduct__block select', function (){
        $('.price__variant > *').remove();
        $('.scroll__info-item p.scroll__info-inside>*').remove()

        let price = $('.oneproduct__desc .woocommerce-variation-price')
        let priceClone = price.clone()
        let onStock = $('.oneproduct__desc .woocommerce-variation-availability')
        let onStockClone = onStock.clone()

        $('.oneproduct__desc .price__variant').append(onStock)
        $('.oneproduct__desc .price__variant').append(price)

        $('.scroll__info-item p.scroll__info-inside').append(onStockClone)
        $('.scroll__info-item p.scroll__info-inside').append(priceClone)

    })
}



function getOptionSize(){
    let arrText= [];
    let arrValue= [];
    let arrStock= [];



    $(".oneproduct__block select#size option").each(function () {
        arrText.push($(this).text());
        arrValue.push($(this).val());
        if($(this).hasClass('outofstock')){
            arrStock.push(1);
        } else{
            arrStock.push(0);
        }
    });
    arrText.shift()
    arrValue.shift()
    arrStock.shift()

    if($(".scroll__info select#size").length > 0 ){
        $(".scroll__info").addClass('clothes__info')
        $('.clothes__info .button ').removeClass('disabled')
    }


    let options = $(".oneproduct__block select#size .enabled");
    options.each(function(index, element) {
        if ($(element).hasClass('outofstock')) {
            return true;
        } else {
            $(element).attr('selected','selected');
            return false;
        }
    });

    let optionsScroll = $(".scroll__info select#size .enabled");
    optionsScroll.each(function(index, element) {
        if ($(element).hasClass('outofstock')) {
            return true;
        } else {
            $(element).attr('selected','selected');
            return false;
        }
    });

    $.each(arrText,function (key,value) {
        let createElem = $(`<div class='product__select-item' data-val='${arrValue[key]}' data-onstock='${arrStock[key]}'>${value} </div>`)
        $(".shop__variant").append(createElem)
    });
    if($('select#size').length==0 ){
        $(".shop__variant").remove()
    }

    $('.product__select-item').each(function (){
        if($(this).data('onstock') == 1){
            $(this).addClass('product__select-item-outstock')
            $(this).removeClass('product__select-item')
        }
    })

    let outputSelect = $(".oneproduct__desc select#size")

    $(document).on('click','.scroll__info .product__select-item',function(){
        $(this).closest('.shop__variant').find('.active').removeClass('active');
        $(this).addClass('active');
        outputSelect.val($(this).attr('data-val')).trigger('change');
        let val = $(this).data('val')


        $('.clothes__info .button ').removeClass('disabled')

        $('.oneproduct__desc .product__select-item').each(function (){
            if($(this).data('val') == val){
                $('.oneproduct__desc .product__select-item').removeClass('active')
                $(this).addClass('active')
            }
        })
    });

    $(document).on('click','.oneproduct__desc .product__select-item',function(){
        $(this).closest('.shop__variant').find('.active').removeClass('active');
        $(this).addClass('active');
        outputSelect.val($(this).attr('data-val')).trigger('change');
        let val = $(this).data('val')
        if (window.innerWidth <= 666) {
            if ($('.oneproduct__desc .price__variant .woocommerce-variation-availability').text()!== "") {
                $('.oneproduct__desc .shop__variant').css('margin-top', '5.6rem')
            } else {
                $('.oneproduct__desc .shop__variant').css('margin-top', '2.6rem')
            }
        }

        $('.scroll__info .product__select-item').each(function (){
            if($(this).data('val') == val){
                $('.scroll__info .product__select-item').removeClass('active')
                $(this).addClass('active')
            }
        })
    });
    let option = $('.shop__variant .product__select-item')
    let optionLenght = option.length
    let active = optionLenght/2
    let activeSize = option.eq(active)
    let activeScroll = option.eq(0)
    activeScroll.trigger('click');
    activeSize.trigger('click');

    if(outputSelect.length > 0 ){
        $('.variations').css('width','0')
        $('.oneproduct .variations_form').css('gap','0')
        $('.woocommerce-variation-add-to-cart').addClass('full-width')
        $('.single_variation_wrap').addClass('single_variation_wrap-full')
    }
}


function selectChange(){
    $('#ticket-type').on('change', function() {
        let selectedValue = $(this).val();
        if (selectedValue !== $("#ticket-type-scroll").val()) {
            $("#ticket-type-scroll").val(selectedValue).trigger("change");
        }
        disButton()
    });
    $('#ticket-type-scroll').on('change', function() {
        let selectedValue = $(this).val();
        if (selectedValue !== $("#ticket-type").val()) {
            $("#ticket-type").val(selectedValue).trigger("change");
        }
        disButton()
    });
}

function showPasswordField(){
    $(document).on('click','#create_account',function (){
        if ($('#create_account').prop('checked')) {
            $('.checkout__password').show()
            $('#billing_password').attr("required", true);
        } else {
            $('.checkout__password').hide()
            $('#billing_password').removeAttr('required')
        }
    })
}

function addLine(){
    if($('select#size').length>0){
        $('.shop__variant').addClass('line')
    }
    if($('.single_image').length>0){
        $('.swiper-pagination').remove();
    }
}

function initSelectType(){
    $('#ticket-type').select2();
    $('#ticket-type-scroll').select2();
    let selectFirstOptionVal = $("#ticket-type option").eq(1).val();
    $(`option[value=${selectFirstOptionVal}]`).prop('selected', true);
}

function initSelectCountry(){
    $('#billing_country').select2().on('select2:opening', function (e) {
        $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder', 'Search');
    });
}

function showStock(){
    if (window.innerWidth <= 666) {
        setTimeout(function () {
            if ($('.oneproduct__desc .price__variant .woocommerce-variation-availability').text()!== "") {
                $('.oneproduct__desc .shop__variant').css('margin-top', '5.6rem')
            }
        }, 100)
    }
}
$(document).ready(function () {
    addLine()
    deleteCart()
    applyCoupon()
    showPasswordField()
    addToCartModal()
    getVariant()
    changePlacePrice()
    changeCount()
    deleteFromCheckout()
    submitCheckoutForm()
    addActiveLabel()
    counter()
    addressLabel()
    addWrap()
    openPopup()
    hideSearch()

    addToCart()
    checkoutTicketInfo()
    priceScroll()
    removeCoupon()
    fixThank()
    initSelectType();
    initSelectCountry();
    hideCash()

});

$(window).load(function () {
    addValidationMessage();
    getOptionSize();
    priceVariant();
    selectChange();
    showStock()
    disButton()
    $('.preloader__wrap').hide()
    // $(document).trigger("click");
});

$(window).scroll(function () {
    var scrolled = $(window).scrollTop();
    if(scrolled>300){
        $('.scroll__info').show()
    }else{
        $('.scroll__info').hide()
    }
});
