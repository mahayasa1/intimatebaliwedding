<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Wedding Enquiry</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background-color: #f5f0eb;
            color: #333;
        }
        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
        }
        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 40px 48px;
            text-align: center;
        }
        .header-ornament {
            color: #D4AF37;
            font-size: 28px;
            letter-spacing: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            color: #D4AF37;
            font-size: 22px;
            font-weight: normal;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .header p {
            color: #999;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        /* Alert Banner */
        .alert-banner {
            background: #D4AF37;
            padding: 14px 48px;
            text-align: center;
        }
        .alert-banner p {
            color: #1a1a1a;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        /* Body */
        .body {
            padding: 40px 48px;
        }
        .greeting {
            font-size: 16px;
            color: #555;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        /* Section */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #D4AF37;
            border-bottom: 1px solid #e8e0d5;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        /* Detail Table */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        .detail-table tr {
            border-bottom: 1px solid #f0ebe4;
        }
        .detail-table tr:last-child {
            border-bottom: none;
        }
        .detail-table td {
            padding: 12px 8px;
            font-size: 14px;
            line-height: 1.5;
        }
        .detail-table .label {
            color: #999;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            width: 38%;
            font-weight: bold;
        }
        .detail-table .value {
            color: #333;
        }
        .detail-table .value strong {
            color: #1a1a1a;
        }
        /* Message Box */
        .message-box {
            background: #faf7f3;
            border-left: 3px solid #D4AF37;
            padding: 20px 24px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 32px;
        }
        .message-box p {
            font-size: 14px;
            color: #555;
            line-height: 1.8;
            font-style: italic;
        }
        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: 1px solid #ffc107;
        }
        /* CTA Button */
        .cta-section {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            background: #D4AF37;
            color: #1a1a1a;
            text-decoration: none;
            padding: 14px 36px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 2px;
        }
        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #e8e0d5;
            margin: 28px 0;
        }
        /* Meta Info */
        .meta-info {
            background: #faf7f3;
            border-radius: 4px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }
        .meta-info p {
            font-size: 12px;
            color: #999;
            line-height: 1.7;
        }
        .meta-info span {
            color: #666;
            font-weight: bold;
        }
        /* Footer */
        .footer {
            background: #1a1a1a;
            padding: 28px 48px;
            text-align: center;
        }
        .footer-ornament {
            color: #D4AF37;
            font-size: 18px;
            letter-spacing: 6px;
            margin-bottom: 10px;
        }
        .footer p {
            color: #666;
            font-size: 11px;
            line-height: 1.7;
            letter-spacing: 0.5px;
        }
        .footer a {
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="header-ornament">✦ ✦ ✦</div>
            <h1>Intimate Bali Wedding</h1>
            <p>Admin Notification</p>
        </div>

        <!-- Alert Banner -->
        <div class="alert-banner">
            <p>📨 New Wedding Enquiry Received</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">
                Hello Admin,<br><br>
                You have received a new wedding enquiry through the website. 
                Please review the details below and follow up with the couple as soon as possible.
            </p>

            <!-- Enquiry Status -->
            <p style="margin-bottom: 28px;">
                Status: <span class="status-badge">New Enquiry</span>
            </p>

            <!-- Contact Information -->
            <div class="section-title">Contact Information</div>
            <table class="detail-table">
                <tr>
                    <td class="label">Full Name</td>
                    <td class="value"><strong>{{ $enquiry->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Email Address</td>
                    <td class="value">
                        <a href="mailto:{{ $enquiry->email }}" style="color: #D4AF37; text-decoration: none;">
                            {{ $enquiry->email }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="label">Phone / WhatsApp</td>
                    <td class="value">
                        @if($enquiry->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enquiry->phone) }}" 
                               style="color: #D4AF37; text-decoration: none;">
                                {{ $enquiry->phone }}
                            </a>
                        @else
                            <span style="color: #bbb;">Not provided</span>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Wedding Details -->
            <div class="section-title">Wedding Details</div>
            <table class="detail-table">
                <tr>
                    <td class="label">Wedding Date</td>
                    <td class="value">
                        @if($enquiry->wedding_date)
                            <strong>{{ \Carbon\Carbon::parse($enquiry->wedding_date)->format('d F Y') }}</strong>
                        @else
                            <span style="color: #bbb;">Not specified</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Wedding Type</td>
                    <td class="value">
                        @if($enquiry->wedding_type)
                            {{ $enquiry->wedding_type }}
                        @else
                            <span style="color: #bbb;">Not specified</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Guest Count</td>
                    <td class="value">
                        @if($enquiry->guest_count)
                            <strong>{{ $enquiry->guest_count }}</strong> guests (approx.)
                        @else
                            <span style="color: #bbb;">Not specified</span>
                        @endif
                    </td>
                </tr>
            </table>

            <!-- Message -->
            <div class="section-title">Their Message</div>
            <div class="message-box">
                <p>{{ $enquiry->message }}</p>
            </div>

            <!-- CTA -->
            <div class="cta-section">
                <a href="{{ url('/admin/enquiries') }}" class="cta-button">
                    View in Admin Panel
                </a>
            </div>

            <hr class="divider">

            <!-- Meta -->
            <div class="meta-info">
                <p>
                    <span>Received:</span> {{ $enquiry->created_at->format('l, d F Y – H:i') }} (UTC)<br>
                    <span>Enquiry ID:</span> {{ $enquiry->id }}<br>
                    <span>Source:</span> Contact Form – intimatebaliwedding.com
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-ornament">✦ ✦ ✦</div>
            <p>
                Intimate Bali Wedding<br>
                Jl. Puri Dewata No. 98, Sidakarya, Denpasar, Bali<br>
                <a href="mailto:intimatebaliwedding@gmail.com">intimatebaliwedding@gmail.com</a>
            </p>
        </div>
    </div>
</body>
</html>