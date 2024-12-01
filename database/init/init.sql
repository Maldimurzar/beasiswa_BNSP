CREATE TABLE registrant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telp VARCHAR(255),
    semester INT NOT NULL,
    ipk FLOAT NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'pending',
    berkas VARCHAR(255),
    beasiswa VARCHAR(255)
);
