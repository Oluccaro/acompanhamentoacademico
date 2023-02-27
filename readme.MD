Tecnologia em Análise e Desenvolvimento de Sistemas

Setor de Educação Profissional e Tecnológica - SEPT

Universidade Federal do Paraná - UFPR

---

*DS122 - Desenvolvimento Web 1*

Prof. Alexander Robert Kutzke

# Trabalho Prático de Desenvolvimento WEB

### Alunos

| Nome | GRR |
| ---|---|
| Caroline Picanço Prockmann | 20224163 |
| Gabriel Hissatomi Davanzo | 20221110 |
| Laísa Cristina Krolikovski da Silva | 20221113 |
| Lucas Fracaro Nunes | 20221125 |
| Lucas Henrique Ramos Cazionato | 20221122 |


# Aplicação de Acompanhamento Acadêmico

![alt text](./logo.png "Logo Quanto Falta?")

A Aplicação web **Quanto Falta?**  é feita visando uma melhor organização acadêmica do estudante. 

Ela permite que o estudante faça um cadastro na plataforma e possa registrar **Disciplinas**, junto com suas **cargas horárias**, especificando a modalidade da disciplina, podendo o estudante escolher entre: **Presencial**, **EAD** e **Híbrido**. A aplicação permite que o estudante registre o **total de trabalhos** que precisa entregar para a modalidade que possui carga horária à distância, assim como o número de faltas que ele já possui na modalidade presencial.

A aplicação também permite que você cadastre uma mesma disciplina em semestre diferentes e registre notas diferentes nas provas daquela disciplina para cada semestre.

A aplicação registra esses dados e avisa a porcentagem de presença total do estudante na disciplinas, assim como o número de faltas que ele ainda pode ter sem reprovar por falta. Ela avisa também quantos trabalhos da modalidade EAD precisam ser entregues.

Além disso, a aplicação **Quanto Falta?** deixa que o estudante registre as avaliações contidas naquela disciplina, informando o valor de cada avaliação, o peso e metodologia de cálculo de média (se por soma simples ou média ponderada). Com essas informações, a aplicação permite que o estudante informe a nota que tirou nessas avaliação e ela cálcula o quanto falta para você atingir a média, se você reprovou, está de final ou está aprovado.


# Instruções de como iniciar o projeto

1. Fazer o clone do repositório inteiro na pasta onde possa rodar o seu servidor local
2. Alterar o arquivo `db_credentials.php` com os dados do seu MySQL;
3. Mudar para as suas credenciais, mudar o nome do $dbname no `db_credentials.php`, para um nome que não cause conflito no seu banco de dados
4. Acessar o arquivo `create_db.php` pelo seu navegador para criar o banco de dados e as tabelas (é pra aparecer mensagens confirmando que o db foi criado)
5. Acessar o arquivo `insert_db_data.php` pelo seu navegador para inserir dados fictícios na tabela (Para assim observar os comportamentos padrões da página)
6. Agora basta acessa o `index.php` e navegar pelas abas, realizar um cadastro e acessar com seu login e senha
7. Caso queira observar o comportamento para um usuário que já possui itens no seu BD, utilize o login: *fulana@email.com*  senha: *123456Aa*;
8. Selecione o ano 2022 e semestre 1, que já poderá observar as tabelas.
9. Um vídeo explicando o funcionamento da página pode ser encontrado [aqui](https://drive.google.com/file/d/1N4U1nG0Qh0itUUyNtkOMHIXVuKho85vq/view?usp=sharing)
