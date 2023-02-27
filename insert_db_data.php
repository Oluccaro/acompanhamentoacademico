<?php
require 'db_credentials.php';

$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if(!$conn){
  die("Conexão falhou" . mysqli_connect_error());
}

// Select Database

$sql = "USE $dbname";
if(mysqli_query($conn, $sql)){
  echo "<br> Acessou o Banco de Dados com Sucesso <br>";
} else {
  echo "<br> Erro ao acessar o Banco de Dados: " . mysqli_error($conn) . "<br>";
}

// Insert data in the database

$sql_queries = ["INSERT INTO $table_users VALUES
(20224321, 'cicrano@email.com', '3e6c7d141e32189c917761138b026b74', '2023-02-18 18:40:56', NULL, NULL, NULL),
(20211234, 'fulana@email.com', '3e6c7d141e32189c917761138b026b74', '2023-02-18 18:40:56', NULL, NULL, NULL);",

"INSERT INTO Estudante VALUES 
(20224321, 'Cicrano da Costa', '2022','1'),
(20211234, 'Fulana da Silva', '2021', '2');",

"INSERT INTO Disciplina VALUES 
('DS320', 20224321, 'Banco de Dados I', 'Presencial','soma'),
('DS220', 20224321, 'Análise e Projeto de Sistemas I', 'Hibrido','media'),
('DS632', 20224321, 'Humanidades', 'EAD', 'soma'),
('DS320', 20211234, 'Banco de Dados I', 'Presencial','soma'),
('DS220', 20211234, 'Análise e Projeto de Sistemas I', 'Hibrido','media'),
('DS632', 20211234, 'Humanidades', 'EAD', 'soma');",

"INSERT INTO Presencial VALUES
('DS320', 20224321, 'Presencial', 60),
('DS320', 20211234, 'Presencial', 60);",

"INSERT INTO Hibrido VALUES
('DS220', 20224321, 'Hibrido', 30, 30, 1),
('DS220', 20211234, 'Hibrido', 30, 30, 1);",

"INSERT INTO EAD VALUES
('DS632', 20224321, 'EAD', 30, 8),
('DS632', 20211234, 'EAD', 30, 8);",

"INSERT INTO Avaliacao VALUES
('DS632', 20224321, 'Trabalhos', 60, 1),
('DS632', 20224321, 'P1', 10, 1),
('DS632', 20224321, 'P2', 30, 1),
('DS632', 20224321, 'Exame', 100, 1),
('DS320', 20224321, 'Trabalhos', 40, 1),
('DS320', 20224321, 'P1', 30, 1),
('DS320', 20224321, 'P2', 30, 1),
('DS320', 20224321, 'Exame', 100, 1),
('DS220', 20224321, 'P1', 100, 1),
('DS220', 20224321, 'P2', 100, 1),
('DS220', 20224321, 'Trabalho Final', 100, 1),
('DS220', 20224321, 'Exame', 100, 1),
('DS632', 20211234, 'Trabalhos', 60, 1),
('DS632', 20211234, 'P1', 10, 1),
('DS632', 20211234, 'P2', 30, 1),
('DS632', 20211234, 'Exame', 100, 1),
('DS320', 20211234, 'Trabalhos', 40, 1),
('DS320', 20211234, 'P1', 30, 1),
('DS320', 20211234, 'P2', 30, 1),
('DS320', 20211234, 'Exame', 100, 1),
('DS220', 20211234, 'P1', 100, 1),
('DS220', 20211234, 'P2', 100, 1),
('DS220', 20211234, 'Trabalho Final', 100, 1),
('DS220', 20211234, 'Exame', 100, 1);",

"INSERT INTO Cursa VALUES
('DS632', 20224321, 2022, '2', 0, 0),
('DS320', 20224321, 2022, '2', 3, 0),
('DS220', 20224321, 2022, '2', 4, 0),
('DS632', 20211234, 2022, '1', 4, 0),
('DS320', 20211234, 2022, '1', 5, 0),
('DS220', 20211234, 2022, '1', 7, 0);",

"INSERT INTO fezProva VALUES
(20224321, 'DS220', 'P1', 90,2022,'2'),
(20224321, 'DS220', 'P2', 70,2022,'2'),
(20224321, 'DS220', 'Trabalho Final', 100,2022,'2'),
(20224321, 'DS220', 'Exame', NULL,2022,'2'),
(20224321, 'DS632', 'Trabalhos', 60,2022,'2'),
(20224321, 'DS632', 'P1', 0,2022,'2'),
(20224321, 'DS632', 'P2', 5,2022,'2'),
(20224321, 'DS632', 'Exame', NULL,2022,'2'),
(20224321, 'DS320', 'Trabalhos', 35,2022,'2'),
(20224321, 'DS320', 'P1', 10,2022,'2'),
(20224321, 'DS320', 'P2', 15,2022,'2'),
(20224321, 'DS320', 'Exame', NULL,2022,'2'),
(20211234, 'DS220', 'P1', 100,2022,'1'),
(20211234, 'DS220', 'P2', NULL,2022,'1'),
(20211234, 'DS220', 'Trabalho Final', 100,2022,'1'),
(20211234, 'DS220', 'Exame', NULL,2022,'1'),
(20211234, 'DS632', 'Trabalhos', 40,2022,'1'),
(20211234, 'DS632', 'P1', 10,2022,'1'),
(20211234, 'DS632', 'P2', NULL,2022,'1'),
(20211234, 'DS632', 'EXAME', NULL,2022,'1'),
(20211234, 'DS320', 'Trabalhos', 40,2022,'1'),
(20211234, 'DS320', 'P1', 30,2022,'1'),
(20211234, 'DS320', 'P2', 20,2022,'1'),
(20211234, 'DS320', 'Exame', NULL,2022,'1');
"];
foreach($sql_queries as $k => $sql){
  if(mysqli_query($conn, $sql)){
    echo "<br>dados inseridos com sucesso!<br>";
  } else {
    echo "<br>Erro ao inserir dados: " . mysqli_error($conn) . "<br>";
  }
}

// Closing Connection

mysqli_close($conn);

?>