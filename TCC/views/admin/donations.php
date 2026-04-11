<?php
// View: Admin - Donations
// Renderizada por AdminController::manageDonations() e receiveDonation()
SessionManager::requireRole('admin');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Doações - Admin</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filtros {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filtros input, .filtros select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .filtros button {
            padding: 8px 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .filtros button:hover {
            background: #1d4ed8;
        }

        .doacoes-tabela {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 15px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background: #f3f4f6;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
        }

        .status-badge.pendente {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.recebida {
            background: #dcfce7;
            color: #166534;
        }

        .form-receber {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .form-receber select {
            padding: 6px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-receber button {
            padding: 6px 12px;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }

        .form-receber button:hover {
            background: #15803d;
        }

        .mensagem {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
        }

        .mensagem.sucesso {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .mensagem.erro {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .vazio {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .codigo-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .codigo-box form {
            max-width: none;
            box-shadow: none;
            background: transparent;
            padding: 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: end;
        }

        .codigo-box input {
            max-width: 320px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<?php include(__DIR__ . '/../layouts/navbar.php'); ?>

<main>
    <div class="admin-container">
        <div class="admin-header">
            <h2>Gerenciar Doações</h2>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo htmlspecialchars($tipoMensagem); ?>">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <div class="codigo-box">
            <h3>Receber por código da doação</h3>
            <form method="POST" action="index.php?page=admin_receive_donation">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(SessionManager::getCsrfToken()); ?>">
                <input type="hidden" name="receber_doacao" value="1">
                <div>
                    <label for="codigo_doacao">Código apresentado pelo doador</label>
                    <input type="text" name="codigo_doacao" id="codigo_doacao" placeholder="Ex.: DCS-20260410-000002" required>
                </div>
                <button type="submit">Receber por código</button>
            </form>
        </div>

        <div class="filtros">
            <form method="GET" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <input type="hidden" name="page" value="admin_donations">
                
                <select name="status" onchange="this.form.submit()">
                    <option value="todos" <?php echo $filtro === 'todos' ? 'selected' : ''; ?>>Todos os Status</option>
                    <option value="pendente" <?php echo $filtro === 'pendente' ? 'selected' : ''; ?>>Pendentes</option>
                    <option value="recebida" <?php echo $filtro === 'recebida' ? 'selected' : ''; ?>>Recebidas</option>
                </select>

                <input type="text" name="busca" placeholder="Buscar por nome, campanha ou código..." value="<?php echo htmlspecialchars($busca); ?>">
                
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <?php if (empty($doacoes)): ?>
            <div class="vazio">
                <p>Nenhuma doação encontrada com esses filtros.</p>
            </div>
        <?php else: ?>
            <div class="doacoes-tabela">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Doador</th>
                            <th>Campanha</th>
                            <th>Ponto</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($doacoes as $doacao): ?>
                            <tr>
                                <td>#<?php echo $doacao['id']; ?></td>
                                <td><?php echo htmlspecialchars($doacao['codigo_publico']); ?></td>
                                <td><?php echo htmlspecialchars($doacao['usuario_nome']); ?></td>
                                <td><?php echo htmlspecialchars($doacao['campanha_nome']); ?></td>
                                <td><?php echo htmlspecialchars($doacao['ponto_nome']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($doacao['data_criacao'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $doacao['status']; ?>">
                                        <?php echo ucfirst($doacao['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($doacao['status'] === 'pendente'): ?>
                                        <form method="POST" action="index.php?page=admin_receive_donation" class="form-receber" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(SessionManager::getCsrfToken()); ?>">
                                            <input type="hidden" name="doacao_id" value="<?php echo $doacao['id']; ?>">
                                            <input type="hidden" name="receber_doacao" value="1">

                                            <button type="submit">Receber</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color: #16a34a;">✓ Recebida</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>© 2026 ConectaSolidária</p>
</footer>

</body>
</html>
