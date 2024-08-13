<!-- FOOTER -->
<section>
    <div class="modal modal__subs">
        <div class="modal__wrap">
            <div class="modal__content modal__content-full">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Thank you for subscribing</h2>
                    </div>
                    <div class="modal__subtitle">
                        <h3>We promise to send you only the most useful news. Now you will always be aware of all
                            promotional offers!</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal__cart">
        <div class="modal__wrap">
            <div class="modal__content">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>The item has been added to the shopping cart</h2>
                    </div>
                    <div class="modal__cart-subtitle">
                        <h3>To place an order, go to the shopping cart and enter all the necessary data</h3>
                    </div>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="section__button">To order</a>
                    <button class="modal__cart-close">Continue shopping</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal__join">
        <div class="modal__wrap">
            <div class="modal__content modal__content-full">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Join Us in CMDN</h2>
                    </div>
                    <form action="" class="modal__form modal__form-join ">
                        <input type="hidden" name="action" value="join">
                        <div class="modal__join-flex">
                            <div class="modal__form-content">
                                <input name="band_name" id="band" type="text"/>
                                <label class="label" for="band">Band’s name*</label>
                            </div>
                            <div class="modal__form-content">
                                <input name="contact_name" id="firstname" type="text"/>
                                <label class="label" for="firstname">Contact name*</label>
                            </div>


                        </div>
                        <div class="modal__join-flex">
                            <div class="modal__form-content">
                                <input name="email" type="email" id="emailJoin"/>
                                <label class="label" for="emailJoin">Email*</label>
                            </div>
                            <div class="modal__form-content">
                                <input name="phone" id="phone" type="text"/>
                                <label class="label" for="phone">Phone number*</label>
                            </div>

                        </div>
                        <div class="modal__join-flex">
                            <div class="modal__form-content">
                                <input name="spotify" id="spotify" type="text"/>
                                <label class="label" for="spotify">Spotify link</label>
                            </div>
                            <div class="modal__form-content">
                                <input name="instagram" id="instagram" type="text"/>
                                <label class="label" for="instagram">Instagram link</label>
                            </div>


                        </div>
                        <div class="modal__join-flex">
                            <div class="modal__form-content">
                                <input name="youtube" id="youtube" type="text"/>
                                <label class="label" for="youtube">Youtube link</label>
                            </div>
                            <div class="modal__form-content">
                                <input name="website" id="website" type="text"/>
                                <label class="label" for="website">Website link</label>
                            </div>


                        </div>
                        <div class="modal__form-content">
                            <input name="message" id="message" type="text"/>
                            <label class="label" for="message">Messagebox</label>
                        </div>

                        <button type="submit" class="section__btn">Send</button>
                    </form>
                </div>
            </div>
            <div class="modal__content modal__join-success">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>We send your form!<br>Thank You</h2>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal modal__reset-password">
        <div class="modal__wrap">
            <div class="modal__content">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Reset password</h2>
                    </div>
                    <form action="" class="modal__form modal__reset-password-form ">
                        <input type="hidden" name="action" value="reset_password">
                        <?php if( !empty( $_GET['confirm'] ) ) : ?>
                            <input name="email" type="hidden" value="<?php echo $_GET['confirm']; ?>"/>
                        <?php endif; ?>
                        <div class="modal__form-content modal__form-password">
                            <input name="password" id="password" type="password"/>
                            <label class="label" for="passwordEnter">Password</label>
                            <div class="modal__form-eye"></div>
                        </div>
                        <div class="modal__form-content modal__form-password">
                            <input name="password_confirm" id="passwordConfirm" type="password"/>
                            <label class="label" for="passwordConfirm">Repeat password</label>
                            <div class="modal__form-eye"></div>
                        </div>
                        <button type="submit" class="section__btn">Reset password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal__map">
        <div class="modal__wrap">
            <div class="modal__content modal__content-full">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="map" id="map"></div>

                    <script>
                        (g => {
                            var h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary",
                                q = "__ib__", m = document, b = window;
                            b = b[c] || (b[c] = {});
                            var d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams,
                                u = () => h || (h = new Promise(async (f, n) => {
                                    await (a = m.createElement("script"));
                                    e.set("libraries", [...r] + "");
                                    for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                                    e.set("callback", c + ".maps." + q);
                                    a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                                    d[q] = f;
                                    a.onerror = () => h = n(Error(p + " could not load."));
                                    a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                                    m.head.append(a)
                                }));
                            d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
                        })({
                            key: "AIzaSyDD6ffsydvAmFzM1JjRfjS_oRrtJMt3NOk",
                            v: "weekly",
                        });
                    </script>


                </div>
            </div>
        </div>
    </div>
    <div class="modal modal__auth">
        <div class="modal__wrap">
            <!--modal sign in-->
            <div class="modal__content modal__enter">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Sign In</h2>
                    </div>
                    <form action="" class="modal__form modal__form-enter ">
                        <input type="hidden" name="action" value="login">
                        <div class="modal__form-content">
                            <input name="email" id="email" type="email"/>
                            <label class="label" for="email">Email</label>
                        </div>
                        <div class="modal__form-content modal__form-password">
                            <input name="password" id="passwordEnter" type="password"/>
                            <label class="label" for="passwordEnter">Password</label>
                            <div class="modal__form-eye"></div>
                        </div>
                        <div class="modal__flex">
                            <div class="modal__form-content modal__checkbox">
                                <div class="form-group">
                                    <input type="checkbox" id="remember" checked name="remember">
                                    <label for="remember">Remember me</label>
                                </div>
                            </div>
                            <div class="modal__text"><span class="modal__enter-forget">Forgot password?</span></div>
                        </div>

                        <button type="submit" class="section__btn">Login</button>
                    </form>
                    <div class="modal__text modal__text-bottom">Don’t have an account? <span class="modal__enter-reg ">Create now</span>
                    </div>
                </div>
            </div>

            <!-- modal sign up-->
            <div class="modal__content modal__reg">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Sign Up</h2>
                    </div>
                    <form action="" class="modal__form modal__form-reg">
                        <input type="hidden" name="action" value="registration">
                        <div class="modal__form-content">
                            <input name="name" id="firstname" type="text"/>
                            <label class="label" for="firstname">First name</label>
                        </div>
                        <div class="modal__form-content">
                            <input name="lastname" id="lastname" type="text"/>
                            <label class="label" for="lastname">Last name</label>
                        </div>
                        <div class="modal__form-content">
                            <input name="email" type="email" id="emailReg"/>
                            <label class="label" for="emailReg">Email</label>
                        </div>
                        <div class="modal__form-content modal__form-password">
                            <input name="password" type="password" id="passwordReg"/>
                            <label class="label" for="passwordReg">Create account password</label>
                            <div class="modal__form-eye"></div>
                        </div>
                        <!--                            <div class="modal__title modal__title-role">-->
                        <!--                                <h2>Select your role</h2>-->
                        <!--                            </div>-->
                        <!--                            <div class="modal__form-content">-->
                        <!--                                <div class="filter__item modal__dropdown filter__dropdown">-->
                        <!--                                    <div class="filter__header">Select here</div>-->
                        <!--                                    <input type="text" class="filter__sort-input" name="role" hidden>-->
                        <!--                                    <div class="filter__inner">-->
                        <!--                                        <div class="filter__inner-item">admin1</div>-->
                        <!--                                        <div class="filter__inner-item">admin2</div>-->
                        <!--                                        <div class="filter__inner-item">admin3</div>-->
                        <!--                                    </div>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <div class="modal__reg-btn">
                            <button type="submit" class="section__btn">Create my account</button>
                            <div class="modal__text modal__text-bottom">Already have an account? <span
                                        class="modal__reg-enter">Sign in</span></div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal__content modal__reg-success">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Thank you for registration</h2>
                    </div>
                </div>
            </div>
            <!-- modal forgot password-->
            <div class="modal__content modal__forget">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Forgot your password?</h2>
                    </div>
                    <form action="" class="modal__form modal__form-forget">
                        <input type="hidden" name="action" value="send_mail_reset_password">
                        <div class="modal__form-content">
                            <input name="email" type="email" id="passwordForgot"/>
                            <label class="label" for="passwordForgot">Your email</label>
                        </div>
                        <button type="submit" class="section__btn">Change password</button>
                    </form>
                </div>
            </div>

            <div class="modal__content modal__forget-mail ">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>We send a message to your email</h2>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal modal__password">
        <div class="modal__wrap">
            <div class="modal__content ">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Password changed successfully</h2>
                    </div>
                    <button class="modal__ok section__btn">
                        Okay
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal__social">
        <div class="modal__wrap">
            <div class="modal__content modal__social-content ">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__title">
                        <h2>Share</h2>
                    </div>
                </div>
            </div>
            <div class="modal__content modal__link">
                <div class="modal__close img">
                    <img src="<?= TEMPLATE_PATH ?>/img/close.svg" alt="">
                </div>
                <div class="modal__overflow">
                    <div class="modal__link-text">
                        <h2>Link copied</h2>
                    </div>
                </div>
            </div>
        </div>

</section>
<?php $footer = get_field('footer', 'option'); ?>
<footer class="footer">
    <div class="main__back img">
        <img src="<?= TEMPLATE_PATH ?>/img/back-img.png" alt="">
    </div>
    <div class="container">
        <div class="footer__block">
            <?php if (!empty($footer['logo'])) : ?>
                <a href="<?php echo home_url(); ?>" class="footer__logo img">
                    <img src="<?php echo $footer['logo']; ?>" alt="">
                </a>
            <?php endif; ?>
            <?php if (!empty($footer['menu'])) : ?>
                <ul class="footer__menu">
                    <?php foreach ($footer['menu'] as $item) : ?>
                        <li class="footer__item">
                            <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php $hide_array = [
                is_cart(),
                is_checkout(),
                is_account_page(),
                is_product(),
                is_page_template(['page-change-password.php', 'page-ticket-info.php'])
        ]; ?>

        <?php if( !in_array(true, $hide_array) ) : ?>
            <a class="main__message" href="#subs"></a>
        <?php endif; ?>
    </div>

</footer>

<!-- /FOOTER -->
</div>

<!-- SCRIPTS -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

<!-- build:js scripts/validate_script.js -->
<!--<script type="text/javascript" src="scripts/validate_script.js" ></script>-->
<!-- endbuild -->

<!-- build:js scripts/plagins.js -->


<script>
    let mapIcon = '<?= TEMPLATE_PATH ?>/img/maps.svg';
</script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/device.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/jquery.formstyler.min.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/jquery.easy-ticker.min.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/maskInput.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/slick.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/plagins/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!-- add you plagins js here -->

<!-- endbuild -->

<!-- build:js scripts/main.js -->
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/basic_scripts.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/develop/index.js"></script>
<script type="text/javascript" src="<?= TEMPLATE_PATH ?>/scripts/develop/woocommerce.js"></script>
<!-- add you develop js here -->
<!-- endbuild -->

<!-- /SCRIPTS -->
<?php wp_footer(); ?>
</body>
</html>