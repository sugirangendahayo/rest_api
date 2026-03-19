<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REST API Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .result { background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 3px; white-space: pre-wrap; }
        button { padding: 8px 15px; margin: 5px; cursor: pointer; }
        input { padding:5px; margin: 5px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>REST API Test Interface</h1>

    <div class="section">
        <h2>GET All Users</h2>
        <button onclick="getAllUsers()">Get All Users</button>
        <div id="getAllResult" class="result"></div>
    </div>

    <div class="section">
        <h2>GET Single User</h2>
        <input type="number" id="getUserId" placeholder="User ID" value="1">
        <button onclick="getUser()">Get User</button>
        <div id="getUserResult" class="result"></div>
    </div>

    <div class="section">
        <h2>POST Create User</h2>
        <input type="text" id="createName" placeholder="Name">
        <input type="email" id="createEmail" placeholder="Email">
        <button onclick="createUser()">Create User</button>
        <div id="createResult" class="result"></div>
    </div>

    <div class="section">
        <h2>PUT Update User</h2>
        <input type="number" id="updateId" placeholder="User ID" value="1">
        <input type="text" id="updateName" placeholder="Name">
        <input type="email" id="updateEmail" placeholder="Email">
        <button onclick="updateUser()">Update User</button>
        <div id="updateResult" class="result"></div>
    </div>

    <div class="section">
        <h2>PATCH Partial Update</h2>
        <input type="number" id="patchId" placeholder="User ID" value="1">
        <input type="text" id="patchName" placeholder="Name (optional)">
        <input type="email" id="patchEmail" placeholder="Email (optional)">
        <button onclick="patchUser()">Patch User</button>
        <div id="patchResult" class="result"></div>
    </div>

    <div class="section">
        <h2>DELETE User</h2>
        <input type="number" id="deleteId" placeholder="User ID">
        <button onclick="deleteUser()">Delete User</button>
        <div id="deleteResult" class="result"></div>
    </div>

    <div class="section">
        <h2>Search Users</h2>
        <input type="text" id="searchQuery" placeholder="Search keywords">
        <button onclick="searchUsers()">Search</button>
        <div id="searchResult" class="result"></div>
    </div>

    <script>
        async function makeRequest(url, method, body, resultId) {
            const el = document.getElementById(resultId);
            el.innerHTML = 'Loading...';
            try {
                const options = {
                    method: method,
                    headers: { 'Content-Type': 'application/json' }
                };
                if (body) options.body = JSON.stringify(body);

                const response = await fetch(url, options);
                const data = await response.json();
                el.innerHTML = 
                    `<span class="${response.ok ? 'success' : 'error'}">Status: ${response.status}</span>\n` +
                    JSON.stringify(data, null, 2);
            } catch (err) {
                el.innerHTML = `<span class="error">Error: ${err.message}</span>`;
            }
        }

        function getAllUsers() {
            makeRequest('api.php', 'GET', null, 'getAllResult');
        }

        function getUser() {
            const id = document.getElementById('getUserId').value;
            if (!id) { document.getElementById('getUserResult').innerHTML = '<span class="error">ID is required</span>'; return; }
            makeRequest('api.php?id=' + id, 'GET', null, 'getUserResult');
        }

        function createUser() {
            const name  = document.getElementById('createName').value;
            const email = document.getElementById('createEmail').value;
            if (!name || !email) { document.getElementById('createResult').innerHTML = '<span class="error">Name and email are required</span>'; return; }
            makeRequest('api.php', 'POST', { name, email }, 'createResult');
        }

        function updateUser() {
            const id    = document.getElementById('updateId').value;
            const name  = document.getElementById('updateName').value;
            const email = document.getElementById('updateEmail').value;
            if (!id || !name || !email) { document.getElementById('updateResult').innerHTML = '<span class="error">ID, name and email are required</span>'; return; }
            makeRequest('api.php?id=' + id, 'PUT', { name, email }, 'updateResult');
        }

        function patchUser() {
            const id    = document.getElementById('patchId').value;
            const name  = document.getElementById('patchName').value;
            const email = document.getElementById('patchEmail').value;
            if (!id) { document.getElementById('patchResult').innerHTML = '<span class="error">ID is required</span>'; return; }
            const body = {};
            if (name)  body.name  = name;
            if (email) body.email = email;
            if (Object.keys(body).length === 0) { document.getElementById('patchResult').innerHTML = '<span class="error">At least one field required</span>'; return; }
            makeRequest('api.php?id=' + id, 'PATCH', body, 'patchResult');
        }

        function deleteUser() {
            const id = document.getElementById('deleteId').value;
            if (!id) { document.getElementById('deleteResult').innerHTML = '<span class="error">ID is required</span>'; return; }
            makeRequest('api.php?id=' + id, 'DELETE', null, 'deleteResult');
        }

        function searchUsers() {
            const query = document.getElementById('searchQuery').value;
            if (!query) { document.getElementById('searchResult').innerHTML = '<span class="error">Search query is required</span>'; return; }
            makeRequest('api.php?search=' + encodeURIComponent(query), 'GET', null, 'searchResult');
        }
    </script>
</body>
</html>
