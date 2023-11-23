<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form method="POST" action="/" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            placeholder="Tu email"
            id="email"
            name="email"
            value="<?php echo s($auth->email)?>"
        />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            placeholder="Tu password"
            id="password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Iniciar sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>