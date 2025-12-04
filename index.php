<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShareCode - Share Your Code Like Netflix</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
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
        .code-editor {
            background: #1e1e1e;
            border: 1px solid #333;
        }
        .glow-effect:hover {
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        }
    </style>
</head>
<body class="netflix-gradient min-h-screen text-white">
    <!-- Header -->
    <header class="p-6">
        <div class="container mx-auto flex items-center justify-between">
            <h1 class="text-3xl font-bold text-red-600">ShareCode</h1>
            <p class="text-gray-300">Share your code with the world</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="netflix-card rounded-lg p-8 max-w-4xl mx-auto glow-effect transition-all duration-300">
            <h2 class="text-2xl font-bold mb-6 text-center">Create New Code Share</h2>
            
            <form id="pasteForm" class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label class="block text-sm font-medium mb-2">Title (Optional)</label>
                    <input type="text" id="title" placeholder="Enter a title for your code..."
                           class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none transition-colors">
                </div>

                <!-- Language Selection -->
                <div>
                    <label class="block text-sm font-medium mb-2">Language</label>
                    <select id="language" class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:border-red-500 focus:outline-none">
                        <option value="javascript">JavaScript</option>
                        <option value="python">Python</option>
                        <option value="php">PHP</option>
                        <option value="java">Java</option>
                        <option value="cpp">C++</option>
                        <option value="c">C</option>
                        <option value="csharp">C#</option>
                        <option value="html">HTML</option>
                        <option value="css">CSS</option>
                        <option value="sql">SQL</option>
                        <option value="json">JSON</option>
                        <option value="xml">XML</option>
                        <option value="bash">Bash</option>
                        <option value="text">Plain Text</option>
                    </select>
                </div>

                <!-- Code Editor -->
                <div>
                    <label class="block text-sm font-medium mb-2">Code</label>
                    <textarea id="content" placeholder="Paste your code here..."
                              class="code-editor w-full h-96 p-4 rounded-lg font-mono text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-500"
                              spellcheck="false"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" 
                            class="netflix-red px-8 py-3 rounded-lg font-semibold hover:scale-105 transform transition-all duration-200 shadow-lg">
                        Share Code
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
            <div class="netflix-card rounded-lg p-8 max-w-md mx-4">
                <h3 class="text-xl font-bold mb-4 text-green-400">Code Shared Successfully!</h3>
                <p class="mb-4">Your code has been shared. Use the link below:</p>
                <div class="bg-gray-800 p-3 rounded border mb-4">
                    <input type="text" id="shareUrl" readonly 
                           class="w-full bg-transparent text-sm focus:outline-none">
                </div>
                <div class="flex space-x-3">
                    <button onclick="copyToClipboard()" 
                            class="netflix-red px-4 py-2 rounded text-sm hover:scale-105 transform transition-all">
                        Copy Link
                    </button>
                    <button onclick="closeModal()" 
                            class="bg-gray-600 px-4 py-2 rounded text-sm hover:bg-gray-500 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('pasteForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const language = document.getElementById('language').value;
            
            if (!content.trim()) {
                alert('Please enter some code to share!');
                return;
            }
            
            try {
                const response = await fetch('/sharecode/api/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        title: title || 'Untitled',
                        content: content,
                        language: language
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('shareUrl').value = result.url;
                    document.getElementById('successModal').classList.remove('hidden');
                    document.getElementById('successModal').classList.add('flex');
                } else {
                    alert('Error: ' + result.error);
                }
            } catch (error) {
                alert('Failed to create paste. Please try again.');
            }
        });
        
        function copyToClipboard() {
            const urlInput = document.getElementById('shareUrl');
            urlInput.select();
            document.execCommand('copy');
            
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copied!';
            button.classList.add('bg-green-600');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600');
            }, 2000);
        }
        
        function closeModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
            
            // Reset form
            document.getElementById('pasteForm').reset();
        }
    </script>
</body>
</html>
