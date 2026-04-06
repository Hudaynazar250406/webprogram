CREATE DATABASE IF NOT EXISTS friends CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE friends;

CREATE TABLE IF NOT EXISTS contacts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    surname     VARCHAR(100)  NOT NULL,
    firstname   VARCHAR(100)  NOT NULL,
    patronymic  VARCHAR(100)  DEFAULT '',
    gender      ENUM('М','Ж') NOT NULL DEFAULT 'М',
    birthdate   DATE          DEFAULT NULL,
    phone       VARCHAR(50)   DEFAULT '',
    address     VARCHAR(255)  DEFAULT '',
    email       VARCHAR(150)  DEFAULT '',
    comment     TEXT          DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO contacts (surname, firstname, patronymic, gender, birthdate, phone, address, email, comment) VALUES
('Иванов',   'Иван',    'Иванович',   'М', '1995-03-15', '+7 (900) 111-22-33', 'г. Москва, ул. Ленина, д. 1',     'ivanov@mail.ru',   'Коллега по работе'),
('Петрова',  'Мария',   'Сергеевна',  'Ж', '1998-07-22', '+7 (900) 444-55-66', 'г. Санкт-Петербург, пр. Невский', 'petrova@ya.ru',    'Однокурсница'),
('Сидоров',  'Алексей', 'Николаевич', 'М', '1990-11-08', '+7 (900) 777-88-99', 'г. Казань, ул. Баумана, д. 10',   'sidorov@gmail.com','Друг'),
('Козлова',  'Анна',    'Дмитриевна', 'Ж', '2001-05-30', '+7 (900) 222-33-44', 'г. Новосибирск, ул. Мира, д. 5',  'kozlova@mail.ru',  'Однокурсница'),
('Смирнов',  'Дмитрий', 'Павлович',   'М', '1993-09-12', '+7 (900) 555-66-77', 'г. Екатеринбург, ул. Малышева',   'smirnov@gmail.com','Сосед');
