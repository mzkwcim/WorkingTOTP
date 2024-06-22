<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzaj użytkownikami</title>
    <link rel="stylesheet" href="/2fatest/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Zarządzaj użytkownikami</h2>
            <div class="back-button-container">
                <a href="/2fatest/dashboard" class="back-button">&lt;</a>
            </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa użytkownika</th>
                    <th>Email</th>
                    <th>Rola</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <form method="post" action="/2fatest/change_role">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="auditor" <?php echo $user['role'] == 'auditor' ? 'selected' : ''; ?>>Auditor</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="/2fatest/reset_password">
                                <input type="hidden" name="reset_password" value="<?php echo $user['id']; ?>">
                                <button type="submit">Resetuj hasło</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="successMessage" class="message"><?php echo $success ?? ''; ?></p>
        </div>
    </div>

    <script src="/2fatest/js/script.js"></script>
    <?php if (isset($success) && !empty($success)): ?>
        <script>
            initializeModal();
        </script>
    <?php endif; ?>
</body>
</html>
