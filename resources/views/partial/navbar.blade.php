<!-- nav -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-brand">
            <a href="{{route('index')}}" class="brand-link">
                <i class="fas fa-tooth"></i>
                <span class="brand-text">DentalCare</span>
            </a>
        </div>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <div class="nav-menu" id="navMenu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{route('index')}}" class="nav-link active">
                        <i class="fas fa-home"></i>
                        <span>الرئيسية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#services" class="nav-link">
                        <i class="fas fa-tooth"></i>
                        <span>الخدمات</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#doctors" class="nav-link">
                        <i class="fas fa-user-md"></i>
                        <span>الأطباء</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#comments" class="nav-link">
                        <i class="fas fa-comment-dots"></i>
                        <span>التعليقات</span>
                    </a>
                </li>
                @auth
                 <li class="nav-item">
                    @if(auth()->user()->role === 'doctor')
                    
                      <a href="{{ route('doctorDashboard') }}" class="nav-link">لوحة التحكم</a>
                    @else
                      <a href="{{ route('dashboard') }}" class="nav-link">لوحة التحكم</a>
                    @endif
                 </li>
                @endauth
                <li class="nav-item nav-item-auth">
                    @auth
                        <form action="{{route('auth.logout')}}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-btn nav-btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    @else
                        <a href="{{route('auth.login')}}" class="nav-btn nav-btn-login">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>تسجيل الدخول</span>
                        </a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>



