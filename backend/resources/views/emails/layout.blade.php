<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $emailTitle ?? 'JanBhasha' }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* ─── Reset & Base ────────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; display: block; max-width: 100%; }
        a { text-decoration: none; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
                         'Helvetica Neue', Arial, sans-serif;
            background-color: #eef2f7;
            color: #1a202c;
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
        }

        /* ─── Outer wrapper ───────────────────────────────────── */
        .email-wrapper {
            width: 100%;
            background-color: #eef2f7;
            padding: 32px 16px;
        }

        /* ─── Card ────────────────────────────────────────────── */
        .email-card {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10), 0 2px 8px rgba(0,0,0,0.06);
        }

        /* ─── Header ──────────────────────────────────────────── */
        .email-header {
            background: linear-gradient(135deg, #4f46e5 0%, #6d28d9 45%, #1d4ed8 100%);
            padding: 44px 40px 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .email-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .email-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: -30px;
            width: 120px; height: 120px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.22);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            backdrop-filter: blur(8px);
        }
        .brand-name {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .header-title {
            font-size: 19px;
            font-weight: 600;
            color: rgba(255,255,255,0.92);
            margin-top: 6px;
            line-height: 1.5;
            position: relative;
            z-index: 1;
        }
        .header-badge {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 50px;
            padding: 4px 16px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        /* ─── Body ────────────────────────────────────────────── */
        .email-body {
            padding: 40px 40px 32px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 14px;
            line-height: 1.3;
        }
        .body-text {
            font-size: 15px;
            line-height: 1.75;
            color: #4a5568;
            margin-bottom: 16px;
        }

        /* ─── Info box ────────────────────────────────────────── */
        .info-box {
            background: linear-gradient(135deg, #f7f8ff 0%, #eef0ff 100%);
            border: 1px solid #e0e7ff;
            border-left: 4px solid #4f46e5;
            border-radius: 12px;
            padding: 20px 22px;
            margin: 22px 0;
        }
        .info-box p {
            font-size: 14px;
            color: #374151;
            line-height: 1.65;
            margin-bottom: 6px;
        }
        .info-box p:last-child { margin-bottom: 0; }
        .info-label {
            font-weight: 700;
            color: #4f46e5;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ─── CTA Button ──────────────────────────────────────── */
        .cta-container {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            letter-spacing: 0.3px;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4), 0 2px 6px rgba(0,0,0,0.1);
            border: none;
        }

        /* ─── Divider ─────────────────────────────────────────── */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 28px 0;
        }

        /* ─── Footer ──────────────────────────────────────────── */
        .email-footer {
            background: #f8fafc;
            border-top: 1px solid #e8edf5;
            padding: 28px 40px;
            text-align: center;
        }
        .footer-text {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        /* ─── Footer Links Row ─────────────────────────────────── */
        .footer-links-row {
            display: flex;
            justify-content: center;
            gap: 0;
            flex-wrap: wrap;
            margin-bottom: 4px;
        }
        .footer-btn {
            display: inline-block;
            margin: 4px 6px;
            padding: 9px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .footer-btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }
        .footer-btn-secondary {
            background: #f1f5f9;
            color: #4f46e5 !important;
            border: 1px solid #e0e7ff;
        }
        .footer-social {
            font-size: 11px;
            color: #cbd5e1;
            margin-top: 12px;
        }
        .footer-social a {
            color: #94a3b8;
        }

        /* ─── Security chip ───────────────────────────────────── */
        .security-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 12px;
            color: #15803d;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* ─── Mobile Responsive ───────────────────────────────── */
        @media only screen and (max-width: 620px) {
            .email-wrapper {
                padding: 16px 10px !important;
            }
            .email-card {
                border-radius: 16px !important;
            }
            .email-header {
                padding: 32px 24px 28px !important;
            }
            .brand-name {
                font-size: 22px !important;
            }
            .header-title {
                font-size: 16px !important;
            }
            .email-body {
                padding: 28px 24px 24px !important;
            }
            .greeting {
                font-size: 20px !important;
            }
            .body-text {
                font-size: 14px !important;
            }
            .info-box {
                padding: 16px 16px !important;
            }
            .cta-button {
                padding: 14px 30px !important;
                font-size: 14px !important;
                width: auto !important;
                display: inline-block !important;
            }
            .email-footer {
                padding: 22px 20px !important;
            }
            .footer-btn {
                padding: 9px 16px !important;
                font-size: 12px !important;
                margin: 4px 4px !important;
            }
            .footer-links-row {
                gap: 0 !important;
            }
        }

        @media only screen and (max-width: 400px) {
            .email-header {
                padding: 26px 16px 22px !important;
            }
            .email-body {
                padding: 22px 16px 18px !important;
            }
            .email-footer {
                padding: 18px 14px !important;
            }
            .brand-icon {
                width: 40px !important;
                height: 40px !important;
                font-size: 20px !important;
            }
            .brand-name {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-card">

        {{-- ─── Header ─────────────────────────────────────── --}}
        <div class="email-header">
            <div class="brand-logo">
                <div class="brand-icon">🌏</div>
                <span class="brand-name">JanBhasha</span>
            </div>
            <p class="header-title">{{ $headerTitle ?? '' }}</p>
        </div>

        {{-- ─── Body ───────────────────────────────────────── --}}
        <div class="email-body">
            @yield('content')
        </div>

        {{-- ─── Footer ─────────────────────────────────────── --}}
        <div class="email-footer">
            <div class="footer-links-row">
                <a href="https://janbhasha.in" class="footer-btn footer-btn-primary">
                    🌐 &nbsp;Visit JanBhasha
                </a>
                <a href="{{ config('app.url') . '/dashboard' }}" class="footer-btn footer-btn-secondary">
                    📊 &nbsp;My Dashboard
                </a>
            </div>
            <p class="footer-text" style="margin-top: 16px;">
                You're receiving this email because you have an account on <strong>JanBhasha</strong>.<br>
                &copy; {{ date('Y') }} JanBhasha — Government Translation Portal. All rights reserved.
            </p>
            <p class="footer-social">
                <a href="https://janbhasha.in">janbhasha.in</a>
            </p>
        </div>

    </div>
</div>
</body>
</html>
