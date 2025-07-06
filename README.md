# 📝 Listsy

<div align="center">
  <img src="https://img.shields.io/badge/PHP-308585?style=for-the-badge&logo=php&logoColor=E6F4F5" alt="PHP">
  <img src="https://img.shields.io/badge/Bootstrap-57A2A2?style=for-the-badge&logo=bootstrap&logoColor=E6F4F5" alt="Bootstrap">
  <img src="https://img.shields.io/badge/CSS3-308585?style=for-the-badge&logo=css3&logoColor=E6F4F5" alt="CSS3">
  <img src="https://img.shields.io/badge/JavaScript-222C2C?style=for-the-badge&logo=javascript&logoColor=E6F4F5" alt="JavaScript">
</div>

## 🎨 Sobre o Projeto

**Listsy** é uma aplicação web para criação e gerenciamento de listas colaborativas. Organize-se junto de quem importa através de grupos privados onde todos podem contribuir em tempo real.

### ✨ Características Visuais

- **🎨 Paleta de Cores Personalizada:**
  - Verde Principal: `#308585` (maingreen)
  - Verde Navbar: `#57A2A2` 
  - Fundo Escuro: `#222C2C`
  - Branco Suave: `#E6F4F5` (mainwhite)
  - Super Branco: `#F5FEFF`

- **📱 Design Responsivo:** Interface adaptável para desktop, tablet e mobile
- **🌈 Gradiente Elegante:** Transição suave do verde para o fundo escuro
- **💫 Efeitos Visuais:** Sombras suaves e hover effects para melhor UX

## 🚀 Funcionalidades

### 👥 Grupos Colaborativos
- Crie grupos privados com amigos, família ou colegas
- Gerencie membros e permissões
- Sistema de convites por usuário

### 📋 Listas Inteligentes
- Listas colaborativas em tempo real
- Adicione, edite e marque itens
- Organização por categorias

### 🔐 Sistema de Usuários
- Cadastro e login seguro
- Perfis personalizados
- Controle de acesso por grupo

## 🛠️ Tecnologias Utilizadas

- **Backend:** PHP
- **Banco de Dados:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5.3.7 (customizado)
- **Pré-processador:** SASS/SCSS
- **Ícones:** Bootstrap Icons

## 📁 Estrutura do Projeto

```
Desenvolvimento Web/
├── 📁 components/
│   ├── navbar.php
│   └── footer.php
├── 📁 css/
│   ├── main.css (Bootstrap customizado)
│   ├── main.scss (configurações SASS)
│   └── style.css (estilos personalizados)
├── 📁 elements/
│   └── Vector.png
├── 📄 index.php (página inicial)
├── 📄 cadastro.php
├── 📄 login.php
├── 📄 meus-grupos.php
├── 📄 grupo.php
├── 📄 lista.php
├── 📄 membros.php
├── 📄 convites.php
├── 📄 database.sql (estrutura do banco)
└── 📄 package.json
```

## 🎯 Como Usar

1. **Clone o repositório**
2. **Configure o servidor local** (XAMPP, WAMP, etc.)
3. **Importe o banco de dados** executando o arquivo `database.sql` no MySQL
4. **Acesse** `localhost/Desenvolvimento Web/`
5. **Cadastre-se** e comece a criar seus grupos!

## 🎨 Customização de Cores

O projeto utiliza variáveis CSS customizadas no Bootstrap:

```scss
$custom-colors: (
    "maingreen": #308585,
    "mainwhite": #E6F4F5
);
```

Essas cores estão disponíveis em todas as classes do Bootstrap:
- `.bg-maingreen` / `.bg-mainwhite`
- `.text-maingreen` / `.text-mainwhite`
- `.btn-maingreen` / `.btn-mainwhite`
- `.border-maingreen` / `.border-mainwhite`

## 📊 Banco de Dados

O sistema utiliza MySQL com as seguintes tabelas principais:
- **usuarios** - Gerenciamento de usuários
- **grupos** - Grupos colaborativos
- **membros_grupo** - Relacionamento usuários/grupos
- **convites** - Sistema de convites
- **listas** - Listas dentro dos grupos
- **itens_lista** - Itens das listas com status

## 👨‍💻 Desenvolvedores

<div align="center">
  
| 👤 **João Paulo Moura** | 👤 **Rafael Eich Fernandes** |
|:---:|:---:|
| [![GitHub](https://img.shields.io/badge/GitHub-222C2C?style=for-the-badge&logo=github&logoColor=E6F4F5)](https://github.com/JPaulo-mrs) | [![GitHub](https://img.shields.io/badge/GitHub-222C2C?style=for-the-badge&logo=github&logoColor=E6F4F5)](https://github.com/eichfernandes) |
| [![Instagram](https://img.shields.io/badge/Instagram-308585?style=for-the-badge&logo=instagram&logoColor=E6F4F5)](https://www.instagram.com/jpaulo_mrs/) | [![Instagram](https://img.shields.io/badge/Instagram-308585?style=for-the-badge&logo=instagram&logoColor=E6F4F5)](https://www.instagram.com/eich_fernandes/) |
| 📱 @JPaulo_Moura | 📱 @Rafaeich |

</div>

## 📄 Licença

© 2025 Listsy. Todos os direitos reservados.

---

<div align="center">
  <p><strong>Listas compartilhadas, simples como devem ser</strong></p>
  <p>🌟 Organize-se junto de quem importa 🌟</p>
</div>