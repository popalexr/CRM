<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact; 
            }
           
            .no-print {
                display: none;
            }
            
            .print-no-shadow {
                box-shadow: none !important;
            }
            .print-no-border {
                border: none !important;
            }
    
            .print-overflow-visible {
                overflow: visible !important;
                max-height: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 sm:p-8">
        @yield('content')
    </div>
</body>
</html>