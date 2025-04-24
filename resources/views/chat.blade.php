<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Help-Desk Chatbot</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="chat-container">
        <div id="chat-box" class="chat-box"></div>
        <form id="chat-form">
            <input type="text" id="user-input" placeholder="Type your question..." autocomplete="off" required />
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('chat-form');
        const input = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const userMessage = input.value.trim();
            if (!userMessage) return;

            appendMessage('user', userMessage);
            input.value = '';

            // Send to backend
            try {
                const res = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: userMessage
                    })
                });

                const data = await res.json();
                appendMessage('bot', data.choices[0].message.content);


                console.log(data.choices);
            } catch (err) {
                appendMessage('bot', "⚠️ Something went wrong. Please try again.");
            }
        });

        function appendMessage(sender, text) {
            const div = document.createElement('div');
            div.classList.add('message', sender);
            div.textContent = text;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>

    {{-- <script src="script.js"></script> --}}
</body>

</html>
