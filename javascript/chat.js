
// open chat button
function chatButton() {
    const chatButton = document.querySelector('#navtop .chat_button');
    if (chatButton) {
        chatButton.addEventListener('click', function() {
            const chat = document.querySelector('#chat');
            chat.classList.toggle('toggle');
            if (chat.classList.contains('toggle')) {
                drawRecentConversations();
            }
        })
    }
}

chatButton();

// minimize chat button
function minimizeChat() {
    const minimizeChat = document.querySelector('#close_chat');
    if (minimizeChat) {
        minimizeChat.addEventListener('click', function() {
            const chat = document.querySelector('#chat');
            chat.classList.remove('toggle');
        })
    }
}

minimizeChat();

// return to recent conversations button
function returnChatButton() {
    const returnChat = document.querySelector('#chat_back');
    if (returnChat) {
        returnChat.addEventListener('click', function() {
            drawRecentConversations();
        })
    }
}

returnChatButton();


function openConversationButtons() {
    const openItemPage = document.querySelector("#user button:last-child");
    const openProfilePage = document.querySelector('#about .userButton');
    if (openItemPage) {
        const username = document.querySelector('#user a h3').textContent;
        openItemPage.addEventListener('click', openConversation.bind(openItemPage, username));
    }
    if (openProfilePage) {
        const username = document.querySelector('#about h2:first-child').textContent;
        openProfilePage.addEventListener('click', openConversation.bind(openProfilePage, username));
    }
}

openConversationButtons();

async function openConversation(username) {
    const user = this.getAttribute('data-id');
    const messages = await getConversation(user);

    const chat = document.querySelector('#chat');
    chat.classList.add('toggle');
    drawConversation(messages, username, user);
}

// Recent Conversations

async function getRecentConversations() {
    const response = await fetch("../api/api_chat.php?all=true");
    const recentConversations = await response.json();

    return recentConversations;
}

async function drawRecentConversations() {
    const conversations = await getRecentConversations();

    const chat = document.querySelector('#chat');

    const goBackButton = chat.firstElementChild.firstElementChild;
    goBackButton.style.visibility = "hidden";

    const headerName = goBackButton.nextElementSibling;
    headerName.firstElementChild.textContent = "Messages";
    headerName.classList.add('disabledAnchor');
    headerName.href = '';

    const mainMessages = chat.firstElementChild.nextElementSibling;
    mainMessages.id = "main_messages";
    mainMessages.innerHTML = '';

    const footer = mainMessages.nextElementSibling;
    if (footer) {
        footer.remove();
    }

    conversations.forEach(conversation => {
        const recentChat = document.createElement('div');
        recentChat.classList.add('recent_chat');
        recentChat.setAttribute('data-id', conversation.id);

        const imgUsername = document.createElement('div');
        imgUsername.classList.add('img_username');

        const img = document.createElement('img');
        img.src = "../uploads/small_" + conversation.image_path;
        const username = document.createElement('h3');
        username.textContent = conversation.username;

        imgUsername.appendChild(img);
        imgUsername.appendChild(username);

        const lastMessage = document.createElement('p');
        lastMessage.textContent = conversation.msg;

        recentChat.appendChild(imgUsername);
        recentChat.appendChild(lastMessage);

        mainMessages.appendChild(recentChat);
    });

    openConversations();
}


// Opening conversation
function openConversations() {
    const conversations = document.querySelectorAll('.recent_chat');
    conversations.forEach(conversation => {
        conversation.addEventListener('click', async function () {
            const correspondentId = conversation.getAttribute('data-id');
            const correspondentUsername = conversation.firstElementChild.firstElementChild.nextElementSibling;

            const messages = await getConversation(correspondentId);
            drawConversation(messages, correspondentUsername.textContent, correspondentId);
        })
    });
}

async function getConversation(user) {
    const response = await fetch("../api/api_chat.php?other=" + encodeURIComponent(user));
    const messages = await response.json();

    return messages;
}

async function drawConversation(messages, correspondentUsername, correspondentId) {
    const chat = document.querySelector('#chat');

    const goBackButton = chat.firstElementChild.firstElementChild;
    goBackButton.style.visibility = "visible";

    const headerUsername = goBackButton.nextElementSibling;
    headerUsername.classList.remove('disabledAnchor');
    headerUsername.href = "../pages/profile.php?user=" + encodeURIComponent(correspondentId);
    headerUsername.firstElementChild.textContent = correspondentUsername;

    const conversation = chat.firstElementChild.nextElementSibling;
    conversation.id = 'conversation';
    conversation.innerHTML = '';

    const messagesDiv = document.createElement('div');
    messagesDiv.id = 'messages';
    conversation.appendChild(messagesDiv);

    messages.forEach(message => drawMessage(message, correspondentId));

    //footer
    const footers = document.querySelectorAll('#chat footer');
    if (footers) {
        for(const footer of footers) {
            footer.remove();
        }
    }
    const footer = document.createElement('footer');

    const sendForm = document.createElement('form');
    sendForm.id = "send_msg_form";

    const input = document.createElement('input');
    input.type = "text";
    input.id = "chat_input";
    input.autocomplete = 'off';
    input.placeholder = "Send a message..";

    const sendButton = document.createElement('button');
    sendButton.type = "submit";
    const icon = document.createElement('i');
    icon.classList.add("fa-solid", "fa-paper-plane");
    sendButton.appendChild(icon);

    sendForm.appendChild(input);
    sendForm.appendChild(sendButton);

    sendForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const messageText = sendForm.firstElementChild;
        if (messageText.value !== '') {
            await sendMessage(messageText.value, correspondentId);
            messageText.value = '';
            const message = await getLastMessage(correspondentId);
            drawMessage(message);
            conversation.scrollTop = conversation.scrollHeight;
        }
    })

    footer.appendChild(sendForm);

    chat.appendChild(footer);

    conversation.scrollTop = conversation.scrollHeight;
}


async function sendMessage(messageText, user) {
    const response = await fetch('../api/api_chat.php', {
        method: "POST",
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: "msg=" + encodeURIComponent(messageText) + "&user=" + encodeURIComponent(user)
    });
}

async function getLastMessage(user) {
    const response = await fetch('../api/api_chat.php?user=' + encodeURIComponent(user));
    const lastMessage = await response.json();

    return lastMessage;
}

function drawMessage(message, correspondentId) {
    const sentBy = message.sender_id == correspondentId ? 'other' : 'self';

    const messageDiv = document.createElement('div');
    messageDiv.classList.add(sentBy);
    const userLink = document.createElement('a');
    userLink.href = "../pages/profile.php?user=" + encodeURIComponent(message.sender_id);
    const userImg = document.createElement('img');
    userImg.src = "../uploads/small_" + message.image_path;
    userLink.appendChild(userImg);

    const text = document.createElement('div');
    text.classList.add('message_text');

    const messageText = document.createElement('p');
    messageText.textContent = message.msg;
    const date = document.createElement('p');
    date.id = "message_date"
    date.textContent = message.date;

    text.appendChild(messageText);
    text.appendChild(date);

    messageDiv.appendChild(userLink);
    messageDiv.appendChild(text);

    const messagesDiv = document.querySelector('#messages');
    messagesDiv.appendChild(messageDiv);
}