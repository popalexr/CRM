<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Invoice is Ready</title>
    <style>
        /* Reset styles for consistency */
        body, table, td, a {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            text-size-adjust: 100%;
        }
        body {
            margin: 0;
            padding: 0;
            background: #f5f7fa;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .header {
            background: #4f46e5;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .content {
            padding: 30px 25px;
            line-height: 1.6;
            font-size: 15px;
        }
        .content p {
            margin: 0 0 15px;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 10px;
            background: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .footer {
            background: #f5f7fa;
            text-align: center;
            padding: 15px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- Body -->
        <div class="content">
            <p>Hi {{ $client->client_name ?? 'Customer' }},</p>

            <p>Your invoice <strong>#{{ $invoice->id }}</strong> dated 
               <strong>{{ $invoice->created_at }}</strong> is now ready.</p>

            <p>Your invoice total is <strong>{{ $invoice->total }} {{ $invoice->currency }}</strong>.</p>
            <p>Payment is due by <strong>{{ $invoice->payment_deadline }}</strong>.</p>

            <p>The invoice is attached to this email as a PDF. Please review it at your earliest convenience.</p>

            <p>If you have any questions or need help, feel free to contact us.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
