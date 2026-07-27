<!-- footer__section__start -->
<style>
    .footer-custom-text {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.8;
        font-size: 15px;
    }

    .footer-custom-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-custom-links li {
        margin-bottom: 12px;
        transition: transform 0.3s ease;
    }

    .footer-custom-links li:hover {
        transform: translateX(5px);
    }

    .footer-custom-links li a {
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none !important;
        transition: color 0.3s ease;
        display: inline-block;
    }

    .footer-custom-links li a:hover {
        color: #fff !important;
    }

    .footer-contact-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .footer-contact-icon {
        color: #fff;
        font-size: 20px;
        margin-right: 15px;
        background: rgba(255, 255, 255, 0.1);
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .footer-contact-item:hover .footer-contact-icon {
        background: #fff;
        color: #000;
        transform: scale(1.1);
    }

    .footer-contact-text {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        font-size: 15px;
        text-decoration: none !important;
        transition: color 0.3s ease;
    }

    .footer-contact-item:hover .footer-contact-text {
        color: #fff !important;
    }

    .footer-hr {
        border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
        margin: 25px 0 20px 0 !important;
        opacity: 1 !important;
    }

    .footer-social-list {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-social-link {
        color: #fff !important;
        font-size: 18px;
        text-decoration: none !important;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .footer-social-link:hover {
        background: #fff;
        color: #333 !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .footerarea__heading h3 {
        position: relative;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .footerarea__heading h3::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 40px;
        height: 2px;
        background: #fff;
        transition: width 0.3s ease;
    }

    .footerarea:hover .footerarea__heading h3::after {
        width: 60px;
    }
</style>
<div class="footerarea">
    <div class="container">
        <div class="footerarea__wrapper footerarea__wrapper__2">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up">
                    <div class="footerarea__inner footerarea__about__us">
                        <div class="footerarea__heading">
                            <h3>About us</h3>
                        </div>
                        <div class="footerarea__content">
                            <p class="footer-custom-text">Sudan University of Science and Technology SUST, one of the
                                distinguished institutions of
                                applied sciences, a global center of excellence in scientific research. SUST is
                                committed to excellence and innovation, and preparing students for leadership over the
                                world, and committed to community service.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="footerarea__inner">
                        <div class="footerarea__heading">
                            <h3>Usefull Links</h3>
                        </div>
                        <div class="footerarea__list">
                            <ul class="footer-custom-links">
                                <li>
                                    <a href="https://el.sustech.edu/login/index.php" target="_blank"><i
                                            class="icofont-rounded-double-right"
                                            style="font-size: 13px; margin-right: 5px;"></i> E-Learning System</a>
                                </li>
                                <li>
                                    <a href="http://repository.sustech.edu" target="_blank"><i
                                            class="icofont-rounded-double-right"
                                            style="font-size: 13px; margin-right: 5px;"></i> SUST Repository</a>
                                </li>
                                <li>
                                    <a href="http://196.1.226.111/sustech/r/sust_portal/sust-graduate-studies/open-progs"
                                        target="_blank"><i class="icofont-rounded-double-right"
                                            style="font-size: 13px; margin-right: 5px;"></i> Program electronic index
                                        College of Graduate Studies</a>
                                </li>
                                <li>
                                    <a href="https://mail01.sustech.edu" target="_blank"><i
                                            class="icofont-rounded-double-right"
                                            style="font-size: 13px; margin-right: 5px;"></i> Staff Webmail</a>
                                </li>
                                <li>
                                    <a href="Student.sustech.edu" target="_blank"><i
                                            class="icofont-rounded-double-right"
                                            style="font-size: 13px; margin-right: 5px;"></i> Student Portal</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="footerarea__inner footerarea__padding__left">
                        <div class="footerarea__heading">
                            <h3>Contacts</h3>
                        </div>
                        <div class="footerarea__contact__info">
                            <ul class="footer-contact-list">
                                <li class="footer-contact-item">
                                    <div class="footer-contact-icon">
                                        <i class="icofont-envelope"></i>
                                    </div>
                                    <a href="mailto:contact@sustech.edu"
                                        class="footer-contact-text">contact@sustech.edu</a>
                                </li>
                                <li class="footer-contact-item">
                                    <div class="footer-contact-icon">
                                        <i class="icofont-location-pin"></i>
                                    </div>
                                    <span class="footer-contact-text">Khartoum, Sudan</span>
                                </li>
                            </ul>
                        </div>
                        <hr class="footer-hr">
                        <div class="footerarea__heading" style="margin-bottom: 15px;">
                            <h3>Follow Us</h3>
                        </div>
                        <div class="footerarea__social__icons">
                            <ul class="footer-social-list">
                                <li><a href="//facebook.com" class="footer-social-link"><i
                                            class="icofont-facebook"></i></a></li>
                                <li><a href="//twitter.com" class="footer-social-link"><i
                                            class="icofont-twitter"></i></a></li>
                                <li><a href="//youtube.com" class="footer-social-link"><i
                                            class="icofont-youtube-play"></i></a></li>
                                <li><a href="//linkedin.com" class="footer-social-link"><i
                                            class="icofont-linkedin"></i></a></li>
                                <li><a href="//instagram.com" class="footer-social-link"><i
                                            class="icofont-instagram"></i></a></li>
                                <li><a href="#" class="footer-social-link"><i class="icofont-rss"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footerarea__copyright__wrapper footerarea__copyright__wrapper__2">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="footerarea__copyright__content footerarea__copyright__content__2 text-center">
                        <p style="color: rgba(255, 255, 255, 0.7);">Copyright © {{ Carbon\Carbon::now()->format('Y') }}
                            by Sudan University of Science &
                            Technology. All Rights Reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer__section__end -->