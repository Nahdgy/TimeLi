#Edition d'un utilisateur
<div class="container">
    <form action="?ctrl=admin&action=edit&id=<?= $user->getId() ?>" method="post">
        <input type="text" name="firstname" value="<?= $user->getFirstname() ?>">
        <input type="text" name="lastname" value="<?= $user->getLastname() ?>">
        <input type="text" name="email" value="<?= $user->getEmail() ?>">
        <input type="submit" name="submit" value="Modifier">
    </form>
</div>
