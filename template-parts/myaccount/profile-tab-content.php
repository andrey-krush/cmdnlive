<?php

class Myaccount_Profile_Tab_Content {

    public function __construct() {

        $current_user = wp_get_current_user();
        $this->user_id = $current_user->data->ID;
        $user_meta = get_user_meta( $current_user->data->ID );
        $this->first_name = $user_meta['first_name'][0];
        $this->last_name = $user_meta['last_name'][0];
        $this->phone = $user_meta['billing_phone'][0];
        $this->mail = $current_user->data->user_email;
        $this->birhdate = get_field('birthdate', 'user_' . $current_user->data->ID);

        $this->change_password_page = (new Change_Password_Page())::get_url();

    }

    public function render() {
        ?>

        <div class="tab__item">
            <form action="" class="profile__info">
                <input type="hidden" name="action" value="change_profile">
                <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>">
                <div class="profile__info-input">
                    <label>First Name</label><input disabled name="name" type="text" value="<?php echo $this->first_name; ?>"/>
                    <div class="profile__info-button">
                        <button type="button" class="profile__info-icon profile__info-edit"></button>
                        <div class="profile__info-btn">
                            <button type="submit"
                                    class="profile__info-icon profile__info-change"></button>
                            <button type="button"
                                    class="profile__info-icon profile__info-cancel"></button>
                        </div>
                    </div>
                </div>
                <div class="profile__info-input">
                    <label>Last Name</label><input disabled name="lastname" type="text" value="<?php echo $this->last_name; ?>"/>
                    <div class="profile__info-button">
                        <button type="button" class="profile__info-icon profile__info-edit"></button>
                        <div class="profile__info-btn">
                            <button type="submit"
                                    class="profile__info-icon profile__info-change"></button>
                            <button type="button"
                                    class="profile__info-icon profile__info-cancel"></button>
                        </div>
                    </div>
                </div>
                <div class="profile__info-input">
                    <label>Email</label><input name="email" disabled type="text"
                                               value="<?php echo $this->mail; ?>"/>
                    <div class="profile__info-button">
                        <button type="button" class="profile__info-icon profile__info-edit"></button>
                        <div class="profile__info-btn">
                            <button type="submit"
                                    class="profile__info-icon profile__info-change"></button>
                            <button type="button"
                                    class="profile__info-icon profile__info-cancel"></button>
                        </div>
                    </div>
                </div>
                <div class="profile__info-input">
                    <label>Phone</label><input name="phoneAccount" disabled type="text"
                                               value="<?php echo $this->phone; ?>"/>
                    <div class="profile__info-button">
                        <button type="button" class="profile__info-icon profile__info-edit"></button>
                        <div class="profile__info-btn">
                            <button type="submit"
                                    class="profile__info-icon profile__info-change"></button>
                            <button type="button"
                                    class="profile__info-icon profile__info-cancel"></button>
                        </div>
                    </div>
                </div>
                <div class="profile__info-input">
                    <label>Birthdate</label> <input class="birthdate__input" disabled name="birthdate" type="text"
                                                    value="<?php echo $this->birhdate; ?>"/>
                    <div class="profile__info-button">
                        <button type="button" class="profile__info-icon profile__info-edit"></button>
                        <div class="profile__info-btn">
                            <button type="submit"
                                    class="profile__info-icon profile__info-change"></button>
                            <button type="button"
                                    class="profile__info-icon profile__info-cancel"></button>
                        </div>
                    </div>
                </div>
                <a href="<?php echo $this->change_password_page; ?>" class="profile__info-password">Change password</a>
                <button class="profile__info-logout">Sign out of the account</button>
            </form>

        </div>

        <?php
    }

}