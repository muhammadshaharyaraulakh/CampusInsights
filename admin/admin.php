<?php 
require_once __DIR__."/includes/header.php";
?>

<div class="main-wrapper">

    <div class="dashboard-center-container">
        
        <div class="ai-widget-card">
            <div class="ai-header">
                <div class="header-left">
                    <i class="fas fa-robot"></i> 
                    <h3>AI Insight Assistant</h3>
                    <span class="badge-ai">Beta</span>
                </div>
                <button class="btn-clear-chat" onclick="clearChat()" title="Clear History">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="ai-body" id="aiResponseArea">
                <div class="ai-message bot">
                    <p><strong>Welcome, Shaharyar.</strong></p>
                    <p>I have access to the latest survey data. Ask me about student satisfaction, facility issues, or specific department feedback.</p>
                </div>
            </div>

            <div class="ai-footer">
                <div class="input-group">
                    <input type="text" id="aiQuestionInput" placeholder="Ask a question about the survey data..." autocomplete="off">
                    <button id="btnAskAi" onclick="askAi()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="action-buttons-row">
            
            <button class="action-card btn-open" onclick="updateSurveyStatus('open')">
                <div class="icon-box"><i class="fas fa-door-open"></i></div>
                <div class="text-box">
                    <h4>Open Survey Portal</h4>
                    <span>Allow students to submit</span>
                </div>
            </button>

            <button class="action-card btn-close" onclick="updateSurveyStatus('close')">
                <div class="icon-box"><i class="fas fa-door-closed"></i></div>
                <div class="text-box">
                    <h4>Close Survey Portal</h4>
                    <span>Stop new submissions</span>
                </div>
            </button>

            <a href="analytics.php" class="action-card btn-analytics">
                <div class="icon-box"><i class="fas fa-chart-pie"></i></div>
                <div class="text-box">
                    <h4>View Analytics</h4>
                    <span>See detailed reports</span>
                </div>
            </a>

        </div>

    </div>

</div>

<style>
    /* Centering Wrapper */
    .main-wrapper {
        
        min-height: 100vh; /* Takes up most of the screen height */
        display: flex;
        justify-content: center;
        align-items: center; /* Vertically center */
        background-color: #f3f4f6;
        padding: 20px;
        margin-top: 6rem;
    }

    .dashboard-center-container {
        width: 100%;
        max-width: 700px; /* Limits width so it looks good on PC */
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* --- AI WIDGET STYLES --- */
    .ai-widget-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid #e1e4e8;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 450px; /* Slightly taller for better view */
        width: 100%;
    }

    .ai-header {
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        color: white;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ai-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-ai {
        background: rgba(255,255,255,0.2);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .btn-clear-chat {
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.7);
        cursor: pointer;
        font-size: 1rem;
        transition: color 0.2s;
    }

    .btn-clear-chat:hover {
        color: #fff;
    }

    .ai-body {
        flex: 1;
        background-color: #f8fafc;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        scroll-behavior: smooth;
    }

    /* Scrollbar Styling */
    .ai-body::-webkit-scrollbar {
        width: 6px;
    }
    .ai-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 3px;
    }

    .ai-message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.95rem;
        line-height: 1.5;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .ai-message.bot {
        align-self: flex-start;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        color: #334155;
        border-bottom-left-radius: 2px;
    }

    .ai-message.user {
        align-self: flex-end;
        background-color: #4f46e5;
        color: white;
        border-bottom-right-radius: 2px;
    }

    .ai-footer {
        padding: 15px 20px;
        background: #fff;
        border-top: 1px solid #e2e8f0;
    }

    .input-group {
        display: flex;
        gap: 10px;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 12px;
        border: 1px solid transparent;
        transition: 0.3s;
    }

    .input-group:focus-within {
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .input-group input {
        flex: 1;
        padding: 12px;
        border: none;
        background: transparent;
        outline: none;
        font-size: 0.95rem;
        color: #334155;
    }

    .input-group button {
        background: #4f46e5;
        color: white;
        border: none;
        width: 45px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        font-size: 1rem;
    }

    .input-group button:hover {
        background: #4338ca;
    }

    /* --- ACTION BUTTONS ROW --- */
    .action-buttons-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr; /* 3 Columns */
        gap: 15px;
    }

    .action-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: left;
        background: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .action-card .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .action-card h4 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
    }

    .action-card span {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }

    /* Button Specific Colors */
    /* Open (Green) */
    .btn-open .icon-box { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .btn-open:hover { border: 1px solid #10b981; }

    /* Close (Red) */
    .btn-close .icon-box { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .btn-close:hover { border: 1px solid #ef4444; }

    /* Analytics (Blue) */
    .btn-analytics .icon-box { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .btn-analytics:hover { border: 1px solid #3b82f6; }

    /* Typing Animation */
    .typing-indicator span {
        display: inline-block;
        width: 6px;
        height: 6px;
        background: #9ca3af;
        border-radius: 50%;
        margin: 0 2px;
        animation: bounce 1.4s infinite ease-in-out both;
    }
    .typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
    .typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

    /* Responsive for Mobile */
    @media (max-width: 600px) {
        .action-buttons-row {
            grid-template-columns: 1fr; /* Stack vertically on phone */
        }
        .ai-widget-card {
            height: 400px;
        }
    }
</style>

<script>
    // 1. Ask AI Logic
    function askAi() {
        const inputField = document.getElementById('aiQuestionInput');
        const responseArea = document.getElementById('aiResponseArea');
        const question = inputField.value.trim();

        if (question === "") return;

        // User Bubble
        const userBubble = document.createElement('div');
        userBubble.className = 'ai-message user';
        userBubble.textContent = question;
        responseArea.appendChild(userBubble);
        inputField.value = "";
        responseArea.scrollTop = responseArea.scrollHeight;

        // Loading
        const loadingBubble = document.createElement('div');
        loadingBubble.className = 'ai-message bot';
        loadingBubble.id = 'aiLoading';
        loadingBubble.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
        responseArea.appendChild(loadingBubble);
        responseArea.scrollTop = responseArea.scrollHeight;

        // Simulate AI
        setTimeout(() => {
            document.getElementById('aiLoading').remove();
            const botBubble = document.createElement('div');
            botBubble.className = 'ai-message bot';
            
            // Basic Fake Logic for Demo
            if(question.toLowerCase().includes("clear")) {
                botBubble.innerHTML = "To clear chat, click the Trash icon at the top right.";
            } else if(question.toLowerCase().includes("open")) {
                botBubble.textContent = "You can open the survey using the green button below.";
            } else {
                botBubble.innerHTML = "That's an interesting point. Based on the <strong>450 responses</strong>, the trend seems positive regarding this topic.";
            }
            
            responseArea.appendChild(botBubble);
            responseArea.scrollTop = responseArea.scrollHeight;
        }, 1200);
    }

    // Enter Key Support
    document.getElementById("aiQuestionInput").addEventListener("keypress", function(event) {
        if (event.key === "Enter") askAi();
    });

    // 2. Clear Chat Logic
    function clearChat() {
        const responseArea = document.getElementById('aiResponseArea');
        responseArea.innerHTML = `
            <div class="ai-message bot">
                <p>Chat history cleared.</p>
                <p>I am ready for your next question.</p>
            </div>
        `;
    }

    // 3. Survey Status Logic (Placeholder)
    function updateSurveyStatus(status) {
        if(status === 'open') {
            // Here you will eventually add AJAX to update DB
            alert("Survey Portal is now OPEN for students.");
        } else {
            if(confirm("Are you sure you want to CLOSE the survey? Students won't be able to submit.")) {
                alert("Survey Portal is now CLOSED.");
            }
        }
    }
</script>

<?php 
require_once __DIR__."/includes/footer.php";
?>