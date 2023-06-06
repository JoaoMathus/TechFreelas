<?php
    include "app/db-php/db.php";
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        if (isset($_POST["entrar"])) {
            // Assegurando se os campos foram preeenchidos...
            $login = isset($_POST["login"]) ? $_POST["login"] : '';
            $senha = isset($_POST["senha"]) ? $_POST["senha"] : '';
            $mensagem = '';
            $nome = '';

            if (empty($login) || empty($senha)) {
                $mensagem = "Todos os campos precisam ser preenchidos!";
            } else {
                // Checando se há o usuário no banco de dados
                $achou = false;
                $select = $db->prepare("SELECT * FROM Usuario 
                                        WHERE Email = :login OR Cpf = :login");
                $select->bindParam(':login', $login);
                $select->execute();
                while ($linha = $select->fetch(PDO::FETCH_ASSOC)) {
                    if ($linha["Senha"] == $senha) {
                        $achou = true;
                        $nome = $linha["Nome"];
                        break;
                    }
                }
                if ($achou) {
                    $_SESSION["usuario"] = $nome;
                    header("Location: ../main-page/index.php");
                } else {
                    $mensagem = "Login ou senha inválido(s).";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechFreelas | Login</title>
    <script src="https://kit.fontawesome.com/8ad2738107.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../tailwindCSS/tailwind.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Urbanist:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../src/scroll.css">
</head>
<body class="bodyPattern">
    <main class="flex flex-col items-center justify-evenly h-screen">
        <h1>
            <a href="../main-page/index.php">
                <figure class="items-center text-3xl flex flex-col">
                    <img class="w-20 mb-2" src="../../src/imgs/logo.svg" alt="Logo Techfreelas">
                    <figcaption class="font-semibold">TechFreelas</figcaption>
                </figure>
            </a>
        </h1>
        <fieldset>
            <form class="flex item-center justify-center flex-col"
                  action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="POST">
                <div class="flex flex-col">
                    <label for="login">E-Mail ou CPF</label>
                    <input id="login" class="bg-rich outline-none text-lg p-2 border border-blue-600/40 rounded-lg" type="text" name="login" required>
                </div>
                <div class="flex flex-col">
                    <label for="password">Senha</label>
                    <input id="password" class="bg-rich outline-none text-lg p-2 border border-blue-600/40 rounded-lg" type="password" name="senha" required>
                </div>
                <?php
                    echo '<p>'.$mensagem.'</p>';
                ?>
                <button class="button w-full mt-5" type="submit" name="entrar">Entrar</button>
                <div class="mt-7 h-28 flex flex-col justify-around">
                    <a class="flex items-center justify-center bg-slate-300 text-black font-semibold button hover:bg-gray-400" href="#"><i class="fa-brands fa-google text-red-600 mr-3"></i><p>Entrar com Google</p></a>
                    <a class="flex items-center justify-center bg-slate-300 text-black font-semibold button hover:bg-gray-400" href="#"><i class="fa-brands fa-square-facebook text-blue-600 mr-3"></i><p>Entrar com Facebook</p></a>
                </div>
                <a class="block text-center mt-2 underline text-blue-400 hover:text-blue-800" href="cadastro.php">Não possui uma conta ?</a>
            </form>
        </fieldset>
    </main>
</body>
</html>