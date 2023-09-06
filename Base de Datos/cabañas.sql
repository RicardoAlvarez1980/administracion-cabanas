CREATE TABLE Cabanas (
  numero SERIAL PRIMARY KEY,
  capacidad INTEGER,
  descripcion TEXT,
  costoDiario DECIMAL(10, 2)
);

CREATE TABLE Clientes (
  DNI int PRIMARY KEY,
  nombre TEXT,
  direccion TEXT,
  telefono TEXT,
  email TEXT
);

CREATE TABLE Reservas (
  numero SERIAL PRIMARY KEY,
  cliente_dni INTEGER,
  cabana_numero INTEGER,
  fechaInicio DATE,
  fechaFin DATE
);

ALTER TABLE Reservas ADD FOREIGN KEY (cliente_dni) REFERENCES Clientes (DNI);

ALTER TABLE Reservas ADD FOREIGN KEY (cabana_numero) REFERENCES Cabanas (numero);
