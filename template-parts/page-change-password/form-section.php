<?php

class Page_Change_Password_Form_Section {

    public function __construct() {

    }

    public function render() {
        ?>

        <section class="profile password">
            <div class="container">
                <div class="article__back password__back">
                    <button></button>
                </div>
                <div class="password__title">
                    <h2>Change password</h2>
                </div>
                <form action="" class="password__form modal__form ">
                    <input type="hidden" name="action" value="change_password">
                    <div class="modal__form-content modal__form-password">
                        <input id="passwordOld" name="password" type="password" />
                        <label class="label" for="passwordOld"> Old password</label>
                        <div class="modal__form-eye"></div>
                    </div>
                    <div class="modal__form-content modal__form-password">
                        <input name="passwordNew" id="passwordNew" type="password"/>
                        <label class="label" for="passwordNew">New password</label>
                        <div class="modal__form-eye"></div>
                    </div>
                    <div class="modal__form-content modal__form-password">
                        <input name="passwordNewRepeat" type="password"/>
                        <label class="label" for="passwordNew">Repeat the new password</label>
                        <div class="modal__form-eye"></div>
                    </div>
                    <div class="modal__reg-btn" >
                        <button type="submit" class="section__btn">Change password</button>
                    </div>
                </form>
            </div>
        </section>

        <?php
    }
}