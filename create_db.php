<?php
require 'db_credentials.php';

// Create connection with database
$conn = mysqli_connect($host, $user, $pass);

// Check connection
if(!$conn){
  die("Conexão falhou" . mysqli_connect_error());
}

// Delete Database

$sql = "DROP DATABASE IF EXISTS $dbname";
if(mysqli_query($conn, $sql)){
  echo "<br> Banco de Dados removido com sucesso<br>";
} else {
  echo "<br> Erro ao remover o Banco de Dados: " . mysqli_error($conn) . "<br>";
}
// Create Database

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if(mysqli_query($conn, $sql)){
  echo "<br> Banco de Dados criado com sucesso<br>";
} else {
  echo "<br> Erro ao criar o Banco de Dados: " . mysqli_error($conn) . "<br>";
}

// Select Database

$sql = "USE $dbname";
if(mysqli_query($conn, $sql)){
  echo "<br> Acessou o Banco de Dados com Sucesso <br>";
} else {
  echo "<br> Erro ao acessar o Banco de Dados: " . mysqli_error($conn) . "<br>";
}

// Create Tables 

$sql_queries = ["CREATE TABLE IF NOT EXISTS $table_users(
  grr INT PRIMARY KEY,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(128) NOT NULL,
  created_at DATETIME,
  updated_at DATETIME,
  last_login_at DATETIME,
  last_logout_at DATETIME,
  UNIQUE (email)
);",

"CREATE TABLE IF NOT EXISTS Disciplina (
    codDisc VARCHAR(5),
    fk_grr INT,
    nomeDisc VARCHAR(100),
    modalidade ENUM('EAD','Presencial','Hibrido'),
    tipoAval ENUM('soma','media'),
    PRIMARY KEY (codDisc, fk_grr, modalidade)
);",

"CREATE TABLE IF NOT EXISTS Estudante (
    grr INT PRIMARY KEY,
    nome VARCHAR(120),
    anoIngresso YEAR,
    semestreIngresso ENUM('1','2')
);",

"CREATE TABLE IF NOT EXISTS Presencial (
    fk_codDisc VARCHAR(5),
    fk_grr INT,
    fk_modalidade ENUM('EAD','Presencial','Hibrido'),
    cargaHor INT,
    PRIMARY KEY (fk_codDisc, fk_grr),
    CHECK (fk_modalidade = 'Presencial')
);",

"CREATE TABLE IF NOT EXISTS Hibrido (
    fk_codDisc VARCHAR(5),
    fk_grr INT,
    fk_modalidade ENUM('EAD','Presencial','Hibrido'),
    cargaPres INT,
    cargaEad INT,
    nTrabalhos INT,
    PRIMARY KEY (fk_codDisc, fk_grr),
    CHECK (fk_modalidade = 'Hibrido')
);",

"CREATE TABLE IF NOT EXISTS EAD (
    fk_codDisc VARCHAR(5),
    fk_grr INT,
    fk_modalidade ENUM('EAD','Presencial','Hibrido'),
    cargaHor INT,
    nTrabalhos INT,
    PRIMARY KEY (fk_codDisc, fk_grr),
    check (fk_modalidade = 'EAD')
);",

"CREATE TABLE IF NOT EXISTS Avaliacao (
    fk_codDisc VARCHAR(5), 
    fk_grr INT,   
    nome VARCHAR(30),
    valor INT,
    peso int,
    PRIMARY KEY (fk_grr, fk_codDisc, nome)
);",

"CREATE TABLE IF NOT EXISTS Cursa (
    fk_codDisc VARCHAR(5),
    fk_grr INT,
    Ano YEAR,
    semestre ENUM('1','2'),
    aulaFalta INT,
    trabalhoFalta INT,
    PRIMARY KEY (fk_codDisc, fk_grr, Ano, semestre)
);",

"CREATE TABLE IF NOT EXISTS fezProva (
    fk_grr INT,
    fk_codDisc VARCHAR(5),
    fk_Aval_nome VARCHAR(30),
    nota INT,
    fk_ano YEAR,
    fk_sem ENUM('1','2'),
    PRIMARY KEY(fk_grr,fk_codDisc,fk_Aval_nome,fk_ano,fk_sem)
);",

"ALTER TABLE Disciplina ADD CONSTRAINT fk_grr_Criador
    FOREIGN KEY (fk_grr)
    REFERENCES User(grr);",


"ALTER TABLE Estudante ADD CONSTRAINT fk_user
    FOREIGN KEY (grr)
    REFERENCES User(grr);",
 
"ALTER TABLE EAD ADD CONSTRAINT FK_EAD_2
    FOREIGN KEY (fk_codDisc, fk_grr,fk_modalidade)
    REFERENCES Disciplina (codDisc, fk_grr, modalidade)
    ON DELETE CASCADE;",
 
"ALTER TABLE Presencial ADD CONSTRAINT FK_Presencial_3
    FOREIGN KEY (fk_codDisc, fk_grr, fk_modalidade)
    REFERENCES Disciplina (codDisc, fk_grr, modalidade)
    ON DELETE CASCADE;",
 
"ALTER TABLE Hibrido ADD CONSTRAINT FK_Hibrido_3
    FOREIGN KEY (fk_codDisc, fk_grr, fk_modalidade)
    REFERENCES Disciplina (codDisc, fk_grr, modalidade)
    ON DELETE CASCADE;",

"ALTER TABLE Avaliacao ADD CONSTRAINT FK_Avaliacao_2
    FOREIGN KEY (fk_codDisc, fk_grr)
    REFERENCES Disciplina (codDisc, fk_grr)
    ON DELETE CASCADE;", 

"ALTER TABLE Cursa ADD CONSTRAINT FK_Cursa_1
    FOREIGN KEY (fk_codDisc, fk_grr)
    REFERENCES Disciplina (codDisc, fk_grr)
    ON DELETE CASCADE;",


"ALTER TABLE fezProva ADD CONSTRAINT FK_fezProva_2
    FOREIGN KEY (fk_grr, fk_codDisc,fk_Aval_nome)
    REFERENCES Avaliacao (fk_grr,fk_codDisc,nome)
    ON DELETE CASCADE;",

"ALTER TABLE fezProva ADD CONSTRAINT fk_fezProva_3
    FOREIGN KEY(fk_codDisc,fk_grr,fk_ano,fk_sem)
    REFERENCES Cursa(fk_codDisc,fk_grr,Ano,semestre);"    
];

foreach ($sql_queries as $k => $sql) {
  if(mysqli_query($conn, $sql)){
    echo "<br>Tabela criada com sucesso!<br>";
  } else {
    echo "<br>Erro ao criar Tabelas: " . mysqli_error($conn) . "<br>";
  }
  
}

// Closing Connection

mysqli_close($conn);

?>
