<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to {{ $applicationName }}...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .redirect-container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0d6efd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .app-name {
            color: #0d6efd;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="spinner"></div>
        <div class="message">
            Securely redirecting you to<br>
            <span class="app-name">{{ $applicationName }}</span>
        </div>
        <small style="color: #adb5bd;">Please wait...</small>
        <!-- Debug info (remove in production) -->
        <div style="margin-top: 1rem; font-size: 11px; color: #adb5bd;">
            Target: {{ $redirectUrl }}
        </div>
    </div>

    <!-- Auto-submitting form -->
    <form id="redirectForm" method="POST" action="{{ $redirectUrl }}" style="display: none;">
        @csrf
        @foreach($formData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>

    <script>
        // Auto-submit the form after a brief delay
        setTimeout(function() {
            document.getElementById('redirectForm').submit();
        }, 2000);
    </script>
</body>
</html>
