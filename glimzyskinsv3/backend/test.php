<?php
/**
 * Plik testowy do sprawdzenia działania endpointów
 * Użyj: http://localhost/glimzyskins/test.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test API Glimzyskins</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .test-section { margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        button { padding: 10px 20px; background: #FFC107; color: #000; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #FFA000; }
        .result { margin-top: 10px; padding: 10px; background: #fff; border: 1px solid #ddd; border-radius: 4px; }
        input { padding: 8px; margin: 5px; width: 200px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test API Glimzyskins</h1>
        
        <div class="test-section">
            <h3>Test połączenia z bazą danych</h3>
            <button onclick="testDB()">Test DB</button>
            <div id="dbResult" class="result" style="display:none;"></div>
        </div>
        
        <div class="test-section">
            <h3>Test rejestracji</h3>
            <input type="email" id="regEmail" placeholder="Email" value="test@test.com">
            <input type="password" id="regPassword" placeholder="Hasło" value="test123">
            <br><button onclick="testRegister()">Test Rejestracji</button>
            <div id="regResult" class="result" style="display:none;"></div>
        </div>
        
        <div class="test-section">
            <h3>Test logowania</h3>
            <input type="email" id="loginEmail" placeholder="Email" value="test@test.com">
            <input type="password" id="loginPassword" placeholder="Hasło" value="test123">
            <br><button onclick="testLogin()">Test Logowania</button>
            <div id="loginResult" class="result" style="display:none;"></div>
        </div>
    </div>

    <script>
        async function testDB() {
            const resultDiv = document.getElementById('dbResult');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = 'Sprawdzanie...';
            
            try {
                const response = await fetch('db.php');
                const text = await response.text();
                resultDiv.innerHTML = '<pre>' + text + '</pre>';
            } catch (error) {
                resultDiv.innerHTML = 'Błąd: ' + error.message;
            }
        }
        
        async function testRegister() {
            const email = document.getElementById('regEmail').value;
            const password = document.getElementById('regPassword').value;
            const resultDiv = document.getElementById('regResult');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = 'Wysyłanie...';
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                resultDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                resultDiv.innerHTML = 'Błąd: ' + error.message;
            }
        }
        
        async function testLogin() {
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const resultDiv = document.getElementById('loginResult');
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = 'Wysyłanie...';
            
            try {
                const formData = new FormData();
                formData.append('email', email);
                formData.append('password', password);
                
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                resultDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                resultDiv.innerHTML = 'Błąd: ' + error.message;
            }
        }
    </script>
</body>
</html>

