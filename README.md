# 📝 Listsy

<div align="center">
  <img src="https://img.shields.io/badge/PHP-308585?style=for-the-badge&logo=php&logoColor=E6F4F5" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-57A2A2?style=for-the-badge&logo=mysql&logoColor=E6F4F5" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-308585?style=for-the-badge&logo=bootstrap&logoColor=E6F4F5" alt="Bootstrap">
  <img src="https://img.shields.io/badge/JavaScript-222C2C?style=for-the-badge&logo=javascript&logoColor=E6F4F5" alt="JavaScript">
</div>

## 🎯 Sobre o Projeto

**Listsy** é uma plataforma web colaborativa para gerenciamento de listas em grupos. Desenvolvida para facilitar a organização compartilhada entre famílias, equipes e comunidades através de um sistema intuitivo e responsivo.

### 🎨 Design System

- **Paleta de Cores:**
  - Verde Principal: `#308585`
  - Verde Secundário: `#57A2A2`
  - Fundo Escuro: `#222C2C`
  - Branco Principal: `#E6F4F5`

- **Interface Responsiva** com Bootstrap 5.3.7 customizado
- **Experiência Otimizada** para desktop e dispositivos móveis

## ⚡ Funcionalidades

### 🔐 Autenticação
- Sistema de cadastro e login seguro
- Controle de sessões e permissões
- Proteção contra acesso não autorizado

### 👥 Gerenciamento de Grupos
- Criação e administração de grupos privados
- Sistema de convites entre usuários
- Controle de membros por grupo
- Edição de nomes de grupos (apenas admins)

### 📋 Listas Colaborativas
- Criação de listas dentro dos grupos
- Adição, edição e remoção de itens
- Sistema de marcação (check/uncheck)
- Edição de nomes das listas
- Remoção em lote de itens marcados
- Persistência em tempo real no banco de dados

## 🛠️ Stack Tecnológica

- **Backend:** PHP 8+ com PDO
- **Banco de Dados:** MySQL 8.0
- **Frontend:** HTML5, CSS3, JavaScript ES6+
- **Framework CSS:** Bootstrap 5.3.7 customizado
- **Ícones:** Bootstrap Icons
- **Servidor:** Apache (XAMPP)

## 📁 Arquitetura

```
Desenvolvimento Web/
├── 📁 components/          # Componentes reutilizáveis
├── 📁 config/             # Configurações do sistema
│   └── database.php       # Conexão com MySQL
├── 📁 css/               # Estilos e temas
├── 📄 index.php          # Página inicial
├── 📄 cadastro.php       # Registro de usuários
├── 📄 login.php          # Autenticação
├── 📄 meus-grupos.php    # Dashboard de grupos
├── 📄 grupo.php          # Visualização do grupo
├── 📄 lista.php          # Gerenciamento de listas
├── 📄 membros.php        # Administração de membros
├── 📄 convites.php       # Sistema de convites
└── 📄 database.sql       # Schema do banco
```

## 🚀 Instalação

1. **Configure o ambiente:**
   ```bash
   # Instale XAMPP ou similar
   # Inicie Apache e MySQL
   ```

2. **Configure o banco de dados:**
   ```sql
   # Execute database.sql no phpMyAdmin
   # ou via linha de comando:
   mysql -u root -p < database.sql
   ```

3. **Acesse a aplicação:**
   ```
   http://localhost/Desenvolvimento Web/
   ```

## 🎨 Personalização

O sistema utiliza Bootstrap customizado com variáveis CSS:

```scss
$custom-colors: (
    "maingreen": #308585,
    "mainwhite": #E6F4F5
);
```

Classes disponíveis: `.bg-maingreen`, `.text-maingreen`, `.btn-maingreen`, etc.

## 🗄️ Modelo de Dados

- **usuarios** - Autenticação e perfis
- **grupos** - Grupos colaborativos com administradores
- **membros_grupo** - Relacionamento N:N usuários/grupos
- **convites** - Sistema de convites com status
- **listas** - Listas organizadas por grupo
- **itens_lista** - Itens com status de marcação

## 👨‍💻 Equipe de Desenvolvimento

<div align="center">

| **João Paulo Moura** | **Rafael Eich Fernandes** |
|:---:|:---:|
| [![GitHub](https://img.shields.io/badge/GitHub-222C2C?style=for-the-badge&logo=github&logoColor=E6F4F5)](https://github.com/JPaulo-mrs) | [![GitHub](https://img.shields.io/badge/GitHub-222C2C?style=for-the-badge&logo=github&logoColor=E6F4F5)](https://github.com/eichfernandes) |

</div>

---

<div align="center">
  <p><strong>Listsy - Organização colaborativa simplificada</strong></p>
  <p>© 2025 - Desenvolvido com 💚</p>
</div>