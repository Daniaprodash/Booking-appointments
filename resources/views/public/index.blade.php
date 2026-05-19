@extends('layouts.app')
@section('title','welcome')
@section('content')
<!-- hero -->
 <div class="hero">
    <div class="hero-background">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
    <div class="hero-items">
        <div class="hero-badge">
            <i class="fas fa-calendar-check"></i>
            <span>حجز المواعيد أصبح أسهل</span>
        </div>
        <h1 class="title">
            احجز موعدك مع طبيب الأسنان
            <span class="title-highlight">بسهولة تامة</span>
        </h1>
        <h4 class="sec-title">
            اختر الطبيب المناسب واحجز موعدك في دقائق معدودة. 
            <br>
            لا مزيد من الانتظار - إدارة مواعيدك في متناول يدك
        </h4>
        <form action="{{route('appsearch')}}" method="GET">
         <div class="hero-search">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <input type="text" class="input-search" placeholder="ابحث عن طبيب، خدمة، أو تخصص..." name="keyword" value="{{ request('keyword') }}">
            <button type="submit" class="button-search">
                <i class="fas fa-search"></i>
                <span>بحث</span>
            </button>
         </div>
        </form>
      
        <div class="hero-features">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <span>مواعيد فورية</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <span>آمن ومضمون</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <span>أطباء معتمدون</span>
            </div>
        </div>
    </div>
 </div>
<!-- end hero -->

<!-- services section -->
 <section class="services-section" id="services">
    <div class="services-header">
        <div class="section-badge">
            <i class="fas fa-tooth"></i>
            <span>خدماتنا</span>
        </div>
        <h2 class="services-title">
            خدمات <span class="title-accent">طبية متكاملة</span>
        </h2>
        <p class="services-sub">
            نقدم مجموعة واسعة من خدمات طب الأسنان بأعلى معايير الجودة والرعاية
        </p>
    </div>

    <div class="services-container">
        @forelse($services ?? [] as $service)
            <div class="service-card">
                <div class="service-card-inner">
                    <div class="service-icon-wrapper">
                        <div class="service-icon-bg"></div>
                        <i class="fas fa-tooth service-icon"></i>
                    </div>
                    <div class="service-content">
                        <h4 class="service-title">{{ $service->title }}</h4>
                        <p class="service-desc">{{ $service->description }}</p>
                        <div class="service-footer">
                            <div class="service-price">
                                <span class="price-currency">تبدأ من $</span>
                                <span class="price-amount">{{ $service->price }}</span>
                            </div>
                            <a href="{{ Auth::check() ? (auth()->user()->role === 'doctor' ? route('doctorDashboard') : route('dashboard')) : route('auth.login') }}" class="service-btn">
                                <span>احجز الآن</span>
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-services">
                <div class="empty-icon">
                    <i class="fas fa-tooth"></i>
                </div>
                <p>لا توجد خدمات متاحة حالياً</p>
                <span>سيتم إضافة خدمات جديدة قريباً</span>
            </div>
        @endforelse
    </div>
 </section>
<!-- end services section -->

<!-- doctors -->
 <section class="doctors-section" id="doctors">
    <div class="doctors-header">
        <div class="section-badge">
            <i class="fas fa-user-md"></i>
            <span>فريقنا الطبي</span>
        </div>
        <h2 class="doctors-title">
            أطباء الأسنان <span class="title-accent">المختصون</span>
        </h2>
        <p class="doctors-sub">
            اختر من بين أفضل الأطباء المعتمدين واحجز موعدك بسهولة
        </p>
    </div>

    <div class="doctor-grid">
        @forelse($doctors ?? [] as $doctor)
            <article class="doctor-card" role="article" aria-labelledby="doc-{{ $doctor->id }}">
                <div class="doctor-card-inner">
                    <div class="avatar-wrap">
                        <div class="avatar-border"></div>
                        <img
                            src="{{ ($img = $doctor->user?->image ?? $doctor->image) && trim($img) ? asset($img) : asset('assets/images/default-doctor.jpg') }}"
                            alt="صورة {{ $doctor->name }}"
                            class="avatar"
                            onerror="this.src='{{ asset('assets/images/default-doctor.jpg') }}'">
                        <div class="doctor-status">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>

                    <div class="doctor-info">
                        <h4 id="doc-{{ $doctor->id }}" class="doctor-name">{{ $doctor->user->name }}</h4>
                        <div class="doctor-specialty">
                            <i class="fas fa-stethoscope"></i>
                            {{ $doctor->specialty ?? 'طبيب أسنان' }}
                        </div>

                        <p class="doctor-desc">
                            {{ \Illuminate\Support\Str::limit($doctor->bio ?? 'طبيب مختص في طب الأسنان يقدم رعاية وقائية وعلاجية مع الاهتمام براحة المريض.', 120) }}
                        </p>
                       
                        <div class="doctor-contact" aria-label="معلومات التواصل">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <a href="tel:{{ $doctor->user->phone_number }}">{{ $doctor->user->phone_number ?? 'غير متوفر' }}</a>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <a href="mailto:{{ $doctor->user->email }}">{{ $doctor->user->email ?? 'غير متوفر' }}</a>
                            </div>

                        </div>

                        <a href="{{ Auth::check() ? (auth()->user()->role === 'doctor' ? route('doctorDashboard') : route('dashboard')) : route('auth.login') }}" class="btn-book-doctor">
                            <i class="fas fa-calendar-plus"></i>
                            <span>احجز موعد</span>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty-doctors">
                <div class="empty-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <p>لا توجد أطباء متاحين حالياً</p>
                <span>سيتم إضافة أطباء جدد قريباً</span>
            </div>
        @endforelse
    </div>
 </section>
<!-- end doctors -->


<!-- Steps Section -->
 <section class="steps-section">
    <div class="steps-header">
        <div class="section-badge">
            <i class="fas fa-list-ol"></i>
            <span>كيفية الحجز</span>
        </div>
        <h2 class="steps-title">
            خطوات <span class="title-accent">الحجز السهلة</span>
        </h2>
        <p class="steps-sub">
            احجز موعدك في ثلاث خطوات بسيطة وسريعة
        </p>
    </div>
    <div class="steps-container">
        <div class="step-item">
            <div class="step-number">1</div>
            <div class="step-icon">
                <i class="fas fa-tooth"></i>
            </div>
            <h3 class="step-title">اختر الخدمة</h3>
            <p class="step-desc">اختر من بين خدماتنا المتنوعة</p>
        </div>

        <div class="step-item">
            <div class="step-number">2</div>
            <div class="step-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="step-title">حدد الموعد</h3>
            <p class="step-desc">اختر التاريخ والوقت المناسبين</p>
        </div>

        <div class="step-item">
            <div class="step-number">3</div>
            <div class="step-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="step-title">تأكيد الحجز</h3>
            <p class="step-desc">احصل على تأكيد فوري لحجزك</p>
        </div>
    </div>
 </section>
<!-- end steps section -->

<!-- testimonials section -->
 <section class="testimonials-section" id="comments">
    <div class="testimonials-container">
        <div class="testimonials-header">
            <div class="section-badge">
                <i class="fas fa-star"></i>
                <span>آراء العملاء</span>
            </div>
            <h2 class="testimonials-title">ماذا يقول <span class="title-accent">عملاؤنا</span></h2>
            <p class="testimonials-sub">نفتخر بثقة عملائنا ورضاهم عن خدماتنا المتميزة</p>
        </div>

        <div class="testimonials-grid">
            @forelse($testimonials ?? [] as $testimonial)
            <div class="testimonial-card">
            @if (auth()->id() === $testimonial->user_id)
             <form action="{{ route('testimonials.delete', $testimonial->id) }}"
              method="POST" 
              onsubmit="return confirm('هل أنت متأكد من حذف هذا التعليق؟')">
                @csrf
                @method('DELETE')
                <button class="testimonial-delete-btn" type="submit" title="حذف التعليق">
                    <i class="fas fa-trash"></i>
                </button>
             </form>
            @endif
                <div class="testimonial-header">
                    <div class="testimonial-avatar">
                        @php
                          $initials =mb_substr($testimonial->user->name, 0, 2);
                          $colors = ['#16e1c7', '#7c5cff', '#ff6b6b', '#ffa726', '#0fb3a1', '#ff4f70'];
                          $color = $colors[$testimonial->user->id % count($colors)];
                        @endphp
                        <div class="testimonial-avatar-initials" style="background-color: {{ $color }};">
                            {{ $initials }}
                        </div>
                    </div>
                    <div class="testimonial-info">
                        <h4 class="testimonial-name">{{ $testimonial->user->name }}</h4>
                        <p class="testimonial-role">{{ $testimonial->user->role }}</p>
                        <div class="testimonial-rating">
                         @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $testimonial->rating ? 'fas fa-star' : 'far fa-star' }}"></i>
                         @endfor
                        </div>
                    </div>
                    <div class="quote-icon">
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
                <p class="testimonial-text">
                    {{ $testimonial->comment }}
                </p>
                <div class="testimonial-date">
                    <i class="far fa-calendar"></i>
                    <span>{{ $testimonial->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
                <div class="empty-testimonials">
                    <div class="empty-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <p>لا توجد آراء متاحة حالياً</p>
                    <p>يمكنك إضافة آراءك عن طريق التسجيل في الموقع</p>
                    
                </div>
            @endforelse
        </div>

        @if(auth()->check())
        <div class="testimonial-form-section">
            <div class="testimonial-form-card">
                <h3 class="testimonial-form-title">
                    <i class="fas fa-comment-dots"></i>
                    شاركنا رأيك
                </h3>
                <p class="testimonial-form-subtitle">نقدر آراءكم ونعمل دائماً على تحسين خدماتنا</p>
                
                @if(session('success'))
                    <div class="testimonial-form-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="testimonial-form-errors">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('testimonials.store') }}" method="POST" class="testimonial-form">
                    @csrf
                    <div class="form-group">
                        <label for="rating" class="form-label">التقييم</label>
                        <div class="rating-input">
                            <input type="radio" name="rating" id="rating5" value="5" >
                            <label for="rating5" class="rating-star"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="rating4" value="4">
                            <label for="rating4" class="rating-star"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="rating3" value="3">
                            <label for="rating3" class="rating-star"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="rating2" value="2">
                            <label for="rating2" class="rating-star"><i class="fas fa-star"></i></label>
                            <input type="radio" name="rating" id="rating1" value="1">
                            <label for="rating1" class="rating-star"><i class="fas fa-star"></i></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment" class="form-label">تعليقك</label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            class="form-textarea" 
                            rows="5" 
                            placeholder="اكتب تعليقك هنا..." 
                            maxlength="500"
                        >{{ old('comment') }}</textarea>
                        <div class="char-count">
                            <span id="charCount">0</span> / 500 حرف
                        </div>
                    </div>

                    <button type="submit" class="testimonial-form-submit">
                        <i class="fas fa-paper-plane"></i>
                        <span>إرسال التعليق</span>
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="testimonial-form-prompt">
            <div class="testimonial-form-prompt-content">
                <i class="fas fa-user-plus"></i>
                <p>تسجيل الدخول لإضافة تعليقك</p>
                <a href="{{ route('auth.login') }}" class="testimonial-form-prompt-btn">
                    تسجيل الدخول
                </a>
            </div>
        </div>
        @endif
    </div>
 </section>
<!-- end testimonials section -->




<script>
    // Character counter---عداد الحروف المكتوبة
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('comment');
        const charCount = document.getElementById('charCount');
        
        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length > 450) {
                    charCount.style.color = '#ff6b6b';
                } else {
                    charCount.style.color = '#8a93ad';
                }
            });
        }

        // Rating stars interaction---تقييم الطبيب
        const ratingInputs = document.querySelectorAll('.rating-input input[type="radio"]');
        const ratingLabels = document.querySelectorAll('.rating-input .rating-star');
        
        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                const value = parseInt(this.value);
                ratingLabels.forEach((label, labelIndex) => {
                    // Since we're using row-reverse, index 0 is rating 5, index 4 is rating 1---من 5 الى 1
                    const labelRating = 5 - labelIndex;
                    if (labelRating <= value) {
                        label.classList.add('selected');
                    } else {
                        label.classList.remove('selected');
                    }
                });
            });
        });

        // Initialize selected stars based on old input --ابدأ بالتقييم المحدد
        const selectedRating = document.querySelector('input[name="rating"]:checked');
        if (selectedRating) {
            const value = parseInt(selectedRating.value);
            ratingLabels.forEach((label, labelIndex) => {
                const labelRating = 5 - labelIndex;
                if (labelRating <= value) {
                    label.classList.add('selected');
                }
            });
        }

        // Initialize character count if there's old input--ابدأ بعدد الحروف المكتوبة
        if (textarea && textarea.value.length > 0) {
            charCount.textContent = textarea.value.length;
            if (textarea.value.length > 450) {
                charCount.style.color = '#ff6b6b';
            }
        }
    });
</script>

@endsection