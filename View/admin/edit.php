#Edition d'un utilisateur
<form action="?ctrl=admin&action=update&id=<?= $user->getId() ?>" method="post">
    <input type="text" name="firstname" value="<?= $user->getFirstname() ?>">
    <input type="text" name="lastname" value="<?= $user->getLastname() ?>">
    <input type="text" name="email" value="<?= $user->getEmail() ?>">
    <input type="submit" name="submit" value="Modifier">
</form>
