<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie użytkownikami</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <div class="table-container">
            <div class="back-button-container">
                <a href="/2fatest/dashboard" class="back-button">&lt;</a>
            </div>
            <h2>Zarządzanie użytkownikami</h2>
            <?php if (isset($success)): ?>
                <p><?php echo $success; ?></p>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nazwa użytkownika</th>
                        <th>Email</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Rola</th>
                        <th>Resetuj hasło</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <form method="post" action="/2fatest/reset_password">
                                <button type="submit" name="reset_password" value="<?php echo htmlspecialchars($user['id']); ?>">Resetuj hasło</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
