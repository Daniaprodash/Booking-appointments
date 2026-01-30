<!-- footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <!-- Brand Section -->
            <div class="footer-section footer-brand">
                <div class="footer-logo">
                    <i class="fas fa-tooth"></i>
                    <span class="footer-brand-text">DentalCare</span>
                </div>
                <p class="footer-description">
                    نوفر لك أفضل خدمات طب الأسنان مع فريق طبي محترف ومرافق حديثة لرعاية صحتك الفموية.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h3 class="footer-title">روابط سريعة</h3>
                <ul class="footer-links">
                    <li><a href="{{route('index')}}"><i class="fas fa-chevron-left"></i> الرئيسية</a></li>
                    <li><a href="#services"><i class="fas fa-chevron-left"></i> الخدمات</a></li>
                    <li><a href="#doctors"><i class="fas fa-chevron-left"></i> الأطباء</a></li>
                    @auth
                      @if(auth()->user()->role == 'doctor')
                       <li><a href="{{route('doctorDashboard')}}"><i class="fas fa-chevron-left"></i> لوحة التحكم</a></li>
                       @elseif(auth()->user()->role == 'user')
                       <li><a href="{{route('dashboard')}}"><i class="fas fa-chevron-left"></i> لوحة التحكم</a></li>
                      @endif

                    @else
                        <li><a href="{{route('auth.login')}}"><i class="fas fa-chevron-left"></i> تسجيل الدخول</a></li>
                        <li><a href="{{route('auth.register')}}"><i class="fas fa-chevron-left"></i> إنشاء حساب</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Services -->
            <div class="footer-section">
                <h3 class="footer-title">خدماتنا</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-left"></i> تنظيف الأسنان</a></li>
                    <li><a href="#"><i class="fas fa-chevron-left"></i> تبييض الأسنان</a></li>
                    <li><a href="#"><i class="fas fa-chevron-left"></i> تقويم الأسنان</a></li>
                    <li><a href="#"><i class="fas fa-chevron-left"></i> زراعة الأسنان</a></li>
                    <li><a href="#"><i class="fas fa-chevron-left"></i> علاج الجذور</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section footer-contact">
                <h3 class="footer-title">تواصل معنا</h3>
                <ul class="footer-contact-list">
                    <li class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>العنوان</strong>
                            <span>شارع الملك فهد، الرياض، المملكة العربية السعودية</span>
                        </div>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>الهاتف</strong>
                            <a href="tel:+966123456789">+966 12 345 6789</a>
                        </div>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>البريد الإلكتروني</strong>
                            <a href="mailto:info@dentalcare.com">info@dentalcare.com</a>
                        </div>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>ساعات العمل</strong>
                            <span>السبت - الخميس: 9 صباحاً - 9 مساءً</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="copyright">
                    <i class="far fa-copyright"></i>
                    {{ date('Y') }} جميع الحقوق محفوظة لـ <strong>DentalCare</strong>
                </p>
                <div class="footer-bottom-links">
                    <a href="#">سياسة الخصوصية</a>
                    <span>|</span>
                    <a href="#">شروط الاستخدام</a>
                    <span>|</span>
                    <a href="#">سياسة الاسترجاع</a>
                </div>
            </div>
        </div>
    </div>
</footer>

