<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Help-Desk Chatbot</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .chat-container {
            background: #fff;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-box {
            padding: 16px;
            height: 400px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
        }

        .chat-box .message {
            margin: 8px 0;
        }

        .chat-box .user {
            text-align: right;
            color: #007bff;
        }

        .chat-box .bot {
            text-align: left;
            color: #333;
        }

        #chat-form {
            display: flex;
            padding: 8px;
        }

        #user-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 16px;
            margin-left: 8px;
            border: none;
            background: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

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
