<!-- Core -->
<script src="{{ asset('public/vendor/js-cookie/js.cookie.js') }}"></script>

<script type="text/javascript">
    var company_currency_code = '{{ setting("default.currency") }}';
</script>

@stack('scripts_start')

@apexchartsScripts

@stack('charts')

<!-- <script type="text/javascript" src="{{ asset('public/akaunting-js/hotkeys.js') }}" defer></script> -->
<script type="text/javascript" src="{{ asset('public/akaunting-js/generalAction.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/akaunting-js/popper.js') }}"></script>

<script type="text/javascript">
    "use strict";

    var Layout = (function() {

        const toggleButton = document.querySelector(".toggle-button");
        const sideBar = document.querySelector(".js-main-menu");
        const navbarMenu = document.querySelector(".js-menu");
        const mainContent = document.querySelector(".main-menu");
        const menus = document.querySelectorAll(".user-menu");
        const menuButtons = document.querySelectorAll(".menu-button");
        const detailsEL = mainContent.getElementsByTagName("details");
        const sectionContent = document.querySelector(".main-content");
        const menuBackground = document.querySelector(".js-menu-background");
        const menuClose = document.querySelector("[data-menu-close]");

        if (document.querySelector('[data-menu="notifications-menu"]')) {
            setTimeout(function() {
                document.querySelector('[data-menu="notifications-menu"]').classList.remove("animate-vibrate");
            }, 6000);
        }

        Array.from(detailsEL).forEach((el) => {
                el.addEventListener("toggle", function(e) {
                    if(e.target.querySelector(".material-icons-outlined")) {
                        e.target.querySelector(".material-icons").classList.toggle("rotate-180");
                    } else {
                        e.target.querySelectorAll(".material-icons")[1].classList.toggle("rotate-180");
                    }
                })
            }
        );

        function contentTransitionLeft() {
            sectionContent.classList.add("xl:ltr:ml-0", "xl:rtl:mr-0");
            sectionContent.classList.remove("xl:ltr:ml-64", "xl:rtl:mr-64");
            toggleButton.querySelector("span").classList.add("ltr:-rotate-90", "rtl:rotate-90");
        }

        function contentTransitionRight() {
            sectionContent.classList.remove("xl:ltr:ml-0", "xl:rtl:mr-0");
            sectionContent.classList.add("xl:ltr:ml-64", "xl:rtl:mr-64");
            toggleButton.querySelector("span").classList.remove("ltr:-rotate-90", "rtl:rotate-90");
        }

        function slideMenu() {
            if (document.body.clientWidth <= 1280) {
                mobileMenuHidden();
            } else {
                if (sideBar.classList.contains("menu-list-hidden")) {
                    toggleButton.classList.remove("ltr:left-12", "rtl:right-12");
                    sideBar.classList.remove("menu-list-hidden");

                    if (document.body.clientWidth > "991") {
                        contentTransitionRight();
                    }
                } else {
                    sideBar.classList.add("menu-list-hidden");
                    toggleButton.classList.add("ltr:left-12", "rtl:right-12");

                    if (document.body.clientWidth > "991") {
                        contentTransitionLeft();
                    }
                }
            }

        }

        toggleButton.addEventListener("click", function() {
            slideMenu();
        });

        function toggleMenu(iconButton, event) {
            const menuRef = iconButton.getAttribute("data-menu");
            const icon = iconButton.children[0].getAttribute("name");

            if (iconButton.getAttribute("data-menu") === "profile-menu") {
                if (iconButton.children[0].textContent != "cancel") {
                    iconButton.children[0].classList.remove("hidden");
                    iconButton.children[1].classList.add("hidden");
                } else {
                    iconButton.children[0].classList.add("hidden");
                    iconButton.children[1].classList.remove("hidden");
                }
            }

            menuButtons.forEach((button) => {
                if (icon) {
                    if (button.getAttribute("data-menu") !== menuRef && iconButton.children[0].textContent != "cancel") {
                        button.children[0].textContent = button.children[0].getAttribute("name");
                        button.children[0].classList.remove("active"); // inactive icon
                    }
                }
            });

            menus.forEach((menu) => {
                if (menu.classList.contains(menuRef) && iconButton.children[0].textContent != "cancel") {
                    iconButton.children[0].textContent = "cancel";
                    iconButton.children[0].classList.add("active");

                    menu.classList.remove("ltr:-left-80", "rtl:-right-80");
                    menu.classList.add("ltr:left-14", "rtl:right-14");
                    mainContent.classList.add("hidden");
                    toggleButton.classList.add("invisible");
                    menuClose.classList.remove("hidden");

                } else if (menu.classList.contains(menuRef) && iconButton.children[0].textContent == "cancel") {
                    iconButton.children[0].textContent = icon;
                    iconButton.children[0].classList.remove("active");

                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    menu.classList.remove("ltr:left-14", "rtl:right-14");
                    mainContent.classList.remove("hidden");
                    toggleButton.classList.remove("invisible");
                    menuClose.classList.add("hidden");
                } else {
                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    menu.classList.remove("ltr:left-14", "rtl:right-14");
                }

                menuClose.addEventListener("click", function() {
                    menu.classList.add("ltr:-left-80", "rtl:-right-80");
                    iconButton.children[0].textContent = icon;
                    iconButton.children[0].classList.remove("active");
                    mainContent.classList.remove("hidden");
                    this.classList.add("hidden");
                    toggleButton.classList.remove("invisible");
                });
            });
        }

        if (document.body.clientWidth >= 1280) {
            if (is_profile_menu == 1) {
                let profile_menu_html = document.querySelector(".profile-menu");
                let profile_icon_html = document.querySelector("[data-menu='profile-menu']");

                profile_menu_html.classList.add("ltr:left-14", "rtl:right-14");
                profile_menu_html.classList.remove("ltr:-left-80", "rtl:-right-80");

                profile_icon_html.children[0].textContent = "cancel";
                profile_icon_html.children[0].classList.add("active");

                profile_icon_html.children[0].classList.remove("hidden");
                profile_icon_html.children[1].classList.add("hidden");
                toggleButton.classList.add("invisible");
                menuClose.classList.remove("hidden");
            }
        }

        function mobileMenuActive() {
            navbarMenu.classList.add("ltr:left-0", "rtl:right-0");
            navbarMenu.classList.remove("ltr:-left-80", "rtl:-right-80");

            menuBackground.classList.add("visible");
            menuBackground.classList.remove("invisible");
        }

        function mobileMenuHidden() {
            navbarMenu.classList.remove("ltr:left-0", "rtl:right-0");
            navbarMenu.classList.add("ltr:-left-80", "rtl:-right:80");
            mainContent.classList.remove("hidden");

            menus.forEach((menu) => {
                menu.classList.remove("ltr:left-14", "rtl:right-14");
                menu.classList.add("ltr:-left-80", "rtl:-right-80");
            });

            menuButtons.forEach((iconButton) => {
                iconButton.children[0].classList.remove("active");
                iconButton.children[0].textContent = iconButton.children[0].getAttribute("name");
            });

            menuBackground.classList.remove("visible");
            menuBackground.classList.add("invisible");
        }

        document.querySelector(".js-hamburger-menu").addEventListener("click", function() {
            mobileMenuActive();
        });

        menuBackground.addEventListener("click", function() {
            mobileMenuHidden();
        });

        menuButtons.forEach((iconButton) =>
            iconButton.addEventListener("click", function() {
                toggleMenu(iconButton, event);
            })
        );
    })(500);
</script>

@stack('body_css')

@stack('body_stylesheet')

@stack('body_js')

@stack('body_scripts')

@livewireScripts

<script src="{{ asset('public/vendor/alpinejs/alpine.min.js') }}"></script>

<!-- Livewire -->
<script type="text/javascript">
    window.livewire_app_url = {{ company_id() }};
</script>

@stack('scripts_end')
