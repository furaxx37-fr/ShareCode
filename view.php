<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareCode - View Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css" rel="stylesheet">
    <style>
        .netflix-gradient {
            background: linear-gradient(135deg, #141414 0%, #000000 100%);
        }
        .netflix-red {
            background: linear-gradient(45deg, #e50914 0%, #b20710 100%);
        }
        .netflix-card {
            background: rgba(34, 34, 34, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glow-effect:hover {
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        }
        pre[class*="language-"] {
            margin: 0;
            border-radius: 8px;
        }
        .code-container {
            position: relative;
        }
        .copy-button {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
    </style>
</head>
<body class="netflix-gradient min-h-screen text-white">
    <!-- Header -->
    <header class="p-6">
        <div class="container mx-auto flex items-center justify-between">
            <a href="/sharecode/" class="text-3xl font-bold text-red-600 hover:scale-105 transform transition-all">ShareCode</a>
            <a href="/sharecode/" class="netflix-red px-4 py-2 rounded-lg hover:scale-105 transform transition-all">
                Create New
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div id="loading" class="text-center">
            <div class="netflix-card rounded-lg p-8 max-w-4xl mx-auto">
                <p class="text-xl">Loading code...</p>
            </div>
        </div>

        <div id="error" class="hidden text-center">
            <div class="netflix-card rounded-lg p-8 max-w-4xl mx-auto">
                <h2 class="text-2xl font-bold mb-4 text-red-400">Code Not Found</h2>
                <p class="mb-6">The requested code snippet could not be found.</p>
                <a href="/sharecode/" class="netflix-red px-6 py-3 rounded-lg hover:scale-105 transform transition-all">
                    Create New Code Share
                </a>
            </div>
        </div>

        <div id="content" class="hidden">
            <div class="netflix-card rounded-lg p-8 max-w-6xl mx-auto glow-effect transition-all duration-300">
                <!-- Paste Info -->
                <div class="mb-6 pb-6 border-b border-gray-600">
                    <h2 id="pasteTitle" class="text-2xl font-bold mb-2"></h2>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <span>Language: <span id="pasteLanguage" class="text-red-400"></span></span>
                        <span>Created: <span id="pasteDate"></span></span>
                        <span>Views: <span id="pasteViews" class="text-green-400"></span></span>
                    </div>
                </div>

                <!-- Code Display -->
                <div class="code-container">
                    <button onclick="copyCode()" 
                            class="copy-button netflix-red px-3 py-1 rounded text-sm hover:scale-105 transform transition-all">
                        Copy
                    </button>
                    <pre class="line-numbers"><code id="codeContent" class="language-javascript"></code></pre>
                </div>

                <!-- Actions -->
                <div class="mt-6 pt-6 border-t border-gray-600 flex flex-wrap gap-4">
                    <button onclick="copyUrl()" 
                            class="bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 transition-colors">
                        Copy URL
                    </button>
                    <a href="/sharecode/" 
                       class="netflix-red px-4 py-2 rounded hover:scale-105 transform transition-all">
                        Create New
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Get paste ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const pasteId = urlParams.get('id');

        if (!pasteId) {
            showError();
        } else {
            loadPaste(pasteId);
        }

        async function loadPaste(id) {
            try {
                const response = await fetch(`/sharecode/api/get.php?id=${id}`);
                const result = await response.json();

                if (result.success) {
                    displayPaste(result.paste);
                } else {
                    showError();
                }
            } catch (error) {
                showError();
            }
        }

        function displayPaste(paste) {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('content').classList.remove('hidden');

            // Update page title
            document.title = `ShareCode - ${paste.title}`;

            // Fill in paste information
            document.getElementById('pasteTitle').textContent = paste.title;
            document.getElementById('pasteLanguage').textContent = paste.language.toUpperCase();
            document.getElementById('pasteDate').textContent = new Date(paste.created_at).toLocaleDateString();
            document.getElementById('pasteViews').textContent = paste.views;

            // Display code with syntax highlighting
            const codeElement = document.getElementById('codeContent');
            codeElement.textContent = paste.content;
            codeElement.className = `language-${paste.language}`;

            // Apply syntax highlighting
            Prism.highlightElement(codeElement);
        }

        function showError() {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('error').classList.remove('hidden');
        }

        function copyCode() {
            const codeContent = document.getElementById('codeContent').textContent;
            navigator.clipboard.writeText(codeContent).then(() => {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            });
        }

        function copyUrl() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'URL Copied!';
                button.classList.add('bg-green-600');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            });
        }
    </script>
</body>
</html>
