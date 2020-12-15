create database obrazki;

create table images (
    id INT AUTO_INCREMENT NOT NULL,
    photo_url TEXT NOT NULL,
    PRIMARY KEY (id)
);

create table ratings (
    id INT AUTO_INCREMENT NOT NULL,
    image_id INT NOT NULL,
    score INT NOT NULL,
    created_at INT NOT NULL,
    ip VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);

create table comments (
    id INT AUTO_INCREMENT NOT NULL,
    image_id INT NOT NULL,
    content VARCHAR(320) NOT NULL,
    created_at INT NOT NULL,
    ip VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
);