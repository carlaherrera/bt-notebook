# Padrão de Acesso Seguro a Arrays - Projeto BOCA

## Problema
Erros de "Undefined array key" ocorrem quando views acessam arrays sem verificar existência de chaves.

## Solução Implementada

### 1. Funções Helper Globais

Todas as views têm acesso a funções helper para acesso seguro:

#### `safe($array, $key, $default = '')`
Acesso seguro a array com valor padrão.

**Antes (Inseguro):**
```php
<?= htmlspecialchars($user['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
```

**Depois (Seguro):**
```php
<?= esc(safe($user, 'nome', 'Sem nome')) ?>
```

#### `esc($value, $default = '')`
Escape HTML seguro com null coalescing.

**Antes:**
```php
<?= htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
```

**Depois:**
```php
<?= esc($valor, 'padrão') ?>
```

#### `num($value, $decimals = 2, $default = '0,00')`
Formatação de número segura.

**Antes:**
```php
<?= number_format((float)($preco ?? 0), 2, ',', '.') ?>
```

**Depois:**
```php
<?= num($preco, 2) ?>
```

#### `path($array, 'chave.aninhada', $default = '')`
Acesso a arrays aninhados de forma segura.

**Uso:**
```php
<?= esc(path($data, 'user.profile.nome', 'Sem nome')) ?>
```

#### `ensure($array, ['chave1', 'chave2'])`
Garante que array tem todas as chaves esperadas.

**Uso em Controller:**
```php
$user = ensure($userData, ['id', 'nome', 'email', 'status']);
```

### 2. Padrão em Controllers/Repositories

**Garantir estrutura de dados consistente:**

```php
// Antes (inconsistente)
$user = $repo->find($id); // Pode ter chaves diferentes

// Depois (consistente)
$user = ensure($repo->find($id), [
    'id', 'nome', 'email', 'status', 'created_at'
]);
```

### 3. Padrão em Views

**Sempre usar helpers para acesso a dados:**

```php
<!-- Antes (inseguro) -->
<p><?= htmlspecialchars($user['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>

<!-- Depois (seguro) -->
<p><?= esc(safe($user, 'nome', 'Sem nome')) ?></p>
```

### 4. Checklist para Novas Views

- [ ] Usar `safe()` para acesso a arrays
- [ ] Usar `esc()` para output HTML
- [ ] Usar `num()` para números
- [ ] Usar `path()` para dados aninhados
- [ ] Usar `ensure()` em controllers para garantir estrutura

## Benefícios

✅ Sem mais erros de "Undefined array key"
✅ Código mais legível e consistente
✅ Valores padrão automáticos
✅ Proteção contra XSS
✅ Fácil manutenção

## Implementação Gradual

1. Aplicar em novas views
2. Refatorar views críticas (admin/*, cliente/*)
3. Refatorar demais views
4. Adicionar validação em controllers

## Referência Rápida

```php
// Acesso seguro
safe($array, 'key', 'default')

// Escape HTML
esc($value, 'default')

// Número formatado
num($value, 2)

// Array aninhado
path($array, 'a.b.c', 'default')

// Garantir estrutura
ensure($data, ['id', 'nome'])
```
