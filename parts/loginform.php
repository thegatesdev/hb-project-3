<form method="post">
    <h2>Inloggen</h2>
    <input type="text" name="input_username" class="text_field" placeholder="Gebruikersnaam"
    value="<?php isset($_POST['input_username']) ? $_POST['input_username'] : "" ?>">
    <input type="password" name="input_pwd" class="text_field" placeholder="Wachtwoord">
    <input type="submit" name="login" value="Go" class="button-big" class="hover_orange">
    <input type="submit" name="new_account" class="button-quiet" value="Maak account">
</form>