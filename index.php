<?php
session_start();
$contentJson = file_get_contents(__DIR__ . '/data/content.json');
$data = json_decode($contentJson, true);

// Statistics: Increment views with limiter
$userIp = $_SERVER['REMOTE_ADDR'];
$sessionKey = 'viewed_' . date('Ymd');

if (!isset($_SESSION[$sessionKey])) {
    $statsFile = __DIR__ . '/data/stats.json';
    $statsData = file_exists($statsFile) ? json_decode(file_get_contents($statsFile), true) : ['views' => 0, 'unique_ips' => []];

    // Increment total views
    $statsData['views']++;
    
    // Optional: Track unique IPs for more accuracy (stored in JSON)
    if (!isset($statsData['unique_ips'])) {
        $statsData['unique_ips'] = [];
    }
    
    if (!in_array($userIp, $statsData['unique_ips'])) {
        $statsData['unique_ips'][] = $userIp;
        // Keep only last 100 IPs to prevent JSON bloat
        if (count($statsData['unique_ips']) > 100) {
            array_shift($statsData['unique_ips']);
        }
    }

    $_SESSION[$sessionKey] = true;
    file_put_contents($statsFile, json_encode($statsData, JSON_PRETTY_PRINT), LOCK_EX);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rizky Fauzi | Professional Web Developer Portfolio</title>
    <meta name="description" content="Portfolio Rizky Fauzi - Professional Web Developer specializing in modern website solutions for Indonesian MSMEs. Services: Web Development, UI/UX Design, and Responsive Design.">
    <meta name="keywords" content="Rizky Fauzi, Web Developer, Portfolio, UMKM Digital, Website Indonesia, UI/UX Design, Laravel, React, Tailwind CSS">
    <meta name="author" content="Rizky Fauzi">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://rizkyfauzi.com/">
    <meta property="og:title" content="Rizky Fauzi | Professional Web Developer">
    <meta property="og:description" content="Helping Indonesian MSMEs go digital with professional and functional websites. Explore my projects and services.">
    <meta property="og:image" content="https://rizkyfauzi.com/assets/img/profile.webp">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://rizkyfauzi.com/">
    <meta property="twitter:title" content="Rizky Fauzi | Professional Web Developer">
    <meta property="twitter:description" content="Helping Indonesian MSMEs go digital with professional and functional websites. Explore my projects and services.">
    <meta property="twitter:image" content="https://rizkyfauzi.com/assets/img/profile.webp">

    <link rel="canonical" href="https://rizkyfauzi.com/">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="/" style="text-decoration: none; color: inherit;"><h1>Rizky.</h1></a>
        </div>
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>
        <nav id="nav-menu">
            <ul>
                <li><a href="#home" class="active" data-i18n="nav-home">Home</a></li>
                <li><a href="#services" data-i18n="nav-services">Services</a></li>
                <li><a href="#about" data-i18n="nav-about">About</a></li>
                <li><a href="#portfolio" data-i18n="nav-portfolio">Portfolio</a></li>
                <li><a href="#contact" data-i18n="nav-contact">Contact</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <div class="lang-switcher">
                <button class="lang-btn" data-lang="id">ID</button>
                <button class="lang-btn active" data-lang="en">EN</button>
            </div>
            <a href="#" class="btn-hire"><i class="fas fa-terminal"></i> <span data-i18n="hire">Hire Me</span></a>
        </div>
    </header>

    <main>
        <section id="home">
            <div class="section-content show">
                <div class="hero-card">
                    <div class="card-left">
                        <img src="assets/img/profile.webp" alt="Rizky Fauzi" class="profile-img">
                    </div>
                    <div class="card-right">
                        <span class="greeting" data-i18n="greeting">Hello, I'm</span>
                        <h1>Rizky Fauzi</h1>
                        <p class="description" data-i18n="hero-desc">
                            Professional Web Developer focused on creating modern digital solutions, including helping Indonesian MSMEs to go digital with professional and functional websites.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services">
            <div class="section-content">
                <div class="section-header">
                    <span class="sub-title" data-i18n="services-sub">What I Do</span>
                    <h2 data-i18n="services-title">My Services</h2>
                    <div class="underline"></div>
                </div>
                <div class="services-grid">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 data-i18n="service-web-title">Web Development</h3>
                        <p data-i18n="service-web-desc">Building high-quality, responsive websites with the latest technologies and best practices.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <h3 data-i18n="service-ui-title">UI/UX Design</h3>
                        <p data-i18n="service-ui-desc">Creating intuitive and visually appealing user interfaces that provide great user experiences.</p>
                    </div>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 data-i18n="service-resp-title">Responsive Design</h3>
                        <p data-i18n="service-resp-desc">Ensuring your website looks and works perfectly on all devices, from desktops to smartphones.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="about">
            <div class="section-content">
                <div class="section-header">
                    <span class="sub-title" data-i18n="about-sub">Biography</span>
                    <h2 data-i18n="about-title">About Me</h2>
                    <div class="underline"></div>
                </div>
                <p style="text-align: center; max-width: 700px; color: var(--text-muted); margin: 0 auto; line-height: 1.8;" data-i18n="about-desc">
                    I am a dedicated web developer focused on creating clean and efficient digital experiences. My main focus is helping Indonesian MSMEs transform to the digital realm through effective and affordable technology solutions.
                </p>
            </div>
        </section>

        <section id="portfolio">
            <div class="section-content">
                <div class="section-header">
                    <span class="sub-title" data-i18n="portfolio-sub">My Works</span>
                    <h2 data-i18n="portfolio-title">Portfolio</h2>
                    <div class="underline"></div>
                </div>
                <div class="portfolio-grid" id="portfolioContainer">
                    <!-- Dynamic Portfolio Items -->
                </div>
            </div>
        </section>

        <section id="contact">
            <div class="section-content">
                <div class="section-header">
                    <span class="sub-title" data-i18n="contact-sub">Get In Touch</span>
                    <h2 data-i18n="contact-title">Contact Me</h2>
                    <div class="underline"></div>
                </div>
                <p style="text-align: center; color: var(--text-muted); font-weight: 600; margin-bottom: 30px;" data-i18n="contact-email">email@rizkyfauzi.com</p>
                <div class="social-links" style="justify-content: center;">
                    <a href="#" class="social-icon"><i class="fab fa-github"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </section>
    </main>

    <button id="scrollTop" class="scroll-top-btn">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Chatbot AI UI -->
    <div id="chatbot" class="chatbot-container">
        <div class="chatbot-header">
            <div class="bot-info">
                <i class="fas fa-robot"></i>
                <span data-i18n="bot-name">Rizky Bot</span>
            </div>
            <button id="closeChat"><i class="fas fa-times"></i></button>
        </div>
        <div id="chatMessages" class="chat-messages">
            <div class="message bot">
                <p data-i18n="bot-welcome">Hello! I'm Rizky's AI assistant. How can I help you?</p>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Type message...">
            <button id="sendMessage"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
    <button id="openChat" class="chat-toggle">
        <i class="fas fa-comment-dots"></i>
    </button>

    <script>
        const translations = <?php echo json_encode($data); ?>;

        const langBtns = document.querySelectorAll(".lang-btn");
        let currentLang = "en";

        const setLanguage = (lang) => {
            currentLang = lang;
            document.querySelectorAll("[data-i18n]").forEach(el => {
                const key = el.getAttribute("data-i18n");
                if (translations[lang] && translations[lang][key]) {
                    el.textContent = translations[lang][key];
                }
            });
            
            renderPortfolio();

            langBtns.forEach(btn => {
                btn.classList.toggle("active", btn.getAttribute("data-lang") === lang);
            });
        };

        const renderPortfolio = () => {
            const container = document.getElementById("portfolioContainer");
            container.innerHTML = "";
            
            translations.portfolio.forEach(item => {
                const title = currentLang === 'id' ? item.title_id : item.title_en;
                const desc = currentLang === 'id' ? item.desc_id : item.desc_en;
                const image = item.image ? item.image : 'assets/img/project-placeholder.jpg';

                // Escape HTML to prevent XSS
                const escapeHTML = str => str.replace(/[&<>'"]/g, 
                    tag => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        "'": '&#39;',
                        '"': '&quot;'
                    }[tag] || tag)
                );
                
                const safeTitle = escapeHTML(title);
                const safeDesc = escapeHTML(desc);
                const safeImage = escapeHTML(image);
                
                const html = `
                    <div class="portfolio-item">
                        <div class="portfolio-img">
                            <img src="${safeImage}" alt="${safeTitle}">
                        </div>
                        <div class="portfolio-info">
                            <h3>${safeTitle}</h3>
                            <p>${safeDesc}</p>
                            <a href="#" class="view-btn"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                    </div>
                `;
                container.innerHTML += html;
            });
        };

        setLanguage("en");

        langBtns.forEach(btn => {
            btn.addEventListener("click", () => {
                const lang = btn.getAttribute("data-lang");
                setLanguage(lang);
            });
        });

        // Intersection Observer for animations
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const content = entry.target.querySelector(".section-content");
                    if (content) content.classList.add("show");
                }
            });
        }, observerOptions);

        document.querySelectorAll("section").forEach(section => observer.observe(section));

        const sections = document.querySelectorAll("section");
        const navLi = document.querySelectorAll("nav ul li a");
        const scrollTopBtn = document.getElementById("scrollTop");

        window.addEventListener("scroll", () => {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute("id");
                }
            });

            navLi.forEach((a) => {
                a.classList.remove("active");
                if (a.getAttribute("href") === `#${current}`) {
                    a.classList.add("active");
                }
            });

            if (window.pageYOffset > 500) scrollTopBtn.classList.add("show");
            else scrollTopBtn.classList.remove("show");
        });

        scrollTopBtn.addEventListener("click", () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });

        // Chatbot Logic
        const chatbot = document.getElementById("chatbot");
        const openChat = document.getElementById("openChat");
        const closeChat = document.getElementById("closeChat");
        const sendMessage = document.getElementById("sendMessage");
        const userInput = document.getElementById("userInput");
        const chatMessages = document.getElementById("chatMessages");
        const mobileMenu = document.getElementById("mobile-menu");
        const navMenu = document.getElementById("nav-menu");

        // Mobile Menu Toggle
        mobileMenu.addEventListener("click", () => {
            navMenu.classList.toggle("active");
            const icon = mobileMenu.querySelector("i");
            icon.classList.toggle("fa-bars");
            icon.classList.toggle("fa-times");
        });

        // Close menu when clicking link
        document.querySelectorAll("nav ul li a").forEach(link => {
            link.addEventListener("click", () => {
                navMenu.classList.remove("active");
                mobileMenu.querySelector("i").classList.add("fa-bars");
                mobileMenu.querySelector("i").classList.remove("fa-times");
            });
        });

        const addMessage = (text, type) => {
            const div = document.createElement("div");
            div.classList.add("message", type);
            // Replace bold syntax with <strong> and handle new lines
            const formattedText = text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
            div.innerHTML = `<p>${formattedText}</p>`;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };

        openChat.addEventListener("click", () => chatbot.classList.add("show"));
        closeChat.addEventListener("click", () => chatbot.classList.remove("show"));

        const handleChat = async () => {
            const text = userInput.value.trim();
            if (!text) return;
            
            addMessage(text, "user");
            userInput.value = "";
            
            // Add loading indicator
            const loadingId = 'loading-' + Date.now();
            const loadingDiv = document.createElement("div");
            loadingDiv.classList.add("message", "bot");
            loadingDiv.id = loadingId;
            loadingDiv.innerHTML = `<p><i class="fas fa-circle-notch fa-spin"></i> Thinking...</p>`;
            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            try {
                const response = await fetch('chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: text, lang: currentLang })
                });
                const data = await response.json();
                
                // Remove loading and add real message
                document.getElementById(loadingId).remove();
                addMessage(data.reply, "bot");
            } catch (error) {
                document.getElementById(loadingId).remove();
                addMessage("Error connecting to server.", "bot");
            }
        };

        sendMessage.addEventListener("click", handleChat);
        userInput.addEventListener("keypress", (e) => { if (e.key === "Enter") handleChat(); });
    </script>
</body>
</html>
