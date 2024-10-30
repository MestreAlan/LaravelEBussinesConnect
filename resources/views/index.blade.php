<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <div id="login-form">
        <form id="login-form-element">
            @csrf <!-- Adiciona o token CSRF para proteção contra CSRF -->
            <div>
                <label for="login">Login:</label>
                <input type="text" id="login" name="login">
            </div>
            <div>
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
    <div id="response-message"></div>

    <!-- Botão para verificar a autenticação -->
    <button id="verificar-auth">Verificar Autenticação</button>

    <!-- Botão para realizar logout -->
    <button id="logout">Logout</button>

    <!-- Importa o jQuery para utilizar AJAX -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Função para verificar a autenticação
        function verificarAutenticacao() {
            $.ajax({
                type: 'GET',
                url: '/verificar-autenticacao',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token') // Envie o token JWT armazenado no localStorage
                },
                success: function(response) {
                    $('#response-message').text(response.message); // Exiba a mensagem de sucesso no campo #response-message
                },
                error: function(xhr, status, error) {
                    $('#response-message').text('Usuário não autenticado.'); // Exiba a mensagem de erro no campo #response-message
                }
            });
        }

        // Quando o documento estiver pronto
        $(document).ready(function() {
            $('#response-message').text($('meta[name="csrf-token"]').attr('content'));
            // Captura o evento de envio do formulário
            $('#login-form-element').submit(function(event) {
                // Previne o comportamento padrão do formulário
                event.preventDefault();

                // Obtém os dados do formulário
                var login = $('#login').val();
                var password = $('#password').val();


                // Envia uma requisição AJAX para o endpoint de login
                $.ajax({
                    type: 'POST',
                    url: '{{ route("login.submit") }}', // Utiliza a rota nomeada para o endpoint de login
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Obtém o token CSRF do meta tag
                    },
                    data: {
                        login: login,
                        password: password
                    },
                    success: function(response) {
                        // Manipula a resposta da requisição
                        if (response.feedback == 1) {
                            $('#response-message').text('Login bem-sucedido!');

                            // Armazena o token JWT no localStorage
                            localStorage.setItem('token', response.token);

                            // Redireciona o usuário para outra página, se necessário
                            //window.location.href = '/dashboard';
                        } else {
                            console.log(response);
                            $('#response-message').text('Login falhou. Verifique suas credenciais.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        console.log(status);
                        console.log(xhr);
                        $('#response-message').text('Erro ao processar a requisição. Tente novamente mais tarde.');
                    }
                });
            });

            // Adiciona um evento de clique ao botão "Verificar Autenticação"
            $('#verificar-auth').click(function() {
                verificarAutenticacao();
            });

            // Adiciona um evento de clique ao botão "Logout"
            $('#logout').click(function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ route("logout") }}', // Rota para fazer o logout
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Obtém o token CSRF do meta tag
                    },
                    success: function(response) {
                        localStorage.removeItem('token'); // Remove o token JWT armazenado no localStorage
                        window.location.href = response.redirect; // Redireciona para a página de login
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        console.log(status);
                        console.log(xhr);
                        $('#response-message').text('Erro ao fazer logout.'); // Exibe mensagem de erro
                    }
                });
            });
        });
    </script>

</body>
</html>
