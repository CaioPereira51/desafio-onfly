# 🐳 Instalação do Docker no Windows

## Pré-requisitos
- Windows 10 64-bit: Pro, Enterprise ou Education (Build 16299 ou posterior)
- Windows 11 64-bit: Home ou Pro (Build 22000 ou posterior)
- WSL 2 habilitado
- Virtualização habilitada no BIOS

## Passo 1: Habilitar WSL 2

### 1.1. Abra o PowerShell como Administrador
- Clique com botão direito no menu Iniciar
- Selecione "Windows PowerShell (Admin)"

### 1.2. Execute os comandos:
```powershell
# Habilitar WSL
dism.exe /online /enable-feature /featurename:Microsoft-Windows-Subsystem-Linux /all /norestart

# Habilitar recurso de Máquina Virtual
dism.exe /online /enable-feature /featurename:VirtualMachinePlatform /all /norestart

# Reiniciar o computador
Restart-Computer
```

### 1.3. Após reiniciar, baixe e instale o kernel do WSL 2:
- Baixe: https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi
- Execute o arquivo baixado

### 1.4. Defina WSL 2 como padrão:
```powershell
wsl --set-default-version 2
```

## Passo 2: Instalar Docker Desktop

### 2.1. Baixe o Docker Desktop:
- Acesse: https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe
- Ou vá em: https://docs.docker.com/desktop/install/windows-install/

### 2.2. Execute o instalador:
- Execute o arquivo baixado
- Siga as instruções do instalador
- **Importante**: Mantenha marcada a opção "Use WSL 2 instead of Hyper-V"

### 2.3. Reinicie o computador quando solicitado

## Passo 3: Configurar Docker Desktop

### 3.1. Inicie o Docker Desktop:
- Procure "Docker Desktop" no menu Iniciar
- Execute o aplicativo

### 3.2. Aguarde a inicialização:
- O Docker pode demorar alguns minutos para inicializar na primeira vez
- Você verá um ícone de baleia na bandeja do sistema quando estiver pronto

### 3.3. Aceite os termos de uso quando solicitado

## Passo 4: Verificar Instalação

Abra o Command Prompt ou PowerShell e execute:

```cmd
docker --version
docker-compose --version
docker run hello-world
```

Se todos os comandos funcionarem, o Docker está instalado corretamente!

## Passo 5: Executar o Projeto

Agora você pode executar o projeto:

```cmd
cd C:\Users\caiop\OneDrive\Área de Trabalho\desafio
setup.bat
```

## Solução de Problemas

### Erro: "WSL 2 installation is incomplete"
- Certifique-se de que instalou o kernel do WSL 2
- Reinicie o computador
- Execute: `wsl --set-default-version 2`

### Erro: "Docker Desktop requires Windows 10 Pro/Enterprise"
- Se você tem Windows 10 Home, atualize para a versão mais recente
- Ou considere usar Docker Toolbox (versão legacy)

### Docker não inicia:
- Verifique se a virtualização está habilitada no BIOS
- Reinicie o Docker Desktop
- Reinicie o computador

### Performance lenta:
- Certifique-se de que está usando WSL 2
- Aumente a memória alocada ao Docker nas configurações

## Recursos Úteis

- **Documentação oficial**: https://docs.docker.com/desktop/install/windows-install/
- **WSL 2**: https://docs.microsoft.com/pt-br/windows/wsl/install
- **Suporte Docker**: https://docs.docker.com/desktop/troubleshoot/overview/
