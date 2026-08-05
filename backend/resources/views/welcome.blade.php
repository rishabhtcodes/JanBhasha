<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JanBhasha — AI-Powered Multilingual Translation for Indian Government Notices</title>
    <meta name="description" content="Streamline notice creation and delivery. Empower every citizen to understand official communications.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        :root{
            --saffron:#FF9933;--green:#138808;--navy:#1a237e;--blue:#1565c0;--blue-light:#1976d2;
            --bg:#f5f7fa;--white:#ffffff;--text:#1a1a2e;--text2:#4a5568;--text3:#718096;
            --border:#e2e8f0;--card:#ffffff;--shadow:0 2px 12px rgba(0,0,0,0.08);
        }
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}
        a{text-decoration:none;color:inherit;}

        /* Tricolor */
        .tribar{height:4px;background:linear-gradient(90deg,#FF9933 33.33%,#fff 33.33% 66.66%,#138808 66.66%);position:fixed;top:0;left:0;right:0;z-index:100;}

        /* Navbar */
        nav{position:fixed;top:4px;left:0;right:0;z-index:90;background:var(--navy);padding:0 16px;}
        .nav-hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;padding:8px;background:transparent;border:none;}
        .nav-hamburger span{width:22px;height:2px;background:#fff;border-radius:2px;transition:.3s;display:block;}
        .nav-mobile-menu{display:none;position:fixed;top:64px;left:0;right:0;background:var(--navy);z-index:89;padding:16px;border-top:1px solid rgba(255,255,255,0.1);flex-direction:column;gap:10px;}
        .nav-mobile-menu.open{display:flex;}
        .nav-mobile-btn{padding:12px 16px;border-radius:8px;font-size:14px;font-weight:600;text-align:center;}
        .nav-inner{max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;height:60px;}
        .nav-logo{display:flex;align-items:center;gap:10px;}
        .logo-box{width:36px;height:36px;background:linear-gradient(135deg,var(--saffron),#e65100);border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:900;color:#fff;font-size:14px;}
        .logo-text{color:#fff;font-weight:700;font-size:16px;}
        .logo-sub{color:rgba(255,255,255,0.6);font-size:11px;display:block;}
        .nav-right{display:flex;align-items:center;gap:12px;}
        /* Custom language dropdown */
        .lang-drop{position:relative;display:inline-block;}
        .lang-drop-btn{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.25);color:#fff;padding:7px 14px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;white-space:nowrap;}
        .lang-drop-btn:hover{background:rgba(255,255,255,0.18);}
        .lang-drop-btn .arrow{font-size:10px;opacity:0.7;transition:.2s;}
        .lang-drop-menu{position:absolute;top:calc(100% + 6px);right:0;background:#1a237e;border:1px solid rgba(255,255,255,0.2);border-radius:10px;overflow:hidden;min-width:130px;box-shadow:0 8px 24px rgba(0,0,0,0.35);display:none;z-index:200;}
        .lang-drop-menu.open{display:block;}
        .lang-drop-item{padding:10px 16px;color:#fff;font-size:13px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:8px;}
        .lang-drop-item:hover,.lang-drop-item.active{background:rgba(255,255,255,0.12);}
        .nav-btn{padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;border:none;}
        .btn-ghost{background:transparent;color:#fff;border:1px solid rgba(255,255,255,0.3);}
        .btn-orange{background:var(--saffron);color:#fff;}
        .btn-orange:hover{background:#e65100;}
        .user-pill{display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border-radius:8px;padding:6px 12px;color:#fff;font-size:13px;}

        /* Hero */
        .hero{padding:120px 32px 60px;background:linear-gradient(135deg,#fff 60%,#fff8f0 100%);text-align:center;}
        .hero-inner{max-width:800px;margin:0 auto;}
        .hero h1{font-size:42px;font-weight:800;line-height:1.2;color:var(--text);margin-bottom:16px;}
        .hero h1 span{color:var(--saffron);}
        .hero p{color:var(--text2);font-size:16px;line-height:1.7;max-width:560px;margin:0 auto 28px;}
        .hero-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
        .btn-primary{background:var(--saffron);color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;font-size:15px;border:none;cursor:pointer;transition:.2s;}
        .btn-primary:hover{background:#e65100;transform:translateY(-1px);}
        .btn-secondary{background:transparent;color:var(--navy);padding:12px 28px;border-radius:10px;font-weight:600;font-size:15px;border:2px solid var(--navy);cursor:pointer;transition:.2s;}
        .btn-secondary:hover{background:var(--navy);color:#fff;}

        /* Live preview card */
        .preview-card{max-width:700px;margin:40px auto 0;background:#fff;border-radius:16px;box-shadow:0 4px 30px rgba(0,0,0,0.1);padding:20px;text-align:left;}
        .preview-header{display:flex;align-items:center;gap:8px;margin-bottom:16px;font-size:13px;color:var(--text3);}
        .live-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;animation:pulse 2s infinite;}
        @keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}
        .preview-flag{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:var(--text2);margin-bottom:8px;}
        .preview-text{font-size:13px;color:var(--text2);line-height:1.6;background:#f8fafc;border-radius:8px;padding:12px;}
        .preview-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .preview-hi .preview-text{background:#fff8f0;font-family:'Noto Sans Devanagari',sans-serif;}
        .preview-badges{display:flex;gap:12px;margin-top:12px;padding-top:12px;border-top:1px solid var(--border);}
        .badge{font-size:11px;padding:4px 10px;border-radius:20px;font-weight:500;}
        .badge-green{background:#dcfce7;color:#166534;}
        .badge-blue{background:#dbeafe;color:#1e40af;}

        /* Stats */
        .stats{background:var(--navy);padding:40px 32px;}
        .stats-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center;}
        .stat-val{font-size:32px;font-weight:800;color:#fff;}
        .stat-label{font-size:13px;color:rgba(255,255,255,0.6);margin-top:4px;}

        /* Section base */
        .section{padding:60px 32px;}
        .section-inner{max-width:1200px;margin:0 auto;}
        .section-title{font-size:24px;font-weight:700;color:var(--text);margin-bottom:24px;}

        /* Notice cards */
        .notices-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .notice-card{background:#fff;border-radius:14px;padding:20px;box-shadow:var(--shadow);border:1px solid var(--border);}
        .notice-meta{display:flex;align-items:center;gap:8px;margin-bottom:12px;}
        .notice-org-icon{width:32px;height:32px;border-radius:8px;background:#f0f4ff;display:flex;align-items:center;justify-content:center;font-size:16px;}
        .notice-org{font-size:12px;font-weight:600;color:var(--text);}
        .notice-date{font-size:11px;color:var(--text3);}
        .notice-title{font-size:14px;font-weight:600;color:var(--text);margin-bottom:12px;line-height:1.4;}
        .notice-langs{display:flex;gap:8px;margin-bottom:12px;}
        .lang-tag{font-size:11px;color:var(--text2);background:#f8fafc;border:1px solid var(--border);padding:2px 8px;border-radius:4px;}
        .ai-badge{display:inline-flex;align-items:center;gap:4px;background:#fff3e0;color:#e65100;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;margin-bottom:12px;}
        .view-link{font-size:12px;color:var(--blue);font-weight:600;}
        .view-link:hover{text-decoration:underline;}

        /* Translation demo */
        .demo-section{background:#fff;border-radius:20px;box-shadow:var(--shadow);padding:32px;display:grid;grid-template-columns:1fr auto 1fr;gap:24px;align-items:start;}
        .demo-label{font-size:12px;font-weight:600;color:var(--text3);margin-bottom:8px;display:flex;align-items:center;gap:6px;}
        .demo-textarea{width:100%;border:1px solid var(--border);border-radius:10px;padding:14px;font-size:14px;color:var(--text);line-height:1.6;resize:none;font-family:inherit;min-height:120px;outline:none;}
        .demo-textarea:focus{border-color:var(--blue);}
        .demo-output{background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:14px;font-size:14px;color:var(--text);line-height:1.6;min-height:120px;font-family:'Noto Sans Devanagari',sans-serif;}
        .demo-mid{display:flex;flex-direction:column;gap:12px;align-items:center;justify-content:center;padding-top:24px;}
        .lang-sel{background:#fff;border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:13px;color:var(--text);cursor:pointer;width:140px;}
        .translate-btn{background:var(--green);color:#fff;border:none;padding:12px 24px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;width:140px;transition:.2s;}
        .translate-btn:hover{background:#0a6b04;}
        .demo-actions{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap;}
        .action-btn{background:#f8fafc;border:1px solid var(--border);padding:8px 16px;border-radius:8px;font-size:13px;color:var(--text2);cursor:pointer;display:flex;align-items:center;gap:6px;}
        .action-btn:hover{border-color:var(--blue);color:var(--blue);}
        .publish-btn{background:var(--blue);color:#fff;border:none;padding:8px 20px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;}

        /* Languages */
        .langs-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:40px;}
        .dept-grid{display:flex;flex-wrap:wrap;gap:16px;}
        .dept-item{display:flex;flex-direction:column;align-items:center;gap:6px;width:70px;}
        .dept-icon{width:52px;height:52px;border-radius:12px;background:#f0f4ff;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:22px;}
        .dept-name{font-size:11px;color:var(--text2);text-align:center;font-weight:500;}
        .lang-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;}
        .lang-item{display:flex;flex-direction:column;align-items:center;gap:6px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:12px 8px;}
        .lang-script{font-size:18px;}
        .lang-name{font-size:11px;color:var(--text2);font-weight:500;}

        /* Public services */
        .services-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .service-item{background:#fff;border:1px solid var(--border);border-radius:12px;padding:20px;text-align:center;}
        .service-icon{font-size:28px;margin-bottom:10px;}
        .service-title{font-size:13px;font-weight:600;color:var(--text);}

        /* Accessibility */
        .a11y-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:16px;}
        .a11y-item{display:flex;align-items:flex-start;gap:12px;background:#fff;border:1px solid var(--border);border-radius:10px;padding:16px;}
        .a11y-icon{font-size:22px;flex-shrink:0;}
        .a11y-title{font-size:13px;font-weight:600;color:var(--text);}
        .a11y-desc{font-size:12px;color:var(--text3);margin-top:2px;}

        /* Two-col layout */
        .two-col{display:grid;grid-template-columns:1fr 1fr;gap:40px;}
        .sub-title{font-size:18px;font-weight:700;color:var(--text);margin-bottom:16px;}

        /* Footer */
        footer{background:var(--navy);padding:20px 32px;text-align:center;}
        .footer-links{display:flex;justify-content:center;gap:24px;flex-wrap:wrap;margin-bottom:12px;}
        .footer-links a{color:rgba(255,255,255,0.6);font-size:13px;}
        .footer-links a:hover{color:#fff;}
        .footer-copy{color:rgba(255,255,255,0.4);font-size:12px;}
        .footer-top{display:flex;justify-content:center;gap:32px;margin-bottom:12px;flex-wrap:wrap;}
        .footer-top a{color:rgba(255,255,255,0.7);font-size:12px;}

        @media(max-width:900px){
            .langs-grid,.two-col{grid-template-columns:1fr;}
            .demo-section{grid-template-columns:1fr;}
            .hero h1{font-size:28px;}
            .preview-grid{grid-template-columns:1fr;}
            .notices-grid{grid-template-columns:repeat(2,1fr);}
            .stats-inner{grid-template-columns:repeat(2,1fr);}
        }
        @media(max-width:640px){
            nav{padding:0 12px;}
            .nav-right{display:none;}
            .nav-hamburger{display:flex;}
            .hero{padding:90px 16px 40px;}
            .hero h1{font-size:22px;}
            .hero p{font-size:14px;}
            .hero-btns{flex-direction:column;align-items:stretch;}
            .btn-primary,.btn-secondary{padding:14px 20px;font-size:14px;text-align:center;}
            .preview-card{margin:24px 0 0;padding:14px;}
            .preview-grid{grid-template-columns:1fr;}
            .stats{padding:28px 16px;}
            .stats-inner{grid-template-columns:repeat(2,1fr);gap:16px;}
            .stat-val{font-size:24px;}
            .section{padding:36px 16px;}
            .section-title{font-size:20px;}
            .notices-grid{grid-template-columns:1fr;}
            .demo-section{padding:20px 16px;}
            .demo-mid{flex-direction:row;flex-wrap:wrap;justify-content:center;}
            .lang-sel,.translate-btn{width:auto;flex:1;}
            .langs-grid,.two-col,.a11y-grid{grid-template-columns:1fr;}
            .lang-grid{grid-template-columns:repeat(3,1fr);}
            .services-grid{grid-template-columns:repeat(3,1fr);gap:10px;}
            .service-title{font-size:11px;}
            .footer-top{gap:12px;}
            .two-col .section-inner{gap:24px;}
            .demo-actions{flex-direction:column;}
            .action-btn,.publish-btn{width:100%;justify-content:center;}
            section[style*="linear-gradient(135deg,#0f172a"] > div > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr !important;}
        }
    </style>
</head>
<body>
    <div class="tribar"></div>

    <!-- Navbar -->
    <nav>
        <div class="nav-inner">
            <a href="{{ auth()->check() ? route('dashboard') : '/' }}" class="nav-logo">
                <div class="logo-box">JB</div>
                <div>
                    <span class="logo-text">JanBhasha</span>
                    <span class="logo-sub">सॉफ्टवेयर सेवा</span>
                </div>
            </a>
            <div class="nav-right">
                <div class="lang-drop" id="lang-drop">
                    <button class="lang-drop-btn" onclick="toggleLangMenu()" type="button">
                        <span id="lang-label">&#127760; English</span>
                        <span class="arrow">&#9660;</span>
                    </button>
                    <div class="lang-drop-menu" id="lang-drop-menu">
                        <div class="lang-drop-item active" data-lang="en" onclick="selectLang('en')">&#127760; English</div>
                        <div class="lang-drop-item" data-lang="hi" onclick="selectLang('hi')">&#127470;&#127475; हिंदी</div>
                    </div>
                </div>
                <span style="color:rgba(255,255,255,0.5);font-size:13px;">A+</span>
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-btn btn-orange">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn btn-ghost">Sign In</a>
                    <a href="{{ route('register') }}" class="nav-btn btn-orange">Get Started</a>
                @endauth
            </div>
            <!-- Hamburger -->
            <button class="nav-hamburger" id="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>
    <!-- Mobile Menu -->
    <div class="nav-mobile-menu" id="nav-mobile-menu">
        @auth
            <a href="{{ route('dashboard') }}" class="nav-mobile-btn btn-orange" style="background:var(--saffron);color:#fff;border-radius:8px;">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="nav-mobile-btn" style="color:#fff;border:1px solid rgba(255,255,255,0.3);border-radius:8px;">Sign In</a>
            <a href="{{ route('register') }}" class="nav-mobile-btn btn-orange" style="background:var(--saffron);color:#fff;border-radius:8px;">Get Started</a>
        @endauth
        <div style="border-top:1px solid rgba(255,255,255,0.15);padding-top:10px;display:flex;gap:12px;">
            <button onclick="selectLang('en');toggleMobileNav()" style="flex:1;padding:10px;background:rgba(255,255,255,0.1);border:none;color:#fff;border-radius:8px;font-size:13px;cursor:pointer;">🌐 English</button>
            <button onclick="selectLang('hi');toggleMobileNav()" style="flex:1;padding:10px;background:rgba(255,255,255,0.1);border:none;color:#fff;border-radius:8px;font-size:13px;cursor:pointer;">🇮🇳 हिंदी</button>
        </div>
    </div>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-inner">
            <h1 data-en="AI-Powered Multilingual Translation for Indian Government Notices" data-hi="भारतीय सरकारी सूचनाओं के लिए AI-संचालित बहुभाषी अनुवाद">AI-Powered Multilingual Translation<br>for <span>Indian Government Notices</span></h1>
            <p data-en="Streamline notice creation and delivery. Empower every citizen to understand official communications." data-hi="सूचना निर्माण और वितरण को सुव्यवस्थित करें। प्रत्येक नागरिक को आधिकारिक संचार समझने में सशक्त बनाएं।">Streamline notice creation and delivery. Empower every citizen to understand official communications.</p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-primary" data-en="Translate New Notice" data-hi="नई सूचना अनुवाद करें">Translate New Notice</a>
                <a href="{{ route('login') }}" class="btn-secondary" data-en="Learn More" data-hi="अधिक जानें">Learn More</a>
            </div>

            <!-- Live Preview -->
            <div class="preview-card">
                <div class="preview-header">
                    <span class="live-dot"></span>
                    Live Translation Preview
                </div>
                <div class="preview-grid">
                    <div class="preview-en">
                        <div class="preview-flag">🇬🇧 English (Original)</div>
                        <div class="preview-text">The Ministry of Finance announces new measures for rural development, under the PM Awas Yojana scheme. Promotion loan announces new accessment pension for farmers under the general economics…</div>
                    </div>
                    <div class="preview-hi">
                        <div class="preview-flag">🇮🇳 Hindi (AI Translation)</div>
                        <div class="preview-text">वित्त मंत्रालय ग्रामीण विकास के लिए नई योजनाएं घोषित करता है, PM आवास योजना के तहत। प्रमोशन ऋण किसानों के लिए सामान्य अर्थव्यवस्था के तहत नए मूल्यांकन पेंशन की घोषणा करता है…</div>
                    </div>
                </div>
                <div class="preview-badges">
                    <span class="badge badge-green">✅ Glossary Protected</span>
                    <span class="badge badge-blue">⚡ 0.3s response</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <div class="stats">
        <div class="stats-inner">
            <div>
                <div class="stat-val">50+</div>
                <div class="stat-label" data-i18n="stat-1-label">Government Depts.</div>
            </div>
            <div>
                <div class="stat-val">2M+</div>
                <div class="stat-label" data-i18n="stat-2-label">Notices Translated</div>
            </div>
            <div>
                <div class="stat-val">99.9%</div>
                <div class="stat-label" data-i18n="stat-3-label">Uptime SLA</div>
            </div>
            <div>
                <div class="stat-val">&lt; 1s</div>
                <div class="stat-label" data-i18n="stat-4-label">Avg. Response</div>
            </div>
        </div>
    </div>

    <!-- Latest Notices -->
    <section class="section" style="background:#f5f7fa;">
        <div class="section-inner">
            <div class="section-title" data-i18n="sec-notices">Latest Government Notices</div>
            <div class="notices-grid">
                @foreach([
                    ['🏠','MoHit','Pradhan Mantri Awas Yojana — Allocation Notice','18.11.2024'],
                    ['💰','MoF','Ministry of Finance — GST Collection Update','13.11.2024'],
                    ['🚔','Delhi Police','Delhi Police — Public Advisory','13.11.2024'],
                ] as $n)
                <div class="notice-card">
                    <div class="notice-meta">
                        <div class="notice-org-icon">{{ $n[0] }}</div>
                        <div>
                            <div class="notice-org">{{ $n[1] }}</div>
                            <div class="notice-date">📅 {{ $n[3] }}</div>
                        </div>
                    </div>
                    <div class="notice-title">{{ $n[2] }}</div>
                    <div class="notice-langs">
                        <span class="lang-tag">English</span>
                        <span class="lang-tag">Hindi</span>
                        <span class="lang-tag" style="color:var(--text3);">Download languages</span>
                    </div>
                    <div class="ai-badge">✨ AI-Ready</div>
                    <br>
                    <a href="{{ route('login') }}" class="view-link">View Details →</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Translation Demo -->
    <section class="section" style="background:#fff;">
        <div class="section-inner">
            <div class="section-title" data-i18n="sec-try">Try Translation</div>
            <div class="demo-section">
                <div>
                    <div class="demo-label">🇬🇧 English (Original)</div>
                    <textarea class="demo-textarea" id="src-text" rows="5" placeholder="Type or paste government notice here...">The Ministry of Finance announces new measures for rural development, under the PM Awas Yojana scheme. Promotion loan announces new accessment pension for farmers under the general economics of finance to propose new measures for rural development under the PMAY scheme.</textarea>
                    <div style="margin-top:10px;display:flex;gap:8px;">
                        <button class="action-btn">📎 Browse / Upload</button>
                    </div>
                </div>
                <div class="demo-mid">
                    <div style="font-size:12px;color:var(--text3);">Translating into:</div>
                    <select class="lang-sel" id="target-lang">
                        <option value="hi">देवनागरी (Hindi)</option>
                        <option value="ta">தமிழ் (Tamil)</option>
                        <option value="te">తెలుగు (Telugu)</option>
                        <option value="bn">বাংলা (Bengali)</option>
                        <option value="mr">मराठी (Marathi)</option>
                        <option value="gu">ગુજરાતી (Gujarati)</option>
                        <option value="ja">🇯🇵 日本語 (Japanese)</option>
                    </select>
                    <button class="translate-btn" onclick="doTranslate()">Translate</button>
                    <div style="font-size:11px;color:var(--text3);text-align:center;">0.0</div>
                </div>
                <div>
                    <div class="demo-label">🇮🇳 Hindi (AI Translation) <span class="badge badge-green" style="margin-left:auto;">✅ Glossary Protected</span> <span class="badge badge-blue">✔ Verification</span></div>
                    <div class="demo-output" id="translated-out">वित्त मंत्रालय ग्रामीण विकास के लिए नई योजनाएं घोषित करता है। पीएम आवास योजना के तहत प्रोमोशन ऋण किसानों के लिए नई एक्सेसमेंट पेंशन की घोषणा करता है सामान्य अर्थव्यवस्था के तहत वित्त को ग्रामीण विकास के नए उपाय प्रस्तावित करने के लिए पीएमएवाई योजना के तहत।</div>
                    <div class="demo-actions">
                        <button class="action-btn">💾 Save Draft</button>
                        <button class="publish-btn">📢 Publish Notice</button>
                    </div>
                    <div style="margin-top:10px;display:flex;flex-direction:column;gap:6px;">
                        <button class="action-btn">📄 PDF Download →</button>
                        <button class="action-btn">🔤 Devanagari Download →</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Languages & Departments -->
    <section class="section" style="background:#f5f7fa;">
        <div class="section-inner">
            <div class="langs-grid">
                <div>
                    <div class="sub-title" data-i18n="sec-langs">Supported Languages &amp; Departments</div>
                    <div class="dept-grid">
                        @foreach([['🏛️','MoKit'],['🏛️','MoHUA'],['💰','MoF'],['🏛️','MoF'],['🏛️','MoF'],['🏥','MoHFW'],['🚔','Delhi Police']] as $d)
                        <div class="dept-item">
                            <div class="dept-icon">{{ $d[0] }}</div>
                            <div class="dept-name">{{ $d[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="sub-title" data-i18n="sec-sup-langs">Supported Languages</div>
                    <div class="lang-grid">
                        @foreach([['🇬🇧','English'],['हि','Hindi'],['বা','Bengali'],['అ','Telugu'],['ত','Telugu'],['मा','Marathi'],['த','Tamil'],['த','Tamil'],['मर','Marathi'],['த','Tamil'],['Depts','Depts'],['Belic','Belic']] as $l)
                        <div class="lang-item">
                            <div class="lang-script">{{ $l[0] }}</div>
                            <div class="lang-name">{{ $l[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Public Services + Accessibility -->
    <section class="section" style="background:#fff;">
        <div class="section-inner two-col">
            <div>
                <div class="sub-title" data-i18n="sec-services">Public Services</div>
                <div class="services-grid">
                    @foreach([['✅','Verify Translated Notice'],['💬','Citizens Feedback'],['📡','API Documentation']] as $s)
                    <div class="service-item">
                        <div class="service-icon">{{ $s[0] }}</div>
                        <div class="service-title">{{ $s[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div>
                <div class="sub-title" data-i18n="sec-a11y">Accessibility Focus</div>
                <div class="a11y-grid">
                    @foreach([
                        ['Aa','Large fonts','Large fonts aids and services'],
                        ['◑','High contrast','High contrast and rural services'],
                        ['◑','High contrast','High contrast ability and users'],
                        ['🔊','Voice-to-text','Integration elderly and rural users'],
                    ] as $a)
                    <div class="a11y-item">
                        <div class="a11y-icon">{{ $a[0] }}</div>
                        <div>
                            <div class="a11y-title">{{ $a[1] }}</div>
                            <div class="a11y-desc">{{ $a[2] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Live Finance News Feature Banner -->
    <section class="section" style="background:linear-gradient(135deg,#0f172a 0%,#1e3a5f 100%);padding:60px 32px;">
        <div class="section-inner" style="max-width:1100px;margin:0 auto;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
                <!-- Left: Text -->
                <div>
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.35);border-radius:20px;padding:6px 14px;margin-bottom:20px;">
                        <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;display:inline-block;box-shadow:0 0 0 3px rgba(239,68,68,0.3);animation:pulse 1.5s infinite;"></span>
                        <span style="font-size:12px;font-weight:700;color:#fbbf24;letter-spacing:.5px;">LIVE</span>
                        <span style="font-size:12px;color:rgba(255,255,255,0.6);">Real-Time Financial Intelligence</span>
                    </div>
                    <h2 style="font-size:32px;font-weight:800;color:#fff;line-height:1.2;margin-bottom:14px;">Stay Ahead with<br><span style="color:#f59e0b;">Live Finance News</span></h2>
                    <p style="font-size:15px;color:rgba(255,255,255,0.65);line-height:1.75;margin-bottom:28px;">Access real-time Indian and global financial news directly within your dashboard — no switching tabs. Stay informed on RBI announcements, GST updates, budget circulars, and market movements while you work.</p>
                    <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:32px;">
                        <div style="display:flex;align-items:center;gap:12px;"><span style="font-size:20px;">🇮🇳</span><div><div style="font-size:13px;font-weight:600;color:#fff;">India Finance Feed</div><div style="font-size:12px;color:rgba(255,255,255,0.5);">Sensex, RBI, GST, Budget — live from top sources</div></div></div>
                        <div style="display:flex;align-items:center;gap:12px;"><span style="font-size:20px;">🌐</span><div><div style="font-size:13px;font-weight:600;color:#fff;">Global Market Updates</div><div style="font-size:12px;color:rgba(255,255,255,0.5);">World economy, forex, and policy news in one click</div></div></div>
                        <div style="display:flex;align-items:center;gap:12px;"><span style="font-size:20px;">🔍</span><div><div style="font-size:13px;font-weight:600;color:#fff;">Smart Search & Filter</div><div style="font-size:12px;color:rgba(255,255,255,0.5);">Instantly search across all headlines and summaries</div></div></div>
                    </div>
                    <a href="{{ route('register') }}" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:13px 28px;border-radius:10px;font-weight:700;font-size:14px;">📰 Try It in Dashboard →</a>
                </div>
                <!-- Right: Preview Card -->
                <div style="background:rgba(15,23,42,0.85);border:1px solid rgba(245,158,11,0.25);border-radius:20px;padding:20px;backdrop-filter:blur(12px);">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,0.08);">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;">📈</div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#fff;">Live Finance News</div>
                                <div style="font-size:11px;color:#f59e0b;display:flex;align-items:center;gap:6px;"><span style="width:6px;height:6px;background:#ef4444;border-radius:50%;display:inline-block;"></span> India & Global · Real-Time</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:6px;">
                            <span style="background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.4);color:#fbbf24;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;">🇮🇳 India</span>
                            <span style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:#64748b;font-size:11px;padding:4px 10px;border-radius:20px;">🌐 Global</span>
                        </div>
                    </div>
                    @foreach([
                        ['Moneycontrol','Sensex & Nifty Hit Historic Highs Amid Robust GDP Forecast','Indian stock market gains ground as benchmark indices surge...'],
                        ['Economic Times','GST Collection Grosses ₹1.87 Lakh Crore for May, 12% YoY','The Finance Ministry reported record collection figures...'],
                        ['Livemint','RBI Announces Extension of Sovereign Gold Bond (SGB) Schemes','The Reserve Bank of India announced terms for the upcoming...'],
                    ] as $article)
                    <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:12px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <span style="font-size:10px;font-weight:700;color:#f59e0b;background:rgba(245,158,11,0.1);padding:2px 8px;border-radius:4px;text-transform:uppercase;">{{ $article[0] }}</span>
                            <span style="font-size:10px;color:rgba(255,255,255,0.3);">Just now</span>
                        </div>
                        <div style="font-size:12px;font-weight:600;color:#e2e8f0;line-height:1.4;margin-bottom:4px;">{{ $article[1] }}</div>
                        <div style="font-size:11px;color:rgba(255,255,255,0.4);line-height:1.5;">{{ $article[2] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-top">
            <a href="#" data-i18n="footer-about">About JanBhasha</a>
            <a href="#" data-i18n="footer-privacy">Privacy Policy</a>
            <a href="#" data-i18n="footer-terms">Terms of Service</a>
            <a href="#" data-i18n="footer-feedback">Feedback</a>
            <a href="#" data-i18n="footer-help">Help Centre</a>
            <a href="#" data-i18n="footer-portal">Digital India Portal</a>
        </div>
        <div class="footer-copy" data-i18n="footer-copy">© {{ date('Y') }} JanBhasha — An Initiative by Ministry of Electronics &amp; IT, Government of India. Hosted by NIC.</div>
    </footer>

    <script>
    // ── Custom lang dropdown ──
    function toggleLangMenu() {
        document.getElementById('lang-drop-menu').classList.toggle('open');
    }
    // ── Mobile hamburger menu ──
    function toggleMobileNav() {
        const menu = document.getElementById('nav-mobile-menu');
        const btn = document.getElementById('nav-hamburger');
        const isOpen = menu.classList.toggle('open');
        btn.style.opacity = isOpen ? '0.8' : '1';
    }
    // Close mobile menu on outside click
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('nav-mobile-menu');
        const btn = document.getElementById('nav-hamburger');
        if (menu && !menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove('open');
        }
    });
    function selectLang(lang) {
        document.getElementById('lang-drop-menu').classList.remove('open');
        const labels = {en: '&#127760; English', hi: '&#127470;&#127475; हिंदी'};
        document.getElementById('lang-label').innerHTML = labels[lang];
        document.querySelectorAll('.lang-drop-item').forEach(el => {
            el.classList.toggle('active', el.dataset.lang === lang);
        });
        switchLang(lang);
    }
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!document.getElementById('lang-drop').contains(e.target)) {
            document.getElementById('lang-drop-menu').classList.remove('open');
        }
    });

    // ── Translation demo ──
    function doTranslate() {
        const text = document.getElementById('src-text').value;
        const lang = document.getElementById('target-lang').value;
        const out = document.getElementById('translated-out');
        out.textContent = 'Translating…';
        fetch('/api/translate', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:JSON.stringify({text,target_language:lang,source_language:'en'})
        })
        .then(r=>r.json())
        .then(d=>{ out.textContent = d.translated_text || d.error || 'Error'; })
        .catch(()=>{ out.textContent = 'Translation failed.'; });
    }

    // ── Page-wide language switcher ──
    const STRINGS = {
        en: {
            'page-title':      'AI-Powered Multilingual Translation for Indian Government Notices',
            'hero-h1-line1':   'AI-Powered Multilingual Translation',
            'hero-h1-span':    'Indian Government Notices',
            'hero-p':          'Streamline notice creation and delivery. Empower every citizen to understand official communications.',
            'btn-translate':   'Translate New Notice',
            'btn-learn':       'Learn More',
            'preview-live':    '🟢 Live Translation Preview',
            'preview-en-flag': '🇬🇧 English (Original)',
            'preview-hi-flag': '🇮🇳 Hindi (AI Translation)',
            'stat-1-label':    'Government Depts.',
            'stat-2-label':    'Notices Translated',
            'stat-3-label':    'Uptime SLA',
            'stat-4-label':    'Avg. Response',
            'sec-notices':     'Latest Government Notices',
            'sec-try':         'Try Translation',
            'sec-langs':       'Supported Languages & Departments',
            'sec-sup-langs':   'Supported Languages',
            'sec-services':    'Public Services',
            'sec-a11y':        'Accessibility Focus',
            'footer-about':    'About JanBhasha',
            'footer-privacy':  'Privacy Policy',
            'footer-terms':    'Terms of Service',
            'footer-feedback': 'Feedback',
            'footer-help':     'Help Centre',
            'footer-portal':   'Digital India Portal',
            'footer-copy':     '© {{ date("Y") }} JanBhasha — An Initiative by Ministry of Electronics & IT, Government of India. Hosted by NIC.',
        },
        hi: {
            'page-title':      'भारतीय सरकारी सूचनाओं के लिए AI-संचालित बहुभाषी अनुवाद',
            'hero-h1-line1':   'AI-संचालित बहुभाषी अनुवाद',
            'hero-h1-span':    'भारतीय सरकारी सूचनाओं के लिए',
            'hero-p':          'सूचना निर्माण और वितरण को सुव्यवस्थित करें। प्रत्येक नागरिक को आधिकारिक संचार समझने में सशक्त बनाएं।',
            'btn-translate':   'नई सूचना अनुवाद करें',
            'btn-learn':       'अधिक जानें',
            'preview-live':    '🟢 लाइव अनुवाद पूर्वावलोकन',
            'preview-en-flag': '🇬🇧 अंग्रेज़ी (मूल)',
            'preview-hi-flag': '🇮🇳 हिंदी (AI अनुवाद)',
            'stat-1-label':    'सरकारी विभाग',
            'stat-2-label':    'अनुवादित सूचनाएं',
            'stat-3-label':    'अपटाइम SLA',
            'stat-4-label':    'औसत प्रतिक्रिया',
            'sec-notices':     'नवीनतम सरकारी सूचनाएं',
            'sec-try':         'अनुवाद आज़माएं',
            'sec-langs':       'समर्थित भाषाएं और विभाग',
            'sec-sup-langs':   'समर्थित भाषाएं',
            'sec-services':    'सार्वजनिक सेवाएं',
            'sec-a11y':        'पहुंच पर ध्यान',
            'footer-about':    'JanBhasha के बारे में',
            'footer-privacy':  'गोपनीयता नीति',
            'footer-terms':    'सेवा की शर्तें',
            'footer-feedback': 'प्रतिक्रिया',
            'footer-help':     'सहायता केंद्र',
            'footer-portal':   'डिजिटल इंडिया पोर्टल',
            'footer-copy':     '© {{ date("Y") }} JanBhasha — इलेक्ट्रॉनिक्स और सूचना प्रौद्योगिकी मंत्रालय की एक पहल, भारत सरकार। NIC द्वारा होस्ट किया गया।',
        }
    };

    function switchLang(lang) {
        localStorage.setItem('ui-lang', lang);
        const s = STRINGS[lang] || STRINGS.en;

        // Update all [data-i18n] elements
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.dataset.i18n;
            if (s[key] !== undefined) el.textContent = s[key];
        });

        // Update all [data-en] / [data-hi] inline text nodes
        document.querySelectorAll('[data-en]').forEach(el => {
            el.textContent = lang === 'hi' ? el.dataset.hi : el.dataset.en;
        });

        // Page title
        document.title = s['page-title'];

        // Update html lang
        document.documentElement.lang = lang;

        // Sync custom dropdown button label
        const labels = {en: '&#127760; English', hi: '&#127470;&#127475; हिंदी'};
        const lbl = document.getElementById('lang-label');
        if (lbl) lbl.innerHTML = labels[lang] || labels.en;
        document.querySelectorAll('.lang-drop-item').forEach(el => {
            el.classList.toggle('active', el.dataset.lang === lang);
        });
    }

    // Restore saved lang on load
    (function(){
        const saved = localStorage.getItem('ui-lang') || 'en';
        if (saved !== 'en') selectLang(saved);
    })();
    </script>
</body>
</html>
