<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <!-- خطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- ✅ رابط Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- إذا كنت تستخدم Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- css link -->
    <link rel="stylesheet" href="{{asset('assets/css/indexStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/navStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/footerStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/loginStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/registerStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/userDashboardStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/showMedicalRecordStyle.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/doctorDashboardStyle.css')}}">
    
</head> 
<body>
    @include('partial.navbar')
    @yield('content')
    @include('partial.footer')

<!-- chat script -->
 <div class="widget-wrapper" id="widget">
        <div class="fab-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
        </div>

        <div class="chat-content">
            <div class="chat-header" id="dragHandle">
                <div class="status-container">
                    <div class="pulse-dot"></div>
                    <strong>المساعدة والدعم</strong>
                </div>
                <span id="closeBtn" style="cursor:pointer; padding: 5px;">✕</span>
            </div>
            
            <div id="chat-box">
                <div class="msg bot">
                    مرحباً! أضفت زر إرسال جديد لتسهيل حجز المواعيد من هاتفك. كيف يمكنني مساعدتك اليوم؟
                    <div class="btn-group">
                        <button class="action-btn" onclick="quickMsg('Pricing')">Pricing</button>
                        <button class="action-btn" onclick="quickMsg('Services')">Services</button>
                        <button class="action-btn" onclick="quickMsg('Doctors')">Doctors</button>
                    </div>
                </div>
            </div>

            <div class="typing" id="typingIndicator">المساعد يكتب...</div>

            <div class="input-area">
                <input type="text" id="userInput" placeholder="اكتب هنا..." autocomplete="off">
                <button class="send-btn" id="sendButton">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </div>
        </div>
 </div>

<script>
 const widget = document.getElementById('widget');
 const chatBox = document.getElementById('chat-box');
 const userInput = document.getElementById('userInput');
 const sendButton = document.getElementById('sendButton');
 const typing = document.getElementById('typingIndicator');

  let isDragging = false;
  let hasMoved = false;
  let offset = { x: 0, y: 0 };

 // startDrag--بدء السحب 
 const startDrag = (e) => {
    if (e.target.closest('input') || e.target.closest('button') || e.target.id === 'closeBtn') return;
    isDragging = true;
    hasMoved = false;
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    const rect = widget.getBoundingClientRect();
    offset.x = clientX - rect.left;
    offset.y = clientY - rect.top;
    widget.style.transition = 'none';
 };

 // doDrag--اثناء السحب
 const doDrag = (e) => {
    if (!isDragging) return;
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    let x = clientX - offset.x;
    let y = clientY - offset.y;
    x = Math.max(0, Math.min(x, window.innerWidth - widget.offsetWidth));
    y = Math.max(0, Math.min(y, window.innerHeight - widget.offsetHeight));
    widget.style.left = x + 'px';
    widget.style.top = y + 'px';
    widget.style.bottom = 'auto';
    widget.style.right = 'auto';
    hasMoved = true;
 };

 //stopDrag--انهاء السحب
 const stopDrag = () => {
    if (!isDragging) return;
    isDragging = false;
    widget.style.transition = 'all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1)';
    if (!hasMoved && !widget.classList.contains('expanded')) {
        expandWidget();
    }
 };

 widget.addEventListener('mousedown', startDrag);
 window.addEventListener('mousemove', doDrag);
 window.addEventListener('mouseup', stopDrag);
 widget.addEventListener('touchstart', startDrag, { passive: false });
 window.addEventListener('touchmove', doDrag, { passive: false });
 window.addEventListener('touchend', stopDrag);

 function expandWidget() {
    const isMobile = window.innerWidth <= 480;
    const expandWidth = isMobile ? window.innerWidth * 0.9 : 380;
    const expandHeight = isMobile ? window.innerHeight * 0.75 : 580;
    const rect = widget.getBoundingClientRect();
    let currentX = rect.left;
    let currentY = rect.top;
    if (currentX + expandWidth > window.innerWidth) currentX = window.innerWidth - expandWidth - 10;
    if (currentY + expandHeight > window.innerHeight) currentY = window.innerHeight - expandHeight - 10;
    widget.style.left = currentX + 'px';
    widget.style.top = currentY + 'px';
    setTimeout(() => widget.classList.add('expanded'), 10);
 }

 function addMessage(text, side) {
    const div = document.createElement('div');
    div.className = `msg ${side}`;
    div.innerText = text;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
 }

 function botReply(userText) {
    typing.style.display = 'block';
    chatBox.scrollTop = chatBox.scrollHeight;
    setTimeout(() => {
        typing.style.display = 'none';
        let reply = "I'm processing your request regarding: " + userText;
        if (userText.toLowerCase().includes("pricing")) reply = "Our pricing is transparent and scales with your business.";
        addMessage(reply, 'bot');
    }, 1200);
 }

 function handleSendMessage() {
    const val = userInput.value.trim();
    if (val !== "") {
        addMessage(val, 'user');
        userInput.value = '';
        botReply(val);
    }
 }

 // Event listeners for both button click and Enter key
 sendButton.addEventListener('click', handleSendMessage);
 userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') handleSendMessage();
 });

 function quickMsg(text) {
    addMessage(text, 'user');
    botReply(text);
 }

 document.getElementById('closeBtn').onclick = (e) => {
    e.stopPropagation();
    widget.classList.remove('expanded');
 };
</script>
<!--end chat script -->

<script>
        // Navbar Toggle
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                navMenu.classList.toggle('active');
            });
        }

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (navMenu && navToggle && !navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Active link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        function activateNavLink() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', activateNavLink);
</script>
</body>
</html>