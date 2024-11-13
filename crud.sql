CREATE TABLE mst_skillsets (
    sid SERIAL PRIMARY KEY,
    skillset VARCHAR(255) NOT NULL UNIQUE,
    remarks VARCHAR(255)
);

INSERT INTO mst_skillsets (skillset, remarks) VALUES
('Java, Javascript, HTML5, CSS3', 'Example Skillset 1'),
('PHP, Javascript, HTML5, CSS3', 'Example Skillset 2'),
('DotNet, Javascript, HTML5, CSS3', 'Example Skillset 3');

CREATE TABLE manpower (
    manid SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL CHECK (date_of_birth <= CURRENT_DATE - INTERVAL '25 years'),
    skill_code INTEGER REFERENCES mst_skillsets(sid),
    address TEXT,
    mobileno VARCHAR(10) CHECK (mobileno ~ '^[5-9][0-9]{9}$'),
    email VARCHAR(255) UNIQUE,
    remarks VARCHAR(255)
);
