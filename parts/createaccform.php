<form method="post">
    <h2>Nieuw account</h2>
    <input type="text" name="input_username" class="text_field" placeholder="Gebruikersnaam" value="<?php isset($_POST['input_username']) ? $_POST['input_username'] : "" ?>">
    <input type="password" name="input_pwd" class="text_field" placeholder="Wachtwoord">
    <input type="password" name="input_pwd_check" class="text_field" placeholder="Herhaal wachtwoord">
    <input type="submit" name="create_account" value="Go" class="button-big">
    <input type="submit" name="back" class="button-quiet" value="Terug">
</form>