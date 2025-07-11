Como contribuir com o projeto "Agile Team One" no GitHub:
1. Crie um fork do repositório:

Acesse o repositório original: https://github.com/webertmaximiano/agile-team-one
No canto superior direito, clique em "Fork".
Isso criará uma cópia do repositório em sua conta.

2. Clone o fork para o seu computador: escolhendo o branch que ira trabalhar main - front - back

Abra um terminal e navegue até a pasta onde deseja clonar o repositório.
Execute o seguinte comando:
git clone https://github.com/<seu_usuario>/agile-team-one.git

3. Crie uma nova branch para suas alterações:

Navegue até a pasta do projeto clonado no seu computador.
Execute o seguinte comando para criar uma nova branch:
git checkout -b <nome_da_sua_branch>

4. Faça suas alterações e correções:

Edite os arquivos do projeto de acordo com as suas correções e/ou criações.
Certifique-se de seguir as diretrizes de estilo e formatação do projeto.

5. Teste suas alterações:

Execute o projeto localmente para verificar se suas alterações funcionam como esperado.

6. Faça commit das suas alterações:

Adicione os arquivos modificados ao staging area:
git add <nome_dos_arquivos>
Crie um commit com uma mensagem descritiva:
git commit -m "Mensagem descritiva das alterações"

7. Envie suas alterações para o seu fork:

Envie suas alterações para o seu fork remoto:
git push origin <nome_da_sua_branch>

8. Abra uma pull request:

Acesse o seu fork no GitHub: https://github.com/<seu_usuario>/agile-team-one
Clique em "Pull requests".
Clique em "New pull request".
Selecione a branch que você criou com suas alterações.
Escreva um título e uma descrição para a sua pull request.
Clique em "Enviar pull request".

9. Aguarde a revisão:

O mantenedor do projeto irá revisar suas alterações e fornecer feedback.
Você pode precisar fazer alterações adicionais ou responder a perguntas do mantenedor.

10. Merge da pull request:
Se suas alterações forem aprovadas, o mantenedor irá mergear a sua pull request com o repositório original.


Quando o projeto principal é atualizado, o seu fork não é automaticamente atualizado. Você precisa atualizar manualmente o seu fork para incorporar as alterações do projeto principal.

Existem duas maneiras de fazer isso:

1. Sincronizando o seu fork:

Acesse a página do seu fork no GitHub.
Clique no botão "Sync Fork".
Isso irá baixar as últimas alterações do projeto principal para o seu fork.
2. Rebasing o seu branch:

Faça o checkout do seu branch no terminal:
git checkout <nome_do_seu_branch>
Faça o rebase do seu branch com o branch principal:
git rebase upstream/main
Resolva qualquer conflito de merge que possa surgir.
Depois de atualizar o seu fork, você pode:

Mergear as alterações do projeto principal no seu branch:
git merge upstream/main
Enviar as suas alterações para o seu fork:
git push origin <nome_do_seu_branch>
Você não receberá uma notificação automática quando o projeto principal for atualizado. No entanto, você pode configurar o GitHub para enviar notificações por e-mail quando o branch principal for atualizado. Para fazer isso:

Acesse as configurações do seu repositório.
Clique na aba "Notifications".
Selecione a opção "Receive notifications for this repository".
Marque a caixa de seleção ao lado de "Branch updates".

